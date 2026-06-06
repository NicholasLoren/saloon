<?php
namespace App\Controllers\Website;

use Core\View;
use App\Models\Service;
use App\Models\Staff;
use PDO;

class ServicesController {
    public function __construct(private PDO $db) {}

    public function index(): void {
        $services   = (new Service($this->db))->all(true);
        $staff      = (new Staff($this->db))->findAvailable();
        $categories = (new Service($this->db))->categories();

        View::render('website/services/index', [
            'title'      => 'Our Services',
            'page'       => 'services',
            'services'   => $services,
            'staff'      => $staff,
            'categories' => $categories,
        ]);
    }
}
