<?php
if (!isset($_SESSION)) {
    session_start();
}

// Verifica se o usuário está logado
if (!isset($_SESSION['id'])) {
    die("Você não pode acessar esta página porque não está logado.<p><a href=\"index.php\">Entrar</a></p>");
}

// Define o tempo limite de inatividade em segundos (por exemplo, 30 minutos)
$inactiveTimeout = 1800;

// Verifica se a última atividade do usuário ocorreu há mais tempo que o tempo limite
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $inactiveTimeout)) {
    // Destroi a sessão
    session_destroy();
    die("Sua sessão expirou devido à inatividade. <p><a href=\"index.php\">Entrar novamente</a></p>");
}

// Atualiza o timestamp da última atividade do usuário
$_SESSION['last_activity'] = time();
?>
