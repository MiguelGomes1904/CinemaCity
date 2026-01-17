// Movie data
const moviesData = [
    {
        title: "DEPOIS DA CAÇAÇA",
        englishTitle: "After The Hunt",
        image: "assets/images/gallery/capa1.jpg",
        genre: "Animação",
        rating: "M12 - 97 min.",
        meta: "M14 • Ação • 139min.",
        synopsis: "Um intenso drama psicológico sobre uma professora universitária (Julia Roberts) que se vê numa encruzilhada pessoal e profissional quando um aluno destacado faz uma acusação que muda tudo.",
        fullSynopsis: "AFTER THE HUNT é um intenso drama psicológico sobre uma professora universitária (Julia Roberts) que se vê numa encruzilhada pessoal e profissional quando um aluno destacado (Ayo Edebiri) faz uma acusação contra um dos seus colegas (Andrew Garfield), e um segredo sombrio do seu próprio passado ameaça vir à tona.",
        actors: "Julia Roberts, Ayo Edebiri, Andrew Garfield",
        director: "Luca Guadagnino",
        country: "Estados Unidos",
        releaseDate: "01-01-1970"
    },
    {
        title: "GATO FANTASMA ANZU",
        englishTitle: "Ghost Cat Anzu",
        image: "assets/images/gallery/capa 8.jpg",
        genre: "Animação",
        rating: "M12 - 97 min.",
        meta: "M12 • Animação • 97min.",
        synopsis: "Uma história sobre um gato fantasma misterioso.",
        fullSynopsis: "Uma história envolvente e misterioso sobre um gato fantasma chamado Anzu e suas aventuras.",
        actors: "Voice Cast",
        director: "Director TBD",
        country: "Japão",
        releaseDate: "01-01-1970"
    },
    {
        title: "UM LADRÃO NO TELHADO",
        englishTitle: "A Thief on the Roof",
        image: "assets/images/gallery/capa 3.jpg",
        genre: "Animação",
        rating: "M12 - 97 min.",
        meta: "M12 • Animação • 97min.",
        synopsis: "Uma aventura emocionante no telhado.",
        fullSynopsis: "Uma história emocionante de um ladrão astuto que rouba a noite no telhado da cidade.",
        actors: "Voice Cast",
        director: "Director TBD",
        country: "Portugal",
        releaseDate: "01-01-1970"
    },
    {
        title: "PARTIR, UM DIA",
        englishTitle: "Departure, One Day",
        image: "assets/images/gallery/capa 4.jpg",
        genre: "Animação",
        rating: "M12 - 97 min.",
        meta: "M12 • Animação • 97min.",
        synopsis: "Uma história sobre partida e despedida.",
        fullSynopsis: "Uma narrativa tocante sobre os momentos de despedida e as memórias que deixamos para trás.",
        actors: "Voice Cast",
        director: "Director TBD",
        country: "França",
        releaseDate: "01-01-1970"
    },
    {
        title: "SNOW PRINCESA",
        englishTitle: "Snow Princess",
        image: "assets/images/gallery/capa 5.jpg",
        genre: "Animação",
        rating: "M12 - 97 min.",
        meta: "M12 • Animação • 97min.",
        synopsis: "Uma princesa de gelo em uma aventura mágica.",
        fullSynopsis: "A história de uma princesa de gelo que descobre poderes mágicos e embarca em uma jornada épica.",
        actors: "Voice Cast",
        director: "Director TBD",
        country: "Dinamarca",
        releaseDate: "01-01-1970"
    },
    {
        title: "O TELEFONE NEGRO 2",
        englishTitle: "The Black Phone 2",
        image: "assets/images/gallery/capa 6.jpg",
        genre: "Animação",
        rating: "M12 - 97 min.",
        meta: "M12 • Animação • 97min.",
        synopsis: "A sequência do misterioso telefonista negro.",
        fullSynopsis: "A continuação da história assustadora onde um telefone negro antigo conecta os vivos com o desconhecido.",
        actors: "Voice Cast",
        director: "Director TBD",
        country: "Itália",
        releaseDate: "01-01-1970"
    }
];

// Get movie ID from URL
function getMovieIdFromUrl() {
    const params = new URLSearchParams(window.location.search);
    return parseInt(params.get('id')) || 0;
}

// Load movie data
function loadMovieData() {
    const movieId = getMovieIdFromUrl();
    const movie = moviesData[movieId] || moviesData[0];
    
    // Update poster
    const posterImg = document.querySelector('.poster-frame img');
    if (posterImg) {
        posterImg.src = movie.image;
        posterImg.alt = movie.title;
    }
    
    // Update page title
    document.title = movie.title + " — CinemaCity";
    
    // Update hero content
    const metaEl = document.querySelector('.hero-content .meta');
    if (metaEl) metaEl.textContent = movie.meta;
    
    const titleEl = document.querySelector('.hero-content h1');
    if (titleEl) titleEl.textContent = movie.title;
    
    const subtitleEl = document.querySelector('.hero-content .subtitle');
    if (subtitleEl) subtitleEl.textContent = movie.englishTitle;
    
    const synopsisEl = document.querySelector('.hero-content p');
    if (synopsisEl) synopsisEl.textContent = movie.synopsis;
    
    // Update synopsis section
    const fullSynopsisEl = document.querySelector('.synopsis .col-left p');
    if (fullSynopsisEl) fullSynopsisEl.textContent = movie.fullSynopsis;
    
    // Update details
    const actorsEl = document.querySelector('.col-right p:nth-of-type(1)');
    if (actorsEl) actorsEl.innerHTML = '<strong>Atores:</strong> ' + movie.actors;
    
    const directorEl = document.querySelector('.col-right p:nth-of-type(2)');
    if (directorEl) directorEl.innerHTML = '<strong>Realizador:</strong> ' + movie.director;
    
    const countryEl = document.querySelector('.col-right p:nth-of-type(3)');
    if (countryEl) countryEl.innerHTML = '<strong>País:</strong> ' + movie.country;
    
    const releaseEl = document.querySelector('.col-right p:nth-of-type(4)');
    if (releaseEl) releaseEl.innerHTML = '<strong>Estreia:</strong> ' + movie.releaseDate;
}

// Run on page load
document.addEventListener('DOMContentLoaded', loadMovieData);
