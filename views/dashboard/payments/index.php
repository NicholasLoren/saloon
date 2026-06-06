<?php
$baseUrl = url('dashboard/payments');
$columns = [
  ['key'=>'invoice_number','label'=>'Invoice','sortable'=>true,'render'=>fn($r)=>'<span class="text-gold font-mono text-xs">'.e($r['invoice_number']).'</span>'],
  ['key'=>'client_name','label'=>'Client','sortable'=>false,'render'=>fn($r)=>'<div class="text-white/85 text-sm">'.e($r['client_name']).'</div><div class="text-white/40 text-xs">'.e($r['client_email']).'</div>'],
  ['key'=>'service_name','label'=>'Service','sortable'=>false,'render'=>fn($r)=>'<span class="text-white/60 text-xs">'.e($r['service_name']??'—').'</span>'],
  ['key'=>'amount',   'label'=>'Amount', 'sortable'=>true, 'render'=>fn($r)=>'<span class="font-semibold text-sm text-white/85">'.money((float)$r['amount']).'</span>'],
  ['key'=>'payment_method','label'=>'Method','sortable'=>false,'render'=>fn($r)=>'<span class="text-white/50 text-xs capitalize">'.e($r['payment_method']??'—').'</span>'],
  ['key'=>'status',   'label'=>'Status', 'sortable'=>true, 'render'=>fn($r)=>statusBadge($r['status'])],
  ['key'=>'created_at','label'=>'Date',  'sortable'=>true, 'render'=>fn($r)=>'<span class="text-white/40 text-xs">'.date('d M Y',strtotime($r['created_at'])).'</span>'],
];
$actions = function($r) {
  return '<a href="'.url('dashboard/payments/'.$r['id']).'" class="glass p-1.5 rounded-lg text-white/50 hover:text-gold transition-colors text-xs" title="View Invoice"><i class="fa-regular fa-file-invoice"></i></a>';
};
$filters = '<select id="dt-status" class="form-select py-2 text-sm w-auto min-w-[140px]">
  <option value="">All Status</option>
  '.implode('', array_map(fn($s)=>'<option value="'.$s.'" '.($status===$s?'selected':'').'>'.ucfirst($s).'</option>', ['pending','paid','refunded'])).'
</select>';
?>

<div class="flex items-center justify-between mb-5">
  <div>
    <h2 class="font-serif text-xl font-semibold text-white">Payments</h2>
    <p class="text-white/40 text-xs mt-0.5"><?= number_format($total) ?> records</p>
  </div>
</div>

<?php include __DIR__ . '/../components/datatable.php'; ?>
