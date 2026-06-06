<?php
if (session_status() === PHP_SESSION_NONE) session_start();

function e(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

function money(float $n): string {
    return 'UGX ' . number_format($n, 0);
}

function fmtDate(string $d): string {
    return date('D, d M Y', strtotime($d));
}

function fmtTime(string $t): string {
    return date('g:i A', strtotime($t));
}

function flash(string $type, string $msg): void {
    $_SESSION['_flash'] = compact('type', 'msg');
}

function getFlash(): array {
    $f = $_SESSION['_flash'] ?? [];
    unset($_SESSION['_flash']);
    return $f;
}

function old(string $field, array $old = []): string {
    return e($old[$field] ?? ($_SESSION['_old'][$field] ?? ''));
}

function storeOld(array $data): void {
    $_SESSION['_old'] = $data;
}

function clearOld(): void {
    unset($_SESSION['_old']);
}

function statusBadge(string $status): string {
    $map = [
        'pending'   => 'bg-amber-500/20 text-amber-300 border border-amber-500/30',
        'confirmed' => 'bg-blue-500/20 text-blue-300 border border-blue-500/30',
        'completed' => 'bg-green-500/20 text-green-300 border border-green-500/30',
        'cancelled' => 'bg-red-500/20 text-red-300 border border-red-500/30',
        'paid'      => 'bg-green-500/20 text-green-300 border border-green-500/30',
        'pending_payment' => 'bg-amber-500/20 text-amber-300 border border-amber-500/30',
        'refunded'  => 'bg-slate-500/20 text-slate-300 border border-slate-500/30',
        'active'    => 'bg-green-500/20 text-green-300 border border-green-500/30',
        'inactive'  => 'bg-slate-500/20 text-slate-300 border border-slate-500/30',
    ];
    $cls = $map[$status] ?? 'bg-slate-500/20 text-slate-300 border border-slate-500/30';
    return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium '.$cls.'">'.ucfirst(str_replace('_',' ',$status)).'</span>';
}

function uploadImage(string $field, string $folder): ?string {
    if (!isset($_FILES[$field]) || $_FILES[$field]['error'] !== UPLOAD_ERR_OK) {
        return null;
    }
    $file    = $_FILES[$field];
    $allowed = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
    $finfo   = new finfo(FILEINFO_MIME_TYPE);
    $mime    = $finfo->file($file['tmp_name']);

    if (!in_array($mime, $allowed, true)) return null;
    if ($file['size'] > 5 * 1024 * 1024) return null;

    $ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $name = uniqid('img_', true) . '.' . $ext;
    $dest = STORAGE_PATH . '/' . $folder . '/' . $name;

    if (move_uploaded_file($file['tmp_name'], $dest)) {
        return '/storage/' . $folder . '/' . $name;
    }
    return null;
}

function deleteStorageFile(?string $path): void {
    if (!$path) return;
    $file = STORAGE_PATH . str_replace('/storage', '', $path);
    if (file_exists($file)) unlink($file);
}

function asset(string $path): string {
    return BASE_URL . '/assets/' . ltrim($path, '/');
}

function url(string $path = '/'): string {
    return BASE_URL . '/' . ltrim($path, '/');
}

function storageUrl(?string $path): string {
    if (!$path) return asset('images/placeholder.jpg');
    return BASE_URL . $path;
}

function isLoggedIn(): bool {
    return !empty($_SESSION['user_id']);
}

function authUser(): array {
    return [
        'id'    => (int)($_SESSION['user_id'] ?? 0),
        'name'  => $_SESSION['name']  ?? '',
        'email' => $_SESSION['email'] ?? '',
        'role'  => $_SESSION['role']  ?? '',
    ];
}

function isAdmin(): bool { return ($_SESSION['role'] ?? '') === 'admin'; }
function isStaff(): bool { return ($_SESSION['role'] ?? '') === 'staff'; }

function redirect(string $path, int $code = 302): never {
    header('Location: ' . BASE_URL . '/' . ltrim($path, '/'), true, $code);
    exit;
}

function abort(int $code = 404): never {
    http_response_code($code);
    \Core\View::render('errors/' . $code, ['title' => "Error $code"]);
    exit;
}

function generateInvoiceNumber(): string {
    return 'INV-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));
}

function sortUrl(string $col, string $currentSort, string $currentDir, array $extra = []): string {
    $dir = ($currentSort === $col && $currentDir === 'asc') ? 'desc' : 'asc';
    $params = array_merge($extra, ['sort' => $col, 'dir' => $dir]);
    return '?' . http_build_query($params);
}

function sortIcon(string $col, string $currentSort, string $currentDir): string {
    if ($currentSort !== $col) return '<svg class="w-3 h-3 ms-1.5 text-slate-500" fill="currentColor" viewBox="0 0 24 24"><path d="M8 9l4-4 4 4m0 6l-4 4-4-4"/></svg>';
    $up = $currentDir === 'asc';
    return $up
        ? '<svg class="w-3 h-3 ms-1.5 text-gold-400" fill="currentColor" viewBox="0 0 24 24"><path d="M5 15l7-7 7 7"/></svg>'
        : '<svg class="w-3 h-3 ms-1.5 text-gold-400" fill="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"/></svg>';
}

function timeAgo(string $datetime): string {
    $diff = time() - strtotime($datetime);
    if ($diff < 60)         return 'just now';
    if ($diff < 3600)       return (int)($diff / 60) . 'm ago';
    if ($diff < 86400)      return (int)($diff / 3600) . 'h ago';
    if ($diff < 604800)     return (int)($diff / 86400) . 'd ago';
    if ($diff < 2592000)    return (int)($diff / 604800) . 'w ago';
    return date('d M Y', strtotime($datetime));
}
