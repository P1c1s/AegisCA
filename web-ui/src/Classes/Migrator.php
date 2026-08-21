<?php

class Migrator {
    public static function run(PDO $pdo, string $migrationsDir): void {
        // 1. Assicura la presenza della tabella di tracciamento
        $pdo->exec("CREATE TABLE IF NOT EXISTS schema_migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            version VARCHAR(255) NOT NULL UNIQUE,
            executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        // 2. Recupera le migrazioni già applicate
        $executed = $pdo->query("SELECT version FROM schema_migrations")->fetchAll(PDO::FETCH_COLUMN);

        // 3. Trova tutti i file .sql nella cartella Migrations
        $files = glob(rtrim($migrationsDir, '/') . '/*.sql');
        sort($files);

        // 4. Esegue solo i file non ancora registrati
        foreach ($files as $file) {
            $version = basename($file);

            if (!in_array($version, $executed)) {
                $sql = file_get_contents($file);
                
                try {
                    $pdo->beginTransaction();
                    $pdo->exec($sql);
                    
                    $stmt = $pdo->prepare("INSERT INTO schema_migrations (version) VALUES (?)");
                    $stmt->execute([$version]);
                    
                    $pdo->commit();
                } catch (\Exception $e) {
                    $pdo->rollBack();
                    throw new \Exception("Errore durante l'esecuzione della migrazione [$version]: " . $e->getMessage());
                }
            }
        }
    }
}