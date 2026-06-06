<?php
namespace App\Controllers\Dashboard;

use Core\View;
use Core\Validator;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\Staff;
use App\Models\Payment;
use PDO;

class AppointmentController {
    public function __construct(private PDO $db) {}

    public function index(): void {
        $page    = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 10;
        $search  = trim($_GET['search'] ?? '');
        $sort    = $_GET['sort'] ?? 'created_at';
        $dir     = $_GET['dir'] ?? 'desc';
        $status  = $_GET['status'] ?? '';

        $result = (new Appointment($this->db))->paginated($page, $perPage, $search, $sort, $dir, $status);

        View::render('dashboard/appointments/index', [
            'title'   => 'Appointments',
            'page'    => 'appointments',
            'rows'    => $result['data'],
            'total'   => $result['total'],
            'curPage' => $page,
            'perPage' => $perPage,
            'search'  => $search,
            'sort'    => $sort,
            'dir'     => $dir,
            'status'  => $status,
        ]);
    }

    public function show(string $id): void {
        $appt = (new Appointment($this->db))->find((int)$id);
        if (!$appt) abort(404);

        View::render('dashboard/appointments/show', [
            'title' => 'Appointment #' . $id,
            'page'  => 'appointments',
            'appt'  => $appt,
        ]);
    }

    public function create(): void {
        $services = (new Service($this->db))->all(true);
        $staff    = (new Staff($this->db))->findAvailable();
        View::render('dashboard/appointments/form', [
            'title'    => 'New Appointment',
            'page'     => 'appointments',
            'appt'     => null,
            'services' => $services,
            'staff'    => $staff,
            'errors'   => [],
        ]);
    }

    public function store(): void {
        $data = $this->extractData();
        $errors = $this->validate($data);

        if ($errors) {
            $services = (new Service($this->db))->all(true);
            $staff    = (new Staff($this->db))->findAvailable();
            View::render('dashboard/appointments/form', ['title' => 'New Appointment', 'page' => 'appointments', 'appt' => $data, 'services' => $services, 'staff' => $staff, 'errors' => $errors]);
            return;
        }

        $id  = (new Appointment($this->db))->create($data);
        $svc = (new Service($this->db))->find((int)$data['service_id']);
        if ($svc) (new Payment($this->db))->createForAppointment($id, $svc['price']);

        flash('success', 'Appointment created successfully.');
        redirect('dashboard/appointments');
    }

    public function edit(string $id): void {
        $appt = (new Appointment($this->db))->find((int)$id);
        if (!$appt) abort(404);

        $services = (new Service($this->db))->all(true);
        $staff    = (new Staff($this->db))->findAvailable();
        View::render('dashboard/appointments/form', [
            'title'    => 'Edit Appointment',
            'page'     => 'appointments',
            'appt'     => $appt,
            'services' => $services,
            'staff'    => $staff,
            'errors'   => [],
        ]);
    }

    public function update(string $id): void {
        $data   = $this->extractData();
        $errors = $this->validate($data);

        if ($errors) {
            $appt     = (new Appointment($this->db))->find((int)$id) ?? $data;
            $services = (new Service($this->db))->all(true);
            $staff    = (new Staff($this->db))->findAvailable();
            View::render('dashboard/appointments/form', ['title' => 'Edit Appointment', 'page' => 'appointments', 'appt' => $appt, 'services' => $services, 'staff' => $staff, 'errors' => $errors]);
            return;
        }

        (new Appointment($this->db))->update((int)$id, $data);
        flash('success', 'Appointment updated.');
        redirect('dashboard/appointments/' . $id);
    }

    public function updateStatus(string $id): void {
        $status = $_POST['status'] ?? '';
        $allowed = ['pending','confirmed','completed','cancelled'];
        if (in_array($status, $allowed, true)) {
            (new Appointment($this->db))->updateStatus((int)$id, $status);
            flash('success', 'Status updated to ' . ucfirst($status) . '.');
        }
        redirect('dashboard/appointments/' . $id);
    }

    public function destroy(string $id): void {
        (new Appointment($this->db))->delete((int)$id);
        flash('success', 'Appointment deleted.');
        redirect('dashboard/appointments');
    }

    private function extractData(): array {
        return [
            'client_name'      => trim($_POST['client_name'] ?? ''),
            'client_email'     => trim($_POST['client_email'] ?? ''),
            'client_phone'     => trim($_POST['client_phone'] ?? ''),
            'service_id'       => (int)($_POST['service_id'] ?? 0),
            'staff_id'         => !empty($_POST['staff_id']) ? (int)$_POST['staff_id'] : null,
            'appointment_date' => trim($_POST['appointment_date'] ?? ''),
            'appointment_time' => trim($_POST['appointment_time'] ?? ''),
            'status'           => trim($_POST['status'] ?? 'pending'),
            'notes'            => trim($_POST['notes'] ?? ''),
        ];
    }

    private function validate(array $d): array {
        $v = new Validator($d);
        $v->required('client_name')->required('client_email')->email('client_email')
          ->required('service_id', 'Service')->required('appointment_date', 'Date')
          ->date('appointment_date', 'Date')->required('appointment_time', 'Time');
        return $v->errors();
    }
}
