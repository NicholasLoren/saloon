<?php
namespace App\Controllers\Dashboard;

use Core\View;
use App\Models\Payment;
use PDO;

class PaymentController {
    public function __construct(private PDO $db) {}

    public function index(): void {
        $page    = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 10;
        $search  = trim($_GET['search'] ?? '');
        $sort    = $_GET['sort'] ?? 'created_at';
        $dir     = $_GET['dir'] ?? 'desc';
        $status  = $_GET['status'] ?? '';

        $result = (new Payment($this->db))->paginated($page, $perPage, $search, $sort, $dir, $status);
        View::render('dashboard/payments/index', [
            'title'   => 'Payments',
            'page'    => 'payments',
            'rows'    => $result['data'],
            'total'   => $result['total'],
            'curPage' => $page,
            'perPage' => $perPage,
            'search'  => $search,
            'sort'    => $sort,
            'dir'     => $dir,
            'status'  => $status,
        ]);
    }

    public function show(string $id): void {
        $payment = (new Payment($this->db))->find((int)$id);
        if (!$payment) abort(404);
        View::render('dashboard/payments/show', ['title' => 'Invoice ' . $payment['invoice_number'], 'page' => 'payments', 'payment' => $payment]);
    }

    public function markPaid(string $id): void {
        $method = $_POST['payment_method'] ?? 'cash';
        (new Payment($this->db))->markPaid((int)$id, $method);
        flash('success', 'Payment marked as paid.');
        redirect('dashboard/payments/' . $id);
    }
}
