document.addEventListener('DOMContentLoaded', async function() {
    // --- INIZIO SEZIONE THEME --- 

    const root = document.documentElement;
    const appNameEl = document.getElementById('appName');
    const appLogoEl = document.getElementById('appLogo');

    /**
     * Carica il file theme.json e applica la personalizzazione.
     */
    async function applyTheme() {
        try {
            const response = await fetch('theme.json');
            if (!response.ok) {
                throw new Error(`Impossibile caricare theme.json: ${response.statusText}`);
            }
            const theme = await response.json();

            // Applica nome e logo
            document.title = theme.appName;
            appNameEl.textContent = theme.appName;
            if (theme.logoUrl) {
                appLogoEl.src = theme.logoUrl;
                appLogoEl.style.display = 'inline';
            }

            // Applica colori e font come variabili CSS
            for (const color in theme.theme.colors) {
                root.style.setProperty(`--${color}-color`, theme.theme.colors[color]);
            }
            root.style.setProperty('--font-main', theme.theme.fonts.main);

        } catch (error) {
            console.warn('Tema non applicato:', error.message);
            // Se theme.json non è presente o è malformato, l'app usa i valori di fallback in styles.css
        }
    }

    await applyTheme();

    // --- FINE SEZIONE THEME ---

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
            throw error;
        }
    }

    function toLocalISOString(date) {
        const pad = (num) => num.toString().padStart(2, '0');
        const year = date.getFullYear();
        const month = pad(date.getMonth() + 1);
        const day = pad(date.getDate());
        const hours = pad(date.getHours());
        const minutes = pad(date.getMinutes());
        return `${year}-${month}-${day}T${hours}:${minutes}`;
    }

    const calendar = new FullCalendar.Calendar(calendarEl, {
        plugins: ['interaction', 'dayGrid', 'timeGrid'],
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        locale: 'it',
        buttonText: { today: 'oggi', month: 'mese', week: 'settimana', day: 'giorno' },
        events: (fetchInfo, successCallback, failureCallback) => {
            apiCall('api.php', 'GET')
                .then(events => successCallback(events))
                .catch(err => failureCallback(err));
        },
        selectable: true,
        editable: true,
        select: function(info) {
            openModal({ start: info.start, end: info.end });
        },
        eventClick: function(info) {
            openModal({
                id: info.event.id,
                title: info.event.title,
                start: info.event.start,
                end: info.event.end
            });
        },
        eventDrop: handleEventUpdate,
        eventResize: handleEventUpdate,
    });

    calendar.render();

    function openModal(data = {}) {
        appointmentId.value = data.id || '';
        appointmentTitle.value = data.title || '';
        appointmentStart.value = toLocalISOString(data.start ? new Date(data.start) : new Date());
        appointmentEnd.value = toLocalISOString(data.end ? new Date(data.end) : new Date(new Date().getTime() + 60*60*1000));
        modalTitle.innerText = data.id ? 'Modifica Appuntamento' : 'Aggiungi Appuntamento';
        deleteButton.style.display = data.id ? 'inline-block' : 'none';
        modal.style.display = 'block';
    }

    function closeModal() {
        modal.style.display = 'none';
        appointmentForm.reset();
    }

    closeModalBtn.addEventListener('click', closeModal);
    window.addEventListener('click', (event) => {
        if (event.target === modal) closeModal();
    });

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
        } catch (err) {}
    });

    deleteButton.addEventListener('click', async () => {
        const id = appointmentId.value;
        if (id && confirm('Sei sicuro di voler eliminare questo appuntamento?')) {
            try {
                await apiCall('api.php', 'DELETE', { id });
                closeModal();
                calendar.refetchEvents();
                alert('Appuntamento eliminato con successo!');
            } catch (err) {}
        }
    });

    async function handleEventUpdate(info) {
        const eventData = {
            id: info.event.id,
            title: info.event.title,
            start: info.event.start.toISOString(),
            end: info.event.end ? info.event.end.toISOString() : new Date(info.event.start.getTime() + 60*60*1000).toISOString(),
        };
        if (!confirm("Confermi la modifica di questo appuntamento?")) {
            info.revert();
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
