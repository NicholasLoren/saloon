<?php
namespace App\Controllers\Dashboard;

use Core\View;
use Core\Validator;
use App\Models\Staff;
use PDO;

class StaffController {
    public function __construct(private PDO $db) {}

    public function index(): void {
        $page    = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 10;
        $search  = trim($_GET['search'] ?? '');
        $sort    = $_GET['sort'] ?? 'id';
        $dir     = $_GET['dir'] ?? 'asc';

        $result = (new Staff($this->db))->paginated($page, $perPage, $search, $sort, $dir);
        View::render('dashboard/staff/index', [
            'title'   => 'Staff',
            'page'    => 'staff',
            'rows'    => $result['data'],
            'total'   => $result['total'],
            'curPage' => $page,
            'perPage' => $perPage,
            'search'  => $search,
            'sort'    => $sort,
            'dir'     => $dir,
        ]);
    }

    public function create(): void {
        View::render('dashboard/staff/form', ['title' => 'Add Staff', 'page' => 'staff', 'member' => null, 'availability' => [], 'errors' => []]);
    }

    public function store(): void {
        $data   = $this->extractData();
        $errors = $this->validate($data, true);

        if ($errors) {
            View::render('dashboard/staff/form', ['title' => 'Add Staff', 'page' => 'staff', 'member' => $data, 'availability' => [], 'errors' => $errors]);
            return;
        }

        $data['availability'] = $_POST['availability'] ?? [];
        (new Staff($this->db))->create($data);
        flash('success', 'Staff member added.');
        redirect('dashboard/staff');
    }

    public function edit(string $id): void {
        $model  = new Staff($this->db);
        $member = $model->find((int)$id);
        if (!$member) abort(404);
        $availability = $model->getAvailability((int)$id);
        View::render('dashboard/staff/form', ['title' => 'Edit Staff', 'page' => 'staff', 'member' => $member, 'availability' => $availability, 'errors' => []]);
    }

    public function update(string $id): void {
        $data   = $this->extractData();
        $errors = $this->validate($data, false);

        if ($errors) {
            $model        = new Staff($this->db);
            $member       = $model->find((int)$id) ?? $data;
            $availability = $model->getAvailability((int)$id);
            View::render('dashboard/staff/form', ['title' => 'Edit Staff', 'page' => 'staff', 'member' => $member, 'availability' => $availability, 'errors' => $errors]);
            return;
        }

        $data['availability'] = $_POST['availability'] ?? [];
        (new Staff($this->db))->update((int)$id, $data);
        flash('success', 'Staff member updated.');
        redirect('dashboard/staff');
    }

    public function destroy(string $id): void {
        (new Staff($this->db))->delete((int)$id);
        flash('success', 'Staff member removed.');
        redirect('dashboard/staff');
    }

    private function extractData(): array {
        $role = $_POST['role'] ?? 'staff';
        return [
            'name'           => trim($_POST['name'] ?? ''),
            'email'          => trim($_POST['email'] ?? ''),
            'password'       => $_POST['password'] ?? '',
            'phone'          => trim($_POST['phone'] ?? ''),
            'role'           => in_array($role, ['admin', 'staff'], true) ? $role : 'staff',
            'specialization' => trim($_POST['specialization'] ?? ''),
            'bio'            => trim($_POST['bio'] ?? ''),
            'available'      => isset($_POST['available']) ? 1 : 0,
        ];
    }

    private function validate(array $d, bool $passwordRequired): array {
        $v = new Validator($d);
        $v->required('name')->required('email')->email('email');
        if ($passwordRequired) {
            $v->required('password')->min('password', 6);
        } elseif (!empty($d['password'])) {
            $v->min('password', 6);
        }
        return $v->errors();
    }
}
