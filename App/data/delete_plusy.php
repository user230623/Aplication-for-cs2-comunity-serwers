<?php

session_start();



if(!isset($_SESSION['wasLM']) && !$_SESSION['wasLM']){

    header("Location: ../index.php");
    exit;

}



require_once ('../database/db.php');

require_once ('../configs/passwords.php');



$steamid = isset($_GET['steamid']) ? $_GET['steamid'] : null;



if(($_SESSION['userName'] !== $admin['login']) && ($_SESSION['userName'] !== $guard['login'])){

    echo '<script>alert("Nie masz do tego dostępu.");</script>';

    header('Location: dane.php');

    exit;

}



if ($steamid) {

    $stmt = $db->prepare("SELECT plusy FROM results WHERE steamid = :steamid");

    $stmt->execute([

        ':steamid' => $steamid,

    ]);

    $plusy = $stmt->fetchColumn(); 



    if ($plusy > 0) { 

        $plusy--; 

        $stmt2 = $db->prepare("UPDATE results SET plusy = :plusy WHERE steamid = :steamid");

        $stmt2->execute([

            ":plusy" => $plusy,

            ":steamid" => $steamid,

        ]);

    }

} else {

    echo '<script>alert("Błędne steamid.");</script>';

}



header('Location: dane.php');

exit;

?>

