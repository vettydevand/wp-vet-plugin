document.addEventListener('DOMContentLoaded', function() {

    // region: --- Utilities ---
    function escapeHTML(unsafe) {
        if (typeof unsafe !== 'string') return '';
        return unsafe.replace(/[&<>"'/]/g, match => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;',
            '/': '&#x2F;'
        }[match]));
    }
    // endregion: --- Utilities ---


    // region: --- API Service ---
    const api = {
        _ajax: function(action, data, successCallback, errorCallback) {
            jQuery.ajax({
                url: wp_vet_ajax.ajax_url,
                type: 'POST',
                data: { action, ...data },
                success: function(response) {
                    if (response.success) {
                        successCallback(response.data);
                    } else {
                        errorCallback(response.data);
                    }
                },
                error: function() {
                    errorCallback('AJAX call failed.');
                }
            });
        },

        getAppointments: function(fetchInfo, successCallback, failureCallback) {
            this._ajax('get_appointments', {
                nonce: wp_vet_ajax.nonce,
                start: fetchInfo.startStr,
                end: fetchInfo.endStr
            }, successCallback, errorMsg => {
                toastr.error('Failed to fetch appointments.');
                failureCallback(new Error(errorMsg));
            });
        },

        createAppointment: function(title, date, calendar, successCallback) {
            this._ajax('create_appointment', {
                nonce: wp_vet_ajax.create_nonce,
                title: title,
                date: date
            }, data => {
                toastr.success('Appointment created successfully.');
                calendar.refetchEvents();
                if (successCallback) successCallback(data);
            }, errorMsg => toastr.error('Failed to create appointment: ' + escapeHTML(errorMsg)));
        },

        updateAppointment: function(id, title, newDate, calendar, successCallback) {
            this._ajax('update_appointment', {
                nonce: wp_vet_ajax.update_nonce,
                id: id,
                title: title,
                date: newDate
            }, data => {
                toastr.success('Appointment updated successfully.');
                calendar.refetchEvents();
                if (successCallback) successCallback(data);
            }, errorMsg => toastr.error('Failed to update appointment: ' + escapeHTML(errorMsg)));
        },

        deleteAppointment: function(id, calendar, successCallback) {
            this._ajax('delete_appointment', {
                nonce: wp_vet_ajax.delete_nonce,
                id: id
            }, data => {
                toastr.success('Appointment deleted successfully.');
                calendar.refetchEvents();
                if (successCallback) successCallback(data);
            }, errorMsg => toastr.error('Failed to delete appointment: ' + escapeHTML(errorMsg)));
        }
    };
    // endregion: --- API Service ---


    // region: --- Modal Management ---
    function openAppointmentModal(info, calendar) {
        const isNew = !info.event;
        const title = isNew ? '' : info.event.title;
        const id = isNew ? null : info.event.id;

        const modalContent = `
            <h3>${isNew ? 'New Appointment' : 'Edit Appointment'}</h3>
            <input type="text" id="appointment_title" placeholder="Appointment Title" value="${escapeHTML(title)}" required>
            <button id="save_appointment">Save</button>
            ${!isNew ? '<button id="delete_appointment">Delete</button>' : ''}
        `;

        createModal(modalContent);

        document.getElementById('save_appointment').onclick = () => {
            const newTitle = document.getElementById('appointment_title').value;
            if (!newTitle) {
                toastr.error('Title is required');
                return;
            }
            
            if (isNew) {
                api.createAppointment(newTitle, info.dateStr, calendar, closeModal);
            } else {
                api.updateAppointment(id, newTitle, info.event.startStr, calendar, closeModal);
            }
        };

        if (!isNew) {
            document.getElementById('delete_appointment').onclick = () => {
                if (confirm('Are you sure you want to delete this appointment?')) {
                    api.deleteAppointment(id, calendar, closeModal);
                }
            };
        }
    }

    function closeModal() {
        const modal = document.querySelector('.modal');
        if (modal) {
            modal.style.display = 'none';
            document.body.removeChild(modal);
        }
    }
    // endregion: --- Modal Management ---


    // region: --- Calendar Event Handlers ---
    function handleEventDrop(info, calendar) {
        api.updateAppointment(info.event.id, info.event.title, info.event.startStr, calendar);
    }
    // endregion: --- Calendar Event Handlers ---


    // region: --- Calendar Initialization ---
    const calendarEl = document.getElementById('calendar');
    const loader = document.getElementById('calendar-loader');

    if (calendarEl) {
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: window.innerWidth < 768 ? 'listWeek' : 'dayGridMonth',
            editable: true,
            selectable: true,
            droppable: true,
            loading: isLoading => {
                loader.style.display = isLoading ? 'block' : 'none';
            },
            events: (fetchInfo, successCallback, failureCallback) => {
                api.getAppointments(fetchInfo, successCallback, failureCallback);
            },
            dateClick: info => openAppointmentModal(info, calendar),
            eventClick: info => openAppointmentModal(info, calendar),
            eventDrop: info => handleEventDrop(info, calendar)
        });
        calendar.render();
    }
    // endregion: --- Calendar Initialization ---

});
