<?php
$host = 'db.elkmovmnsjribxvoxwtx.supabase.co';
$db   = 'postgres';
$user = 'postgres';
$pass = 'klinikhewan123';
$port = "5432";

$dsn = "pgsql:host=$host;port=$port;dbname=$db";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
     echo "Connected successfully to Supabase!";
} catch (\PDOException $e) {
     echo "Connection failed: " . $e->getMessage();
}
