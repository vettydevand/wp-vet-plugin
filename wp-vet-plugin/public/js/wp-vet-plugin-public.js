document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    if (calendarEl) {
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: function(fetchInfo, successCallback, failureCallback) {
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
        });
        calendar.render();
    }
});
