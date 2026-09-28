<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DbBackupCommand extends Command
{
    protected $signature = 'db:backup';

    protected $description = 'Create a SQLite backup before running database migrations.';

    public function handle(): int
    {
        $defaultConnection = config('database.default');

        if ($defaultConnection !== 'sqlite') {
            $this->error('This backup command supports SQLite databases only.');

            return self::FAILURE;
        }

        $databasePath = config('database.connections.sqlite.database');

        if (empty($databasePath)) {
            $this->error('No SQLite database path is configured.');

            return self::FAILURE;
        }

        $backupDir = database_path('backups');
        if (! is_dir($backupDir) && ! mkdir($backupDir, 0777, true) && ! is_dir($backupDir)) {
            $this->error('Could not create backup directory: ' . $backupDir);

            return self::FAILURE;
        }

        $sourcePath = $databasePath;
        $targetPath = $backupDir . '/database_' . now()->format('Ymd_His') . '.sqlite';

        if (! copy($sourcePath, $targetPath)) {
            $this->error('Could not create SQLite backup at: ' . $targetPath);

            return self::FAILURE;
        }

        $this->info('SQLite backup created successfully: ' . $targetPath);

        return self::SUCCESS;
    }
}
