<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($title ?? 'Welcome') ?> | <?= SALON_NAME ?></title>
  <link rel="icon" type="image/svg+xml" href="<?= asset('images/favicon.svg') ?>">
  <meta name="description" content="Luxury beauty salon in Namasuba, Kampala. Book appointments online.">

  <!-- Tailwind CDN (local) -->
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

  <!-- Flowbite CSS -->
  <link rel="stylesheet" href="<?= asset('css/flowbite.min.css') ?>">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="<?= asset('css/custom.css') ?>">
  <!-- Font Awesome (user will add their v6 pro folder as assets/fa/) -->
  <link rel="stylesheet" href="<?= asset('fa/css/all.css') ?>">
</head>
<body>

<?php include __DIR__ . '/../partials/header.php'; ?>

<main>
  <?php
  $flash = getFlash();
  if ($flash): ?>
    <div class="fixed top-20 left-1/2 -translate-x-1/2 z-50 w-full max-w-md px-4">
      <div class="alert alert-<?= e($flash['type']) ?>" data-flash>
        <i class="fa-solid <?= $flash['type']==='success' ? 'fa-circle-check' : ($flash['type']==='error' ? 'fa-circle-xmark' : 'fa-triangle-exclamation') ?> mt-0.5"></i>
        <span><?= e($flash['msg']) ?></span>
      </div>
    </div>
  <?php endif; ?>

  <?= $content ?>
</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>
<?php include __DIR__ . '/../partials/social_float.php'; ?>

<!-- JS -->
<script src="<?= asset('js/flowbite.min.js') ?>"></script>
<script src="<?= asset('js/app.js') ?>"></script>
</body>
</html>
