<?php
// Configuração da Base de Dados
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'cinema_city');

// Conectar à base de dados
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Criar base de dados se não existir
$sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;
if ($conn->query($sql) === TRUE) {
    // Selecionar a base de dados
    $conn->select_db(DB_NAME);
    
    // Criar tabela de utilizadores se não existir
    $create_table = "CREATE TABLE IF NOT EXISTS usuarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    if ($conn->query($create_table) !== TRUE) {
        die("Erro ao criar tabela: " . $conn->error);
    }
} else {
    die("Erro ao criar base de dados: " . $conn->error);
}

// Função para escapar strings (segurança)
function safe_input($data) {
    global $conn;
    return $conn->real_escape_string($data);
}
?>
