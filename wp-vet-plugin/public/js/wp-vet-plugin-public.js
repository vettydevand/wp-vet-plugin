document.addEventListener('DOMContentLoaded', function() {
    const $ = jQuery;

    // region: --- DOM Elements ---
    const calendarEl = document.getElementById('calendar');
    const loader = document.getElementById('calendar-loader');
    const modal = document.getElementById('appointment-modal');
    const closeModalButton = document.querySelector('.modal .close-button');
    const form = document.getElementById('appointment-form');
    const modalTitle = document.getElementById('modal-title');
    const appointmentIdInput = document.getElementById('appointment-id');
    const ownerNameInput = document.getElementById('owner-name');
    const petNameInput = document.getElementById('pet-name');
    const reasonInput = document.getElementById('appointment-reason');
    const saveButton = document.getElementById('save-appointment');
    const deleteButton = document.getElementById('delete-appointment');
    // endregion: --- DOM Elements ---

    // region: --- Modal Management ---
    /**
     * Resets the modal form to its default state.
     */
    function resetModal() {
        form.reset();
        appointmentIdInput.value = '';
        modalTitle.textContent = wp_vet_strings.new_appointment;
        deleteButton.style.display = 'none';
    }

    /**
     * Opens the modal and prepares it for either a new or an existing appointment.
     * @param {object} [info=null] - Information about the event or date clicked.
     */
    function openModal(info = null) {
        resetModal();
        if (info && info.event) { // Editing an existing event
            const { id, extendedProps, startStr } = info.event;
            modalTitle.textContent = wp_vet_strings.edit_appointment;
            appointmentIdInput.value = id;
            ownerNameInput.value = extendedProps.ownerName || '';
            petNameInput.value = extendedProps.petName || '';
            reasonInput.value = extendedProps.reason || '';
            form.dataset.date = startStr;
            deleteButton.style.display = 'inline-block';
        } else if (info) { // Creating a new event
            form.dataset.date = info.dateStr;
        }
        modal.style.display = 'block';
    }

    /**
     * Closes the modal.
     */
    function closeModal() {
        modal.style.display = 'none';
    }
    // endregion: --- Modal Management ---


    // region: --- API Service ---
    const api = {
        _ajax: function(action, data, successCallback) {
            data.action = action;
            $.ajax({
                url: wp_vet_ajax.ajax_url,
                type: 'POST',
                data: data,
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.data.message || wp_vet_strings.success);
                        successCallback(response.data);
                    } else {
                        toastr.error(response.data.message || wp_vet_strings.error);
                    }
                },
                error: () => toastr.error(wp_vet_strings.ajax_error)
            });
        },
        getAppointments: (fetchInfo, successCallback, failureCallback) => {
            $.ajax({
                url: wp_vet_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'get_appointments',
                    nonce: wp_vet_ajax.get_nonce,
                },
                success: response => response.success ? successCallback(response.data) : failureCallback(new Error(response.data.message)),
                error: () => failureCallback(new Error(wp_vet_strings.ajax_error))
            });
        },
        createAppointment: (data, calendar) => {
            api._ajax('create_appointment', { nonce: wp_vet_ajax.create_nonce, ...data }, () => {
                calendar.refetchEvents();
                closeModal();
            });
        },
        updateAppointment: (data, calendar) => {
            api._ajax('update_appointment', { nonce: wp_vet_ajax.update_nonce, ...data }, () => {
                calendar.refetchEvents();
                closeModal();
            });
        },
        deleteAppointment: (id, calendar) => {
            if (!confirm(wp_vet_strings.confirm_delete)) return;
            api._ajax('delete_appointment', { nonce: wp_vet_ajax.delete_nonce, id }, () => {
                calendar.refetchEvents();
                closeModal();
            });
        }
    };
    // endregion: --- API Service ---

    // region: --- Calendar & Form Event Handlers ---
    function handleFormSubmit(event, calendar) {
        event.preventDefault();
        const appointmentId = appointmentIdInput.value;
        const appointmentData = {
            id: appointmentId,
            ownerName: ownerNameInput.value,
            petName: petNameInput.value,
            reason: reasonInput.value,
            date: form.dataset.date
        };
        
        if (appointmentId) {
            api.updateAppointment(appointmentData, calendar);
        } else {
            api.createAppointment(appointmentData, calendar);
        }
    }

    function handleEventDrop(info, calendar) {
        const { id, extendedProps } = info.event;
        const updatedData = {
            id: id,
            ownerName: extendedProps.ownerName,
            petName: extendedProps.petName,
            reason: extendedProps.reason,
            date: info.event.start.toISOString().slice(0, 10) // Format to YYYY-MM-DD
        };
        api.updateAppointment(updatedData, calendar);
    }
    // endregion: --- Calendar & Form Event Handlers ---


    // region: --- Initialization ---
    if (calendarEl) {
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: window.innerWidth < 768 ? 'listWeek' : 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listWeek'
            },
            editable: true,
            selectable: true,
            droppable: true,
            events: api.getAppointments,
            loading: isLoading => {
                loader.style.display = isLoading ? 'block' : 'none';
            },
            dateClick: openModal,
            eventClick: (info) => openModal(info),
            eventDrop: (info) => handleEventDrop(info, calendar)
        });

        form.addEventListener('submit', (e) => handleFormSubmit(e, calendar));
        deleteButton.addEventListener('click', () => api.deleteAppointment(appointmentIdInput.value, calendar));
        closeModalButton.addEventListener('click', closeModal);
        window.addEventListener('click', (event) => {
            if (event.target === modal) closeModal();
        });

        calendar.render();
    } else {
        console.error("Calendar element not found!");
    }
    // endregion: --- Initialization ---
});
