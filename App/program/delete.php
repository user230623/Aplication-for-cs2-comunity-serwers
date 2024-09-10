<?php
session_start();

if(!isset($_SESSION['wasLM']) && !$_SESSION['wasLM']){
    header("Location: ../index.php");
    exit;
}

require_once('../database/db.php');
require_once('../configs/passwords.php');

$username = isset($_SESSION['userName']) ? $_SESSION['userName'] : null;
$userpassword = isset($_SESSION['userPass']) ? $_SESSION['userPass'] : null;

if ($username !== $admin['login'] || $username !== $guard['login']) {
    echo '<script>alert("Nie masz do tego dostępu!");</script>';
    echo '<script>setTimeout(() => { window.location.href = "dane.php"; }, 0);</script>';
    exit(); 
}

$steamid = isset($_GET['steamid']) ? $_GET['steamid'] : null;

if ($steamid) {
    if (isset($_POST['confirm_delete'])) {
        try {
            if (!$db) {
                die("Błąd połączenia z bazą danych");
            }

            $stmt = $db->prepare('DELETE FROM results WHERE steamid = :steamid');

            $stmt->execute([
                ':steamid' => $steamid,
            ]);
            
            
            header('Location: ../data/dane.php');
            exit();
        } catch (PDOException $e) {
            echo 'Błąd zapytania SQL: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Potwierdzenie usunięcia rekordu</title>
</head>
<body>
    <label for="confirm_delete">Czy na pewno chcesz usunąć ten rekord?</label>
    <form method="post">
        <input type="submit" name="confirm_delete" value="Tak">
        <a href="../data/dane.php">Nie</a>
    </form>
</body>
</html>
