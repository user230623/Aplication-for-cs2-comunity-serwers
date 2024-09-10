<?php
require_once('../configs/config.php');

try {
    // Połączenie z bazą danych serwera
    $db = new PDO("mysql:host={$serwer_db['host']};dbname={$serwer_db['dbname']};charset=utf8", $serwer_db['user'], $serwer_db['pass']);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);


} catch (PDOException $e) {
    die('Błąd połączenia z bazą danych: ' . $e->getMessage());
}
?>
