<?php
use App\Controllers\Dashboard\AuthController;
use App\Controllers\Dashboard\HomeController as DashHome;
use App\Controllers\Dashboard\AppointmentController;
use App\Controllers\Dashboard\ServiceController;
use App\Controllers\Dashboard\StaffController;
use App\Controllers\Dashboard\InventoryController;
use App\Controllers\Dashboard\PaymentController;
use App\Controllers\Dashboard\ClientController;
use App\Controllers\Dashboard\FAQController;
use App\Controllers\Dashboard\PromotionController;
use App\Controllers\Dashboard\MessageController;
use App\Controllers\Dashboard\NotificationController;
use App\Middleware\AuthMiddleware;

$auth = [AuthMiddleware::class];

// Auth (no middleware)
$router->get('/dashboard/login',  [AuthController::class, 'login']);
$router->post('/dashboard/login', [AuthController::class, 'authenticate']);
$router->get('/dashboard/logout', [AuthController::class, 'logout']);

// Dashboard home
$router->get('/dashboard', [DashHome::class, 'index'], $auth);

// Appointments
$router->get('/dashboard/appointments',                  [AppointmentController::class, 'index'],        $auth);
$router->get('/dashboard/appointments/create',           [AppointmentController::class, 'create'],       $auth);
$router->post('/dashboard/appointments/create',          [AppointmentController::class, 'store'],        $auth);
$router->get('/dashboard/appointments/{id}',             [AppointmentController::class, 'show'],         $auth);
$router->get('/dashboard/appointments/{id}/edit',        [AppointmentController::class, 'edit'],         $auth);
$router->post('/dashboard/appointments/{id}/edit',       [AppointmentController::class, 'update'],       $auth);
$router->post('/dashboard/appointments/{id}/status',     [AppointmentController::class, 'updateStatus'], $auth);
$router->post('/dashboard/appointments/{id}/delete',     [AppointmentController::class, 'destroy'],      $auth);

// Services
$router->get('/dashboard/services',              [ServiceController::class, 'index'],   $auth);
$router->get('/dashboard/services/create',       [ServiceController::class, 'create'],  $auth);
$router->post('/dashboard/services/create',      [ServiceController::class, 'store'],   $auth);
$router->get('/dashboard/services/{id}',         [ServiceController::class, 'show'],    $auth);
$router->get('/dashboard/services/{id}/edit',    [ServiceController::class, 'edit'],    $auth);
$router->post('/dashboard/services/{id}/edit',   [ServiceController::class, 'update'],  $auth);
$router->post('/dashboard/services/{id}/delete', [ServiceController::class, 'destroy'], $auth);

// Staff
$router->get('/dashboard/staff',              [StaffController::class, 'index'],   $auth);
$router->get('/dashboard/staff/create',       [StaffController::class, 'create'],  $auth);
$router->post('/dashboard/staff/create',      [StaffController::class, 'store'],   $auth);
$router->get('/dashboard/staff/{id}/edit',    [StaffController::class, 'edit'],    $auth);
$router->post('/dashboard/staff/{id}/edit',   [StaffController::class, 'update'],  $auth);
$router->post('/dashboard/staff/{id}/delete', [StaffController::class, 'destroy'], $auth);

// Inventory
$router->get('/dashboard/inventory',              [InventoryController::class, 'index'],   $auth);
$router->get('/dashboard/inventory/create',       [InventoryController::class, 'create'],  $auth);
$router->post('/dashboard/inventory/create',      [InventoryController::class, 'store'],   $auth);
$router->get('/dashboard/inventory/{id}/edit',    [InventoryController::class, 'edit'],    $auth);
$router->post('/dashboard/inventory/{id}/edit',   [InventoryController::class, 'update'],  $auth);
$router->post('/dashboard/inventory/{id}/delete', [InventoryController::class, 'destroy'], $auth);

// Payments
$router->get('/dashboard/payments',             [PaymentController::class, 'index'],    $auth);
$router->get('/dashboard/payments/{id}',        [PaymentController::class, 'show'],     $auth);
$router->post('/dashboard/payments/{id}/pay',   [PaymentController::class, 'markPaid'], $auth);

// Clients
$router->get('/dashboard/clients',         [ClientController::class, 'index'], $auth);
$router->get('/dashboard/clients/{email}', [ClientController::class, 'show'],  $auth);

// FAQs
$router->get('/dashboard/faqs',              [FAQController::class, 'index'],   $auth);
$router->get('/dashboard/faqs/create',       [FAQController::class, 'create'],  $auth);
$router->post('/dashboard/faqs/create',      [FAQController::class, 'store'],   $auth);
$router->get('/dashboard/faqs/{id}/edit',    [FAQController::class, 'edit'],    $auth);
$router->post('/dashboard/faqs/{id}/edit',   [FAQController::class, 'update'],  $auth);
$router->post('/dashboard/faqs/{id}/delete', [FAQController::class, 'destroy'], $auth);

// Promotions
$router->get('/dashboard/promotions',              [PromotionController::class, 'index'],   $auth);
$router->get('/dashboard/promotions/create',       [PromotionController::class, 'create'],  $auth);
$router->post('/dashboard/promotions/create',      [PromotionController::class, 'store'],   $auth);
$router->get('/dashboard/promotions/{id}/edit',    [PromotionController::class, 'edit'],    $auth);
$router->post('/dashboard/promotions/{id}/edit',   [PromotionController::class, 'update'],  $auth);
$router->post('/dashboard/promotions/{id}/delete', [PromotionController::class, 'destroy'], $auth);

// Contact Messages
$router->get('/dashboard/messages',              [MessageController::class, 'index'],   $auth);
$router->get('/dashboard/messages/{id}',         [MessageController::class, 'show'],    $auth);
$router->post('/dashboard/messages/{id}/delete', [MessageController::class, 'destroy'], $auth);

// Notifications
$router->get('/dashboard/notifications',                          [NotificationController::class, 'index'],      $auth);
$router->post('/dashboard/notifications/mark-all-read',          [NotificationController::class, 'markAllRead'], $auth);
$router->post('/dashboard/notifications/delete-read',            [NotificationController::class, 'deleteRead'],  $auth);
$router->post('/dashboard/notifications/delete-all',             [NotificationController::class, 'deleteAll'],   $auth);
$router->get('/dashboard/notifications/{id}',                    [NotificationController::class, 'view'],        $auth);
$router->post('/dashboard/notifications/{id}/delete',            [NotificationController::class, 'delete'],      $auth);
