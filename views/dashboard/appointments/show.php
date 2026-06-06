<div class="max-w-3xl">
  <!-- Header -->
  <div class="flex items-center gap-3 mb-5">
    <a href="<?= url('dashboard/appointments') ?>" class="glass p-2 rounded-xl text-white/50 hover:text-gold transition-colors">
      <i class="fa-solid fa-arrow-left text-sm"></i>
    </a>
    <div class="flex-1">
      <h2 class="font-serif text-xl font-semibold text-white">Appointment #<?= $appt['id'] ?></h2>
      <p class="text-white/40 text-xs mt-0.5">Created <?= fmtDate($appt['created_at']) ?></p>
    </div>
    <?= statusBadge($appt['status']) ?>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <!-- Client info -->
    <div class="glass rounded-2xl p-6">
      <h3 class="font-serif font-semibold text-gold-light text-sm mb-4 uppercase tracking-wider">Client Details</h3>
      <dl class="space-y-3">
        <?php foreach ([['fa-user','Name',$appt['client_name']],['fa-envelope','Email',$appt['client_email']],['fa-phone','Phone',$appt['client_phone']??'—']] as [$ico,$lbl,$val]): ?>
          <div class="flex items-start gap-3">
            <i class="fa-solid <?= $ico ?> text-gold/60 mt-0.5 w-4 text-sm flex-shrink-0"></i>
            <div><div class="text-white/40 text-xs"><?= $lbl ?></div><div class="text-white/85 text-sm"><?= e($val) ?></div></div>
          </div>
        <?php endforeach; ?>
      </dl>
    </div>

    <!-- Appointment info -->
    <div class="glass rounded-2xl p-6">
      <h3 class="font-serif font-semibold text-gold-light text-sm mb-4 uppercase tracking-wider">Appointment Info</h3>
      <dl class="space-y-3">
        <?php foreach ([
          ['fa-sparkles','Service',$appt['service_name']??'—'],
          ['fa-calendar','Date',fmtDate($appt['appointment_date'])],
          ['fa-clock','Time',fmtTime($appt['appointment_time'])],
          ['fa-user-tie','Stylist',$appt['staff_name']??'Any Available'],
          ['fa-coins','Price',money((float)($appt['service_price']??0))],
        ] as [$ico,$lbl,$val]): ?>
          <div class="flex items-start gap-3">
            <i class="fa-solid <?= $ico ?> text-gold/60 mt-0.5 w-4 text-sm flex-shrink-0"></i>
            <div><div class="text-white/40 text-xs"><?= $lbl ?></div><div class="text-white/85 text-sm"><?= e($val) ?></div></div>
          </div>
        <?php endforeach; ?>
      </dl>
    </div>
  </div>

  <?php if ($appt['notes']): ?>
    <div class="glass rounded-2xl p-5 mt-5">
      <h3 class="font-serif font-semibold text-gold-light text-sm mb-2 uppercase tracking-wider">Notes</h3>
      <p class="text-white/60 text-sm"><?= nl2br(e($appt['notes'])) ?></p>
    </div>
  <?php endif; ?>

  <!-- Status update -->
  <div class="glass rounded-2xl p-6 mt-5">
    <h3 class="font-serif font-semibold text-white text-sm mb-4">Update Status</h3>
    <form method="POST" action="<?= url('dashboard/appointments/'.$appt['id'].'/status') ?>">
      <div class="flex flex-wrap gap-3 items-center">
        <select name="status" class="form-select py-2 text-sm w-auto min-w-[160px]">
          <?php foreach (['pending','confirmed','completed','cancelled'] as $s): ?>
            <option value="<?= $s ?>" <?= $appt['status']===$s?'selected':'' ?>><?= ucfirst($s) ?></option>
          <?php endforeach; ?>
        </select>
        <button type="submit" class="btn-gold px-5 py-2 rounded-xl text-sm font-semibold">Update Status</button>
      </div>
    </form>
  </div>

  <!-- Actions -->
  <div class="flex flex-wrap gap-3 mt-5">
    <a href="<?= url('dashboard/appointments/'.$appt['id'].'/edit') ?>" class="glass px-5 py-2.5 rounded-xl text-sm text-white/70 hover:text-gold transition-colors inline-flex items-center gap-2">
      <i class="fa-regular fa-pen-to-square"></i> Edit
    </a>
    <form method="POST" action="<?= url('dashboard/appointments/'.$appt['id'].'/delete') ?>" data-confirm="Permanently delete this appointment?">
      <button type="submit" class="glass px-5 py-2.5 rounded-xl text-sm text-red-400/70 hover:text-red-400 transition-colors inline-flex items-center gap-2">
        <i class="fa-regular fa-trash-can"></i> Delete
      </button>
    </form>
    <a href="<?= url('dashboard/appointments') ?>" class="glass px-5 py-2.5 rounded-xl text-sm text-white/50 hover:text-white/70 transition-colors inline-flex items-center gap-2">
      <i class="fa-solid fa-arrow-left text-xs"></i> Back to List
    </a>
  </div>
</div>
