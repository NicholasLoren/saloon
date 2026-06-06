<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($title ?? 'Login') ?> | <?= SALON_NAME ?></title>
  <link rel="icon" type="image/svg+xml" href="<?= asset('images/favicon.svg') ?>">
  <script src="<?= asset('js/tailwind.js') ?>"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: { gold: { DEFAULT:'#c9a84c', light:'#e8c97a', dark:'#a8893c' } },
          fontFamily: { sans: ['Inter','system-ui','sans-serif'], serif: ['"Playfair Display"','Georgia','serif'] }
        }
      }
    }
  </script>
  <link rel="stylesheet" href="<?= asset('css/custom.css') ?>">
  <link rel="stylesheet" href="<?= asset('fa/css/all.css') ?>">
</head>
<body class="min-h-screen flex items-center justify-center p-4" style="background:linear-gradient(135deg,#0d0d1a 0%,#1a0a2e 40%,#0a1628 75%,#0d1219 100%);">
  <div class="absolute inset-0 overflow-hidden pointer-events-none">
    <div class="absolute -top-40 -right-40 w-96 h-96 rounded-full opacity-20" style="background:radial-gradient(circle,#c9a84c,transparent 70%)"></div>
    <div class="absolute -bottom-40 -left-40 w-96 h-96 rounded-full opacity-10" style="background:radial-gradient(circle,#1a0a2e,transparent 70%)"></div>
  </div>
  <?= $content ?>
</body>
</html>
