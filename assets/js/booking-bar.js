// booking-bar.js
// Populates booking bar with real data from database

document.addEventListener('DOMContentLoaded', function() {
    const bookingBar = document.querySelector('.booking-bar');
    if (!bookingBar) return;

    const cinemaSelect = bookingBar.querySelectorAll('select')[0];
    const movieSelectOriginal = bookingBar.querySelectorAll('select')[1];
    const dateSelect = bookingBar.querySelectorAll('select')[2];
    const timeSelect = bookingBar.querySelectorAll('select')[3];
    const buyButton = bookingBar.querySelector('button');

    // Replace movie select with input for search
    const movieInputWrapper = document.createElement('div');
    movieInputWrapper.style.cssText = 'position: relative; width: 100%;';
    
    const movieInput = document.createElement('input');
    movieInput.type = 'text';
    movieInput.placeholder = 'Escolha um Filme';
    movieInput.id = 'movie-input';
    movieInput.autocomplete = 'off';
    movieInput.style.cssText = 'width: 100%; box-sizing: border-box; padding-right: 35px;';
    
    const movieToggle = document.createElement('span');
    movieToggle.textContent = '▼';
    movieToggle.style.cssText = 'position: absolute; right: 12px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #666; font-size: 12px; pointer-events: none;';
    
    const movieDropdown = document.createElement('div');
    movieDropdown.style.cssText = 'position: absolute; top: 100%; left: 0; right: 0; background: white; border: 1px solid #ddd; border-top: none; max-height: 300px; overflow-y: auto; display: none; z-index: 1000; box-shadow: 0 4px 6px rgba(0,0,0,0.1);';
    
    movieInputWrapper.appendChild(movieInput);
    movieInputWrapper.appendChild(movieToggle);
    movieInputWrapper.appendChild(movieDropdown);
    movieSelectOriginal.parentNode.replaceChild(movieInputWrapper, movieSelectOriginal);

    let allSessions = [];
    let allMovies = [];
    let selectedCinema = '';
    let selectedMovie = '';
    let selectedDate = '';
    let selectedTime = '';

    cinemaSelect.id = 'cinema-select';
    dateSelect.id = 'date-select';
    timeSelect.id = 'time-select';

    // Populate cinemas
    function populateCinemas(sessions) {
        const cinemas = new Map();
        sessions.forEach(session => {
            if (!cinemas.has(session.cinema_id)) {
                cinemas.set(session.cinema_id, {
                    id: session.cinema_id,
                    name: session.cinema_name,
                    city: session.city
                });
            }
        });

        cinemaSelect.innerHTML = '<option value="">Escolha um Cinema</option>';
        Array.from(cinemas.values()).forEach(cinema => {
            const option = document.createElement('option');
            option.value = cinema.id;
            option.textContent = `${cinema.name} - ${cinema.city}`;
            cinemaSelect.appendChild(option);
        });
        console.log('Cinemas:', Array.from(cinemas.values()));
    }

    // Populate movies
    function populateMovies(movies) {
        allMovies = movies;
        console.log('Movies:', movies);
    }

    // Populate dates
    function populateDates(sessions) {
        const dates = new Set();
        sessions.forEach(session => dates.add(session.session_date));

        dateSelect.innerHTML = '<option value="">Data</option>';
        Array.from(dates).sort().forEach(date => {
            const option = document.createElement('option');
            option.value = date;
            option.textContent = formatDate(date);
            dateSelect.appendChild(option);
        });
        console.log('Dates:', Array.from(dates).sort());
    }

    // Format date
    function formatDate(dateString) {
        const date = new Date(dateString + 'T00:00:00');
        const today = new Date();
        const tomorrow = new Date(today);
        tomorrow.setDate(tomorrow.getDate() + 1);

        today.setHours(0, 0, 0, 0);
        tomorrow.setHours(0, 0, 0, 0);
        date.setHours(0, 0, 0, 0);

        if (date.getTime() === today.getTime()) return 'Hoje';
        if (date.getTime() === tomorrow.getTime()) return 'Amanhã';

        const weekdays = ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];
        const day = date.getDate().toString().padStart(2, '0');
        const month = (date.getMonth() + 1).toString().padStart(2, '0');
        return `${weekdays[date.getDay()]} ${day}/${month}`;
    }

    // Filter sessions
    function filterSessions() {
        let filtered = allSessions;
        if (selectedCinema) filtered = filtered.filter(s => s.cinema_id == selectedCinema);
        if (selectedMovie) filtered = filtered.filter(s => s.movie_id == selectedMovie);
        if (selectedDate) filtered = filtered.filter(s => s.session_date === selectedDate);
        return filtered;
    }

    // Populate times
    function populateTimes() {
        const filtered = filterSessions();
        const times = new Set();
        filtered.forEach(session => times.add(session.session_time));

        timeSelect.innerHTML = '<option value="">Hora</option>';
        if (times.size === 0) {
            timeSelect.disabled = true;
            return;
        }

        timeSelect.disabled = false;
        Array.from(times).sort().forEach(time => {
            const option = document.createElement('option');
            option.value = time;
            option.textContent = time.substring(0, 5);
            timeSelect.appendChild(option);
        });
        console.log('Times:', Array.from(times).sort());
    }

    // Initialize times
    function initializeTimes() {
        const times = new Set();
        allSessions.forEach(session => times.add(session.session_time));

        timeSelect.innerHTML = '<option value="">Hora</option>';
        Array.from(times).sort().forEach(time => {
            const option = document.createElement('option');
            option.value = time;
            option.textContent = time.substring(0, 5);
            timeSelect.appendChild(option);
        });
        console.log('Initial times:', Array.from(times).sort());
    }

    // Filter movies dropdown
    function filterMovies(searchText) {
        const filtered = allMovies.filter(movie => 
            movie.title.toLowerCase().includes(searchText.toLowerCase())
        );
        
        movieDropdown.innerHTML = '';
        
        if (filtered.length > 0 || !searchText) {
            (searchText ? filtered : allMovies).forEach(movie => {
                const item = document.createElement('div');
                item.style.cssText = 'padding: 10px 15px; cursor: pointer; border-bottom: 1px solid #f0f0f0;';
                item.textContent = movie.title;
                item.dataset.id = movie.id;
                
                item.addEventListener('mouseenter', function() {
                    this.style.backgroundColor = '#f5f5f5';
                });
                
                item.addEventListener('mouseleave', function() {
                    this.style.backgroundColor = 'white';
                });
                
                item.addEventListener('click', function() {
                    movieInput.value = movie.title;
                    selectedMovie = movie.id;
                    movieDropdown.style.display = 'none';
                    populateTimes();
                });
                
                movieDropdown.appendChild(item);
            });
            movieDropdown.style.display = 'block';
        } else {
            movieDropdown.style.display = 'none';
        }
    }

    // Movie input events
    movieInput.addEventListener('input', function() {
        const searchText = this.value.trim();
        filterMovies(searchText);
        
        const exact = allMovies.find(m => m.title === searchText);
        if (exact) {
            selectedMovie = exact.id;
            populateTimes();
        } else {
            selectedMovie = '';
        }
    });

    movieToggle.addEventListener('click', function() {
        if (movieDropdown.style.display === 'none') {
            filterMovies('');
            movieInput.focus();
        } else {
            movieDropdown.style.display = 'none';
        }
    });

    movieInput.addEventListener('focus', function() {
        filterMovies(this.value.trim());
    });

    movieInput.addEventListener('blur', function() {
        setTimeout(() => {
            movieDropdown.style.display = 'none';
        }, 200);
    });

    document.addEventListener('click', function(e) {
        if (!movieInputWrapper.contains(e.target)) {
            movieDropdown.style.display = 'none';
        }
    });

    // Select events
    cinemaSelect.addEventListener('change', function() {
        selectedCinema = this.value;
        populateTimes();
    });

    dateSelect.addEventListener('change', function() {
        selectedDate = this.value;
        populateTimes();
    });

    timeSelect.addEventListener('change', function() {
        selectedTime = this.value;
    });

    buyButton.addEventListener('click', function() {
        if (!selectedCinema || !selectedMovie || !selectedDate || !selectedTime) {
            alert('Selecione todos os campos!');
            return;
        }

        const session = allSessions.find(s => 
            s.cinema_id == selectedCinema &&
            s.movie_id == selectedMovie &&
            s.session_date === selectedDate &&
            s.session_time === selectedTime
        );

        if (session) {
            window.location.href = `checkout-seats.html?session=${session.id}`;
        } else {
            alert('Sessão não encontrada.');
        }
    });

    // Load data
    async function loadData() {
        try {
            const moviesRes = await fetch('api/list-movies.php');
            const moviesData = await moviesRes.json();
            
            if (moviesData.success) {
                populateMovies(moviesData.movies);
            }

            const sessionsRes = await fetch('api/list-sessions.php');
            const sessionsData = await sessionsRes.json();
            
            if (sessionsData.success) {
                allSessions = sessionsData.sessions;
                populateCinemas(allSessions);
                populateDates(allSessions);
                initializeTimes();
                console.log('All data loaded successfully');
            }
        } catch (error) {
            console.error('Error loading data:', error);
        }
    }

    loadData();
});
