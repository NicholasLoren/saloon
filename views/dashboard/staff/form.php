<?php $days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday']; ?>
<div class="max-w-2xl">
  <div class="flex items-center gap-3 mb-5">
    <a href="<?= url('dashboard/staff') ?>" class="glass p-2 rounded-xl text-white/50 hover:text-gold transition-colors">
      <i class="fa-solid fa-arrow-left text-sm"></i>
    </a>
    <h2 class="font-serif text-xl font-semibold text-white"><?= $member ? 'Edit Staff Member' : 'Add Staff Member' ?></h2>
  </div>

  <?php if (!empty($errors)): ?>
    <div class="alert alert-error mb-5">
      <i class="fa-solid fa-triangle-exclamation flex-shrink-0"></i>
      <div><strong class="block mb-1 text-sm">Please correct the following:</strong>
      <ul class="text-xs space-y-0.5"><?php foreach($errors as $e2): ?><li>&bull; <?= e($e2) ?></li><?php endforeach; ?></ul></div>
    </div>
  <?php endif; ?>

  <div class="glass rounded-2xl p-7">
    <form method="POST" action="<?= url('dashboard/staff/'.($member&&isset($member['id'])?$member['id'].'/edit':'create')) ?>">

      <h3 class="font-serif text-sm font-semibold text-gold-light uppercase tracking-wider mb-4">Personal Info</h3>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
        <div>
          <label class="form-label">Full Name *</label>
          <input type="text" name="name" value="<?= e($member['name']??'') ?>" class="form-input <?= isset($errors['name'])?'error':'' ?>" required>
          <?php if(isset($errors['name'])): ?><div class="form-error"><i class="fa-solid fa-circle-exclamation text-xs"></i><?= e($errors['name']) ?></div><?php endif; ?>
        </div>
        <div>
          <label class="form-label">Email *</label>
          <input type="email" name="email" value="<?= e($member['email']??'') ?>" class="form-input <?= isset($errors['email'])?'error':'' ?>" required>
          <?php if(isset($errors['email'])): ?><div class="form-error"><i class="fa-solid fa-circle-exclamation text-xs"></i><?= e($errors['email']) ?></div><?php endif; ?>
        </div>
        <div>
          <label class="form-label">Role *</label>
          <select name="role" class="form-input">
            <option value="staff"  <?= ($member['role']??'staff')==='staff'  ? 'selected' : '' ?>>Staff — can log in, manage bookings</option>
            <option value="admin"  <?= ($member['role']??'')==='admin'        ? 'selected' : '' ?>>Admin — full access to all sections</option>
          </select>
        </div>
        <div>
          <label class="form-label">Phone</label>
          <input type="tel" name="phone" value="<?= e($member['phone']??'') ?>" class="form-input" placeholder="+256 7xx xxx xxx">
        </div>
        <div>
          <label class="form-label">Password <?= $member ? '<span class="text-white/35">(leave blank to keep)</span>' : '*' ?></label>
          <input type="password" name="password" class="form-input <?= isset($errors['password'])?'error':'' ?>" <?= !$member?'required':'' ?> placeholder="<?= $member?'Leave blank to keep current':'Minimum 6 characters' ?>">
          <?php if(isset($errors['password'])): ?><div class="form-error"><i class="fa-solid fa-circle-exclamation text-xs"></i><?= e($errors['password']) ?></div><?php endif; ?>
        </div>
      </div>

      <div class="h-px bg-white/8 mb-5"></div>
      <h3 class="font-serif text-sm font-semibold text-gold-light uppercase tracking-wider mb-4">Professional Info</h3>

      <div class="mb-5">
        <label class="form-label">Specialization</label>
        <input type="text" name="specialization" value="<?= e($member['specialization']??'') ?>" class="form-input" placeholder="e.g. Hair Styling & Braiding">
      </div>
      <div class="mb-5">
        <label class="form-label">Bio</label>
        <textarea name="bio" rows="3" class="form-input" placeholder="Brief professional background..."><?= e($member['bio']??'') ?></textarea>
      </div>
      <div class="flex items-center gap-3 mb-6">
        <label class="relative inline-flex items-center cursor-pointer">
          <input type="checkbox" name="available" value="1" class="sr-only peer" <?= ($member['available']??1)?'checked':'' ?>>
          <div class="w-10 h-5 bg-white/10 rounded-full peer peer-checked:bg-gold/70 transition-all after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:w-4 after:h-4 after:rounded-full after:bg-white/80 after:transition-all peer-checked:after:translate-x-5"></div>
        </label>
        <span class="text-white/65 text-sm">Available for bookings</span>
      </div>

      <!-- Availability -->
      <div class="h-px bg-white/8 mb-5"></div>
      <h3 class="font-serif text-sm font-semibold text-gold-light uppercase tracking-wider mb-4">Working Hours</h3>
      <div class="space-y-3">
        <?php for ($d=0;$d<7;$d++):
          $av   = $availability[$d] ?? null;
          $en   = $av ? true : false;
          $s    = $av['start_time'] ?? '09:00';
          $end2 = $av['end_time']   ?? '18:00';
        ?>
          <div class="flex items-center gap-4 py-2">
            <label class="flex items-center gap-2.5 w-32 cursor-pointer flex-shrink-0">
              <input type="checkbox" name="availability[<?= $d ?>][enabled]" value="1" class="w-4 h-4 rounded border-gold/30 bg-white/10 text-gold" id="day-<?= $d ?>" <?= $en?'checked':'' ?>>
              <span class="text-white/70 text-sm"><?= $days[$d] ?></span>
            </label>
            <input type="time" name="availability[<?= $d ?>][start]" value="<?= $s ?>" class="form-input py-1.5 text-sm w-28">
            <span class="text-white/30 text-sm">to</span>
            <input type="time" name="availability[<?= $d ?>][end]" value="<?= $end2 ?>" class="form-input py-1.5 text-sm w-28">
          </div>
        <?php endfor; ?>
      </div>

      <div class="flex flex-wrap gap-3 mt-7">
        <button type="submit" class="btn-gold px-7 py-2.5 rounded-xl font-semibold text-sm inline-flex items-center gap-2">
          <i class="fa-solid fa-floppy-disk"></i> <?= $member ? 'Update' : 'Add' ?> Staff Member
        </button>
        <a href="<?= url('dashboard/staff') ?>" class="glass px-6 py-2.5 rounded-xl text-sm text-white/60 hover:text-white/80 transition-colors">Cancel</a>
      </div>
    </form>
  </div>
</div>
