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

// Verifică tokenul CSRF
if (!verify_csrf_token($_GET['csrf_token'])) {
    die("Token CSRF invalid.");
}

// Verifică dacă această carte este deja în coșul acestui utilizator
$query = "SELECT * FROM cos_inchiriere WHERE id_carte = ? AND id_utilizator = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $carte_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Dacă cartea este deja în coșul acestui utilizator, setează mesajul de eroare în sesiune
if ($result->num_rows > 0) {
    $_SESSION['error_message'] = "Această carte este deja în coșul tău.";
    $_SESSION['error_id'] = $carte_id;
    header("Location: dashboard.php");
    exit();
}

// Verifică dacă această carte este deja în coșul altui utilizator
$query = "SELECT * FROM cos_inchiriere WHERE id_carte = ? AND id_utilizator != ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $carte_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Setează mesajul de eroare în sesiune pentru cazul în care cartea este în coșul altui utilizator
    $_SESSION['error_message'] = "Cartea este momentan în coșul altui student.";
    $_SESSION['error_id'] = $carte_id;
    header("Location: dashboard.php");
    exit();
} else {
    // Adaugă cartea în coșul utilizatorului curent
    $stmt = $conn->prepare("INSERT INTO cos_inchiriere (id_utilizator, id_carte) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $carte_id);
    $stmt->execute();
    header("Location: dashboard.php");
}
$stmt->close();
?>

