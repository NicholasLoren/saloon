<?php
namespace App\Controllers\Dashboard;

use Core\View;
use App\Models\ContactMessage;
use PDO;

class MessageController {
    public function __construct(private PDO $db) {}

    public function index(): void {
        $page    = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 10;
        $search  = trim($_GET['search'] ?? '');

        $result = (new ContactMessage($this->db))->paginated($page, $perPage, $search);
        View::render('dashboard/messages/index', [
            'title'   => 'Messages',
            'page'    => 'messages',
            'rows'    => $result['data'],
            'total'   => $result['total'],
            'curPage' => $page,
            'perPage' => $perPage,
            'search'  => $search,
        ]);
    }

    public function show(string $id): void {
        $model   = new ContactMessage($this->db);
        $message = $model->find((int)$id);
        if (!$message) abort(404);

        if (!$message['is_read']) {
            $model->markRead((int)$id);
            $message['is_read'] = 1;
        }

        View::render('dashboard/messages/show', [
            'title'   => 'Message from ' . $message['name'],
            'page'    => 'messages',
            'message' => $message,
        ]);
    }

    public function destroy(string $id): void {
        (new ContactMessage($this->db))->delete((int)$id);
        flash('success', 'Message deleted.');
        redirect('dashboard/messages');
    }
}
