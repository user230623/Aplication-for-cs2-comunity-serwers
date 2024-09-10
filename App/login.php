<?php

require_once('configs/passwords.php');

session_start();

if(!isset($_SESSION['wasLM']) && !$_SESSION['wasLM']){
    header("Location: ../index.php");
    exit;
}

$username = isset($_POST['username']) ? htmlspecialchars($_POST['username']) : null;
$password = isset($_POST['password']) ? htmlspecialchars($_POST['password']) : null;




if ($username !== null && $password !== null) {

    $_SESSION['userName'] = $username;

    $_SESSION['userPass'] = $password;



    if (($username == $admin['login'] && $password == $admin['password']) || 
    ($username == $guard['login'] && $password == $guard['password']) || 
    ($username == $anonymus['login'] && $password == $anonymus['password']) || 
    ($username == $admins['login'] && $password == $admins['password'])) {

        $_SESSION['wasLM'] = true;

        
        echo '<script>alert("Poprawnie zalogowano.");</script>';

        header('Location: program/main.php');

        exit();

    } else {

        // Błędne dane logowania

        $_SESSION['wasLM'] = false;

        echo '<script>alert("Błędne dane logowania");</script>';

        echo '<script>setTimeout(() => { window.location.href = "index.php"; }, 0);</script>';

        exit();

    }

} else {

    // Brak wprowadzonych danych

    echo '<script>alert("Wprowadź nazwę użytkownika i hasło.");</script>';

    echo '<script>setTimeout(() => { window.location.href = "login.php"; }, 0);</script>';

    exit();

}

?>

