<?php
session_start();
require_once 'config.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    
    if (empty($email)) {
        $message = "Por favor, introduza o seu email!";
    } else {
        $sql = "SELECT id FROM usuarios WHERE email = '" . safe_input($email) . "'";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            $message = "Email enviado com instruções de recuperação. (Simulado)";
        } else {
            $message = "Email não encontrado no sistema.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cinema City - Recuperar Password</title>
    <link rel="stylesheet" href="login-style.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h1>Bem-vindo ao Cinema City</h1>
            <h2>Recuperar Password</h2>
            
            <?php if (!empty($message)): ?>
                <div class="error-message"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
            
            <form class="login-form" method="POST" action="recuperar.php">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="" required>
                </div>
                
                <button type="submit" class="btn-login">Enviar Instruções</button>
            </form>
            
            <div class="footer-links">
                <p><a href="login.php" class="create-account">Voltar ao Login</a></p>
            </div>
        </div>
    </div>
</body>
</html>
