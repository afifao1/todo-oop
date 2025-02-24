<?php
require __DIR__."/credentials.php";
try {
    $db = new PDO("mysql:host=localhost;dbname=$database",$dbUser, $dbPassword);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Unable to connect to database: " . $e->getMessage());
}
?>