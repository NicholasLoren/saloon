<?php
namespace App\Controllers\Website;

use Core\View;
use App\Models\Service;
use App\Models\Promotion;
use App\Models\Staff;
use PDO;

class HomeController {
    public function __construct(private PDO $db) {}

    public function index(): void {
        $services   = (new Service($this->db))->all(true);
        $promotions = (new Promotion($this->db))->allActive();
        $staff      = (new Staff($this->db))->all();

        View::render('website/home/index', [
            'title'      => 'Home',
            'page'       => 'home',
            'services'   => $services,
            'promotions' => $promotions,
            'team'       => $staff,
        ]);
    }
}
