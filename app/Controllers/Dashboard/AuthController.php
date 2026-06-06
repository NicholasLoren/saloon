<?php
namespace App\Controllers\Dashboard;

use Core\View;
use Core\Validator;
use PDO;

class AuthController {
    public function __construct(private PDO $db) {}

    public function login(): void {
        if (isLoggedIn()) { redirect('dashboard'); }
        View::render('dashboard/auth/login', ['title' => 'Login', 'layout' => 'auth', 'errors' => [], 'old' => []]);
    }

    public function authenticate(): void {
        $data = [
            'email'    => trim($_POST['email'] ?? ''),
            'password' => $_POST['password'] ?? '',
        ];

        $v = new Validator($data);
        $v->required('email')->email('email')->required('password');

        if ($v->fails()) {
            View::render('dashboard/auth/login', ['title' => 'Login', 'layout' => 'auth', 'errors' => $v->errors(), 'old' => $data]);
            return;
        }

        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ? AND role IN ('admin','staff')");
        $stmt->execute([$data['email']]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($data['password'], $user['password'])) {
            View::render('dashboard/auth/login', [
                'title'  => 'Login',
                'layout' => 'auth',
                'errors' => ['email' => 'Invalid email or password.'],
                'old'    => $data,
            ]);
            return;
        }

        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name']    = $user['name'];
        $_SESSION['email']   = $user['email'];
        $_SESSION['role']    = $user['role'];

        flash('success', 'Welcome back, ' . $user['name'] . '!');
        redirect('dashboard');
    }

    public function logout(): void {
        session_destroy();
        redirect('dashboard/login');
    }
}
