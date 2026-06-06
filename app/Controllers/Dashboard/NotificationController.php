<?php
namespace App\Controllers\Dashboard;

use Core\View;
use App\Models\Notification;
use PDO;

class NotificationController {
    public function __construct(private PDO $db) {}

    public function index(): void {
        $page    = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 15;
        $result  = (new Notification($this->db))->paginated($page, $perPage);

        View::render('dashboard/notifications/index', [
            'title'   => 'Notifications',
            'page'    => 'notifications',
            'rows'    => $result['data'],
            'total'   => $result['total'],
            'curPage' => $page,
            'perPage' => $perPage,
        ]);
    }

    // Mark single as read and redirect to the notification's target URL
    public function view(string $id): void {
        $model = new Notification($this->db);
        $notif = $model->find((int)$id);
        if (!$notif) {
            redirect('dashboard/notifications');
        }
        $model->markRead((int)$id);
        if (!empty($notif['url'])) {
            header('Location: ' . BASE_URL . '/' . ltrim($notif['url'], '/'));
            exit;
        }
        redirect('dashboard/notifications');
    }

    public function markAllRead(): void {
        (new Notification($this->db))->markAllRead();
        flash('success', 'All notifications marked as read.');
        redirect('dashboard/notifications');
    }

    public function delete(string $id): void {
        (new Notification($this->db))->delete((int)$id);
        flash('success', 'Notification deleted.');
        redirect('dashboard/notifications');
    }

    public function deleteAll(): void {
        (new Notification($this->db))->deleteAll();
        flash('success', 'All notifications cleared.');
        redirect('dashboard/notifications');
    }

    public function deleteRead(): void {
        (new Notification($this->db))->deleteRead();
        flash('success', 'Read notifications deleted.');
        redirect('dashboard/notifications');
    }
}
