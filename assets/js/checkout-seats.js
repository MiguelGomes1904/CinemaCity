// Checkout Seats JavaScript - Cinema City
// Get session ID from URL
const urlParams = new URLSearchParams(window.location.search);
const sessionId = urlParams.get('session');

let selectedSeats = [];
let sessionData = null;
let movieData = null;

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    if (!sessionId) {
        showError('Sessão não encontrada. Por favor, volte e selecione novamente.');
        return;
    }
    loadSessionData();
});

// Load session data
async function loadSessionData() {
    try {
        console.log('Loading session:', sessionId);
        const response = await fetch(`/CinemaCity/api/get-session.php?id=${sessionId}`);
        const data = await response.json();

        if (!data.success || !data.session) {
            showError('Erro ao carregar dados da sessão.');
            return;
        }

        sessionData = data.session;
        await loadMovieData();
        await loadSeats();
    } catch (error) {
        console.error('Error loading session:', error);
        showError('Erro ao conectar ao servidor.');
    }
}

// Load movie data
async function loadMovieData() {
    try {
        const response = await fetch(`/CinemaCity/api/get-movie.php?id=${sessionData.movie_id}`);
        const data = await response.json();

        if (data.success && data.movie) {
            movieData = data.movie;
            updateUIWithData();
        }
    } catch (error) {
        console.error('Error loading movie:', error);
    }
}

// Update UI with data
function updateUIWithData() {
    if (!sessionData || !movieData) return;

    // Update cinema name and address
    document.getElementById('cinemaName').textContent = sessionData.cinema_name || 'Cinema City';
    document.getElementById('cinemaAddress').innerHTML = 
        (sessionData.cinema_address || 'Avenida dos Cavaleiros 60, Portela de Carnaxide - 2790-045 Carnaxide').replace(/\n/g, '<br>');

    // Update movie and session info
    document.getElementById('movieTitle').textContent = movieData.title || 'Filme';
    
    const sessionDate = new Date(sessionData.session_date + 'T' + sessionData.session_time);
    const formattedDate = sessionDate.toLocaleDateString('pt-PT', { year: 'numeric', month: '2-digit', day: '2-digit' });
    const formattedTime = sessionData.session_time.substring(0, 5);
    document.getElementById('sessionDateTime').textContent = `${formattedDate} • ${formattedTime}`;
}

// Load and render seats
async function loadSeats() {
    try {
        const seatsGrid = document.getElementById('seatsGrid');
        seatsGrid.innerHTML = '';

        // For now, create a simple seat layout (10 columns x 8 rows = 80 seats)
        const rows = 8;
        const cols = 10;
        let seatNumber = 1;

        // Randomly reserve some seats for demonstration
        const reservedSeats = new Set();
        for (let i = 0; i < Math.floor(rows * cols * 0.3); i++) {
            reservedSeats.add(Math.floor(Math.random() * (rows * cols)) + 1);
        }

        for (let row = 1; row <= rows; row++) {
            for (let col = 1; col <= cols; col++) {
                const seat = document.createElement('div');
                const seatId = `${String.fromCharCode(64 + row)}${col}`;
                const isReserved = reservedSeats.has(seatNumber);

                seat.className = 'seat' + (isReserved ? ' reserved' : '');
                seat.textContent = seatId;
                seat.dataset.seatId = seatId;
                seat.dataset.seatNumber = seatNumber;

                if (!isReserved) {
                    seat.addEventListener('click', toggleSeat);
                }

                seatsGrid.appendChild(seat);
                seatNumber++;
            }
        }
    } catch (error) {
        console.error('Error loading seats:', error);
        showError('Erro ao carregar assentos.');
    }
}

// Toggle seat selection
function toggleSeat(event) {
    const seat = event.target.closest('.seat');
    if (!seat || seat.classList.contains('reserved')) return;

    const seatId = seat.dataset.seatId;

    if (seat.classList.contains('selected')) {
        seat.classList.remove('selected');
        selectedSeats = selectedSeats.filter(s => s !== seatId);
    } else {
        seat.classList.add('selected');
        selectedSeats.push(seatId);
    }

    updatePrice();
}

// Update price
function updatePrice() {
    const count = selectedSeats.length;
    const price = (sessionData?.price || 7.50) * count;

    document.getElementById('selectedSeatsCount').textContent = count;
    document.getElementById('pluralS').textContent = count === 1 ? '' : 'es';
    document.getElementById('totalPrice').textContent = price.toFixed(2).replace('.', ',') + '€';

    document.getElementById('checkoutBtn').disabled = count === 0;
}

// Proceed to checkout
function proceedToCheckout() {
    if (selectedSeats.length === 0) {
        alert('Por favor, selecione pelo menos um assento.');
        return;
    }

    // Store selected seats and redirect
    const queryString = new URLSearchParams({
        session: sessionId,
        seats: selectedSeats.join(','),
        totalPrice: document.getElementById('totalPrice').textContent.replace('€', '').replace(',', '.')
    }).toString();

    window.location.href = `/CinemaCity/checkout.html?${queryString}`;
}

// Show error
function showError(message) {
    const errorDiv = document.getElementById('seatsError');
    if (errorDiv) {
        errorDiv.textContent = message;
        errorDiv.style.display = 'block';
    }
    document.getElementById('seatsGrid').innerHTML = '';
}
