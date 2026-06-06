<?php
declare(strict_types=1);

// Serve static files directly when using PHP built-in server
if (PHP_SAPI === 'cli-server') {
    $path = __DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if (is_file($path)) return false;
}

// Autoloader
spl_autoload_register(function (string $class): void {
    $map = [
        'App\\'  => __DIR__ . '/app/',
        'Core\\' => __DIR__ . '/core/',
    ];
    foreach ($map as $prefix => $dir) {
        if (str_starts_with($class, $prefix)) {
            $file = $dir . str_replace('\\', DIRECTORY_SEPARATOR, substr($class, strlen($prefix))) . '.php';
            if (file_exists($file)) { require_once $file; return; }
        }
    }
});

// Bootstrap
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/core/helpers.php';

// Router
$router = new Core\Router();
require_once __DIR__ . '/routes/website.php';
require_once __DIR__ . '/routes/dashboard.php';
$router->dispatch();
