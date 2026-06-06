<?php
namespace App\Controllers\Website;

use Core\View;
use Core\Validator;
use App\Models\ContactMessage;
use App\Models\Notification;
use PDO;

class ContactController {
    public function __construct(private PDO $db) {}

    public function index(): void {
        View::render('website/contact/index', ['title' => 'Contact Us', 'page' => 'contact', 'errors' => [], 'old' => []]);
    }

    public function submit(): void {
        $data = [
            'name'    => trim($_POST['name'] ?? ''),
            'email'   => trim($_POST['email'] ?? ''),
            'phone'   => trim($_POST['phone'] ?? ''),
            'subject' => trim($_POST['subject'] ?? ''),
            'message' => trim($_POST['message'] ?? ''),
        ];

        $v = new Validator($data);
        $v->required('name')->min('name', 2)
          ->required('email')->email('email')
          ->required('message')->min('message', 10);

        if ($v->fails()) {
            View::render('website/contact/index', ['title' => 'Contact Us', 'page' => 'contact', 'errors' => $v->errors(), 'old' => $data]);
            return;
        }

        $msgId = (new ContactMessage($this->db))->create($data);

        // Notify admin
        $subject = !empty($data['subject']) ? $data['subject'] : 'No subject';
        (new Notification($this->db))->create(
            'message',
            'New message from ' . $data['name'],
            $subject,
            'dashboard/messages/' . $msgId
        );

        flash('success', 'Thank you! Your message has been sent. We will get back to you shortly.');
        redirect('contact');
    }
}
