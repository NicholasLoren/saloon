<?php
$baseUrl = url('dashboard/appointments');
$columns = [
  ['key'=>'id',       'label'=>'#',        'sortable'=>true, 'render'=>fn($r)=>'<span class="text-white/40 text-xs">#'.e($r['id']).'</span>'],
  ['key'=>'client_name','label'=>'Client', 'sortable'=>true, 'render'=>fn($r)=>'<div class="font-medium text-white/90">'.e($r['client_name']).'</div><div class="text-white/40 text-xs">'.e($r['client_email']).'</div>'],
  ['key'=>'service_name','label'=>'Service','sortable'=>false,'render'=>fn($r)=>'<span class="text-white/75 text-sm">'.e($r['service_name']??'—').'</span>'],
  ['key'=>'appointment_date','label'=>'Date/Time','sortable'=>true,'render'=>fn($r)=>'<div class="text-white/80 text-xs">'.fmtDate($r['appointment_date']).'</div><div class="text-white/45 text-xs">'.fmtTime($r['appointment_time']).'</div>'],
  ['key'=>'staff_name','label'=>'Stylist', 'sortable'=>false,'render'=>fn($r)=>'<span class="text-white/55 text-xs">'.e($r['staff_name']??'Any').'</span>'],
  ['key'=>'status',    'label'=>'Status',  'sortable'=>true, 'render'=>fn($r)=>statusBadge($r['status'])],
];
$actions = function($r) {
  return '
    <div class="flex items-center gap-1.5 justify-end">
      <a href="'.url('dashboard/appointments/'.$r['id']).'" class="glass p-1.5 rounded-lg text-white/50 hover:text-gold transition-colors text-xs" title="View"><i class="fa-regular fa-eye"></i></a>
      <a href="'.url('dashboard/appointments/'.$r['id'].'/edit').'" class="glass p-1.5 rounded-lg text-white/50 hover:text-blue-400 transition-colors text-xs" title="Edit"><i class="fa-regular fa-pen-to-square"></i></a>
      <form method="POST" action="'.url('dashboard/appointments/'.$r['id'].'/delete').'" class="inline" data-confirm="Delete this appointment?">
        <button class="glass p-1.5 rounded-lg text-white/50 hover:text-red-400 transition-colors text-xs" type="submit" title="Delete"><i class="fa-regular fa-trash-can"></i></button>
      </form>
    </div>';
};
$filters = '<select id="dt-status" class="form-select py-2 text-sm w-auto min-w-[140px]">
  <option value="">All Status</option>
  '.implode('', array_map(fn($s)=>'<option value="'.$s.'" '.($status===$s?'selected':'').'>'.ucfirst($s).'</option>', ['pending','confirmed','completed','cancelled'])).'
</select>';
?>

<div class="flex items-center justify-between mb-5">
  <div>
    <h2 class="font-serif text-xl font-semibold text-white">All Appointments</h2>
    <p class="text-white/40 text-xs mt-0.5"><?= number_format($total) ?> total records</p>
  </div>
  <a href="<?= url('dashboard/appointments/create') ?>" class="btn-gold px-5 py-2.5 rounded-xl text-sm font-semibold inline-flex items-center gap-2">
    <i class="fa-solid fa-plus text-xs"></i> New Appointment
  </a>
</div>

<?php include __DIR__ . '/../components/datatable.php'; ?>
