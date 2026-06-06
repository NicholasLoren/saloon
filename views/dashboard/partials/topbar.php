<?php $user = authUser(); ?>
<div class="topbar">
  <div class="flex items-center gap-3">
    <!-- Mobile menu toggle -->
    <button id="sidebar-toggle" class="lg:hidden glass p-2 rounded-lg text-white/60 hover:text-gold transition-colors">
      <i class="fa-solid fa-bars text-sm"></i>
    </button>
    <!-- Page title -->
    <div>
      <h1 class="font-serif font-semibold text-white text-base leading-tight"><?= e($title ?? 'Dashboard') ?></h1>
      <div class="text-white/35 text-xs hidden sm:block">
        <?= date('l, d F Y') ?>
      </div>
    </div>
  </div>

  <div class="flex items-center gap-3">
    <!-- Quick new appointment -->
    <a href="<?= url('dashboard/appointments/create') ?>" class="hidden sm:inline-flex items-center gap-2 btn-gold px-4 py-2 rounded-xl text-xs font-semibold shadow-md">
      <i class="fa-solid fa-plus text-[10px]"></i> New Booking
    </a>

    <!-- Notifications -->
    <?php
    global $pdo;
    $notifUnread = 0;
    try { $notifUnread = (int)$pdo->query("SELECT COUNT(*) FROM notifications WHERE is_read=0")->fetchColumn(); } catch (\Throwable $e) {}
    ?>
    <a href="<?= url('dashboard/notifications') ?>" class="glass p-2 rounded-xl text-white/50 hover:text-gold transition-colors relative" title="Notifications">
      <i class="fa-regular fa-bell text-sm"></i>
      <?php if ($notifUnread > 0): ?>
        <span class="absolute top-1 right-1 w-2 h-2 rounded-full" style="background:#c9a84c;"></span>
      <?php endif; ?>
    </a>

    <!-- Avatar -->
    <div class="flex items-center gap-2.5 glass rounded-xl px-3 py-2">
      <div class="w-7 h-7 rounded-full gold-gradient flex items-center justify-center text-slate-900 text-xs font-bold">
        <?= strtoupper(substr($user['name'],0,1)) ?>
      </div>
      <span class="text-white/70 text-xs font-medium hidden sm:block"><?= e(explode(' ',$user['name'])[0]) ?></span>
      <a href="<?= url('dashboard/logout') ?>" class="text-white/30 hover:text-red-400 transition-colors ml-1" title="Logout">
        <i class="fa-solid fa-right-from-bracket text-xs"></i>
      </a>
    </div>
  </div>
</div>
