<div class="max-w-xl">
  <div class="flex items-center gap-3 mb-5">
    <a href="<?= url('dashboard/promotions') ?>" class="glass p-2 rounded-xl text-white/50 hover:text-gold transition-colors">
      <i class="fa-solid fa-arrow-left text-sm"></i>
    </a>
    <h2 class="font-serif text-xl font-semibold text-white"><?= $promotion ? 'Edit Promotion' : 'New Promotion' ?></h2>
  </div>

  <?php if (!empty($errors)): ?>
    <div class="alert alert-error mb-5">
      <i class="fa-solid fa-triangle-exclamation flex-shrink-0"></i>
      <div><strong class="block mb-1 text-sm">Please correct the following:</strong>
        <ul class="text-xs space-y-0.5"><?php foreach ($errors as $e2): ?><li>&bull; <?= e($e2) ?></li><?php endforeach; ?></ul>
      </div>
    </div>
  <?php endif; ?>

  <div class="glass rounded-2xl p-7">
    <form method="POST" action="<?= url('dashboard/promotions/'.($promotion&&isset($promotion['id'])?$promotion['id'].'/edit':'create')) ?>">
      <div class="space-y-5">

        <div>
          <label class="form-label">Title *</label>
          <input type="text" name="title" value="<?= e($promotion['title'] ?? '') ?>"
                 class="form-input <?= isset($errors['title']) ? 'error' : '' ?>"
                 placeholder="e.g. Weekend Special" required>
          <?php if (isset($errors['title'])): ?>
            <div class="form-error"><i class="fa-solid fa-circle-exclamation text-xs"></i><?= e($errors['title']) ?></div>
          <?php endif; ?>
        </div>

        <div>
          <label class="form-label">Description</label>
          <textarea name="description" rows="3" class="form-input"
                    placeholder="Brief description shown to customers..."><?= e($promotion['description'] ?? '') ?></textarea>
        </div>

        <div>
          <label class="form-label">Discount Percentage *</label>
          <div class="relative">
            <input type="number" name="discount_percent" value="<?= e($promotion['discount_percent'] ?? '0') ?>"
                   min="0" max="100" step="0.5"
                   class="form-input pr-10 <?= isset($errors['discount_percent']) ? 'error' : '' ?>" required>
            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-white/35 text-sm font-semibold">%</span>
          </div>
          <?php if (isset($errors['discount_percent'])): ?>
            <div class="form-error"><i class="fa-solid fa-circle-exclamation text-xs"></i><?= e($errors['discount_percent']) ?></div>
          <?php endif; ?>
        </div>

        <div class="grid grid-cols-2 gap-5">
          <div>
            <label class="form-label">Start Date</label>
            <input type="date" name="start_date" value="<?= e($promotion['start_date'] ?? '') ?>" class="form-input">
          </div>
          <div>
            <label class="form-label">End Date</label>
            <input type="date" name="end_date" value="<?= e($promotion['end_date'] ?? '') ?>" class="form-input">
            <p class="text-white/30 text-xs mt-1">Leave empty for no expiry</p>
          </div>
        </div>

        <div class="flex items-center gap-3">
          <label class="relative inline-flex items-center cursor-pointer">
            <input type="checkbox" name="active" value="1" class="sr-only peer"
              <?= ($promotion['active'] ?? 1) ? 'checked' : '' ?>>
            <div class="w-9 h-5 bg-white/10 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white/60 after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-gold/60"></div>
          </label>
          <span class="text-white/70 text-sm">Active — show on website</span>
        </div>

      </div>

      <div class="flex flex-wrap gap-3 mt-7">
        <button type="submit" class="btn-gold px-7 py-2.5 rounded-xl font-semibold text-sm inline-flex items-center gap-2">
          <i class="fa-solid fa-floppy-disk"></i> <?= $promotion ? 'Update' : 'Create' ?> Promotion
        </button>
        <a href="<?= url('dashboard/promotions') ?>" class="glass px-6 py-2.5 rounded-xl text-sm text-white/60 hover:text-white/80 transition-colors">Cancel</a>
      </div>
    </form>
  </div>
</div>
