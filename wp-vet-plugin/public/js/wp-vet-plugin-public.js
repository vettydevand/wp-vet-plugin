document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    if (calendarEl) {
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            editable: true,
            selectable: true,
            droppable: true,
            events: function(fetchInfo, successCallback, failureCallback) {
                fetchAppointments(fetchInfo, successCallback, failureCallback);
            },
            dateClick: function(info) {
                handleDateClick(info, calendar);
            },
            eventClick: function(info) {
                handleEventClick(info, calendar);
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
                    failureCallback(new Error('Failed to fetch appointments'));
                }
            },
            error: function() {
                failureCallback(new Error('AJAX error'));
            }
        });
    }

    function handleDateClick(info, calendar) {
        var title = prompt('Enter appointment title:');
        if (title) {
            createAppointment(title, info.dateStr, calendar);
        }
    }

    function handleEventClick(info, calendar) {
        if (confirm('Are you sure you want to delete this appointment?')) {
            deleteAppointment(info.event.id, calendar);
        }
    }

    function handleEventDrop(info, calendar) {
        var newDate = info.event.startStr;
        updateAppointment(info.event.id, newDate, calendar);
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
                    calendar.refetchEvents();
                } else {
                    alert('Failed to create appointment: ' + response.data);
                }
            },
            error: function() {
                alert('AJAX error while creating appointment.');
            }
        });
    }

    function updateAppointment(id, newDate, calendar) {
        jQuery.ajax({
            url: wp_vet_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'update_appointment',
                nonce: wp_vet_ajax.update_nonce,
                id: id,
                date: newDate
            },
            success: function(response) {
                if (response.success) {
                    calendar.refetchEvents();
                } else {
                    alert('Failed to update appointment: ' + response.data);
                }
            },
            error: function() {
                alert('AJAX error while updating appointment.');
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
                    calendar.refetchEvents();
                } else {
                    alert('Failed to delete appointment: ' + response.data);
                }
            },
            error: function() {
                alert('AJAX error while deleting appointment.');
            }
        });
    }
});
