<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($title ?? 'Dashboard') ?> | <?= SALON_NAME ?> Admin</title>
  <link rel="icon" type="image/svg+xml" href="<?= asset('images/favicon.svg') ?>">

  <script src="<?= asset('js/tailwind.js') ?>"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            gold: { DEFAULT:'#c9a84c', light:'#e8c97a', dark:'#a8893c' }
          },
          fontFamily: {
            sans: ['Inter','system-ui','sans-serif'],
            serif: ['"Playfair Display"','Georgia','serif'],
          }
        }
      }
    }
  </script>
  <link rel="stylesheet" href="<?= asset('css/flowbite.min.css') ?>">
  <link rel="stylesheet" href="<?= asset('css/custom.css') ?>">
  <link rel="stylesheet" href="<?= asset('fa/css/all.css') ?>">
  <script src="<?= asset('js/chart.min.js') ?>"></script>
</head>
<body>

<!-- Sidebar overlay (mobile) -->
<div id="sidebar-overlay" class="fixed inset-0 z-30 bg-black/60 backdrop-blur-sm hidden lg:hidden"></div>

<!-- Sidebar -->
<aside id="sidebar" class="sidebar">
  <?php include __DIR__ . '/../partials/sidebar.php'; ?>
</aside>

<!-- Main content -->
<div class="dash-main">
  <!-- Topbar -->
  <?php include __DIR__ . '/../partials/topbar.php'; ?>

  <!-- Breadcrumbs -->
  <?php include __DIR__ . '/../partials/breadcrumbs.php'; ?>

  <!-- Flash message -->
  <?php $flash = getFlash(); if ($flash): ?>
    <div class="alert alert-<?= e($flash['type']) ?> mb-5 animate-fade" data-flash>
      <i class="fa-solid <?= match($flash['type']) { 'success'=>'fa-circle-check','error'=>'fa-circle-xmark','warning'=>'fa-triangle-exclamation',default=>'fa-circle-info'} ?> flex-shrink-0"></i>
      <span><?= e($flash['msg']) ?></span>
      <button class="ml-auto text-current opacity-60 hover:opacity-100" data-dismiss="none" onclick="this.closest('[data-flash]').remove()">
        <i class="fa-solid fa-xmark text-xs"></i>
      </button>
    </div>
  <?php endif; ?>

  <!-- Page content -->
  <?= $content ?>
</div>

<script src="<?= asset('js/flowbite.min.js') ?>"></script>
<script src="<?= asset('js/app.js') ?>"></script>
</body>
</html>
