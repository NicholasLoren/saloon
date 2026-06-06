<div class="max-w-2xl">
  <div class="flex items-center gap-3 mb-5">
    <a href="<?= url('dashboard/appointments') ?>" class="glass p-2 rounded-xl text-white/50 hover:text-gold transition-colors">
      <i class="fa-solid fa-arrow-left text-sm"></i>
    </a>
    <h2 class="font-serif text-xl font-semibold text-white"><?= $appt ? 'Edit Appointment #'.e($appt['id']??'') : 'New Appointment' ?></h2>
  </div>

  <?php if (!empty($errors)): ?>
    <div class="alert alert-error mb-5">
      <i class="fa-solid fa-triangle-exclamation flex-shrink-0"></i>
      <div><strong class="block mb-1 text-sm">Please correct the following:</strong>
      <ul class="text-xs space-y-0.5"><?php foreach($errors as $e2): ?><li>&bull; <?= e($e2) ?></li><?php endforeach; ?></ul></div>
    </div>
  <?php endif; ?>

  <div class="glass rounded-2xl p-7">
    <form method="POST" action="<?= url('dashboard/appointments/'.($appt&&isset($appt['id'])?$appt['id'].'/edit':'create')) ?>">

      <!-- Client info -->
      <h3 class="font-serif text-sm font-semibold text-gold-light uppercase tracking-wider mb-4">Client Information</h3>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
        <div>
          <label class="form-label">Full Name *</label>
          <input type="text" name="client_name" value="<?= e($appt['client_name']??'') ?>" class="form-input <?= isset($errors['client_name'])?'error':'' ?>" placeholder="Client full name" required>
          <?php if(isset($errors['client_name'])): ?><div class="form-error"><i class="fa-solid fa-circle-exclamation text-xs"></i><?= e($errors['client_name']) ?></div><?php endif; ?>
        </div>
        <div>
          <label class="form-label">Email *</label>
          <input type="email" name="client_email" value="<?= e($appt['client_email']??'') ?>" class="form-input <?= isset($errors['client_email'])?'error':'' ?>" required>
          <?php if(isset($errors['client_email'])): ?><div class="form-error"><i class="fa-solid fa-circle-exclamation text-xs"></i><?= e($errors['client_email']) ?></div><?php endif; ?>
        </div>
      </div>
      <div class="mb-6">
        <label class="form-label">Phone Number</label>
        <input type="tel" name="client_phone" value="<?= e($appt['client_phone']??'') ?>" class="form-input" placeholder="+256 7xx xxx xxx">
      </div>

      <div class="h-px bg-white/8 mb-6"></div>
      <h3 class="font-serif text-sm font-semibold text-gold-light uppercase tracking-wider mb-4">Appointment Details</h3>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
        <div>
          <label class="form-label">Service *</label>
          <select name="service_id" id="booking-service" class="form-select <?= isset($errors['service_id'])?'error':'' ?>" required>
            <option value="">Select service</option>
            <?php foreach($services as $s): ?>
              <option value="<?= $s['id'] ?>" <?= ($appt['service_id']??'')==$s['id']?'selected':'' ?>><?= e($s['name']) ?></option>
            <?php endforeach; ?>
          </select>
          <?php if(isset($errors['service_id'])): ?><div class="form-error"><i class="fa-solid fa-circle-exclamation text-xs"></i><?= e($errors['service_id']) ?></div><?php endif; ?>
        </div>
        <div>
          <label class="form-label">Stylist <span class="text-white/35">(optional)</span></label>
          <select name="staff_id" id="booking-staff" class="form-select">
            <option value="">Any available</option>
            <?php foreach($staff as $s): ?>
              <option value="<?= $s['id'] ?>" <?= ($appt['staff_id']??'')==$s['id']?'selected':'' ?>><?= e($s['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
        <div>
          <label class="form-label">Date *</label>
          <input type="date" name="appointment_date" id="booking-date" value="<?= e($appt['appointment_date']??'') ?>" class="form-input <?= isset($errors['appointment_date'])?'error':'' ?>" required>
          <?php if(isset($errors['appointment_date'])): ?><div class="form-error"><i class="fa-solid fa-circle-exclamation text-xs"></i><?= e($errors['appointment_date']) ?></div><?php endif; ?>
        </div>
        <div>
          <label class="form-label">Time *</label>
          <select name="appointment_time" id="booking-time" class="form-select <?= isset($errors['appointment_time'])?'error':'' ?>" required>
            <option value="">Select date & service first</option>
            <?php if(!empty($appt['appointment_time'])): ?>
              <option value="<?= e($appt['appointment_time']) ?>" selected><?= fmtTime($appt['appointment_time']) ?></option>
            <?php endif; ?>
          </select>
          <?php if(isset($errors['appointment_time'])): ?><div class="form-error"><i class="fa-solid fa-circle-exclamation text-xs"></i><?= e($errors['appointment_time']) ?></div><?php endif; ?>
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
        <div>
          <label class="form-label">Status</label>
          <select name="status" class="form-select">
            <?php foreach(['pending','confirmed','completed','cancelled'] as $s): ?>
              <option value="<?= $s ?>" <?= ($appt['status']??'pending')===$s?'selected':'' ?>><?= ucfirst($s) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="mb-7">
        <label class="form-label">Notes</label>
        <textarea name="notes" rows="3" class="form-input" placeholder="Internal notes..."><?= e($appt['notes']??'') ?></textarea>
      </div>

      <div class="flex flex-wrap gap-3">
        <button type="submit" class="btn-gold px-7 py-2.5 rounded-xl font-semibold text-sm inline-flex items-center gap-2">
          <i class="fa-solid fa-floppy-disk"></i> <?= $appt ? 'Update' : 'Create' ?> Appointment
        </button>
        <a href="<?= url('dashboard/appointments') ?>" class="glass px-6 py-2.5 rounded-xl text-sm text-white/60 hover:text-white/80 transition-colors">Cancel</a>
      </div>
    </form>
  </div>
</div>
