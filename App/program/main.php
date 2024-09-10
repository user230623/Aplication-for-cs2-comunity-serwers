<?php

require_once('../database/db.php'); 

require_once('../configs/passwords.php'); 

session_start();

if(!isset($_SESSION['wasLM']) && !$_SESSION['wasLM']){
    header("Location: ../index.php");
    exit;
}

$username = isset($_SESSION['userName']) ? $_SESSION['userName'] : null;

$userpassword = isset($_SESSION['userPass']) ? $_SESSION['userPass'] : null;

$steamid = isset($_POST['steamid']) ? htmlspecialchars($_POST['steamid']) : null;

$dane_admin = [];

if($username !== $admin['login']) header('Location: ../data/dane.php'); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if ($username !== $admin['login'] && $usernanme !== $guard['login']) {

        echo '<script>alert("Nie masz do tego dostepu.");</script>';

        echo '<script>setTimeout(() => { window.location.href = "main.php"; }, 0);</script>';

    }else{

        if ($steamid) {

            try {

                $stmt = $db->prepare('SELECT adminName FROM qSpentTime_Players WHERE adminSteam = :steamid');

                $stmt->execute([

                    ':steamid' => $steamid,

                ]);

                $dane = $stmt->fetch(PDO::FETCH_ASSOC);


                if ($dane) {

                    $stmt_insert = $db->prepare('INSERT INTO results (adminSpect, steamid) VALUES (:adminSpect, :steamid)');

                    $stmt_insert->execute([

                        ':adminName' => $dane['adminName'],

                        ':steamid' => $steamid

                    ]);

                }   

            } catch (PDOException $e) {

                echo 'Błąd zapytania SQL: ' . $e->getMessage();

            }

        }

    }

}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="32x32" href="https://strefaskilla.pl/uploads/monthly_2024_02/fav.png.bfcab411934146cff191f333448a5df1.png">
    <title>Dodaj admina | StrefaSkilla.pl</title>
    <link rel="stylesheet" href="../styles/style.css">

</head>

<body>

<form action="" id="menudodajadmina" method="post">

    <label for="steamid">SteamID64:</label>
    <input type="text" id="steamid" name="steamid" required>
    <br>
    <button type="submit">Dodaj admina</button>

</form>

<a href="logout.php" id="wyloguj" >Wyloguj</a>

<a href="../data/dane.php"  id="pokazadminow" >Pokaz adminow</a>



</body>

</html>
