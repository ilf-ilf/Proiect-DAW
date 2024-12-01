<?php
session_start();

// Dacă utilizatorul este deja autentificat, îl redirecționăm către dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Biblioteca Studențească</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Bun venit la Biblioteca Studențească</h1>
        <p>Accesează și închiriază cărțile preferate online!</p>
        <div class="nav-links">
            <a href="login.php">Autentificare</a> |
            <a href="register.php">Înregistrare</a>
        </div>
    </div>
</body>
</html>