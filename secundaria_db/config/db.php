<?php
// MOSTRAR ERRORES (SOLO DESARROLLO)
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
$host = 'localhost';
$db = 'secundaria_db';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("❌ Error BD: " . $e->getMessage());
}
?>