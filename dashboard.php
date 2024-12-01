<?php
include 'db_config.php';
include 'csrf.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$rol = $_SESSION['rol'];

// Obține numele utilizatorului
$query = "SELECT nume FROM utilizatori WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($nume_utilizator);
$stmt->fetch();
$stmt->close();

$csrf_token = generate_csrf_token();
$result = $conn->query("SELECT * FROM carti");

// Verifică dacă există un mesaj de eroare în sesiune
$error_id = isset($_SESSION['error_id']) ? $_SESSION['error_id'] : null;
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : null;

// Șterge mesajul de eroare din sesiune după afișare
unset($_SESSION['error_message']);
unset($_SESSION['error_id']);
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Biblioteca Studențească - Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="page-container">
        <div class="user-info">
            <span>Bine ai venit, <?php echo htmlspecialchars($nume_utilizator, ENT_QUOTES, 'UTF-8'); ?></span>
        </div>

        <div class="container">
            <h1>Cărțile disponibile</h1>
            <?php if ($rol === 'admin'): ?>
                <a href="add_book.php">Adaugă o carte</a> |
            <?php else: ?>
                <a href="cos.php">Vezi coșul de închiriere</a> |
            <?php endif; ?>
            <a href="logout.php">Deconectare</a>
            <ul>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <li>
                        <?php echo htmlspecialchars($row['titlu'], ENT_QUOTES, 'UTF-8'); ?> 
                        - Autor: <?php echo htmlspecialchars($row['autor'], ENT_QUOTES, 'UTF-8'); ?>
                        - Gen: <?php echo htmlspecialchars($row['gen'], ENT_QUOTES, 'UTF-8'); ?>
                        - <strong>Stoc: <?php echo $row['stoc']; ?></strong>
                        - <?php echo $row['stoc'] > 0 ? "Disponibil" : "Indisponibil"; ?>
                        
                        <?php if ($rol === 'admin'): ?>
                            - <a href="edit_book.php?id=<?php echo $row['id']; ?>">Editează</a> | 
                            <a href="delete_book.php?id=<?php echo $row['id']; ?>&csrf_token=<?php echo $csrf_token; ?>">Șterge</a>
                        <?php elseif ($rol === 'student'): ?>
                            - <a href="adauga_in_cos.php?id=<?php echo $row['id']; ?>&csrf_token=<?php echo $csrf_token; ?>">Adaugă în coș</a>
                        <?php endif; ?>

                        <!-- Afișează mesajul de eroare sub carte, dacă există o eroare pentru această carte -->
                        <?php if ($error_message && $error_id === $row['id']): ?>
                            <div class="error-message">
                                <?php echo htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8'); ?>
                            </div>
                        <?php endif; ?>
                    </li>
                <?php endwhile; ?>
            </ul>
        </div>
    </div>
</body>
</html>






