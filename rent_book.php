<?php
include 'db_config.php';
include 'csrf.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'student') {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT stoc FROM carti WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($stoc);
$stmt->fetch();
$stmt->close();

if ($stoc > 0) {
    $new_stoc = $stoc - 1;
    $stmt = $conn->prepare("UPDATE carti SET stoc = ? WHERE id = ?");
    $stmt->bind_param("ii", $new_stoc, $id);
    $stmt->execute();
    $stmt->close();

    echo "Cartea a fost închiriată cu succes!";
} else {
    echo "Cartea nu este disponibilă.";
}
?>
