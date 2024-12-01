<?php
include 'db_config.php';
include 'csrf.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'])) {
        die("Token CSRF invalid.");
    }

    $titlu = htmlspecialchars($_POST['titlu'], ENT_QUOTES, 'UTF-8');
    $autor = htmlspecialchars($_POST['autor'], ENT_QUOTES, 'UTF-8');
    $an_publicare = filter_var($_POST['an_publicare'], FILTER_VALIDATE_INT);
    $gen = htmlspecialchars($_POST['gen'], ENT_QUOTES, 'UTF-8');
    $stoc = filter_var($_POST['stoc'], FILTER_VALIDATE_INT);

    $stmt = $conn->prepare("INSERT INTO carti (titlu, autor, an_publicare, gen, stoc) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssisi", $titlu, $autor, $an_publicare, $gen, $stoc);
    $stmt->execute();
    $stmt->close();

    header("Location: dashboard.php");
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Adaugă o carte</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Adaugă o carte</h1>
        <form method="POST">
            <input type="text" name="titlu" placeholder="Titlu" required><br>
            <input type="text" name="autor" placeholder="Autor" required><br>
            <input type="number" name="an_publicare" placeholder="Anul Publicării" required><br>
            <input type="text" name="gen" placeholder="Gen" required><br>
            <input type="number" name="stoc" placeholder="Stoc" required><br>
            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
            <button type="submit">Adaugă carte</button>
        </form>
        <div class="nav-links">
            <a href="dashboard.php">Înapoi</a> <!-- Buton Înapoi -->
        </div>
    </div>
</body>
</html>
