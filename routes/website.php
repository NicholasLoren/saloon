<?php
use App\Controllers\Website\HomeController;
use App\Controllers\Website\AboutController;
use App\Controllers\Website\ServicesController;
use App\Controllers\Website\ContactController;
use App\Controllers\Website\FAQController;
use App\Controllers\Website\BookingController;

// Public pages
$router->get('/',          [HomeController::class,     'index']);
$router->get('/about',     [AboutController::class,    'index']);
$router->get('/services',  [ServicesController::class, 'index']);
$router->get('/contact',   [ContactController::class,  'index']);
$router->post('/contact',  [ContactController::class,  'submit']);
$router->get('/faq',       [FAQController::class,      'index']);
$router->get('/book',      [BookingController::class,  'index']);
$router->post('/book',     [BookingController::class,  'store']);

// AJAX endpoint for available time slots
$router->get('/api/slots', [BookingController::class,  'slots']);
