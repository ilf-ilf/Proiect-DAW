<?php
include 'db_config.php';
include 'csrf.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nume = htmlspecialchars($_POST['nume'], ENT_QUOTES, 'UTF-8');
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $parola = password_hash($_POST['parola'], PASSWORD_BCRYPT);
    $rol = 'student'; // Rolul implicit este „student”

    if (!verify_csrf_token($_POST['csrf_token'])) {
        die("Token CSRF invalid.");
    }

    // Inserăm utilizatorul cu rol de student
    $stmt = $conn->prepare("INSERT INTO utilizatori (nume, email, parola, rol) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nume, $email, $parola, $rol);
    $stmt->execute();
    $stmt->close();

    header("Location: login.php");
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Înregistrare</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Înregistrare</h1>
        <form method="POST">
            <input type="text" name="nume" placeholder="Nume" required><br>
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="parola" placeholder="Parola" required><br>
            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
            <input type="submit" value="Înregistrează-te">
        </form>
        <div class="nav-links">
            <a href="index.php">Înapoi la Acasă</a> | <a href="login.php">Autentificare</a>
        </div>
    </div>
</body>
</html>
