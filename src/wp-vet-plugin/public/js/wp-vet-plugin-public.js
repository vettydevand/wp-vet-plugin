
import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import interactionPlugin from '@fullcalendar/interaction';
import toastr from '../../assets/js/toastr.min.js';
import { openModal, closeModal } from '../../assets/js/modal.js';

import '../../assets/css/toastr.min.css';
import '../../assets/css/modal.css';
import '../css/wp-vet-plugin-public.css';

document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    if (!calendarEl) {
        return;
    }

    const calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, timeGridPlugin, listPlugin, interactionPlugin],
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listWeek'
        },
        events: function(fetchInfo, successCallback, failureCallback) {
            fetch(wp_vet_ajax.ajax_url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    'action': 'get_appointments',
                    'nonce': wp_vet_ajax.get_nonce,
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    successCallback(data.data);
                } else {
                    failureCallback(new Error(data.data));
                }
            })
            .catch(error => {
                toastr.error(wp_vet_strings.ajax_error);
                failureCallback(error);
            });
        },
        editable: true,
        selectable: true,
        select: function(info) {
            openModal(info.start, info.end, function(title, start, end) {
                const newEvent = {
                    title: title,
                    start: start,
                    end: end,
                    allDay: info.allDay
                };

                fetch(wp_vet_ajax.ajax_url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        'action': 'create_appointment',
                        'nonce': wp_vet_ajax.create_nonce,
                        'title': newEvent.title,
                        'start': newEvent.start,
                        'end': newEvent.end,
                        'allDay': newEvent.allDay,
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        newEvent.id = data.data.id;
                        calendar.addEvent(newEvent);
                        toastr.success(wp_vet_strings.success);
                        closeModal();
                    } else {
                        toastr.error(data.data || wp_vet_strings.error);
                    }
                })
                .catch(() => toastr.error(wp_vet_strings.ajax_error));
            });
        },
        eventClick: function(info) {
            const event = info.event;
            openModal(event.start, event.end, function(title, start, end) {
                // Update logic here
            }, event.title, true, function() {
                // Delete logic here
                if (confirm(wp_vet_strings.confirm_delete)) {
                    fetch(wp_vet_ajax.ajax_url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: new URLSearchParams({
                            'action': 'delete_appointment',
                            'nonce': wp_vet_ajax.delete_nonce,
                            'id': event.id,
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            event.remove();
                            toastr.success(wp_vet_strings.success);
                            closeModal();
                        } else {
                            toastr.error(data.data || wp_vet_strings.error);
                        }
                    })
                    .catch(() => toastr.error(wp_vet_strings.ajax_error));
                }
            });
        },
        eventDrop: function(info) {
            const event = info.event;
            fetch(wp_vet_ajax.ajax_url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    'action': 'update_appointment',
                    'nonce': wp_vet_ajax.update_nonce,
                    'id': event.id,
                    'start': event.start.toISOString(),
                    'end': event.end ? event.end.toISOString() : null,
                    'allDay': event.allDay,
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    toastr.success(wp_vet_strings.success);
                } else {
                    toastr.error(data.data || wp_vet_strings.error);
                    info.revert();
                }
            })
            .catch(() => {
                toastr.error(wp_vet_strings.ajax_error);
                info.revert();
            });
        }
    });

    calendar.render();
});
