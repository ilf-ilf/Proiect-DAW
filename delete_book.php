<?php
include 'db_config.php';
include 'csrf.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    if (!verify_csrf_token($_GET['csrf_token'])) {
        die("Token CSRF invalid.");
    }

    $id = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM carti WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

header("Location: dashboard.php"); // Redirecționează la dashboard după ștergere
exit();
?>

