<div class="flex items-center justify-between mb-5">
  <div>
    <h2 class="font-serif text-xl font-semibold text-white">Promotions</h2>
    <p class="text-white/40 text-xs mt-0.5"><?= count($promotions) ?> promotions</p>
  </div>
  <a href="<?= url('dashboard/promotions/create') ?>" class="btn-gold px-5 py-2.5 rounded-xl text-sm font-semibold inline-flex items-center gap-2">
    <i class="fa-solid fa-plus text-xs"></i> New Promotion
  </a>
</div>

<?php if (empty($promotions)): ?>
  <div class="glass rounded-2xl p-12 text-center">
    <i class="fa-regular fa-tag text-4xl text-white/20 mb-3 block"></i>
    <p class="text-white/40 text-sm">No promotions yet. Create your first offer.</p>
  </div>
<?php else: ?>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <?php foreach ($promotions as $p): ?>
      <?php
        $now      = date('Y-m-d');
        $expired  = $p['end_date'] && $p['end_date'] < $now;
        $active   = $p['active'] && !$expired;
        $future   = $p['start_date'] && $p['start_date'] > $now;
      ?>
      <div class="glass rounded-2xl p-5 flex flex-col gap-3 <?= !$active ? 'opacity-60' : '' ?>">
        <div class="flex items-start justify-between gap-3">
          <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 flex-wrap">
              <span class="font-serif font-semibold text-white text-base"><?= e($p['title']) ?></span>
              <?php if ($expired): ?>
                <span class="text-xs px-2 py-0.5 rounded-full bg-red-500/15 text-red-400">Expired</span>
              <?php elseif (!$p['active']): ?>
                <span class="text-xs px-2 py-0.5 rounded-full bg-white/10 text-white/40">Inactive</span>
              <?php elseif ($future): ?>
                <span class="text-xs px-2 py-0.5 rounded-full bg-blue-500/15 text-blue-400">Upcoming</span>
              <?php else: ?>
                <span class="text-xs px-2 py-0.5 rounded-full bg-emerald-500/15 text-emerald-400">Active</span>
              <?php endif; ?>
            </div>
            <?php if ($p['description']): ?>
              <p class="text-white/45 text-xs mt-1 leading-relaxed"><?= e($p['description']) ?></p>
            <?php endif; ?>
          </div>
          <div class="text-right flex-shrink-0">
            <div class="font-serif text-2xl font-bold text-gold-light"><?= $p['discount_percent'] ?>%</div>
            <div class="text-white/35 text-xs">discount</div>
          </div>
        </div>

        <?php if ($p['start_date'] || $p['end_date']): ?>
          <div class="flex items-center gap-1.5 text-white/35 text-xs">
            <i class="fa-regular fa-calendar text-xs"></i>
            <?= $p['start_date'] ? fmtDate($p['start_date']) : 'Open' ?>
            <span class="text-white/20">→</span>
            <?= $p['end_date'] ? fmtDate($p['end_date']) : 'No expiry' ?>
          </div>
        <?php endif; ?>

        <div class="flex items-center gap-2 pt-1 border-t border-white/6">
          <a href="<?= url('dashboard/promotions/'.$p['id'].'/edit') ?>"
             class="glass px-3 py-1.5 rounded-lg text-white/50 hover:text-blue-400 transition-colors text-xs inline-flex items-center gap-1">
            <i class="fa-regular fa-pen-to-square"></i> Edit
          </a>
          <form method="POST" action="<?= url('dashboard/promotions/'.$p['id'].'/delete') ?>"
                data-confirm="Delete '<?= e($p['title']) ?>'?">
            <button class="glass px-3 py-1.5 rounded-lg text-white/50 hover:text-red-400 transition-colors text-xs inline-flex items-center gap-1">
              <i class="fa-regular fa-trash-can"></i> Delete
            </button>
          </form>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
