<?php

namespace Core\Database;

use Core\Application;

class Migration
{
    private $builder;

    public function __construct()
    {
        $this->builder = Application::get('database');
        $this->createMigrationsTable();
    }

    public function doMigrations()
    {
        $appliedMigrations = $this->getAppliedMigrations();
        
        $newMigrations = [];
        $files = scandir('Database/migrations');
        $toApplyMigrations = array_diff($files, $appliedMigrations);

        
        foreach ($toApplyMigrations as $migration) {
            
            if ($migration === '.' || $migration === '..') {
                continue;
            }
            
            require_once 'Database/migrations/' . $migration;
            
            $className = pathinfo($migration, PATHINFO_FILENAME);
            $className = "\\Database\\migrations\\$className";
            $instance = new $className();

            $this->log("Applying migration $migration");
            $instance->up();
            $this->log("Applied migration $migration");
            $newMigrations[] = $migration;
        }

        if (!empty($newMigrations)) {
            $this->saveMigrations($newMigrations);
        } else {
            $this->log("No hay migraciones que aplicar");
        }
    }

    protected function getAppliedMigrations()
    {
        return $this->builder->selectAll("migrations", ['migration'], 'COLUMN');
    }

    protected function saveMigrations(array $newMigrations)
    {
        $str = implode(',', array_map(fn($m) => "('$m')", $newMigrations));
        return $this->builder->execute("INSERT INTO migrations (migration) VALUES $str");
    }

    private function createMigrationsTable()
    {
        $query = "CREATE TABLE IF NOT EXISTS migrations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )  ENGINE=INNODB;";

        $this->builder->execute($query);
    }

    private function log($message)
    {
        echo "[" . date("Y-m-d H:i:s") . "] - " . $message . '<br/>';
    }
}