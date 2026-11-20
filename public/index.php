<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config/database.php';

$employes = getPdo()->query('SELECT nom, prenom FROM employes LIMIT 5')->fetchAll();
foreach ($employes as $e) {
    echo htmlspecialchars($e['prenom'] . ' ' . $e['nom']) . '<br>';
}