<?php
namespace App\Controllers\Dashboard;

use Core\View;
use Core\Validator;
use App\Models\Promotion;
use PDO;

class PromotionController {
    public function __construct(private PDO $db) {}

    public function index(): void {
        $promotions = (new Promotion($this->db))->all();
        View::render('dashboard/promotions/index', [
            'title'      => 'Promotions',
            'page'       => 'promotions',
            'promotions' => $promotions,
        ]);
    }

    public function create(): void {
        View::render('dashboard/promotions/form', [
            'title'     => 'Add Promotion',
            'page'      => 'promotions',
            'promotion' => null,
            'errors'    => [],
        ]);
    }

    public function store(): void {
        $data   = $this->extractData();
        $errors = $this->validate($data);

        if ($errors) {
            View::render('dashboard/promotions/form', ['title' => 'Add Promotion', 'page' => 'promotions', 'promotion' => $data, 'errors' => $errors]);
            return;
        }

        (new Promotion($this->db))->create($data);
        flash('success', 'Promotion created successfully.');
        redirect('dashboard/promotions');
    }

    public function edit(string $id): void {
        $promotion = (new Promotion($this->db))->find((int)$id);
        if (!$promotion) abort(404);
        View::render('dashboard/promotions/form', ['title' => 'Edit Promotion', 'page' => 'promotions', 'promotion' => $promotion, 'errors' => []]);
    }

    public function update(string $id): void {
        $data   = $this->extractData();
        $errors = $this->validate($data);

        if ($errors) {
            $promotion = (new Promotion($this->db))->find((int)$id) ?? $data;
            View::render('dashboard/promotions/form', ['title' => 'Edit Promotion', 'page' => 'promotions', 'promotion' => $promotion, 'errors' => $errors]);
            return;
        }

        (new Promotion($this->db))->update((int)$id, $data);
        flash('success', 'Promotion updated.');
        redirect('dashboard/promotions');
    }

    public function destroy(string $id): void {
        (new Promotion($this->db))->delete((int)$id);
        flash('success', 'Promotion deleted.');
        redirect('dashboard/promotions');
    }

    private function extractData(): array {
        return [
            'title'            => trim($_POST['title'] ?? ''),
            'description'      => trim($_POST['description'] ?? ''),
            'discount_percent' => trim($_POST['discount_percent'] ?? '0'),
            'start_date'       => trim($_POST['start_date'] ?? ''),
            'end_date'         => trim($_POST['end_date'] ?? ''),
            'active'           => isset($_POST['active']) ? 1 : 0,
        ];
    }

    private function validate(array $d): array {
        $v = new Validator($d);
        $v->required('title')->min('title', 3, 'Title')
          ->required('discount_percent', 'Discount')->numeric('discount_percent', 'Discount');
        return $v->errors();
    }
}
