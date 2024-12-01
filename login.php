<?php
include 'db_config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $parola = $_POST['parola'];

    $stmt = $conn->prepare("SELECT id, parola, rol FROM utilizatori WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($id, $hashed_password, $rol);
    $stmt->fetch();

    if (password_verify($parola, $hashed_password)) {
        $_SESSION['user_id'] = $id;
        $_SESSION['rol'] = $rol; // Salvăm rolul utilizatorului în sesiune
        header("Location: dashboard.php");
    } else {
        echo "Email sau parolă incorectă.";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Autentificare</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Autentificare</h1>
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="parola" placeholder="Parola" required><br>
            <input type="submit" value="Autentificare">
        </form>
        <div class="nav-links">
            <a href="index.php">Înapoi la Acasă</a> | <a href="register.php">Înregistrare</a>
        </div>
    </div>
</body>
</html>