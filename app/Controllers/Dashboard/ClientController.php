<?php
namespace App\Controllers\Dashboard;

use Core\View;
use App\Models\Appointment;
use PDO;

class ClientController {
    public function __construct(private PDO $db) {}

    public function index(): void {
        $page    = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 10;
        $search  = trim($_GET['search'] ?? '');

        $result = (new Appointment($this->db))->uniqueClients($page, $perPage, $search);
        View::render('dashboard/clients/index', [
            'title'   => 'Clients',
            'page'    => 'clients',
            'rows'    => $result['data'],
            'total'   => $result['total'],
            'curPage' => $page,
            'perPage' => $perPage,
            'search'  => $search,
        ]);
    }

    public function show(string $email): void {
        $email   = urldecode($email);
        $apptModel = new Appointment($this->db);
        $history = $apptModel->clientHistory($email);

        if (empty($history)) abort(404);

        $client = ['name' => $history[0]['client_name'], 'email' => $history[0]['client_email'], 'phone' => $history[0]['client_phone']];
        View::render('dashboard/clients/show', [
            'title'   => 'Client: ' . e($client['name']),
            'page'    => 'clients',
            'client'  => $client,
            'history' => $history,
        ]);
    }
}
