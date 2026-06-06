<?php
$baseUrl = url('dashboard/clients');
$columns = [
  ['key'=>'client_name',  'label'=>'Name',    'sortable'=>false,'render'=>fn($r)=>'<div class="flex items-center gap-3"><div class="w-8 h-8 rounded-full gold-gradient flex items-center justify-center text-slate-900 text-xs font-bold flex-shrink-0">'.strtoupper(substr($r['client_name'],0,1)).'</div><div><div class="font-medium text-white/90">'.e($r['client_name']).'</div><div class="text-white/40 text-xs">'.e($r['client_email']).'</div></div></div>'],
  ['key'=>'client_phone', 'label'=>'Phone',   'sortable'=>false,'render'=>fn($r)=>'<span class="text-white/55 text-xs">'.e($r['client_phone']??'—').'</span>'],
  ['key'=>'total_appointments','label'=>'Visits','sortable'=>false,'render'=>fn($r)=>'<span class="font-semibold text-white/80">'.$r['total_appointments'].'</span>'],
  ['key'=>'first_visit',  'label'=>'First Visit','sortable'=>false,'render'=>fn($r)=>'<span class="text-white/45 text-xs">'.fmtDate($r['first_visit']).'</span>'],
  ['key'=>'last_visit',   'label'=>'Last Visit', 'sortable'=>false,'render'=>fn($r)=>'<span class="text-white/45 text-xs">'.fmtDate($r['last_visit']).'</span>'],
];
$actions = function($r) {
  return '<a href="'.url('dashboard/clients/'.urlencode($r['client_email'])).'" class="glass px-3 py-1.5 rounded-lg text-white/50 hover:text-gold transition-colors text-xs inline-flex items-center gap-1"><i class="fa-regular fa-eye"></i> View</a>';
};
?>

<div class="flex items-center justify-between mb-5">
  <div>
    <h2 class="font-serif text-xl font-semibold text-white">Clients</h2>
    <p class="text-white/40 text-xs mt-0.5"><?= number_format($total) ?> unique clients</p>
  </div>
</div>

<?php include __DIR__ . '/../components/datatable.php'; ?>
