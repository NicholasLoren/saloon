<?php
namespace App\Controllers\Website;

use Core\View;
use App\Models\FAQ;
use PDO;

class FAQController {
    public function __construct(private PDO $db) {}

    public function index(): void {
        $faqs = (new FAQ($this->db))->all(true);
        // Group by category
        $grouped = [];
        foreach ($faqs as $faq) {
            $grouped[$faq['category']][] = $faq;
        }
        View::render('website/faq/index', ['title' => 'FAQ', 'page' => 'faq', 'grouped' => $grouped]);
    }
}
