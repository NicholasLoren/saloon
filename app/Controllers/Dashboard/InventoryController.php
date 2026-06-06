<?php
namespace App\Controllers\Dashboard;

use Core\View;
use Core\Validator;
use App\Models\Inventory;
use PDO;

class InventoryController {
    public function __construct(private PDO $db) {}

    public function index(): void {
        $page    = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 10;
        $search  = trim($_GET['search'] ?? '');
        $sort    = $_GET['sort'] ?? 'product_name';
        $dir     = $_GET['dir'] ?? 'asc';

        $result = (new Inventory($this->db))->paginated($page, $perPage, $search, $sort, $dir);
        View::render('dashboard/inventory/index', [
            'title'   => 'Inventory',
            'page'    => 'inventory',
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
        View::render('dashboard/inventory/form', ['title' => 'Add Item', 'page' => 'inventory', 'item' => null, 'errors' => []]);
    }

    public function store(): void {
        $data   = $this->extractData();
        $errors = $this->validate($data);

        if ($errors) {
            View::render('dashboard/inventory/form', ['title' => 'Add Item', 'page' => 'inventory', 'item' => $data, 'errors' => $errors]);
            return;
        }

        (new Inventory($this->db))->create($data);
        flash('success', 'Item added to inventory.');
        redirect('dashboard/inventory');
    }

    public function edit(string $id): void {
        $item = (new Inventory($this->db))->find((int)$id);
        if (!$item) abort(404);
        View::render('dashboard/inventory/form', ['title' => 'Edit Item', 'page' => 'inventory', 'item' => $item, 'errors' => []]);
    }

    public function update(string $id): void {
        $data   = $this->extractData();
        $errors = $this->validate($data);

        if ($errors) {
            $item = (new Inventory($this->db))->find((int)$id) ?? $data;
            View::render('dashboard/inventory/form', ['title' => 'Edit Item', 'page' => 'inventory', 'item' => $item, 'errors' => $errors]);
            return;
        }

        (new Inventory($this->db))->update((int)$id, $data);
        flash('success', 'Item updated.');
        redirect('dashboard/inventory');
    }

    public function destroy(string $id): void {
        (new Inventory($this->db))->delete((int)$id);
        flash('success', 'Item removed.');
        redirect('dashboard/inventory');
    }

    private function extractData(): array {
        return [
            'product_name'  => trim($_POST['product_name'] ?? ''),
            'category'      => trim($_POST['category'] ?? ''),
            'quantity'      => trim($_POST['quantity'] ?? '0'),
            'unit'          => trim($_POST['unit'] ?? 'pcs'),
            'reorder_level' => trim($_POST['reorder_level'] ?? '5'),
            'cost_price'    => trim($_POST['cost_price'] ?? '0'),
            'supplier'      => trim($_POST['supplier'] ?? ''),
        ];
    }

    private function validate(array $d): array {
        $v = new Validator($d);
        $v->required('product_name')->min('product_name', 2, 'Product name')
          ->required('quantity')->numeric('quantity')
          ->required('reorder_level', 'Reorder level')->numeric('reorder_level', 'Reorder level');
        return $v->errors();
    }
}
