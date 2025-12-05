<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cinema City - Home</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <section id="hero" class="hero">
        <div class="hero-content">
            <h2>Colin Farrell & Margot Robbie</h2>
            <h1>Uma Grande, Corajosa e Bela Viagem</h1>
            <p>Exclusivo em cinemas<br>18 de Setembro</p>
            <div class="carousel-dots">
                    <span data-index="0" class="active"></span>
                    <span data-index="1"></span>
                    <span data-index="2"></span>
                    <span data-index="3"></span>
                    <span data-index="4"></span>
            </div>
        </div>
    </section>
    <section class="booking-bar">
        <select><option>Escolha um Cinema</option></select>
        <select><option>Escolha um Filme</option></select>
        <select><option>Data</option></select>
        <select><option>Hora</option></select>
        <button>Comprar bilhetes</button>
    </section>
    <section class="categories">
        <ul class="category-tabs">
            <li class="category-tab active" data-category="big-vip">BIG VIP</li>
            <li class="category-tab" data-category="brevemente">BREVEMENTE</li>
            <li class="category-tab" data-category="em-exibicao">EM EXIBIÇÃO</li>
            <li class="category-tab" data-category="kids">KIDS</li>
            <li class="category-tab" data-category="pre-vendas">PRÉ-VENDAS</li>
        </ul>
        <select class="cinema-filter">
            <option>Todos os cinemas</option>
        </select>
    </section>
    <section class="movie-list">
        <!-- Cartazes dos filmes -->
        <div class="movies">
            <div class="movie-card">
                <a href="movie.php?id=1"><img src="assets/capa1.jpg" alt="Depois da Caçaça"></a>
                <div class="movie-info">
                    <h3>DEPOIS DA CAÇAÇA</h3>
                    <p class="genre">Animação</p>
                    <p class="rating">M12 - 97 min.</p>
                </div>
            </div>
            <div class="movie-card">
                <a href="movie.php?id=2"><img src="assets/capa 2.jpg" alt="Gato Fantasma Anzu"></a>
                <div class="movie-info">
                    <h3>GATO FANTASMA ANZU</h3>
                    <p class="genre">Animação</p>
                    <p class="rating">M12 - 97 min.</p>
                </div>
            </div>
            <div class="movie-card">
                <a href="movie.php?id=3"><img src="assets/capa 3.jpg" alt="Um Ladrão no Telhado"></a>
                <div class="movie-info">
                    <h3>UM LADRÃO NO TELHADO</h3>
                    <p class="genre">Animação</p>
                    <p class="rating">M12 - 97 min.</p>
                </div>
            </div>
            <div class="movie-card">
                <a href="movie.php?id=4"><img src="assets/capa 4.jpg" alt="Partir Um Dia"></a>
                <div class="movie-info">
                    <h3>PARTIR, UM DIA</h3>
                    <p class="genre">Animação</p>
                    <p class="rating">M12 - 97 min.</p>
                </div>
            </div>
            <div class="movie-card">
                <a href="movie.php?id=5"><img src="assets/capa 5.jpg" alt="Snow Princesa"></a>
                <div class="movie-info">
                    <h3>SNOW PRINCESA</h3>
                    <p class="genre">Animação</p>
                    <p class="rating">M12 - 97 min.</p>
                </div>
            </div>
            <div class="movie-card">
                <a href="movie.php?id=6"><img src="assets/capa 6.jpg" alt="O Telefone Negro 2"></a>
                <div class="movie-info">
                    <h3>O TELEFONE NEGRO 2</h3>
                    <p class="genre">Animação</p>
                    <p class="rating">M12 - 97 min.</p>
                </div>
            </div>
            <div class="movie-card">
                <a href="movie.php?id=7"><img src="assets/capa 7.jpg" alt="Kantara"></a>
                <div class="movie-info">
                    <h3>KANTARA</h3>
                    <p class="genre">Animação</p>
                    <p class="rating">M12 - 97 min.</p>
                </div>
            </div>
            <div class="movie-card">
                <a href="movie.php?id=8"><img src="assets/capa 8.jpg" alt="Mr. Burton"></a>
                <div class="movie-info">
                    <h3>MR. BURTON</h3>
                    <p class="genre">Animação</p>
                    <p class="rating">M12 - 97 min.</p>
                </div>
            </div>
            <div class="movie-card hidden">
                <a href="movie.php?id=9"><img src="assets/capa 9.jpg" alt="Filme Extra 1"></a>
                <div class="movie-info">
                    <h3>FILME EXTRA 1</h3>
                    <p class="genre">Animação</p>
                    <p class="rating">M12 - 97 min.</p>
                </div>
            </div>
            <div class="movie-card hidden">
                <a href="movie.php?id=10"><img src="assets/capa 10.jpg" alt="Filme Extra 2"></a>
                <div class="movie-info">
                    <h3>FILME EXTRA 2</h3>
                    <p class="genre">Animação</p>
                    <p class="rating">M12 - 97 min.</p>
                </div>
            </div>
            <div class="movie-card hidden">
                <a href="movie.php?id=11"><img src="assets/capa 12.jpg" alt="Filme Extra 3"></a>
                <div class="movie-info">
                    <h3>FILME EXTRA 3</h3>
                    <p class="genre">Animação</p>
                    <p class="rating">M12 - 97 min.</p>
                </div>
            </div>
            <div class="movie-card hidden">
                <a href="movie.php?id=12"><img src="assets/capa 13.jpg" alt="Filme Extra 4"></a>
                <div class="movie-info">
                    <h3>FILME EXTRA 4</h3>
                    <p class="genre">Animação</p>
                    <p class="rating">M12 - 97 min.</p>
                </div>
            </div>
        </div>
        <button class="show-more" id="show-more-btn">Mostrar mais filmes...</button>
    </section>
    <section class="most-viewed">
        <h2>FILMES MAIS VISTOS :</h2>
        <div class="top-movies">
            <div class="movie-card" data-rank="1">
                <img src="assets/capa 9.jpg" alt="The Conjuring 4">
                <div class="rank-number">1</div>
            </div>
            <div class="movie-card" data-rank="2">
                <img src="assets/capa 10.jpg" alt="Abril">
                <div class="rank-number">2</div>
            </div>
            <div class="movie-card" data-rank="3">
                <img src="assets/capa 12.jpg" alt="Jolly LLB 3">
                <div class="rank-number">3</div>
            </div>
        </div>
    </section>
    <section class="vip-pack">
        <img src="assets/banner.jpg" alt="VIP Pack" class="vip-banner">
    </section>
    <section class="promo-packs">
        <div class="pack">
            <img src="assets/promo.jpg" alt="Pack Quartas">
            <p>Pack Quartas - Pipoca Pequena, Bebida Média, Bilhete Cinema</p>
        </div>
        <div class="pack">
            <img src="assets/promo2.jpg" alt="Pack Total">
            <p>Pack Total - Pipocas Pequena, Bebida 50cl, Bilhete</p>
        </div>
    </section>
    <?php include 'footer.php'; ?>
    <script>
        // Tabs functionality
        document.querySelectorAll('.category-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.category-tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                console.log('Selected category:', this.getAttribute('data-category'));
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
            const images = [
                'assets/FUNDO.jpg',
                'assets/banner.jpg',
                'assets/promo.jpg',
                'assets/promo2.jpg',
                'assets/capa1.jpg'
            ];
            const hero = document.getElementById('hero');
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
            function resetTimer(){ clearInterval(timer); timer = setInterval(()=> setSlide(current+1), 5000); }
            // init
            setSlide(0);
        })();
    </script>
</body>
</html>
