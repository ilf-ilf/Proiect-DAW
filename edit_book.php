<?php
include 'db_config.php';
include 'csrf.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM carti WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$book = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'])) {
        die("Token CSRF invalid.");
    }

    $titlu = htmlspecialchars($_POST['titlu'], ENT_QUOTES, 'UTF-8');
    $autor = htmlspecialchars($_POST['autor'], ENT_QUOTES, 'UTF-8');
    $an_publicare = filter_var($_POST['an_publicare'], FILTER_VALIDATE_INT);
    $gen = htmlspecialchars($_POST['gen'], ENT_QUOTES, 'UTF-8');
    $stoc = filter_var($_POST['stoc'], FILTER_VALIDATE_INT);

    $stmt = $conn->prepare("UPDATE carti SET titlu = ?, autor = ?, an_publicare = ?, gen = ?, stoc = ? WHERE id = ?");
    $stmt->bind_param("ssisii", $titlu, $autor, $an_publicare, $gen, $stoc, $id);
    $stmt->execute();
    header("Location: dashboard.php");
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Editează cartea</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Editează cartea</h1>
        <form method="POST">
            <input type="text" name="titlu" value="<?php echo htmlspecialchars($book['titlu'], ENT_QUOTES, 'UTF-8'); ?>" required><br>
            <input type="text" name="autor" value="<?php echo htmlspecialchars($book['autor'], ENT_QUOTES, 'UTF-8'); ?>" required><br>
            <input type="number" name="an_publicare" value="<?php echo $book['an_publicare']; ?>" required><br>
            <input type="text" name="gen" value="<?php echo htmlspecialchars($book['gen'], ENT_QUOTES, 'UTF-8'); ?>" required><br>
            <input type="number" name="stoc" value="<?php echo $book['stoc']; ?>" required><br>
            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
            <button type="submit">Salvează</button>
        </form>
        <div class="nav-links">
            <a href="dashboard.php">Înapoi</a> <!-- Buton Înapoi -->
        </div>
    </div>
</body>
</html>
