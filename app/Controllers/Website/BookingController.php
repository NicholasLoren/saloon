<?php
namespace App\Controllers\Website;

use Core\View;
use Core\Validator;
use App\Models\Service;
use App\Models\Staff;
use App\Models\Appointment;
use App\Models\Payment;
use App\Models\Notification;
use PDO;

class BookingController {
    public function __construct(private PDO $db) {}

    public function index(): void {
        $services = (new Service($this->db))->all(true);
        $staff    = (new Staff($this->db))->findAvailable();
        View::render('website/book/index', [
            'title'    => 'Book Appointment',
            'page'     => 'book',
            'services' => $services,
            'staff'    => $staff,
            'errors'   => [],
            'old'      => [],
        ]);
    }

    public function store(): void {
        $data = [
            'client_name'       => trim($_POST['client_name'] ?? ''),
            'client_email'      => trim($_POST['client_email'] ?? ''),
            'client_phone'      => trim($_POST['client_phone'] ?? ''),
            'service_id'        => (int)($_POST['service_id'] ?? 0),
            'staff_id'          => !empty($_POST['staff_id']) ? (int)$_POST['staff_id'] : null,
            'appointment_date'  => trim($_POST['appointment_date'] ?? ''),
            'appointment_time'  => trim($_POST['appointment_time'] ?? ''),
            'notes'             => trim($_POST['notes'] ?? ''),
        ];

        $v = new Validator($data);
        $v->required('client_name')->min('client_name', 2, 'Your name')
          ->required('client_email')->email('client_email', 'Your email')
          ->required('client_phone', 'Phone number')->phone('client_phone', 'Phone number')
          ->required('service_id', 'Service')
          ->required('appointment_date', 'Date')->futureDate('appointment_date', 'Date')
          ->required('appointment_time', 'Time');

        if ($v->fails()) {
            $services = (new Service($this->db))->all(true);
            $staff    = (new Staff($this->db))->findAvailable();
            View::render('website/book/index', [
                'title'    => 'Book Appointment',
                'page'     => 'book',
                'services' => $services,
                'staff'    => $staff,
                'errors'   => $v->errors(),
                'old'      => $data,
            ]);
            return;
        }

        $apptModel = new Appointment($this->db);
        $id        = $apptModel->create($data);

        // Auto-create payment record
        $svc = (new Service($this->db))->find($data['service_id']);
        if ($svc) {
            (new Payment($this->db))->createForAppointment($id, $svc['price']);
        }

        // Notify admin
        $svcName = $svc['name'] ?? 'Unknown service';
        (new Notification($this->db))->create(
            'appointment',
            'New booking: ' . $data['client_name'],
            $svcName . ' on ' . date('D d M', strtotime($data['appointment_date'])) . ' at ' . date('g:i A', strtotime($data['appointment_time'])),
            'dashboard/appointments/' . $id
        );

        flash('success', 'Your appointment has been booked! We will confirm it shortly. Check your email for details.');
        redirect('book?success=1');
    }

    public function slots(): void {
        header('Content-Type: application/json');
        $serviceId = (int)($_GET['service_id'] ?? 0);
        $staffId   = !empty($_GET['staff_id']) ? (int)$_GET['staff_id'] : null;
        $date      = trim($_GET['date'] ?? '');

        if (!$serviceId || !$date) {
            echo json_encode(['slots' => []]);
            return;
        }

        $slots = (new Appointment($this->db))->getSlots($date, $serviceId, $staffId);
        echo json_encode(['slots' => $slots]);
    }
}
