<?php
namespace App\Controllers\Dashboard;

use Core\View;
use Core\Validator;
use App\Models\Service;
use PDO;

class ServiceController {
    public function __construct(private PDO $db) {}

    public function index(): void {
        $page    = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 10;
        $search  = trim($_GET['search'] ?? '');
        $sort    = $_GET['sort'] ?? 'id';
        $dir     = $_GET['dir'] ?? 'desc';

        $result = (new Service($this->db))->paginated($page, $perPage, $search, $sort, $dir);
        View::render('dashboard/services/index', [
            'title'   => 'Services',
            'page'    => 'services',
            'rows'    => $result['data'],
            'total'   => $result['total'],
            'curPage' => $page,
            'perPage' => $perPage,
            'search'  => $search,
            'sort'    => $sort,
            'dir'     => $dir,
        ]);
    }

    public function show(string $id): void {
        $service = (new Service($this->db))->find((int)$id);
        if (!$service) abort(404);
        View::render('dashboard/services/show', ['title' => 'Service Details', 'page' => 'services', 'service' => $service]);
    }

    public function create(): void {
        View::render('dashboard/services/form', ['title' => 'New Service', 'page' => 'services', 'service' => null, 'errors' => []]);
    }

    public function store(): void {
        $data   = $this->extractData();
        $errors = $this->validate($data);

        if ($errors) {
            View::render('dashboard/services/form', ['title' => 'New Service', 'page' => 'services', 'service' => $data, 'errors' => $errors]);
            return;
        }

        $image = uploadImage('image', 'services');
        if ($image) $data['image'] = $image;

        (new Service($this->db))->create($data);
        flash('success', 'Service "' . e($data['name']) . '" created.');
        redirect('dashboard/services');
    }

    public function edit(string $id): void {
        $service = (new Service($this->db))->find((int)$id);
        if (!$service) abort(404);
        View::render('dashboard/services/form', ['title' => 'Edit Service', 'page' => 'services', 'service' => $service, 'errors' => []]);
    }

    public function update(string $id): void {
        $data   = $this->extractData();
        $errors = $this->validate($data);

        if ($errors) {
            $service = (new Service($this->db))->find((int)$id) ?? $data;
            View::render('dashboard/services/form', ['title' => 'Edit Service', 'page' => 'services', 'service' => $service, 'errors' => $errors]);
            return;
        }

        $image = uploadImage('image', 'services');
        if ($image) {
            $old = (new Service($this->db))->find((int)$id);
            if ($old) deleteStorageFile($old['image']);
            $data['image'] = $image;
        }

        (new Service($this->db))->update((int)$id, $data);
        flash('success', 'Service updated.');
        redirect('dashboard/services/' . $id);
    }

    public function destroy(string $id): void {
        $service = (new Service($this->db))->find((int)$id);
        if ($service) deleteStorageFile($service['image']);
        (new Service($this->db))->delete((int)$id);
        flash('success', 'Service deleted.');
        redirect('dashboard/services');
    }

    private function extractData(): array {
        return [
            'name'             => trim($_POST['name'] ?? ''),
            'description'      => trim($_POST['description'] ?? ''),
            'price'            => trim($_POST['price'] ?? ''),
            'duration_minutes' => (int)($_POST['duration_minutes'] ?? 60),
            'category'         => trim($_POST['category'] ?? ''),
            'active'           => isset($_POST['active']) ? 1 : 0,
        ];
    }

    private function validate(array $d): array {
        $v = new Validator($d);
        $v->required('name')->min('name', 2)
          ->required('price')->positiveNumber('price')
          ->required('duration_minutes', 'Duration')->numeric('duration_minutes', 'Duration')
          ->required('category');
        return $v->errors();
    }
}
