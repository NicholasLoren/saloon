<div class="max-w-2xl">
  <div class="flex items-center gap-3 mb-5">
    <a href="<?= url('dashboard/faqs') ?>" class="glass p-2 rounded-xl text-white/50 hover:text-gold transition-colors">
      <i class="fa-solid fa-arrow-left text-sm"></i>
    </a>
    <h2 class="font-serif text-xl font-semibold text-white"><?= $faq ? 'Edit FAQ' : 'Add FAQ' ?></h2>
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
    <form method="POST" action="<?= url('dashboard/faqs/'.($faq&&isset($faq['id'])?$faq['id'].'/edit':'create')) ?>">
      <div class="space-y-5">

        <div>
          <label class="form-label">Question *</label>
          <input type="text" name="question" value="<?= e($faq['question'] ?? '') ?>"
                 class="form-input <?= isset($errors['question']) ? 'error' : '' ?>"
                 placeholder="e.g. How do I book an appointment?" required>
          <?php if (isset($errors['question'])): ?>
            <div class="form-error"><i class="fa-solid fa-circle-exclamation text-xs"></i><?= e($errors['question']) ?></div>
          <?php endif; ?>
        </div>

        <div>
          <label class="form-label">Answer *</label>
          <textarea name="answer" rows="5"
                    class="form-input <?= isset($errors['answer']) ? 'error' : '' ?>"
                    placeholder="Provide a clear, helpful answer..."
                    required><?= e($faq['answer'] ?? '') ?></textarea>
          <?php if (isset($errors['answer'])): ?>
            <div class="form-error"><i class="fa-solid fa-circle-exclamation text-xs"></i><?= e($errors['answer']) ?></div>
          <?php endif; ?>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
          <div class="sm:col-span-2">
            <label class="form-label">Category</label>
            <input type="text" name="category" value="<?= e($faq['category'] ?? 'General') ?>"
                   list="faq-cats" class="form-input" placeholder="e.g. Booking, Services, General">
            <datalist id="faq-cats">
              <?php foreach (['General', 'Booking', 'Services', 'Pricing', 'Policies'] as $c): ?>
                <option value="<?= $c ?>">
              <?php endforeach; ?>
            </datalist>
          </div>
          <div>
            <label class="form-label">Sort Order</label>
            <input type="number" name="sort_order" value="<?= (int)($faq['sort_order'] ?? 0) ?>"
                   min="0" class="form-input" placeholder="0">
            <p class="text-white/30 text-xs mt-1">Lower = first</p>
          </div>
        </div>

        <div class="flex items-center gap-3">
          <label class="relative inline-flex items-center cursor-pointer">
            <input type="checkbox" name="active" value="1" class="sr-only peer"
              <?= ($faq['active'] ?? 1) ? 'checked' : '' ?>>
            <div class="w-9 h-5 bg-white/10 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white/60 after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-gold/60"></div>
          </label>
          <span class="text-white/70 text-sm">Visible on public FAQ page</span>
        </div>

      </div>

      <div class="flex flex-wrap gap-3 mt-7">
        <button type="submit" class="btn-gold px-7 py-2.5 rounded-xl font-semibold text-sm inline-flex items-center gap-2">
          <i class="fa-solid fa-floppy-disk"></i> <?= $faq ? 'Update FAQ' : 'Add FAQ' ?>
        </button>
        <a href="<?= url('dashboard/faqs') ?>" class="glass px-6 py-2.5 rounded-xl text-sm text-white/60 hover:text-white/80 transition-colors">Cancel</a>
      </div>
    </form>
  </div>
</div>
