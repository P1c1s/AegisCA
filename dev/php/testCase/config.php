<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=aegis_ca", "athena", "goat-snake-gorgon", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    echo "Connessione riuscita con successo!\n";
} catch (PDOException $e) {
    echo "ERRORE DI CONNESSIONE: " . $e->getMessage();
}
?>