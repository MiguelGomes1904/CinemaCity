// Index Page JavaScript - Cinema City

// Tabs functionality
document.querySelectorAll('.category-tab').forEach(tab => {
    tab.addEventListener('click', function() {
        document.querySelectorAll('.category-tab').forEach(t => t.classList.remove('active'));
        this.classList.add('active');
    });
});

// Show more movies
document.getElementById('show-more-btn').addEventListener('click', function() {
    const hiddenMovies = document.querySelectorAll('.movie-card.hidden');
    hiddenMovies.forEach(movie => {
        movie.classList.remove('hidden');
    });
    this.style.display = 'none';
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
