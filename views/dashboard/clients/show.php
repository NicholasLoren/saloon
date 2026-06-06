<div class="max-w-3xl">
  <div class="flex items-center gap-3 mb-5">
    <a href="<?= url('dashboard/clients') ?>" class="glass p-2 rounded-xl text-white/50 hover:text-gold transition-colors">
      <i class="fa-solid fa-arrow-left text-sm"></i>
    </a>
    <h2 class="font-serif text-xl font-semibold text-white"><?= e($client['name']) ?></h2>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">
    <div class="glass rounded-2xl p-5 flex items-center gap-4">
      <div class="w-14 h-14 gold-gradient rounded-full flex items-center justify-center text-slate-900 text-2xl font-bold flex-shrink-0">
        <?= strtoupper(substr($client['name'],0,1)) ?>
      </div>
      <div>
        <div class="font-semibold text-white/90"><?= e($client['name']) ?></div>
        <div class="text-white/45 text-xs mt-0.5"><?= e($client['email']) ?></div>
        <div class="text-white/45 text-xs"><?= e($client['phone']??'—') ?></div>
      </div>
    </div>
    <div class="glass rounded-2xl p-5 text-center">
      <div class="stat-card-value"><?= count($history) ?></div>
      <div class="text-white/45 text-xs">Total Appointments</div>
    </div>
    <div class="glass rounded-2xl p-5 text-center">
      <div class="font-serif text-xl font-bold text-gold-light">
        <?= !empty($history) ? fmtDate($history[0]['appointment_date']) : '—' ?>
      </div>
      <div class="text-white/45 text-xs">Last Visit</div>
    </div>
  </div>

  <!-- Appointment history -->
  <div class="glass rounded-2xl overflow-hidden">
    <div class="p-5 border-b border-white/8">
      <h3 class="font-serif font-semibold text-white text-base">Appointment History</h3>
    </div>
    <div class="overflow-x-auto">
      <table class="data-table">
        <thead>
          <tr>
            <th>#</th><th>Service</th><th>Date</th><th>Time</th><th>Status</th><th></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($history as $h): ?>
            <tr>
              <td class="text-white/35 text-xs">#<?= $h['id'] ?></td>
              <td class="text-white/75 text-sm"><?= e($h['service_name']??'—') ?></td>
              <td class="text-white/55 text-xs"><?= fmtDate($h['appointment_date']) ?></td>
              <td class="text-white/55 text-xs"><?= fmtTime($h['appointment_time']) ?></td>
              <td><?= statusBadge($h['status']) ?></td>
              <td class="text-right">
                <a href="<?= url('dashboard/appointments/'.$h['id']) ?>" class="glass px-2.5 py-1 rounded-lg text-white/40 hover:text-gold text-xs transition-colors">View</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
