<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class SistemaController extends Controller
{
    public function index()
    {
        return view('sistema.index');
    }

    public function backup()
    {
        $dumpBinary = $this->resolveDatabaseBinary('mysqldump');
        if (!$dumpBinary) {
            return back()->with('error', 'No se encontro mysqldump. Instala el cliente MySQL o agrega XAMPP al PATH.');
        }

        $dbConfig = $this->databaseConfig();
        $database = $dbConfig['database'];

        $filename = 'backup_' . date('Y_m_d_H_i_s') . '.sql';
        $path = storage_path('app/backups');

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $filePath = $path . DIRECTORY_SEPARATOR . $filename;
        $args = [
            $dumpBinary,
            '--single-transaction',
            '--routines',
            '--triggers',
            '--default-character-set=utf8mb4',
        ];

        $args = array_merge($args, $this->connectionArgs($dbConfig));
        $args[] = $database;

        $process = $this->makeProcess($args);
        $process->setTimeout(120);
        $process->run();

        if (!$process->isSuccessful()) {
            Log::error('Backup MySQL fallido: ' . $process->getErrorOutput());
            return back()->with('error', 'Error al generar la copia de seguridad. Revisa las credenciales de base de datos.');
        }

        file_put_contents($filePath, $process->getOutput());

        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    public function restore(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|extensions:sql,txt|max:51200',
        ]);

        $mysqlBinary = $this->resolveDatabaseBinary('mysql');
        if (!$mysqlBinary) {
            return back()->with('error', 'No se encontro mysql. Instala el cliente MySQL o agrega XAMPP al PATH.');
        }

        $file = $request->file('backup_file');
        $dbConfig = $this->databaseConfig();
        $database = $dbConfig['database'];

        $args = [
            $mysqlBinary,
            '--default-character-set=utf8mb4',
        ];

        $args = array_merge($args, $this->connectionArgs($dbConfig));
        $args[] = $database;

        $process = $this->makeProcess($args);
        $process->setInput(file_get_contents($file->getRealPath()));
        $process->setTimeout(180);
        $process->run();

        if (!$process->isSuccessful()) {
            Log::error('Restore MySQL fallido: ' . $process->getErrorOutput());
            return back()->with('error', 'Error al restaurar la base de datos. El archivo podria estar corrupto.');
        }

        return back()->with('success', 'Base de datos restaurada correctamente.');
    }

    public function reset(Request $request)
    {
        $request->validate([
            'confirm_text' => 'required|in:RESETEAR',
        ]);

        try {
            Artisan::call('migrate:fresh', ['--force' => true]);
            Artisan::call('db:seed', ['--force' => true]);

            Auth::logout();

            return redirect()->route('login')
                ->with('status', 'El sistema ha sido reseteado y repoblado con datos demo.');
        } catch (\Throwable $e) {
            Log::error('Error al resetear el sistema: ' . $e->getMessage());
            return back()->with('error', 'Ocurrio un error grave al resetear el sistema: ' . $e->getMessage());
        }
    }

    private function resolveDatabaseBinary(string $binary): ?string
    {
        $candidates = [
            "C:\\xampp\\mysql\\bin\\{$binary}.exe",
            "/usr/bin/{$binary}",
            "/usr/local/bin/{$binary}",
            $binary,
        ];

        foreach ($candidates as $candidate) {
            if ($candidate === $binary || file_exists($candidate)) {
                return $candidate;
            }
        }

        return null;
    }

    private function databaseConfig(): array
    {
        $connection = config('database.default', 'mysql');
        $config = config("database.connections.{$connection}", []);

        return [
            'host' => $config['host'] ?? '127.0.0.1',
            'port' => $config['port'] ?? 3306,
            'database' => $config['database'] ?? env('DB_DATABASE'),
            'username' => $config['username'] ?? env('DB_USERNAME'),
            'password' => $config['password'] ?? env('DB_PASSWORD'),
            'unix_socket' => $config['unix_socket'] ?? null,
        ];
    }

    private function connectionArgs(array $dbConfig): array
    {
        $args = [];

        if (!empty($dbConfig['unix_socket']) && PHP_OS_FAMILY !== 'Windows') {
            $args[] = "--socket={$dbConfig['unix_socket']}";
        } else {
            $args[] = '-h';
            $args[] = (string) $dbConfig['host'];
            $args[] = '-P';
            $args[] = (string) $dbConfig['port'];
        }

        $args[] = '-u';
        $args[] = (string) $dbConfig['username'];

        if (($dbConfig['password'] ?? '') !== '') {
            $args[] = "-p{$dbConfig['password']}";
        }

        return $args;
    }

    private function makeProcess(array $args): Process
    {
        return new Process($args, null, $this->processEnvironment());
    }

    private function processEnvironment(): array
    {
        if (PHP_OS_FAMILY !== 'Windows') {
            return [];
        }

        $systemRoot = getenv('SystemRoot') ?: ($_SERVER['SystemRoot'] ?? 'C:\\Windows');

        return [
            'SystemRoot' => $systemRoot,
            'WINDIR' => getenv('WINDIR') ?: ($_SERVER['WINDIR'] ?? $systemRoot),
            'ComSpec' => getenv('ComSpec') ?: ($_SERVER['ComSpec'] ?? $systemRoot . '\\System32\\cmd.exe'),
        ];
    }
}
