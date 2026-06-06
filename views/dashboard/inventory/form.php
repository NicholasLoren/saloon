<div class="max-w-xl">
  <div class="flex items-center gap-3 mb-5">
    <a href="<?= url('dashboard/inventory') ?>" class="glass p-2 rounded-xl text-white/50 hover:text-gold transition-colors">
      <i class="fa-solid fa-arrow-left text-sm"></i>
    </a>
    <h2 class="font-serif text-xl font-semibold text-white"><?= $item ? 'Edit Item' : 'Add Inventory Item' ?></h2>
  </div>

  <?php if (!empty($errors)): ?>
    <div class="alert alert-error mb-5">
      <i class="fa-solid fa-triangle-exclamation flex-shrink-0"></i>
      <div><strong class="block mb-1 text-sm">Please correct the following:</strong>
      <ul class="text-xs space-y-0.5"><?php foreach($errors as $e2): ?><li>&bull; <?= e($e2) ?></li><?php endforeach; ?></ul></div>
    </div>
  <?php endif; ?>

  <div class="glass rounded-2xl p-7">
    <form method="POST" action="<?= url('dashboard/inventory/'.($item&&isset($item['id'])?$item['id'].'/edit':'create')) ?>">
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
        <div class="sm:col-span-2">
          <label class="form-label">Product Name *</label>
          <input type="text" name="product_name" value="<?= e($item['product_name']??'') ?>" class="form-input <?= isset($errors['product_name'])?'error':'' ?>" required>
          <?php if(isset($errors['product_name'])): ?><div class="form-error"><i class="fa-solid fa-circle-exclamation text-xs"></i><?= e($errors['product_name']) ?></div><?php endif; ?>
        </div>
        <div>
          <label class="form-label">Category</label>
          <select name="category" class="form-select">
            <option value="">— Select category —</option>
            <?php foreach(['Hair Care','Hair Color','Nails','Skin Care','Supplies','Equipment'] as $c): ?>
              <option value="<?= $c ?>" <?= ($item['category']??'')===$c ? 'selected' : '' ?>><?= $c ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label class="form-label">Unit</label>
          <select name="unit" class="form-select">
            <?php foreach(['pcs','bottles','liters','kg','packs','tubes','pairs','kits'] as $u): ?>
              <option value="<?= $u ?>" <?= ($item['unit']??'pcs')===$u ? 'selected' : '' ?>><?= $u ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label class="form-label">Current Quantity *</label>
          <input type="number" name="quantity" value="<?= e($item['quantity']??0) ?>" step="0.01" min="0" class="form-input <?= isset($errors['quantity'])?'error':'' ?>" required>
          <?php if(isset($errors['quantity'])): ?><div class="form-error"><i class="fa-solid fa-circle-exclamation text-xs"></i><?= e($errors['quantity']) ?></div><?php endif; ?>
        </div>
        <div>
          <label class="form-label">Reorder Level *</label>
          <input type="number" name="reorder_level" value="<?= e($item['reorder_level']??5) ?>" step="0.01" min="0" class="form-input <?= isset($errors['reorder_level'])?'error':'' ?>" required>
          <p class="text-white/30 text-xs mt-1">Alert when stock falls below this</p>
          <?php if(isset($errors['reorder_level'])): ?><div class="form-error"><i class="fa-solid fa-circle-exclamation text-xs"></i><?= e($errors['reorder_level']) ?></div><?php endif; ?>
        </div>
        <div>
          <label class="form-label">Cost Price (UGX)</label>
          <input type="number" name="cost_price" value="<?= e($item['cost_price']??0) ?>" step="500" min="0" class="form-input">
        </div>
        <div>
          <label class="form-label">Supplier</label>
          <input type="text" name="supplier" value="<?= e($item['supplier']??'') ?>" class="form-input" placeholder="Supplier name">
        </div>
      </div>

      <div class="flex flex-wrap gap-3 mt-2">
        <button type="submit" class="btn-gold px-7 py-2.5 rounded-xl font-semibold text-sm inline-flex items-center gap-2">
          <i class="fa-solid fa-floppy-disk"></i> <?= $item ? 'Update' : 'Add' ?> Item
        </button>
        <a href="<?= url('dashboard/inventory') ?>" class="glass px-6 py-2.5 rounded-xl text-sm text-white/60 hover:text-white/80 transition-colors">Cancel</a>
      </div>
    </form>
  </div>
</div>
