<?php

declare(strict_types=1);

$root = dirname(__DIR__);
chdir($root);

function printLine(string $message = ''): void
{
    echo $message . PHP_EOL;
}

function runCommand(string $command): void
{
    printLine('');
    printLine('> ' . $command);
    passthru($command, $exitCode);

    if ($exitCode !== 0) {
        printLine('');
        printLine("Setup berhenti karena command gagal: {$command}");
        exit($exitCode);
    }
}

function envValue(string $key): ?string
{
    if (! file_exists('.env')) {
        return null;
    }

    foreach (file('.env', FILE_IGNORE_NEW_LINES) ?: [] as $line) {
        $line = trim($line);

        if ($line === '' || str_starts_with($line, '#') || ! str_contains($line, '=')) {
            continue;
        }

        [$name, $value] = explode('=', $line, 2);

        if (trim($name) === $key) {
            return trim($value, " \t\n\r\0\x0B\"'");
        }
    }

    return null;
}

printLine('Menyiapkan project Profil Kampung Mbu...');

if (! file_exists('.env')) {
    if (! file_exists('.env.example')) {
        printLine('File .env.example tidak ditemukan. Buat .env secara manual lalu ulangi setup.');
        exit(1);
    }

    copy('.env.example', '.env');
    printLine('File .env dibuat dari .env.example.');
} else {
    printLine('File .env sudah ada, tidak ditimpa.');
}

$databaseConnection = envValue('DB_CONNECTION') ?: 'sqlite';
$databaseName = envValue('DB_DATABASE');

if ($databaseConnection === 'sqlite' && ($databaseName === null || $databaseName === '')) {
    $sqlitePath = $root . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'database.sqlite';

    if (! file_exists($sqlitePath)) {
        touch($sqlitePath);
        printLine('Database SQLite dibuat di database/database.sqlite.');
    } else {
        printLine('Database SQLite sudah ada.');
    }
}

if (! file_exists('vendor/autoload.php')) {
    runCommand('composer install --no-interaction');
} else {
    printLine('Dependency Composer sudah ada, composer install dilewati.');
}

if ((envValue('APP_KEY') ?: '') === '') {
    runCommand('php artisan key:generate --ansi');
} else {
    printLine('APP_KEY sudah ada, tidak diganti.');
}

if (file_exists('public/storage')) {
    printLine('Storage link sudah ada, storage:link dilewati.');
} else {
    runCommand('php artisan storage:link --ansi');
}
runCommand('php artisan migrate --seed --force --ansi');

if (file_exists('package.json')) {
    if (file_exists('public/build/manifest.json')) {
        printLine('Asset frontend sudah tersedia di public/build, npm install dan build dilewati.');
    } elseif (! is_dir('node_modules')) {
        runCommand('npm install');
        runCommand('npm run build');
    } else {
        printLine('Dependency NPM sudah ada, npm install dilewati.');
        runCommand('npm run build');
    }
}

runCommand('php artisan optimize:clear --ansi');

printLine('');
printLine('Setup selesai.');
printLine('Jalankan server dengan: php artisan serve');
