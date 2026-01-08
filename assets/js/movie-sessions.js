// movie-sessions.js
// Dynamically populate cinema sessions on movie page

document.addEventListener('DOMContentLoaded', function() {
    const cinemasSection = document.querySelector('.cinemas');
    if (!cinemasSection) return;

    const filtersCol = cinemasSection.querySelector('.filters-col');
    const cardsCol = cinemasSection.querySelector('.cards-col');
    const tabGroup = document.querySelector('.tab-group');

    let allSessions = [];
    let selectedDate = null;
    let selectedCinema = 'Todos';

    // Get movie ID from URL
    const urlParams = new URLSearchParams(window.location.search);
    const movieId = urlParams.get('id');

    // Load sessions for this movie
    async function loadSessions() {
        try {
            const url = movieId 
                ? `api/list-sessions.php?movie_id=${movieId}`
                : 'api/list-sessions.php';
            
            const response = await fetch(url);
            const data = await response.json();

            if (data.success) {
                allSessions = data.sessions;
                setupDates();
                setupCinemaFilters();
                displaySessions();
            }
        } catch (error) {
            console.error('Error loading sessions:', error);
        }
    }

    // Setup date tabs
    function setupDates() {
        if (!tabGroup) return;

        const dates = new Set();
        allSessions.forEach(session => {
            dates.add(session.session_date);
        });

        const sortedDates = Array.from(dates).sort().slice(0, 3); // First 3 dates
        
        tabGroup.innerHTML = '';
        
        sortedDates.forEach((date, index) => {
            const tab = document.createElement('div');
            tab.className = index === 0 ? 'tab active' : 'tab';
            tab.textContent = formatDate(date);
            tab.dataset.date = date;
            
            tab.addEventListener('click', function() {
                document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                selectedDate = this.dataset.date;
                displaySessions();
            });
            
            tabGroup.appendChild(tab);
        });

        selectedDate = sortedDates[0];
    }

    // Format date to Portuguese
    function formatDate(dateString) {
        const date = new Date(dateString + 'T00:00:00');
        const today = new Date();
        const tomorrow = new Date(today);
        tomorrow.setDate(tomorrow.getDate() + 1);

        today.setHours(0, 0, 0, 0);
        tomorrow.setHours(0, 0, 0, 0);
        date.setHours(0, 0, 0, 0);

        if (date.getTime() === today.getTime()) {
            return 'Hoje';
        } else if (date.getTime() === tomorrow.getTime()) {
            return 'Amanhã';
        } else {
            const weekdays = ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];
            const day = date.getDate().toString().padStart(2, '0');
            const month = (date.getMonth() + 1).toString().padStart(2, '0');
            const weekday = weekdays[date.getDay()];
            return `${weekday} ${day}/${month}`;
        }
    }

    // Setup cinema filters
    function setupCinemaFilters() {
        if (!filtersCol) return;

        const cinemas = new Set(['Todos']);
        allSessions.forEach(session => {
            cinemas.add(session.cinema_name);
        });

        filtersCol.innerHTML = '';

        cinemas.forEach(cinema => {
            const btn = document.createElement('button');
            btn.className = cinema === 'Todos' ? 'filter-btn active' : 'filter-btn';
            btn.textContent = cinema;
            
            btn.addEventListener('click', function() {
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                selectedCinema = cinema;
                displaySessions();
            });
            
            filtersCol.appendChild(btn);
        });
    }

    // Display filtered sessions
    function displaySessions() {
        if (!cardsCol) return;

        let filtered = allSessions;

        // Filter by date
        if (selectedDate) {
            filtered = filtered.filter(s => s.session_date === selectedDate);
        }

        // Filter by cinema
        if (selectedCinema && selectedCinema !== 'Todos') {
            filtered = filtered.filter(s => s.cinema_name === selectedCinema);
        }

        // Group by cinema
        const groupedByCinema = {};
        filtered.forEach(session => {
            if (!groupedByCinema[session.cinema_name]) {
                groupedByCinema[session.cinema_name] = {
                    name: session.cinema_name,
                    city: session.city,
                    sessions: []
                };
            }
            groupedByCinema[session.cinema_name].sessions.push(session);
        });

        // Display cards
        cardsCol.innerHTML = '';

        if (Object.keys(groupedByCinema).length === 0) {
            cardsCol.innerHTML = '<p style="padding: 20px; text-align: center;">Não há sessões disponíveis para a data selecionada.</p>';
            return;
        }

        Object.values(groupedByCinema).forEach(cinema => {
            const card = document.createElement('div');
            card.className = 'card';

            const cardName = document.createElement('div');
            cardName.className = 'card-name';
            cardName.textContent = `${cinema.name} - ${cinema.city}`;

            const cardTimes = document.createElement('div');
            cardTimes.className = 'card-times';

            // Sort sessions by time
            cinema.sessions.sort((a, b) => a.session_time.localeCompare(b.session_time));

            cinema.sessions.forEach(session => {
                const time = document.createElement('span');
                time.className = 'time';
                time.textContent = session.session_time.substring(0, 5); // HH:MM
                time.dataset.sessionId = session.id;
                time.dataset.availableSeats = session.available_seats;
                
                // Add click handler
                time.addEventListener('click', function() {
                    if (session.available_seats > 0) {
                        window.location.href = `checkout.html?session=${session.id}`;
                    } else {
                        alert('Esta sessão está esgotada.');
                    }
                });

                // Add visual indicator if sold out
                if (session.available_seats === 0) {
                    time.classList.add('sold-out');
                    time.style.opacity = '0.5';
                    time.style.cursor = 'not-allowed';
                }

                cardTimes.appendChild(time);
            });

            card.appendChild(cardName);
            card.appendChild(cardTimes);
            cardsCol.appendChild(card);
        });
    }

    // Initialize
    loadSessions();
});
