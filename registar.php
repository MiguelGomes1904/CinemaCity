<?php
session_start();
require_once 'config.php';

$error_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');
    
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error_message = "Por favor, preencha todos os campos!";
    } elseif ($password !== $confirm_password) {
        $error_message = "As passwords não coincidem!";
    } elseif (strlen($password) < 6) {
        $error_message = "A password deve ter pelo menos 6 caracteres!";
    } else {
        // Verificar se o email já existe
        $sql_check = "SELECT id FROM usuarios WHERE email = '" . safe_input($email) . "'";
        $result_check = $conn->query($sql_check);
        
        if ($result_check->num_rows > 0) {
            $error_message = "Este email já está registado!";
        } else {
            // Inserir novo utilizador
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql_insert = "INSERT INTO usuarios (name, email, password) 
                          VALUES ('" . safe_input($name) . "', '" . safe_input($email) . "', '" . safe_input($hashed_password) . "')";
            
            if ($conn->query($sql_insert) === TRUE) {
                $_SESSION['user_id'] = $conn->insert_id;
                $_SESSION['user_email'] = $email;
                $_SESSION['user_name'] = $name;
                header('Location: index.php');
                exit();
            } else {
                $error_message = "Erro ao registar utilizador: " . $conn->error;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cinema City - Criar Conta</title>
    <link rel="stylesheet" href="login-style.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h1>Bem-vindo ao Cinema City</h1>
            <h2>Criar Conta</h2>
            
            <?php if (!empty($error_message)): ?>
                <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>
            
            <form class="login-form" method="POST" action="registar.php">
                <div class="form-group">
                    <label for="name">Nome Completo</label>
                    <input type="text" id="name" name="name" placeholder="" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="" required>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirmar Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="" required>
                </div>
                
                <button type="submit" class="btn-login">Criar Conta</button>
            </form>
            
            <div class="footer-links">
                <p>Já tem conta? <a href="login.php" class="create-account">Iniciar Sessão</a></p>
            </div>
        </div>
    </div>
</body>
</html>
