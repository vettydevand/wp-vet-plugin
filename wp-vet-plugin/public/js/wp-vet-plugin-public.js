document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var loader = document.getElementById('calendar-loader');

    if (calendarEl) {
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: window.innerWidth < 768 ? 'listWeek' : 'dayGridMonth',
            editable: true,
            selectable: true,
            droppable: true,
            loading: function(isLoading) {
                if (isLoading) {
                    loader.style.display = 'block';
                } else {
                    loader.style.display = 'none';
                }
            },
            events: function(fetchInfo, successCallback, failureCallback) {
                fetchAppointments(fetchInfo, successCallback, failureCallback);
            },
            dateClick: function(info) {
                openAppointmentModal(info, calendar);
            },
            eventClick: function(info) {
                openAppointmentModal(info, calendar);
            },
            eventDrop: function(info) {
                handleEventDrop(info, calendar);
            }
        });
        calendar.render();
    }

    function fetchAppointments(fetchInfo, successCallback, failureCallback) {
        jQuery.ajax({
            url: wp_vet_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'get_appointments',
                nonce: wp_vet_ajax.nonce,
                start: fetchInfo.startStr,
                end: fetchInfo.endStr
            },
            success: function(response) {
                if (response.success) {
                    successCallback(response.data);
                } else {
                    toastr.error('Failed to fetch appointments');
                    failureCallback(new Error('Failed to fetch appointments'));
                }
            },
            error: function() {
                toastr.error('AJAX error while fetching appointments.');
                failureCallback(new Error('AJAX error'));
            }
        });
    }

    function openAppointmentModal(info, calendar) {
        const isNew = !info.event;
        const title = isNew ? '' : info.event.title;
        const id = isNew ? null : info.event.id;

        const modalContent = '
            <h3>' + (isNew ? 'New Appointment' : 'Edit Appointment') + '</h3>
            <input type="text" id="appointment_title" placeholder="Appointment Title" value="' + title + '" required>
            <button id="save_appointment">Save</button>
            ' + (!isNew ? '<button id="delete_appointment">Delete</button>' : '');

        createModal(modalContent);

        document.getElementById('save_appointment').onclick = function() {
            const newTitle = document.getElementById('appointment_title').value;
            if (newTitle) {
                if (isNew) {
                    createAppointment(newTitle, info.dateStr, calendar);
                } else {
                    updateAppointment(id, newTitle, info.event.startStr, calendar);
                }
                closeModal();
            } else {
                toastr.error('Title is required');
            }
        };

        if (!isNew) {
            document.getElementById('delete_appointment').onclick = function() {
                if (confirm('Are you sure you want to delete this appointment?')) {
                    deleteAppointment(id, calendar);
                    closeModal();
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


    function handleEventDrop(info, calendar) {
        var newDate = info.event.startStr;
        updateAppointment(info.event.id, info.event.title, newDate, calendar);
    }

    function createAppointment(title, date, calendar) {
        jQuery.ajax({
            url: wp_vet_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'create_appointment',
                nonce: wp_vet_ajax.create_nonce,
                title: title,
                date: date
            },
            success: function(response) {
                if (response.success) {
                    toastr.success('Appointment created successfully.');
                    calendar.refetchEvents();
                } else {
                    toastr.error('Failed to create appointment: ' + response.data);
                }
            },
            error: function() {
                toastr.error('AJAX error while creating appointment.');
            }
        });
    }

    function updateAppointment(id, title, newDate, calendar) {
        jQuery.ajax({
            url: wp_vet_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'update_appointment',
                nonce: wp_vet_ajax.update_nonce,
                id: id,
                title: title,
                date: newDate
            },
            success: function(response) {
                if (response.success) {
                    toastr.success('Appointment updated successfully.');
                    calendar.refetchEvents();
                } else {
                    toastr.error('Failed to update appointment: ' + response.data);
                }
            },
            error: function() {
                toastr.error('AJAX error while updating appointment.');
            }
        });
    }

    function deleteAppointment(id, calendar) {
        jQuery.ajax({
            url: wp_vet_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'delete_appointment',
                nonce: wp_vet_ajax.delete_nonce,
                id: id
            },
            success: function(response) {
                if (response.success) {
                    toastr.success('Appointment deleted successfully.');
                    calendar.refetchEvents();
                } else {
                    toastr.error('Failed to delete appointment: ' + response.data);
                }
            },
            error: function() {
                toastr.error('AJAX error while deleting appointment.');
            }
        });
    }
});
