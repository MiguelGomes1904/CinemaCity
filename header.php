<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
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
                <a class="login-btn" href="logout.php" style="background: #d32f2f;display:inline-block;padding:8px 12px;border-radius:6px;color:#fff;text-decoration:none;">Logout (<?php echo htmlspecialchars(
                    isset($_SESSION['user_name']) ? $_SESSION['user_name'] : $_SESSION['user_email']
                ); ?>)</a>
            <?php else: ?>
                <button class="login-btn" onclick="window.location.href='login.php'">Login</button>
            <?php endif; ?>
        </div>
    </nav>
</header>
