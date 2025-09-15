
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var modal = document.getElementById('appointmentModal');
    var closeModal = document.getElementsByClassName('close-button')[0];
    var appointmentForm = document.getElementById('appointmentForm');
    var modalTitle = document.getElementById('modalTitle');
    var appointmentId = document.getElementById('appointmentId');
    var appointmentTitle = document.getElementById('appointmentTitle');
    var appointmentStart = document.getElementById('appointmentStart');
    var appointmentEnd = document.getElementById('appointmentEnd');
    var saveButton = document.getElementById('saveButton');
    var deleteButton = document.getElementById('deleteButton');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        plugins: [ 'interaction', 'dayGrid', 'timeGrid' ],
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: 'api.php',
        selectable: true,
        select: function(info) {
            modalTitle.innerText = 'Add Appointment';
            appointmentId.value = '';
            appointmentTitle.value = '';
            appointmentStart.value = moment(info.start).format('YYYY-MM-DDTHH:mm');
            appointmentEnd.value = moment(info.end).format('YYYY-MM-DDTHH:mm');
            deleteButton.style.display = 'none';
            modal.style.display = 'block';
        },
        eventClick: function(info) {
            modalTitle.innerText = 'Edit Appointment';
            appointmentId.value = info.event.id;
            appointmentTitle.value = info.event.title;
            appointmentStart.value = moment(info.event.start).format('YYYY-MM-DDTHH:mm');
            appointmentEnd.value = moment(info.event.end).format('YYYY-MM-DDTHH:mm');
            deleteButton.style.display = 'inline-block';
            modal.style.display = 'block';
        }
    });

    calendar.render();

    closeModal.onclick = function() {
        modal.style.display = 'none';
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }

    appointmentForm.onsubmit = function(e) {
        e.preventDefault();
        var id = appointmentId.value;
        var title = appointmentTitle.value;
        var start = appointmentStart.value;
        var end = appointmentEnd.value;

        var url = id ? 'api.php' : 'api.php';
        var method = id ? 'PUT' : 'POST';

        var data = {
            id: id,
            title: title,
            start: start,
            end: end
        };

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        })
        .then(response => response.json())
        .then(function() {
            modal.style.display = 'none';
            calendar.refetchEvents();
        });
    }

    deleteButton.onclick = function() {
        var id = appointmentId.value;
        if (confirm('Are you sure you want to delete this event?')) {
            fetch('api.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({id: id}),
            })
            .then(response => response.json())
            .then(function() {
                modal.style.display = 'none';
                calendar.refetchEvents();
            });
        }
    }
});
