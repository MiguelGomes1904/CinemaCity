<?php
session_start();
require_once 'config.php';

$error_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    if (empty($email) || empty($password)) {
        $error_message = "Por favor, preencha todos os campos!";
    } else {
        // Consultar a base de dados
        $sql = "SELECT * FROM usuarios WHERE email = '" . safe_input($email) . "'";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            // Verificar password
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_name'] = $user['name'];
                header('Location: index.php');
                exit();
            } else {
                $error_message = "Email ou password incorretos!";
            }
        } else {
            $error_message = "Email ou password incorretos!";
        }
    }
}

// Se o utilizador já está logado
if (isset($_SESSION['user_email'])) {
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cinema City - Login</title>
    <link rel="stylesheet" href="login-style.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h1>Bem-vindo ao Cinema City</h1>
            <h2>Iniciar Sessão</h2>
            
            <?php if (!empty($error_message)): ?>
                <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>
            
            <form class="login-form" method="POST" action="login.php">
                <div class="form-group">
                    <label for="email">Número de telefone ou email</label>
                    <input type="text" id="email" name="email" placeholder="" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="" required>
                </div>
                
                <button type="submit" class="btn-login">Entrar</button>
            </form>
            
            <button class="btn-google" onclick="alert('Google login em breve!')">
                <img src="https://www.gstatic.com/firebaseapp/v7.14.6/images/firebaseui/google.svg" alt="Google">
                Continuar com Google
            </button>
            
            <div class="footer-links">
                <a href="recuperar.php">Recuperar password</a>
                <p>Ainda não está registado? <a href="registar.php" class="create-account">Criar conta</a></p>
            </div>
        </div>
    </div>
</body>
</html>
