<div class="max-w-2xl">
  <div class="flex items-center gap-3 mb-5">
    <a href="<?= url('dashboard/services') ?>" class="glass p-2 rounded-xl text-white/50 hover:text-gold transition-colors">
      <i class="fa-solid fa-arrow-left text-sm"></i>
    </a>
    <h2 class="font-serif text-xl font-semibold text-white"><?= e($service['name']) ?></h2>
    <?= statusBadge($service['active'] ? 'active' : 'inactive') ?>
  </div>

  <div class="glass rounded-2xl overflow-hidden">
    <?php if ($service['image']): ?>
      <div style="height:240px;" class="overflow-hidden">
        <img src="<?= storageUrl($service['image']) ?>" alt="<?= e($service['name']) ?>" class="w-full h-full object-cover">
      </div>
    <?php endif; ?>
    <div class="p-7">
      <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-6">
        <?php foreach ([['Category',$service['category']??'—'],['Price',money((float)$service['price'])],['Duration',$service['duration_minutes'].' min']] as [$lbl,$val]): ?>
          <div class="glass rounded-xl p-4">
            <div class="text-white/35 text-xs mb-1"><?= $lbl ?></div>
            <div class="font-semibold text-white/85 text-sm"><?= e($val) ?></div>
          </div>
        <?php endforeach; ?>
      </div>
      <?php if ($service['description']): ?>
        <div class="mb-6">
          <div class="text-white/35 text-xs mb-2">Description</div>
          <p class="text-white/70 text-sm leading-relaxed"><?= nl2br(e($service['description'])) ?></p>
        </div>
      <?php endif; ?>
      <div class="flex gap-3">
        <a href="<?= url('dashboard/services/'.$service['id'].'/edit') ?>" class="btn-gold px-5 py-2.5 rounded-xl text-sm font-semibold inline-flex items-center gap-2">
          <i class="fa-regular fa-pen-to-square"></i> Edit
        </a>
        <form method="POST" action="<?= url('dashboard/services/'.$service['id'].'/delete') ?>" data-confirm="Delete this service?">
          <button class="glass px-5 py-2.5 rounded-xl text-sm text-red-400/70 hover:text-red-400 transition-colors inline-flex items-center gap-2">
            <i class="fa-regular fa-trash-can"></i> Delete
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
