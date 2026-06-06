<?php
namespace App\Controllers\Website;

use Core\View;
use App\Models\Staff;
use PDO;

class AboutController {
    public function __construct(private PDO $db) {}

    public function index(): void {
        $team = (new Staff($this->db))->all();
        View::render('website/about/index', ['title' => 'About Us', 'page' => 'about', 'team' => $team]);
    }
}
