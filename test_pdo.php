<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=bmwdatabase;charset=utf8mb4', 'root', '');
    echo 'PDO OK';
} catch (Exception $e) {
    echo get_class($e) . ': ' . $e->getMessage();
}
