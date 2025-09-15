document.addEventListener('DOMContentLoaded', function() {

    // --- ELEMENTI DEL DOM ---
    const calendarEl = document.getElementById('calendar');
    const modal = document.getElementById('appointment-modal');
    const closeModalButton = document.querySelector('.close-button');
    const form = document.getElementById('appointment-form');
    const modalTitle = document.getElementById('modal-title');
    const eventIdInput = document.getElementById('event-id');
    const ownerNameInput = document.getElementById('owner-name');
    const petNameInput = document.getElementById('pet-name');
    const visitReasonInput = document.getElementById('visit-reason');
    const deleteButton = document.getElementById('delete-appointment');

    // --- STATO DELLA DEMO ---
    // Usiamo un array in memoria per simulare il database degli appuntamenti.
    let events = [
        {
            id: '1',
            title: 'Fido - Vaccino',
            start: new Date().toISOString().split('T')[0] + 'T10:30:00',
            extendedProps: {
                owner_name: 'Mario Rossi',
                pet_name: 'Fido',
                visit_reason: 'Vaccino annuale'
            }
        },
        {
            id: '2',
            title: 'Micio - Controllo',
            start: new Date(new Date().setDate(new Date().getDate() + 2)).toISOString().split('T')[0] + 'T14:00:00',
            extendedProps: {
                owner_name: 'Anna Bianchi',
                pet_name: 'Micio',
                visit_reason: 'Controllo di routine'
            }
        }
    ];
    let currentEventInfo = null; // Per tenere traccia dell'evento/data corrente

    // --- CONFIGURAZIONE TOASTR ---
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "5000"
    };

    // --- INIZIALIZZAZIONE FULLCALENDAR ---
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: events,
        editable: true,    // Abilita drag-and-drop
        selectable: true,    // Abilita la selezione di date
        locale: 'it',        // Lingua italiana
        buttonText: { today: 'Oggi' },

        // Click su un giorno vuoto
        dateClick: function(info) {
            openModal(info);
        },

        // Click su un evento esistente
        eventClick: function(info) {
            openModal(info);
        },

        // Drag-and-drop di un evento
        eventDrop: function(info) {
            handleEventDrop(info);
        }
    });

    calendar.render();

    // --- GESTIONE MODALE ---

    function openModal(info) {
        form.reset();
        currentEventInfo = info;

        if (info.event) { // Modifica di un evento esistente
            modalTitle.innerText = 'Modifica Appuntamento';
            const props = info.event.extendedProps;
            eventIdInput.value = info.event.id;
            ownerNameInput.value = props.owner_name || '';
            petNameInput.value = props.pet_name || '';
            visitReasonInput.value = props.visit_reason || '';
            deleteButton.style.display = 'inline-block';
        } else { // Nuovo appuntamento
            modalTitle.innerText = 'Nuovo Appuntamento';
            eventIdInput.value = '';
            deleteButton.style.display = 'none';
        }

        modal.style.display = 'block';
    }

    function closeModal() {
        modal.style.display = 'none';
    }

    closeModalButton.addEventListener('click', closeModal);
    window.addEventListener('click', function(event) {
        if (event.target == modal) {
            closeModal();
        }
    });

    // --- GESTIONE FORM ---

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const id = eventIdInput.value;
        const newEventData = {
            title: `${petNameInput.value} - ${visitReasonInput.value}`,
            start: currentEventInfo.event ? currentEventInfo.event.startStr : currentEventInfo.dateStr,
            extendedProps: {
                owner_name: ownerNameInput.value,
                pet_name: petNameInput.value,
                visit_reason: visitReasonInput.value
            }
        };

        if (id) { // Aggiorna evento
            const index = events.findIndex(e => e.id === id);
            if (index > -1) {
                events[index] = { ...events[index], ...newEventData };
                toastr.success('Appuntamento aggiornato con successo!');
            }
        } else { // Crea nuovo evento
            newEventData.id = Date.now().toString(); // ID unico per la demo
            events.push(newEventData);
            toastr.success('Appuntamento creato con successo!');
        }

        calendar.refetchEvents();
        closeModal();
    });

    deleteButton.addEventListener('click', function() {
        const id = eventIdInput.value;
        if (!id || !confirm('Sei sicuro di voler eliminare questo appuntamento?')) {
            return;
        }

        events = events.filter(e => e.id !== id);
        calendar.refetchEvents();
        closeModal();
        toastr.error('Appuntamento eliminato.');
    });

    // --- GESTIONE DRAG-AND-DROP ---

    function handleEventDrop(info) {
        const id = info.event.id;
        const index = events.findIndex(e => e.id === id);
        if (index > -1) {
            events[index].start = info.event.startStr;
            toastr.info('Appuntamento riprogrammato.');
            calendar.refetchEvents();
        }
    }

    // Ricarica gli eventi (simula la provenienza da una fonte esterna)
    calendar.setOption('events', (fetchInfo, successCallback, failureCallback) => {
        // In una vera app, qui faresti una chiamata AJAX.
        // Per la demo, restituiamo semplicemente l'array in memoria.
        successCallback(events);
    });
});
