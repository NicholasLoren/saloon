<?php
namespace App\Middleware;

class AuthMiddleware {
    public function handle(): void {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (empty($_SESSION['user_id'])) {
            $_SESSION['_flash'] = ['type' => 'warning', 'msg' => 'Please login to access the dashboard.'];
            header('Location: ' . BASE_URL . '/dashboard/login');
            exit;
        }

        if (!in_array($_SESSION['role'] ?? '', ['admin', 'staff'], true)) {
            $_SESSION['_flash'] = ['type' => 'error', 'msg' => 'Access denied. Insufficient privileges.'];
            header('Location: ' . BASE_URL . '/');
            exit;
        }
    }
}
