<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class SafeMigrateCommand extends Command
{
    protected $signature = 'db:safe-migrate';

    protected $description = 'Create a SQLite backup and then run database migrations.';

    public function handle(): int
    {
        $defaultConnection = config('database.default');

        if ($defaultConnection !== 'sqlite') {
            $this->error('This script is designed for SQLite databases only.');

            return self::FAILURE;
        }

        $databasePath = config('database.connections.sqlite.database');
        $backupDir = database_path('backups');

        if (! is_dir($backupDir) && ! mkdir($backupDir, 0777, true) && ! is_dir($backupDir)) {
            $this->error('Could not create backup directory: ' . $backupDir);

            return self::FAILURE;
        }

        $backupFile = $backupDir . '/database_' . now()->format('Y-m-d_H-i-s') . '.sqlite';

        if (! copy($databasePath, $backupFile)) {
            $this->error('Could not create backup file at: ' . $backupFile);

            return self::FAILURE;
        }

        $this->info('Backup created: ' . $backupFile);

        $process = new Process(['php', 'artisan', 'migrate']);
        $process->setWorkingDirectory(base_path());
        $process->run(function ($type, $buffer) {
            $this->output->write($buffer);
        });

        if (! $process->isSuccessful()) {
            $this->error('Migration failed.');

            return self::FAILURE;
        }

        $this->info('Migrations completed successfully.');

        return self::SUCCESS;
    }
}
