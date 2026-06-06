<?php
namespace App\Controllers\Dashboard;

use Core\View;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\Inventory;
use App\Models\Payment;
use App\Models\ContactMessage;
use PDO;

class HomeController {
    public function __construct(private PDO $db) {}

    public function index(): void {
        $apptModel = new Appointment($this->db);
        $payModel  = new Payment($this->db);

        $stats = [
            'today'        => $apptModel->countToday(),
            'pending'      => $apptModel->countPending(),
            'services'     => (new Service($this->db))->count(),
            'low_stock'    => (new Inventory($this->db))->lowStockCount(),
            'revenue'      => $payModel->totalRevenue(),
            'revenue_month'=> $payModel->revenueThisMonth(),
            'unread_msgs'  => (new ContactMessage($this->db))->countUnread(),
        ];

        $recent    = $apptModel->recentFive();
        $chartData = $apptModel->monthlyRevenue();

        View::render('dashboard/home/index', [
            'title'     => 'Dashboard',
            'page'      => 'dashboard',
            'stats'     => $stats,
            'recent'    => $recent,
            'chartData' => $chartData,
        ]);
    }
}
