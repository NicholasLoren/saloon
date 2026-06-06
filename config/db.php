<?php
define('DB_HOST',    getenv('DB_HOST')    ?: 'localhost');
define('DB_PORT',    getenv('DB_PORT')    ?: '3306');
define('DB_USER',    getenv('DB_USER')    ?: 'root');
define('DB_PASS',    getenv('DB_PASS')    ?: '');
define('DB_NAME',    getenv('DB_NAME')    ?: 'saloon_online');
define('BASE_URL',   getenv('BASE_URL')   ?: 'http://localhost:5000');
define('SALON_NAME', getenv('SALON_NAME') ?: "Matilda's Salon & Spa");
define('STORAGE_PATH', __DIR__ . '/../storage');

// Allow setup.php to run without triggering this check
$_isSetup = (basename($_SERVER['SCRIPT_NAME'] ?? '') === 'setup.php') ||
            (isset($_SERVER['REQUEST_URI']) && str_contains($_SERVER['REQUEST_URI'], '/setup.php'));

function _setup_redirect(): never {
    header('Location: ' . BASE_URL . '/setup.php');
    exit;
}

function _db_error_page(string $msg): never {
    $safeMsg = htmlspecialchars($msg, ENT_QUOTES, 'UTF-8');
    die('<!DOCTYPE html><html><head><title>DB Error</title>
    <style>*{box-sizing:border-box}body{font-family:sans-serif;background:#0d0d1a;display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0;color:#f0ede8}
    .box{background:rgba(255,255,255,.06);border:1px solid rgba(201,168,76,.3);backdrop-filter:blur(16px);padding:2rem 2.5rem;border-radius:16px;max-width:540px;width:90%}
    h3{color:#e8c97a;margin:0 0 .75rem}p{color:rgba(240,237,232,.6);font-size:.9rem;line-height:1.6}
    code{background:rgba(255,255,255,.1);padding:2px 8px;border-radius:6px;font-size:.85em}
    a{color:#c9a84c}</style></head><body>
    <div class="box"><h3>&#9888; Database Connection Failed</h3>
    <p>' . $safeMsg . '</p>
    <p>Check that MySQL is running, update credentials in <code>config/db.php</code> if needed, then visit <a href="' . BASE_URL . '/setup.php">setup.php</a>.</p>
    </div></body></html>');
}

// Step 1 — connect to MySQL server (no DB selected yet)
try {
    $pdo = new PDO(
        "mysql:host=".DB_HOST.";port=".DB_PORT.";charset=utf8mb4",
        DB_USER, DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    if ($_isSetup) { return; }   // let setup.php handle its own connection
    _db_error_page($e->getMessage());
}

if (!$_isSetup) {
    // Step 2 — check the database exists
    $dbExists = (bool) $pdo->query(
        "SELECT SCHEMA_NAME FROM information_schema.SCHEMATA WHERE SCHEMA_NAME = '" . DB_NAME . "'"
    )->fetch();

    if (!$dbExists) {
        _setup_redirect();
    }

    // Step 3 — select the database and check core tables exist
    $pdo->exec("USE `" . DB_NAME . "`");

    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    $required = ['users', 'services', 'appointments', 'staff', 'payments', 'inventory', 'notifications'];
    $missing  = array_diff($required, $tables);

    if (!empty($missing)) {
        _setup_redirect();
    }

    // Step 4 — check at least one admin user exists
    $hasAdmin = (bool) $pdo->query("SELECT id FROM users WHERE role='admin' LIMIT 1")->fetch();
    if (!$hasAdmin) {
        _setup_redirect();
    }
} else {
    // setup.php runs with a plain server connection — it creates the DB itself
    // Do not select a database here; setup.php handles that.
}
