document.addEventListener('DOMContentLoaded', function() {
    // Elementi del DOM
    const calendarEl = document.getElementById('calendar');
    const modal = document.getElementById('appointmentModal');
    const closeModalBtn = document.querySelector('.close-button');
    const appointmentForm = document.getElementById('appointmentForm');
    const modalTitle = document.getElementById('modalTitle');
    const appointmentId = document.getElementById('appointmentId');
    const appointmentTitle = document.getElementById('appointmentTitle');
    const appointmentStart = document.getElementById('appointmentStart');
    const appointmentEnd = document.getElementById('appointmentEnd');
    const saveButton = document.getElementById('saveButton');
    const deleteButton = document.getElementById('deleteButton');

    // Funzione wrapper per le chiamate API
    async function apiCall(url, method, data) {
        try {
            const response = await fetch(url, {
                method: method,
                headers: { 'Content-Type': 'application/json' },
                body: data ? JSON.stringify(data) : undefined,
            });

            const result = await response.json();

            if (!response.ok) {
                throw new Error(result.message || `Errore HTTP: ${response.status}`);
            }
            return result;
        } catch (error) {
            console.error('Errore API:', error);
            alert(`Operazione fallita: ${error.message}`);
            throw error; // Rilancia l'errore per essere gestito dal chiamante
        }
    }

    // Converte una data in formato ISO per l'input datetime-local
    function toLocalISOString(date) {
        const pad = (num) => num.toString().padStart(2, '0');
        const year = date.getFullYear();
        const month = pad(date.getMonth() + 1);
        const day = pad(date.getDate());
        const hours = pad(date.getHours());
        const minutes = pad(date.getMinutes());
        return `${year}-${month}-${day}T${hours}:${minutes}`;
    }

    // Inizializzazione di FullCalendar
    const calendar = new FullCalendar.Calendar(calendarEl, {
        plugins: ['interaction', 'dayGrid', 'timeGrid'],
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        locale: 'it', // Imposta la lingua italiana
        buttonText: { today: 'oggi', month: 'mese', week: 'settimana', day: 'giorno' },
        events: (fetchInfo, successCallback, failureCallback) => {
            apiCall('api.php', 'GET')
                .then(events => successCallback(events))
                .catch(err => failureCallback(err));
        },
        selectable: true,
        editable: true, // Abilita il drag-and-drop e il resize

        // Apre il modale per creare un nuovo evento
        select: function(info) {
            openModal({
                start: info.start,
                end: info.end
            });
        },

        // Apre il modale per modificare un evento esistente
        eventClick: function(info) {
            openModal({
                id: info.event.id,
                title: info.event.title,
                start: info.event.start,
                end: info.event.end
            });
        },

        // Gestisce l'aggiornamento di un evento (drag o resize)
        eventDrop: handleEventUpdate,
        eventResize: handleEventUpdate,
    });

    calendar.render();

    // Funzioni di gestione del modale
    function openModal(data = {}) {
        appointmentId.value = data.id || '';
        appointmentTitle.value = data.title || '';
        appointmentStart.value = toLocalISOString(data.start ? new Date(data.start) : new Date());
        appointmentEnd.value = toLocalISOString(data.end ? new Date(data.end) : new Date(new Date().getTime() + 60*60*1000)); // Default a 1 ora dopo

        modalTitle.innerText = data.id ? 'Modifica Appuntamento' : 'Aggiungi Appuntamento';
        deleteButton.style.display = data.id ? 'inline-block' : 'none';
        modal.style.display = 'block';
    }

    function closeModal() {
        modal.style.display = 'none';
        appointmentForm.reset();
    }

    // Gestione degli eventi del modale
    closeModalBtn.addEventListener('click', closeModal);
    window.addEventListener('click', (event) => {
        if (event.target === modal) closeModal();
    });

    // Gestione del salvataggio (creazione/aggiornamento)
    appointmentForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const eventData = {
            id: appointmentId.value || null,
            title: appointmentTitle.value.trim(),
            start: new Date(appointmentStart.value).toISOString(),
            end: new Date(appointmentEnd.value).toISOString(),
        };

        if (!eventData.title) {
            alert('Il titolo è obbligatorio.');
            return;
        }

        const method = eventData.id ? 'PUT' : 'POST';

        try {
            await apiCall('api.php', method, eventData);
            closeModal();
            calendar.refetchEvents();
            alert(`Appuntamento ${eventData.id ? 'aggiornato' : 'creato'} con successo!`);
        } catch (err) {
            // L'errore è già gestito in apiCall, ma si potrebbe aggiungere logica qui
        }
    });

    // Gestione dell'eliminazione
    deleteButton.addEventListener('click', async () => {
        const id = appointmentId.value;
        if (id && confirm('Sei sicuro di voler eliminare questo appuntamento?')) {
            try {
                await apiCall('api.php', 'DELETE', { id });
                closeModal();
                calendar.refetchEvents();
                alert('Appuntamento eliminato con successo!');
            } catch (err) {
                // L'errore è già gestito in apiCall
            }
        }
    });

    // Funzione per aggiornare l'evento dopo drag o resize
    async function handleEventUpdate(info) {
        const eventData = {
            id: info.event.id,
            title: info.event.title,
            start: info.event.start.toISOString(),
            end: info.event.end ? info.event.end.toISOString() : new Date(info.event.start.getTime() + 60*60*1000).toISOString(),
        };

        if (!confirm("Confermi la modifica di questo appuntamento?")) {
            info.revert(); // Annulla la modifica se l'utente non conferma
            return;
        }

        try {
            await apiCall('api.php', 'PUT', eventData);
            calendar.refetchEvents();
            alert('Appuntamento aggiornato con successo!');
        } catch (err) {
            info.revert();
        }
    }
});
