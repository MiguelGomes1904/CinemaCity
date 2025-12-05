<?php
session_start();

// Se não está logado, redirecionar para login
if (!isset($_SESSION['user_email'])) {
    header('Location: login.php');
    exit();
}

// Logout
if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cinema City - Minha Conta</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .dashboard-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
            min-height: 60vh;
        }
        
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            padding: 20px;
            background: linear-gradient(135deg, #0a1f4d 0%, #1a3a70 100%);
            border-radius: 10px;
            color: #fff;
        }
        
        .dashboard-header h1 {
            font-size: 2em;
        }
        
        .logout-btn {
            background: #d32f2f;
            color: #fff;
            border: none;
            padding: 10px 25px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .logout-btn:hover {
            background: #b71c1c;
            transform: translateY(-2px);
        }
        
        .dashboard-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .dashboard-card {
            background: #f5f5f5;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .dashboard-card h2 {
            color: #0a1f4d;
            margin-bottom: 15px;
            font-size: 1.3em;
        }
        
        .dashboard-card p {
            color: #555;
            line-height: 1.6;
        }
        
        .dashboard-card .card-value {
            color: #d32f2f;
            font-size: 1.5em;
            font-weight: 700;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="logo"><img src="assets/logo.png" alt="Cinema City Logo"></div>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="#">Filmes</a></li>
                <li><a href="#">Cinemas</a></li>
                <li><a href="#">Destaques</a></li>
            </ul>
            <div class="search-login">
                <input type="text" placeholder="Pesquise por filme, actores, realizadores">
                <form method="POST" style="display: inline;">
                    <button type="submit" name="logout" class="login-btn">Logout</button>
                </form>
            </div>
        </nav>
    </header>
    
    <div class="dashboard-container">
        <div class="dashboard-header">
            <div>
                <h1>Bem-vindo, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
                <p>Email: <?php echo htmlspecialchars($_SESSION['user_email']); ?></p>
            </div>
        </div>
        
        <div class="dashboard-content">
            <div class="dashboard-card">
                <h2>📽️ Filmes Favoritados</h2>
                <p>Aqui aparecerão os seus filmes favoritados.</p>
                <div class="card-value">0</div>
            </div>
            
            <div class="dashboard-card">
                <h2>🎫 Bilhetes</h2>
                <p>Consulte os seus bilhetes e reservas.</p>
                <div class="card-value">0</div>
            </div>
            
            <div class="dashboard-card">
                <h2>💳 Método de Pagamento</h2>
                <p>Gerencie os seus métodos de pagamento.</p>
                <button class="btn-login" style="margin-top: 10px; padding: 8px 15px; width: auto;">Adicionar</button>
            </div>
            
            <div class="dashboard-card">
                <h2>🔐 Segurança</h2>
                <p>Alterar password e configurações de segurança.</p>
                <button class="btn-login" style="margin-top: 10px; padding: 8px 15px; width: auto;">Alterar Password</button>
            </div>
        </div>
    </div>
</body>
</html>
