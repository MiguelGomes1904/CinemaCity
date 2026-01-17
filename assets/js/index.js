// Index Page JavaScript - Cinema City

// Tabs functionality
document.querySelectorAll('.category-tab').forEach(tab => {
    tab.addEventListener('click', function() {
        document.querySelectorAll('.category-tab').forEach(t => t.classList.remove('active'));
        this.classList.add('active');
    });
});

// Initialize: Hide movies after the first 4
(function() {
    const movieCards = document.querySelectorAll('.movie-list .movie-card');
    const initialVisible = 4;
    
    movieCards.forEach((card, index) => {
        if (index >= initialVisible) {
            card.classList.add('hidden');
        }
    });
    
    // Hide button if there are no extra movies
    const showMoreBtn = document.getElementById('show-more-btn');
    if (movieCards.length <= initialVisible) {
        showMoreBtn.style.display = 'none';
    }
})();

// Show more movies
document.getElementById('show-more-btn').addEventListener('click', function() {
    const hiddenMovies = document.querySelectorAll('.movie-list .movie-card.hidden');
    const showCount = 6; // Show 6 more movies at a time
    
    // Show next batch of movies
    for (let i = 0; i < showCount && i < hiddenMovies.length; i++) {
        hiddenMovies[i].classList.remove('hidden');
    }
    
    // Hide button if no more hidden movies
    const remainingHidden = document.querySelectorAll('.movie-list .movie-card.hidden');
    if (remainingHidden.length === 0) {
        this.style.display = 'none';
    }
});

// Hero carousel: cycles background images and updates dots
(function(){
    // Get images from data attribute
    const hero = document.getElementById('hero');
    const imagesData = hero ? hero.getAttribute('data-images') : null;
    if (!imagesData) return;
    
    const images = JSON.parse(imagesData);
    const dots = document.querySelectorAll('.carousel-dots span');
    let current = 0;
    
    function setSlide(i){
        current = (i + images.length) % images.length;
        hero.style.backgroundImage = "url('" + images[current] + "')";
        dots.forEach((d, idx) => d.classList.toggle('active', idx === current));
    }
    
    dots.forEach(d => d.addEventListener('click', function(){
        const idx = parseInt(this.getAttribute('data-index'));
        setSlide(idx);
        resetTimer();
    }));
    
    let timer = setInterval(()=> setSlide(current+1), 5000);
    function resetTimer(){ 
        clearInterval(timer); 
        timer = setInterval(()=> setSlide(current+1), 5000); 
    }
    
    // Initialize
    setSlide(0);
})();
