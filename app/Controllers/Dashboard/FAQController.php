<?php
namespace App\Controllers\Dashboard;

use Core\View;
use Core\Validator;
use App\Models\FAQ;
use PDO;

class FAQController {
    public function __construct(private PDO $db) {}

    public function index(): void {
        $faqs = (new FAQ($this->db))->all(false);
        View::render('dashboard/faqs/index', [
            'title' => 'FAQs',
            'page'  => 'faqs',
            'faqs'  => $faqs,
        ]);
    }

    public function create(): void {
        View::render('dashboard/faqs/form', [
            'title'  => 'Add FAQ',
            'page'   => 'faqs',
            'faq'    => null,
            'errors' => [],
        ]);
    }

    public function store(): void {
        $data   = $this->extractData();
        $errors = $this->validate($data);

        if ($errors) {
            View::render('dashboard/faqs/form', ['title' => 'Add FAQ', 'page' => 'faqs', 'faq' => $data, 'errors' => $errors]);
            return;
        }

        (new FAQ($this->db))->create($data);
        flash('success', 'FAQ added successfully.');
        redirect('dashboard/faqs');
    }

    public function edit(string $id): void {
        $faq = (new FAQ($this->db))->find((int)$id);
        if (!$faq) abort(404);
        View::render('dashboard/faqs/form', ['title' => 'Edit FAQ', 'page' => 'faqs', 'faq' => $faq, 'errors' => []]);
    }

    public function update(string $id): void {
        $data   = $this->extractData();
        $errors = $this->validate($data);

        if ($errors) {
            $faq = (new FAQ($this->db))->find((int)$id) ?? $data;
            View::render('dashboard/faqs/form', ['title' => 'Edit FAQ', 'page' => 'faqs', 'faq' => $faq, 'errors' => $errors]);
            return;
        }

        (new FAQ($this->db))->update((int)$id, $data);
        flash('success', 'FAQ updated.');
        redirect('dashboard/faqs');
    }

    public function destroy(string $id): void {
        (new FAQ($this->db))->delete((int)$id);
        flash('success', 'FAQ deleted.');
        redirect('dashboard/faqs');
    }

    private function extractData(): array {
        return [
            'question'   => trim($_POST['question'] ?? ''),
            'answer'     => trim($_POST['answer'] ?? ''),
            'category'   => trim($_POST['category'] ?? 'General'),
            'sort_order' => (int)($_POST['sort_order'] ?? 0),
            'active'     => isset($_POST['active']) ? 1 : 0,
        ];
    }

    private function validate(array $d): array {
        $v = new Validator($d);
        $v->required('question')->min('question', 5, 'Question')
          ->required('answer')->min('answer', 5, 'Answer');
        return $v->errors();
    }
}
