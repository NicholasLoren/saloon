<?php
$typeMap = [
    'appointment' => ['icon' => 'fa-calendar-check',  'color' => '#3b82f6', 'bg' => 'rgba(59,130,246,.15)'],
    'message'     => ['icon' => 'fa-envelope',         'color' => '#22c55e', 'bg' => 'rgba(34,197,94,.15)'],
    'payment'     => ['icon' => 'fa-credit-card',      'color' => '#10b981', 'bg' => 'rgba(16,185,129,.15)'],
    'system'      => ['icon' => 'fa-gear',             'color' => '#a855f7', 'bg' => 'rgba(168,85,247,.15)'],
    'default'     => ['icon' => 'fa-bell',             'color' => '#c9a84c', 'bg' => 'rgba(201,168,76,.15)'],
];

$unread = count(array_filter($rows, fn($r) => !$r['is_read']));
$totalPages = $total > 0 ? (int)ceil($total / $perPage) : 1;

// Group rows by date
$grouped = [];
$today     = date('Y-m-d');
$yesterday = date('Y-m-d', strtotime('-1 day'));
foreach ($rows as $row) {
    $day = date('Y-m-d', strtotime($row['created_at']));
    if ($day === $today)         $group = 'Today';
    elseif ($day === $yesterday) $group = 'Yesterday';
    else                         $group = date('D, d M Y', strtotime($row['created_at']));
    $grouped[$group][] = $row;
}
?>

<!-- Header -->
<div class="flex flex-wrap items-start justify-between gap-4 mb-5">
  <div>
    <h2 class="font-serif text-xl font-semibold text-white">Notifications</h2>
    <p class="text-white/40 text-xs mt-0.5">
      <?= $total ?> total
      <?php if ($unread > 0): ?>
        &bull; <span class="text-gold"><?= $unread ?> unread</span>
      <?php endif; ?>
    </p>
  </div>

  <!-- Bulk actions -->
  <?php if ($total > 0): ?>
    <div class="flex flex-wrap items-center gap-2">
      <?php if ($unread > 0): ?>
        <form method="POST" action="<?= url('dashboard/notifications/mark-all-read') ?>">
          <button class="glass px-4 py-2 rounded-xl text-xs text-white/60 hover:text-gold transition-colors inline-flex items-center gap-2">
            <i class="fa-regular fa-circle-check"></i> Mark all read
          </button>
        </form>
      <?php endif; ?>
      <form method="POST" action="<?= url('dashboard/notifications/delete-read') ?>">
        <button class="glass px-4 py-2 rounded-xl text-xs text-white/60 hover:text-amber-400 transition-colors inline-flex items-center gap-2">
          <i class="fa-regular fa-trash-can"></i> Delete read
        </button>
      </form>
      <form method="POST" action="<?= url('dashboard/notifications/delete-all') ?>" data-confirm="Clear all notifications?">
        <button class="glass px-4 py-2 rounded-xl text-xs text-red-400/60 hover:text-red-400 transition-colors inline-flex items-center gap-2">
          <i class="fa-solid fa-trash"></i> Clear all
        </button>
      </form>
    </div>
  <?php endif; ?>
</div>

<?php if (empty($rows)): ?>
  <div class="glass rounded-2xl p-16 text-center">
    <i class="fa-regular fa-bell-slash text-5xl text-white/15 mb-4 block"></i>
    <p class="text-white/40 text-sm">No notifications yet.</p>
    <p class="text-white/25 text-xs mt-1">New bookings and messages will appear here.</p>
  </div>
<?php else: ?>

  <div class="space-y-6">
    <?php foreach ($grouped as $groupLabel => $items): ?>
      <div>
        <div class="text-white/30 text-[10px] font-semibold tracking-widest uppercase mb-2 px-1"><?= $groupLabel ?></div>
        <div class="glass rounded-2xl overflow-hidden divide-y divide-white/5">
          <?php foreach ($items as $n):
            $t = $typeMap[$n['type']] ?? $typeMap['default'];
          ?>
            <div class="flex items-start gap-4 p-4 <?= !$n['is_read'] ? 'bg-white/[.03]' : '' ?> hover:bg-white/[.02] transition-colors group">

              <!-- Icon -->
              <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 mt-0.5"
                   style="background:<?= $t['bg'] ?>;">
                <i class="fa-solid <?= $t['icon'] ?> text-sm" style="color:<?= $t['color'] ?>;"></i>
              </div>

              <!-- Content -->
              <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-3">
                  <div class="flex items-center gap-2 flex-wrap">
                    <?php if (!$n['is_read']): ?>
                      <span class="w-2 h-2 rounded-full bg-gold flex-shrink-0" title="Unread"></span>
                    <?php endif; ?>
                    <span class="text-white/<?= $n['is_read'] ? '60' : '90' ?> text-sm font-<?= $n['is_read'] ? 'normal' : 'semibold' ?>">
                      <?= e($n['title']) ?>
                    </span>
                  </div>
                  <span class="text-white/25 text-xs whitespace-nowrap flex-shrink-0"><?= timeAgo($n['created_at']) ?></span>
                </div>
                <?php if ($n['body']): ?>
                  <p class="text-white/40 text-xs mt-0.5 line-clamp-2"><?= e($n['body']) ?></p>
                <?php endif; ?>
              </div>

              <!-- Actions -->
              <div class="flex items-center gap-1.5 flex-shrink-0 opacity-0 group-hover:opacity-100 transition-opacity">
                <?php if ($n['url']): ?>
                  <a href="<?= url('dashboard/notifications/'.$n['id']) ?>"
                     class="glass p-1.5 rounded-lg text-white/50 hover:text-gold transition-colors text-xs" title="View">
                    <i class="fa-regular fa-arrow-up-right-from-square"></i>
                  </a>
                <?php endif; ?>
                <form method="POST" action="<?= url('dashboard/notifications/'.$n['id'].'/delete') ?>">
                  <button class="glass p-1.5 rounded-lg text-white/50 hover:text-red-400 transition-colors text-xs" title="Delete">
                    <i class="fa-regular fa-trash-can"></i>
                  </button>
                </form>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- Pagination -->
  <?php if ($totalPages > 1): ?>
    <div class="flex items-center justify-between mt-5 flex-wrap gap-3">
      <div class="text-white/35 text-xs">Page <?= $curPage ?> of <?= $totalPages ?></div>
      <div class="pagination">
        <?php
        $base = url('dashboard/notifications');
        $prev = max(1, $curPage - 1);
        $next = min($totalPages, $curPage + 1);
        ?>
        <a href="<?= $base ?>?page=<?= $prev ?>" class="page-btn <?= $curPage <= 1 ? 'disabled' : '' ?>">
          <i class="fa-solid fa-chevron-left text-[10px]"></i>
        </a>
        <?php for ($p = max(1, $curPage-2); $p <= min($totalPages, $curPage+2); $p++): ?>
          <a href="<?= $base ?>?page=<?= $p ?>" class="page-btn <?= $p === $curPage ? 'active' : '' ?>"><?= $p ?></a>
        <?php endfor; ?>
        <a href="<?= $base ?>?page=<?= $next ?>" class="page-btn <?= $curPage >= $totalPages ? 'disabled' : '' ?>">
          <i class="fa-solid fa-chevron-right text-[10px]"></i>
        </a>
      </div>
    </div>
  <?php endif; ?>

<?php endif; ?>
