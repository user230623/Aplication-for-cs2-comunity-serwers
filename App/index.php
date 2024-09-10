<?php

session_start();

if (isset($_SESSION['wasLM']) && $_SESSION['wasLM']) {
    // Jeśli zmienna istnieje i ma wartość true
    header('Location: program/main.php');
    exit(); 
}

?>


<!DOCTYPE html>

<html lang="pl-PL">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="32x32" href="https://strefaskilla.pl/uploads/monthly_2024_02/fav.png.bfcab411934146cff191f333448a5df1.png">
    <title>Aktywność Adminów LOSOWE MOCE | StrefaSkilla.pl</title>
    <link rel="stylesheet" href="styles/style.css">

</head>

<body>

    <img src="https://strefaskilla.pl/uploads/monthly_2024_02/logo.webp.c9beb036601699a31e242e650fe458f2.webp" id="logo"></img>

    <form action="login.php" method="post">

        <label for="username">Nick:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="password">Hasło:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <button type="submit" id="form__button" >Zaloguj</button>



    </form>
<a href="../../index.html" id="wyborserwera" >Wybór serwera</a>
</body>

</html>

