<?php
session_start();

if(!isset($_SESSION['wasLM']) && !$_SESSION['wasLM']){
    header("Location: ../index.php");
}

require_once ('../database/db.php');
require_once ('../configs/passwords.php');

try {
    
    $stmt_admin = $db->prepare('SELECT * FROM results');
    $stmt_admin->execute([]);
    $dane_admin = $stmt_admin->fetchAll(PDO::FETCH_ASSOC);

    $userName = isset($_SESSION['userName']) ? $_SESSION['userName'] : null;

    function formatujCzas($sekundy) {
        $minuty = floor($sekundy / 60);
        $godziny = floor($minuty / 60);
        $minutyReszta = $minuty % 60;
        return $godziny . 'h ' . $minutyReszta . 'm';
    }

    function ostatniaDataDlaAdmina($db, $steamid) {
        $stmt_last_date_per_admin = $db->prepare('SELECT MAX(adminDate) AS lastDate FROM qSpentTime_Players WHERE adminSteam = :steamid');
        $stmt_last_date_per_admin->execute([
            ':steamid' => $steamid
        ]);
        $last_date_result = $stmt_last_date_per_admin->fetch();
    
        if ($last_date_result['lastDate'] === null) {
            return 'Brak danych';
        }
    
        $lastDate = strtotime($last_date_result['lastDate']);
        $currentDate = time();
        $difference = $currentDate - $lastDate;
        $daysAgo = floor($difference / (60 * 60 * 24));
    
        switch($daysAgo){
            case 0:
                return 'Dziś';
            case 1:
                return 'Wczoraj';
            case 2:
                return 'Przedwczoraj';
            default:
                if ($daysAgo <= 30) return "$daysAgo dni temu";
                return "Miesiąc temu";
        }
    }

    function pobierzCzasAdminTime($db, $steamid) {
        try {
            $stmt_admin_time = $db->prepare(
                'SELECT SUM(adminTime) AS sumaAdminTime 
                FROM qSpentTime_Players 
                WHERE 
                    adminSteam = :steamid 
                    AND adminDate >= CURDATE() - INTERVAL (DAYOFWEEK(CURDATE()) - 2) DAY - INTERVAL 7 DAY 
                    AND adminDate < CURDATE() - INTERVAL (DAYOFWEEK(CURDATE()) - 2) DAY + INTERVAL 7 DAY '
            );
            $stmt_admin_time->execute([
                ':steamid' => $steamid
            ]);
            $czas_admina = $stmt_admin_time->fetch(PDO::FETCH_ASSOC);
    
            return isset($czas_admina['sumaAdminTime']) ? $czas_admina['sumaAdminTime'] : 0;
    
        } catch (PDOException $e) {
            echo "Błąd bazy danych: " . $e->getMessage();
            return 0;
        }
    }

    function pobierzCzasAdminSpect($db, $steamid) {
        try {
            $stmt_admin_spect = $db->prepare('SELECT SUM(adminSpect) AS sumaAdminSpect 
            ]                                 FROM qSpentTime_Players 
                                              WHERE 
                                                    adminSteam = :steamid 
                                                    AND adminDate >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)');
            $stmt_admin_spect->execute([
                ':steamid' => $steamid
            ]);
            $czas_admin_spect = $stmt_admin_spect->fetch(PDO::FETCH_ASSOC);
    
            return isset($czas_admin_spect['sumaAdminSpect']) ? $czas_admin_spect['sumaAdminSpect'] : 0;
    
        } catch (PDOException $e) {
            echo "Błąd bazy danych: " . $e->getMessage();
            return 0;
        }
    }
    

} catch (PDOException $e) {
    echo "Błąd bazy danych: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="32x32" href="https://strefaskilla.pl/uploads/monthly_2024_02/fav.png.bfcab411934146cff191f333448a5df1.png">
    <title>Lista adminów | StrefaSkilla.pl</title>
    <link rel="stylesheet" href="../styles/style.css">
</head>
<style>
    table {
        border-collapse: collapse;
        width: 100%;
    }

    table, th, td {
        border-bottom: 1px solid #00569d;
        border-radius: 5px;
        border: 1px solid #00569d;
    }

    th, td {
        padding: 10px;
        text-align: left;
    }
</style>
<body>
<?php if (!empty($dane_admin)): ?>
    <div class="ramki">
        <?php foreach ($dane_admin as $admin_dane): ?>
            <div class="ramka">
                <div class="group">
                    <p class="value" id="nick"><?php echo $admin_dane['adminName']; ?></p>
                </div>
                <div class="group">
                    <p class="label">Steam ID:</p>
                    <p class="value"><?php echo isset($admin_dane['steamid']) ? $admin_dane['steamid'] : ''; ?></p>
                </div>
                <div class="group">
                    <p class="label">Ostatnio Online:</p>
                    <p class="value"><?php echo ostatniaDataDlaAdmina($db, isset($admin_dane['steamid']) ? $admin_dane['steamid'] : ''); ?></p>
                </div>
                <div class="group">
                    <p class="label">Czas admina (ostatnie 7 dni):</p>
                    <?php 
                        $steamid = isset($admin_dane['steamid']) ? $admin_dane['steamid'] : '';
                        $czas_admina_time = pobierzCzasAdminTime($db, $steamid);
                        echo "<p class='value'>" . formatujCzas($czas_admina_time) . "</p>";
                    ?>
                </div>
                <div class="group">
                    <p class="label">Czas admina na spect (ostatnie 7 dni):</p>
                    <?php 
                        $steamid = isset($admin_dane['steamid']) ? $admin_dane['steamid'] : '';
                        $czas_admina_spect = pobierzCzasAdminSpect($db, $steamid);
                        echo "<p class='value'>" . formatujCzas($czas_admina_spect) . "</p>";
                    ?>
                </div>
                <div class="group" id="plusy">
                    <p class="label">Plusy:</p>
                    <p class="value"><?php echo isset($admin_dane['plusy']) ? $admin_dane['plusy'] : ''; ?></p>
                    <?php if(isset($userName) && ($userName === $admin['login']  || $userName === $guard['login'])): ?>
                        <p><a href="plusy.php?steamid=<?= $steamid; ?>">Dodaj plusa</a></p>
                        <p><a href="delete_plusy.php?steamid=<?= $steamid; ?>">Usuń plusa</a></p>
                    <?php endif?>
                </div>
                <div class="group" id="minusy">
                    <p class="label">Minusy:</p>
                    <p class="value"><?php echo isset($admin_dane['minusy']) ? $admin_dane['minusy'] : ''; ?></p>
                    <?php if(isset($userName) && ($userName === $admin['login']  || $userName === $guard['login'])): ?>
                        <p><a href="minusy.php?steamid=<?= $steamid; ?>">Dodaj minusa</a></p>
                        <p><a href="delete_minus.php?steamid=<?= $steamid; ?>">Usuń minusa</a></p>
                    <?php endif ?>
                </div>
                <div>
                    <?php if(isset($userName) && ($userName == $admin['login'] || $username !== $guard['login'])): ?>
                        <p><a href="../program/delete.php?steamid=<?= $steamid; ?>" id="delete">Usuń</a></p>
                    <?php endif ?>
                </div>
            </div>
        <?php endforeach; ?> 
    </div>
<?php else:?>
    <h1>BRAK ADMINÓW</h1>
<?php endif; ?>
<?php if(isset($userName) && ($userName == $admin['login'])): ?>
    <a href="../program/main.php" id="powrot">Powrót</a>
<?php endif ?>
<?php if(isset($userName) && ($userName !== $admin['login'])): ?>
    <a href="../program/logout.php" id="wylogujadmop" >Wyloguj</a>
<?php endif ?>


</body>
</html>
