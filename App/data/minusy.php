<?php

session_start();



if(!isset($_SESSION['wasLM']) && !$_SESSION['wasLM']){

    header("Location: ../index.php");
    exit;

}



require_once ('../database/db.php');

require_once ('../configs/passwords.php');



if ($_SESSION['userName'] !== $admin['login'] && $_SESSION['userName'] !== $guard['login']) {

    echo '<script>alert("Nie masz do tego dostępu.");</script>';

    header('Location: dane.php');

    exit; 

}



$steamid = isset($_GET['steamid']) ? $_GET['steamid'] : null;



if ($steamid) {

    $stmt = $db->prepare("SELECT minusy FROM results WHERE steamid = :steamid");

    $stmt->execute([

        ':steamid' => $steamid,

    ]);

    $minusy = $stmt->fetchColumn(); 



    $minusy++; 

    $stmt2 = $db->prepare("UPDATE results SET minusy = :minusy WHERE steamid = :steamid");

    $stmt2->execute([

        ":minusy" => $minusy,

        ":steamid" => $steamid,

    ]);

    

} else {

    echo '<script>alert("Błędne steamid.");</script>';

}



header('Location: dane.php');

exit; 

?>

