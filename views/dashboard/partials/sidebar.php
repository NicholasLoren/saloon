<?php
$user = authUser();
$cp   = $page ?? '';
$nav  = [
  ['dashboard',    'Dashboard',    'fa-gauge-simple-high'],
  ['appointments', 'Appointments', 'fa-calendar-check'],
  ['services',     'Services',     'fa-sparkles'],
  ['staff',        'Staff',        'fa-user-tie'],
  ['inventory',    'Inventory',    'fa-boxes-stacked'],
  ['payments',     'Payments',     'fa-credit-card'],
  ['clients',      'Clients',      'fa-users'],
  ['promotions',   'Promotions',   'fa-tag'],
  ['faqs',         'FAQs',         'fa-circle-question'],
  ['messages',       'Messages',      'fa-envelope'],
  ['notifications',  'Notifications', 'fa-bell'],
];
?>
<div class="flex flex-col h-full">
  <!-- Logo -->
  <div class="p-5 mb-2">
    <a href="<?= url('/') ?>" class="flex items-center gap-2.5 group">
      <div class="w-8 h-8 rounded-lg gold-gradient flex items-center justify-center text-slate-900 text-sm flex-shrink-0">
        <i class="fa-solid fa-scissors"></i>
      </div>
      <div>
        <div class="font-serif font-bold text-sm text-gold-light leading-none">Matilda's</div>
        <div class="text-white/40 text-xs leading-tight">Salon & Spa</div>
      </div>
    </a>
  </div>

  <!-- User info -->
  <div class="mx-3 mb-4 glass rounded-xl p-3.5 flex items-center gap-3">
    <div class="w-9 h-9 rounded-full gold-gradient flex items-center justify-center text-slate-900 text-sm font-bold flex-shrink-0">
      <?= strtoupper(substr($user['name'],0,1)) ?>
    </div>
    <div class="min-w-0">
      <div class="text-white/90 text-xs font-semibold truncate"><?= e($user['name']) ?></div>
      <div class="text-gold text-xs capitalize"><?= e($user['role']) ?></div>
    </div>
  </div>

  <!-- Nav -->
  <div class="px-1.5 flex-1">
    <div class="text-white/30 text-[10px] font-semibold tracking-widest uppercase px-3 mb-2">Main Menu</div>
    <nav>
      <?php foreach ($nav as [$key, $label, $icon]): ?>
        <a href="<?= url('dashboard/' . ($key === 'dashboard' ? '' : $key)) ?>"
           class="sidebar-link <?= $cp === $key ? 'active' : '' ?>">
          <span class="icon"><i class="fa-solid <?= $icon ?> text-sm"></i></span>
          <span><?= $label ?></span>
          <?php if ($key === 'appointments'): ?>
            <?php
            global $pdo;
            try {
              $pending = (int)$pdo->query("SELECT COUNT(*) FROM appointments WHERE status='pending'")->fetchColumn();
              if ($pending > 0): ?>
                <span class="ml-auto text-xs font-bold px-1.5 py-0.5 rounded-full" style="background:rgba(201,168,76,.2);color:#e8c97a;"><?= $pending ?></span>
            <?php endif;
            } catch (\Throwable $e) {}
            ?>
          <?php elseif ($key === 'messages'): ?>
            <?php
            global $pdo;
            try {
              $unread = (int)$pdo->query("SELECT COUNT(*) FROM contact_messages WHERE is_read=0")->fetchColumn();
              if ($unread > 0): ?>
                <span class="ml-auto text-xs font-bold px-1.5 py-0.5 rounded-full" style="background:rgba(201,168,76,.2);color:#e8c97a;"><?= $unread ?></span>
            <?php endif;
            } catch (\Throwable $e) {}
            ?>
          <?php elseif ($key === 'notifications'): ?>
            <?php
            global $pdo;
            try {
              $unreadN = (int)$pdo->query("SELECT COUNT(*) FROM notifications WHERE is_read=0")->fetchColumn();
              if ($unreadN > 0): ?>
                <span class="ml-auto text-xs font-bold px-1.5 py-0.5 rounded-full" style="background:rgba(201,168,76,.2);color:#e8c97a;"><?= $unreadN ?></span>
            <?php endif;
            } catch (\Throwable $e) {}
            ?>
          <?php endif; ?>
        </a>
      <?php endforeach; ?>
    </nav>

    <div class="h-px bg-white/8 my-4 mx-2"></div>
    <div class="text-white/30 text-[10px] font-semibold tracking-widest uppercase px-3 mb-2">Settings</div>
    <a href="<?= url('/') ?>" class="sidebar-link" target="_blank">
      <span class="icon"><i class="fa-solid fa-globe text-sm"></i></span>
      <span>View Website</span>
      <i class="fa-solid fa-arrow-up-right-from-square text-xs ml-auto text-white/30"></i>
    </a>
    <a href="<?= url('dashboard/logout') ?>" class="sidebar-link text-red-400/70 hover:!text-red-300 hover:!bg-red-500/10">
      <span class="icon"><i class="fa-solid fa-right-from-bracket text-sm"></i></span>
      <span>Logout</span>
    </a>
  </div>

  <!-- Version -->
  <div class="px-5 py-4 text-white/20 text-xs">v1.0 &copy; <?= date('Y') ?></div>
</div>
