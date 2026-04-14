Alpha Amaraa, [14 Apr 2026 at 13:32:50]:
<?php
/**
 * Git Webhook Deploy Script
 *
 * Хэрэглээ:
 *   1. git commit & push хийсний дараа
 *   2. https://yourdomain/deploy.php?token=YOUR_SECRET дуудна
 *   3. Сервер дээр git pull + Laravel cache clear автоматаар ажиллана
 *
 * Тохиргоо:
 *   - SSH key: сервер дээр GitHub-руу ssh key бүртгэсэн байх (git pull ажиллахын тулд)
 *   - Файлын эрх: www-data/apache хэрэглэгч git pull хийх эрхтэй байх
 */

// ============================================================
// CONFIG — энд тохируулна
// ============================================================
$config = [
    'secret_token'  => 'es2026deploy',                        // URL-д ?token=xxx гэж дамжуулна
    'repo_path'     => '/opt/sites/bloodcenter.mn',           // Сервер дээрх project зам
    'branch'        => 'main',                                // Git branch
    'php_binary'    => 'php',                                 // php binary зам (жнь: /usr/bin/php)
    'log_file'      => __DIR__ . '/../storage/logs/deploy.log',   // Deploy лог
];

// ============================================================
// AUTH CHECK
// ============================================================
$token = $_GET['token'] ?? $_SERVER['HTTP_X_DEPLOY_TOKEN'] ?? '';

if ($token !== $config['secret_token']) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Invalid token']);
    exit;
}

// ============================================================
// DEPLOY
// ============================================================
$startTime = microtime(true);
$output = [];
$errors = [];

// Helper: run command and capture output
function run($cmd, $cwd = null) {
    $descriptors = [
        0 => ['pipe', 'r'],
        1 => ['pipe', 'w'],
        2 => ['pipe', 'w'],
    ];
    $process = proc_open($cmd, $descriptors, $pipes, $cwd);
    if (!is_resource($process)) {
        return ['ok' => false, 'output' => "Failed to run: {$cmd}", 'code' => -1];
    }
    fclose($pipes[0]);
    $stdout = stream_get_contents($pipes[1]); fclose($pipes[1]);
    $stderr = stream_get_contents($pipes[2]); fclose($pipes[2]);
    $code = proc_close($process);
    return [
        'ok'     => $code === 0,
        'output' => trim($stdout . "\n" . $stderr),
        'code'   => $code,
    ];
}

$steps = [];
$repoPath = $config['repo_path'];
$php = $config['php_binary'];

// Step 1: git pull
$steps[] = ['name' => 'git pull', 'result' => run("git pull origin {$config['branch']}", $repoPath)];

// Step 2: composer install (if composer.lock changed)
$steps[] = ['name' => 'composer install', 'result' => run("composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --ignore-platform-reqs 2>&1", $repoPath)];

// Step 3: Laravel cache clear
$steps[] = ['name' => 'config:clear', 'result' => run("{$php} artisan config:clear", $repoPath)];
$steps[] = ['name' => 'view:clear',   'result' => run("{$php} artisan view:clear", $repoPath)];
$steps[] = ['name' => 'cache:clear',  'result' => run("{$php} artisan cache:clear", $repoPath)];
$steps[] = ['name' => 'route:clear',  'result' => run("{$php} artisan route:clear", $repoPath)];

// Step 4: Optimize (optional — production cache)
$steps[] = ['name' => 'config:cache', 'result' => run("{$php} artisan config:cache", $repoPath)];
$steps[] = ['name' => 'route:cache',  'result' => run("{$php} artisan route:cache", $repoPath)];

// Step 5: File permissions
$steps[] = ['name' => 'permissions', 'result' => run("chmod -R 775 storage bootstrap/cache", $repoPath)];

$elapsed = round((microtime(true) - $startTime) * 1000);

// ============================================================
// BUILD RESPONSE
// ============================================================
$allOk = true;
$report = [];
foreach ($steps as $step) {
    $ok = $step['result']['ok'];
    if (!$ok) $allOk = false;
    $report[] = [
        'step'   => $step['name'],
        'status' => $ok ? 'OK' : 'FAIL',
        'output' => mb_substr($step['result']['output'], 0, 500),
    ];
}

$response = [
    'status'    => $allOk ? 'success' : 'partial_failure',
    'time_ms'   => $elapsed,
    'branch'    => $config['branch'],
    'timestamp' => date('Y-m-d H:i:s'),
    'steps'     => $report,
];

// Write to log
$logLine = date('Y-m-d H:i:s') . " | " . ($allOk ? 'OK' : 'FAIL') . " | {$elapsed}ms | " . $_SERVER['REMOTE_ADDR'] . "\n";
foreach ($report as $r) {
    $logLine .= "  [{$r['status']}] {$r['step']}: " . str_replace("\n", " ", mb_substr($r['output'], 0, 200)) . "\n";
}
$logLine .= "---\n";
@file_put_contents($config['log_file'], $logLine, FILE_APPEND | LOCK_EX);

// Output
header('Content-Type: application/json; charset=utf-8');
echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);