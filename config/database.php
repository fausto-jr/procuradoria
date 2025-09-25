<?php

define('DB_HOST', 'sql109.infinityfree.com');
define('DB_USER', 'if0_38685571');
define('DB_PASS', 'o03KxVRQCc9j4a');
define('DB_NAME', 'if0_38685571_procuradoria');

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
        DB_USER,
        DB_PASS,
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
    );
} catch(PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage();
    exit();
}