<!-- Stats -->
<div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-5 gap-4 mb-6">
  <?php
  $cards = [
    ['Today\'s Bookings',  $stats['today'],         'fa-calendar-day',        '#3b82f6'],
    ['Pending',            $stats['pending'],        'fa-clock',               '#f59e0b'],
    ['Active Services',    $stats['services'],       'fa-sparkles',            '#c9a84c'],
    ['Low Stock Items',    $stats['low_stock'],      'fa-triangle-exclamation','#ef4444'],
    ['Unread Messages',    $stats['unread_msgs'],    'fa-envelope',            '#a855f7'],
  ];
  $cardLinks = [
    url('dashboard/appointments?status=confirmed'),
    url('dashboard/appointments?status=pending'),
    url('dashboard/services'),
    url('dashboard/inventory'),
    url('dashboard/messages'),
  ];
  foreach ($cards as $i => [$label,$val,$icon,$clr]):
  ?>
    <a href="<?= $cardLinks[$i] ?>" class="glass rounded-2xl p-5 flex items-center gap-4 hover:border-white/20 transition-colors">
      <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background:<?= $clr ?>22;">
        <i class="fa-solid <?= $icon ?> text-xl" style="color:<?= $clr ?>"></i>
      </div>
      <div>
        <div class="stat-card-value"><?= $val ?></div>
        <div class="text-white/45 text-xs mt-0.5"><?= $label ?></div>
      </div>
    </a>
  <?php endforeach; ?>
</div>

<!-- Revenue cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
  <div class="glass rounded-2xl p-5 flex items-center gap-4">
    <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background:rgba(34,197,94,.12);">
      <i class="fa-solid fa-coins text-xl text-green-400"></i>
    </div>
    <div>
      <div class="stat-card-value text-green-400"><?= money($stats['revenue']) ?></div>
      <div class="text-white/45 text-xs mt-0.5">Total Revenue</div>
    </div>
  </div>
  <div class="glass rounded-2xl p-5 flex items-center gap-4">
    <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background:rgba(168,243,208,.1);">
      <i class="fa-solid fa-chart-line text-xl text-emerald-400"></i>
    </div>
    <div>
      <div class="stat-card-value text-emerald-400"><?= money($stats['revenue_month']) ?></div>
      <div class="text-white/45 text-xs mt-0.5">This Month</div>
    </div>
  </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-5">
  <!-- Recent appointments -->
  <div class="xl:col-span-2 glass rounded-2xl overflow-hidden">
    <div class="flex items-center justify-between p-5 border-b border-white/8">
      <h2 class="font-serif font-semibold text-white text-base">Recent Appointments</h2>
      <a href="<?= url('dashboard/appointments') ?>" class="text-gold text-xs hover:text-gold-light transition-colors flex items-center gap-1">
        View All <i class="fa-solid fa-arrow-right text-[10px]"></i>
      </a>
    </div>
    <div class="overflow-x-auto">
      <table class="data-table">
        <thead>
          <tr>
            <th>Client</th>
            <th>Service</th>
            <th>Date</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($recent)): ?>
            <tr><td colspan="4" class="text-center text-white/35 py-8">No appointments yet</td></tr>
          <?php else: foreach ($recent as $a): ?>
            <tr>
              <td>
                <div class="font-medium text-white/85"><?= e($a['client_name']) ?></div>
              </td>
              <td class="text-white/60"><?= e($a['service_name'] ?? '—') ?></td>
              <td class="text-white/60 text-xs"><?= fmtDate($a['appointment_date']) ?><br><?= fmtTime($a['appointment_time']) ?></td>
              <td><?= statusBadge($a['status']) ?></td>
            </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Revenue chart + quick actions -->
  <div class="space-y-5">
    <div class="glass rounded-2xl p-5">
      <h2 class="font-serif font-semibold text-white text-base mb-4">Revenue Trend</h2>
      <canvas id="revenueChart" height="160"></canvas>
    </div>

    <div class="glass rounded-2xl p-5">
      <h2 class="font-serif font-semibold text-white text-sm mb-3">Quick Actions</h2>
      <div class="space-y-2">
        <?php foreach ([
          ['dashboard/appointments/create', 'fa-calendar-plus', 'New Appointment','btn-gold'],
          ['dashboard/services/create',     'fa-plus-circle',   'Add Service',     'glass glass-hover'],
          ['dashboard/staff/create',        'fa-user-plus',     'Add Staff',       'glass glass-hover'],
          ['dashboard/inventory/create',    'fa-box-circle-check','Add Inventory', 'glass glass-hover'],
        ] as [$href,$icon,$label,$cls]): ?>
          <a href="<?= url($href) ?>" class="<?= $cls ?> flex items-center gap-2.5 px-4 py-2.5 rounded-xl text-xs font-medium transition-all <?= $cls==='btn-gold' ? '' : 'text-white/65 hover:text-gold-light' ?>">
            <i class="fa-solid <?= $icon ?> text-sm"></i><?= $label ?>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>

<script>
(function(){
  const ctx = document.getElementById('revenueChart');
  if (!ctx) return;
  const raw = <?= json_encode($chartData) ?>;
  const labels  = raw.map(r => { const d=r.month.split('-'); return ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'][parseInt(d[1])-1]+' '+d[0].slice(2); });
  const data    = raw.map(r => parseFloat(r.revenue));
  new Chart(ctx, {
    type:'line',
    data:{
      labels,
      datasets:[{
        label:'Revenue (UGX)',
        data,
        borderColor:'#c9a84c',
        backgroundColor:'rgba(201,168,76,.1)',
        fill:true,
        tension:.4,
        pointBackgroundColor:'#c9a84c',
        pointRadius:4,
        pointHoverRadius:6,
      }]
    },
    options:{
      responsive:true,
      maintainAspectRatio:true,
      plugins:{legend:{display:false},tooltip:{callbacks:{label:v=>'UGX '+v.raw.toLocaleString()}}},
      scales:{
        x:{grid:{color:'rgba(255,255,255,.04)'},ticks:{color:'rgba(255,255,255,.4)',font:{size:9}}},
        y:{grid:{color:'rgba(255,255,255,.04)'},ticks:{color:'rgba(255,255,255,.4)',font:{size:9},callback:v=>v>=1e6?(v/1e6).toFixed(1)+'M':v>=1e3?(v/1e3).toFixed(0)+'K':v}}
      }
    }
  });
})();
</script>
