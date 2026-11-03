<?php
/**
 * API bootstrap: config, CORS, autoloading, and a shared service container.
 */

declare(strict_types=1);

require __DIR__ . '/config/config.php';
require __DIR__ . '/config/database.php';

// ---- Naive autoloader for helpers / middleware / models / controllers ----
spl_autoload_register(static function (string $class): void {
    foreach (['helpers', 'middleware', 'models', 'controllers'] as $dir) {
        $path = __DIR__ . '/' . $dir . '/' . $class . '.php';
        if (is_file($path)) {
            require $path;
            return;
        }
    }
});

// ---- CORS ------------------------------------------------------------
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if (in_array($origin, CORS_ALLOWED_ORIGINS, true)) {
    header("Access-Control-Allow-Origin: $origin");
}
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// ---- Global error / exception handling -> JSON -----------------------
set_exception_handler(static function (Throwable $e): void {
    $msg = APP_ENV === 'development' ? $e->getMessage() : 'Internal server error';
    Response::error($msg, 500);
});
