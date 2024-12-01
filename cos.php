<?php
include 'db_config.php';
include 'csrf.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'student') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$csrf_token = generate_csrf_token();

// Obține numele utilizatorului
$query = "SELECT nume FROM utilizatori WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($nume_utilizator);
$stmt->fetch();
$stmt->close();

// Obține cărțile din coșul utilizatorului curent
$query = "SELECT carti.id, carti.titlu, carti.autor FROM cos_inchiriere 
          JOIN carti ON cos_inchiriere.id_carte = carti.id 
          WHERE cos_inchiriere.id_utilizator = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Coș de închiriere</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="page-container">
        <!-- Afișează numele utilizatorului deasupra containerului principal -->
        <div class="user-info">
            <span>Bine ai venit, <?php echo htmlspecialchars($nume_utilizator, ENT_QUOTES, 'UTF-8'); ?></span>
        </div>

        <div class="container">
            <h1>Coșul meu de închiriere</h1>
            <a href="dashboard.php">Înapoi la catalog</a>
            <ul>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <li>
                        <?php echo htmlspecialchars($row['titlu'], ENT_QUOTES, 'UTF-8'); ?> 
                        - Autor: <?php echo htmlspecialchars($row['autor'], ENT_QUOTES, 'UTF-8'); ?>
                        - <a href="sterge_din_cos.php?id=<?php echo $row['id']; ?>&csrf_token=<?php echo $csrf_token; ?>">Șterge din coș</a>
                    </li>
                <?php endwhile; ?>
            </ul>
        </div>
    </div>
</body>
</html>



