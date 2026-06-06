<div class="max-w-2xl">
  <div class="flex items-center gap-3 mb-5">
    <a href="<?= url('dashboard/services') ?>" class="glass p-2 rounded-xl text-white/50 hover:text-gold transition-colors">
      <i class="fa-solid fa-arrow-left text-sm"></i>
    </a>
    <h2 class="font-serif text-xl font-semibold text-white"><?= $service ? 'Edit Service' : 'New Service' ?></h2>
  </div>

  <?php if (!empty($errors)): ?>
    <div class="alert alert-error mb-5">
      <i class="fa-solid fa-triangle-exclamation flex-shrink-0"></i>
      <div><strong class="block mb-1 text-sm">Please correct the following:</strong>
      <ul class="text-xs space-y-0.5"><?php foreach($errors as $e2): ?><li>&bull; <?= e($e2) ?></li><?php endforeach; ?></ul></div>
    </div>
  <?php endif; ?>

  <div class="glass rounded-2xl p-7">
    <form method="POST" action="<?= url('dashboard/services/'.($service&&isset($service['id'])?$service['id'].'/edit':'create')) ?>" enctype="multipart/form-data">

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
        <div class="sm:col-span-2">
          <label class="form-label">Service Name *</label>
          <input type="text" name="name" value="<?= e($service['name']??'') ?>" class="form-input <?= isset($errors['name'])?'error':'' ?>" placeholder="e.g. Hair Coloring" required>
          <?php if(isset($errors['name'])): ?><div class="form-error"><i class="fa-solid fa-circle-exclamation text-xs"></i><?= e($errors['name']) ?></div><?php endif; ?>
        </div>
        <div>
          <label class="form-label">Price (UGX) *</label>
          <input type="number" name="price" value="<?= e($service['price']??'') ?>" step="500" min="0" class="form-input <?= isset($errors['price'])?'error':'' ?>" placeholder="25000" required>
          <?php if(isset($errors['price'])): ?><div class="form-error"><i class="fa-solid fa-circle-exclamation text-xs"></i><?= e($errors['price']) ?></div><?php endif; ?>
        </div>
        <div>
          <label class="form-label">Duration (minutes) *</label>
          <input type="number" name="duration_minutes" value="<?= e($service['duration_minutes']??60) ?>" min="15" step="15" class="form-input <?= isset($errors['duration_minutes'])?'error':'' ?>" required>
          <?php if(isset($errors['duration_minutes'])): ?><div class="form-error"><i class="fa-solid fa-circle-exclamation text-xs"></i><?= e($errors['duration_minutes']) ?></div><?php endif; ?>
        </div>
        <div>
          <label class="form-label">Category *</label>
          <input type="text" name="category" value="<?= e($service['category']??'') ?>" list="cat-list" class="form-input <?= isset($errors['category'])?'error':'' ?>" placeholder="e.g. Hair, Nails, Skin" required>
          <datalist id="cat-list">
            <?php foreach(['Hair','Nails','Skin','Beauty','Spa','General'] as $c): ?><option value="<?= $c ?>"><?php endforeach; ?>
          </datalist>
          <?php if(isset($errors['category'])): ?><div class="form-error"><i class="fa-solid fa-circle-exclamation text-xs"></i><?= e($errors['category']) ?></div><?php endif; ?>
        </div>
        <div class="flex items-center gap-3 pt-5">
          <label class="relative inline-flex items-center cursor-pointer">
            <input type="checkbox" name="active" value="1" class="sr-only peer" <?= ($service['active']??1)?'checked':'' ?>>
            <div class="w-10 h-5 bg-white/10 rounded-full peer peer-checked:bg-gold/70 peer-focus:ring-2 peer-focus:ring-gold/30 transition-all after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:w-4 after:h-4 after:rounded-full after:bg-white/80 after:transition-all peer-checked:after:translate-x-5"></div>
          </label>
          <span class="text-white/65 text-sm">Active (visible on website)</span>
        </div>
      </div>

      <div class="mb-5">
        <label class="form-label">Description</label>
        <textarea name="description" rows="3" class="form-input" placeholder="Describe this service..."><?= e($service['description']??'') ?></textarea>
      </div>

      <!-- Image upload -->
      <div class="mb-7">
        <label class="form-label">Service Image</label>
        <div class="glass rounded-xl p-4 border-dashed border border-gold/20 text-center">
          <?php if (!empty($service['image'])): ?>
            <img id="img-preview" src="<?= storageUrl($service['image']) ?>" class="w-32 h-24 object-cover rounded-lg mx-auto mb-3">
          <?php else: ?>
            <img id="img-preview" src="" class="w-32 h-24 object-cover rounded-lg mx-auto mb-3 hidden">
            <i class="fa-regular fa-image text-3xl text-white/20 mb-2 block"></i>
          <?php endif; ?>
          <input type="file" name="image" accept="image/*" data-preview="img-preview" class="hidden" id="img-input">
          <label for="img-input" class="cursor-pointer text-gold text-sm hover:text-gold-light transition-colors">
            <?= !empty($service['image']) ? 'Change Image' : 'Upload Image' ?>
          </label>
          <p class="text-white/30 text-xs mt-1">JPG, PNG or WebP · Max 5MB</p>
        </div>
      </div>

      <div class="flex flex-wrap gap-3">
        <button type="submit" class="btn-gold px-7 py-2.5 rounded-xl font-semibold text-sm inline-flex items-center gap-2">
          <i class="fa-solid fa-floppy-disk"></i> <?= $service ? 'Update' : 'Create' ?> Service
        </button>
        <a href="<?= url('dashboard/services') ?>" class="glass px-6 py-2.5 rounded-xl text-sm text-white/60 hover:text-white/80 transition-colors">Cancel</a>
      </div>
    </form>
  </div>
</div>
