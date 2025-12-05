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
    <header>
        <nav class="navbar">
            <div class="logo"><img src="assets/logo.png" alt="Cinema City Logo"></div>
            <ul class="nav-links">
                <li><a href="#">Produtos</a></li>
                <li><a href="#">Serviços</a></li>
                <li><a href="#">Cinemas</a></li>
                <li><a href="#">Destaques</a></li>
            </ul>
            <div class="search-login">
                <input type="text" placeholder="Pesquise por filme, actores, realizadores">
                <?php if (isset($_SESSION['user_email'])): ?>
                    <button class="login-btn" onclick="window.location.href='index.php?logout=1'" style="background: #d32f2f;">Logout (<?php echo htmlspecialchars($_SESSION['user_name']); ?>)</button>
                    <?php if (isset($_GET['logout']) && $_GET['logout'] == 1) {
                        session_destroy();
                        header('Location: index.php');
                        exit();
                    } ?>
                <?php else: ?>
                    <button class="login-btn" onclick="window.location.href='login.php'">Login</button>
                <?php endif; ?>
            </div>
        </nav>
        <section class="hero" style="background-image: url('assets/FUNDO.jpg');">
            <div class="hero-content">
                <h2>Colin Farrell & Margot Robbie</h2>
                <h1>Uma Grande, Corajosa e Bela Viagem</h1>
                <p>Exclusivo em cinemas<br>18 de Setembro</p>
                <div class="carousel-dots">
                    <span></span><span></span><span></span><span></span><span></span>
                </div>
            </div>
        </section>
    </header>
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
                <img src="assets/capa1.jpg" alt="Depois da Caçaça">
                <div class="movie-info">
                    <h3>DEPOIS DA CAÇAÇA</h3>
                    <p class="genre">Animação</p>
                    <p class="rating">M12 - 97 min.</p>
                </div>
            </div>
            <div class="movie-card">
                <img src="assets/capa 2.jpg" alt="Gato Fantasma Anzu">
                <div class="movie-info">
                    <h3>GATO FANTASMA ANZU</h3>
                    <p class="genre">Animação</p>
                    <p class="rating">M12 - 97 min.</p>
                </div>
            </div>
            <div class="movie-card">
                <img src="assets/capa 3.jpg" alt="Um Ladrão no Telhado">
                <div class="movie-info">
                    <h3>UM LADRÃO NO TELHADO</h3>
                    <p class="genre">Animação</p>
                    <p class="rating">M12 - 97 min.</p>
                </div>
            </div>
            <div class="movie-card">
                <img src="assets/capa 4.jpg" alt="Partir Um Dia">
                <div class="movie-info">
                    <h3>PARTIR, UM DIA</h3>
                    <p class="genre">Animação</p>
                    <p class="rating">M12 - 97 min.</p>
                </div>
            </div>
            <div class="movie-card">
                <img src="assets/capa 5.jpg" alt="Snow Princesa">
                <div class="movie-info">
                    <h3>SNOW PRINCESA</h3>
                    <p class="genre">Animação</p>
                    <p class="rating">M12 - 97 min.</p>
                </div>
            </div>
            <div class="movie-card">
                <img src="assets/capa 6.jpg" alt="O Telefone Negro 2">
                <div class="movie-info">
                    <h3>O TELEFONE NEGRO 2</h3>
                    <p class="genre">Animação</p>
                    <p class="rating">M12 - 97 min.</p>
                </div>
            </div>
            <div class="movie-card">
                <img src="assets/capa 7.jpg" alt="Kantara">
                <div class="movie-info">
                    <h3>KANTARA</h3>
                    <p class="genre">Animação</p>
                    <p class="rating">M12 - 97 min.</p>
                </div>
            </div>
            <div class="movie-card">
                <img src="assets/capa 8.jpg" alt="Mr. Burton">
                <div class="movie-info">
                    <h3>MR. BURTON</h3>
                    <p class="genre">Animação</p>
                    <p class="rating">M12 - 97 min.</p>
                </div>
            </div>
            <div class="movie-card hidden">
                <img src="assets/capa 9.jpg" alt="Filme Extra 1">
                <div class="movie-info">
                    <h3>FILME EXTRA 1</h3>
                    <p class="genre">Animação</p>
                    <p class="rating">M12 - 97 min.</p>
                </div>
            </div>
            <div class="movie-card hidden">
                <img src="assets/capa 10.jpg" alt="Filme Extra 2">
                <div class="movie-info">
                    <h3>FILME EXTRA 2</h3>
                    <p class="genre">Animação</p>
                    <p class="rating">M12 - 97 min.</p>
                </div>
            </div>
            <div class="movie-card hidden">
                <img src="assets/capa 12.jpg" alt="Filme Extra 3">
                <div class="movie-info">
                    <h3>FILME EXTRA 3</h3>
                    <p class="genre">Animação</p>
                    <p class="rating">M12 - 97 min.</p>
                </div>
            </div>
            <div class="movie-card hidden">
                <img src="assets/capa 13.jpg" alt="Filme Extra 4">
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
    <footer>
        <div class="footer-links">
            <div>
                <h4>Serviços Exclusivos</h4>
                <ul>
                    <li>Festas Aniversário</li>
                </ul>
            </div>
            <div>
                <h4>Cinema City</h4>
                <ul>
                    <li>Contactos</li>
                    <li>Normas Cinema City</li>
                    <li>Parcerias</li>
                    <li>Publicidade</li>
                    <li>Trabalhe connosco</li>
                </ul>
            </div>
        </div>
    </footer>
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
    </script>
</body>
</html>
