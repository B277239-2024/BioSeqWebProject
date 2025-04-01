<?php
$host = '127.0.0.1';
$dbname = 's2703447_BioSeqAnalysis';
$username = 's2703447';
$password = 'Boyuan-Zhou2024fall';

try{
$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
	die("Database Connection Failed: " . $e->getMessage());
}
?>
