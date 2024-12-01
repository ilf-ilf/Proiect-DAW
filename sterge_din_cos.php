<?php
include 'db_config.php';
include 'csrf.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'student') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$carte_id = $_GET['id'];

if (!verify_csrf_token($_GET['csrf_token'])) {
    die("Token CSRF invalid.");
}

// Elimină cartea din coșul utilizatorului curent
$stmt = $conn->prepare("DELETE FROM cos_inchiriere WHERE id_utilizator = ? AND id_carte = ?");
$stmt->bind_param("ii", $user_id, $carte_id);
$stmt->execute();
$stmt->close();

// Redirecționează înapoi la pagina coșului
header("Location: cos.php");
exit();
?>
