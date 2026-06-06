<div class="flex items-center justify-between mb-5">
  <div>
    <h2 class="font-serif text-xl font-semibold text-white">FAQs</h2>
    <p class="text-white/40 text-xs mt-0.5"><?= count($faqs) ?> questions</p>
  </div>
  <a href="<?= url('dashboard/faqs/create') ?>" class="btn-gold px-5 py-2.5 rounded-xl text-sm font-semibold inline-flex items-center gap-2">
    <i class="fa-solid fa-plus text-xs"></i> Add FAQ
  </a>
</div>

<?php if (empty($faqs)): ?>
  <div class="glass rounded-2xl p-12 text-center">
    <i class="fa-regular fa-circle-question text-4xl text-white/20 mb-3 block"></i>
    <p class="text-white/40 text-sm">No FAQs yet. Add your first one.</p>
  </div>
<?php else: ?>
  <?php
    $grouped = [];
    foreach ($faqs as $f) {
      $grouped[$f['category'] ?? 'General'][] = $f;
    }
  ?>
  <div class="space-y-5">
    <?php foreach ($grouped as $cat => $items): ?>
      <div class="glass rounded-2xl overflow-hidden">
        <div class="p-4 border-b border-white/8 flex items-center gap-2">
          <i class="fa-solid fa-folder-open text-gold/70 text-sm"></i>
          <h3 class="font-serif font-semibold text-white/90 text-sm"><?= e($cat) ?></h3>
          <span class="text-white/30 text-xs ml-1">(<?= count($items) ?>)</span>
        </div>
        <div class="divide-y divide-white/6">
          <?php foreach ($items as $f): ?>
            <div class="p-4 flex items-start gap-4 hover:bg-white/3 transition-colors group">
              <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-1">
                  <span class="text-white/80 text-sm font-medium"><?= e($f['question']) ?></span>
                  <?php if (!$f['active']): ?>
                    <span class="text-xs px-2 py-0.5 rounded-full bg-red-500/15 text-red-400">Hidden</span>
                  <?php endif; ?>
                </div>
                <p class="text-white/45 text-xs leading-relaxed line-clamp-2"><?= e($f['answer']) ?></p>
              </div>
              <div class="flex items-center gap-1.5 flex-shrink-0 opacity-0 group-hover:opacity-100 transition-opacity">
                <a href="<?= url('dashboard/faqs/'.$f['id'].'/edit') ?>" class="glass p-1.5 rounded-lg text-white/50 hover:text-blue-400 transition-colors">
                  <i class="fa-regular fa-pen-to-square text-xs"></i>
                </a>
                <form method="POST" action="<?= url('dashboard/faqs/'.$f['id'].'/delete') ?>" data-confirm="Delete this FAQ?">
                  <button class="glass p-1.5 rounded-lg text-white/50 hover:text-red-400 transition-colors">
                    <i class="fa-regular fa-trash-can text-xs"></i>
                  </button>
                </form>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
