<?php
$baseUrl = url('dashboard/services');
$columns = [
  ['key'=>'image',    'label'=>'',        'sortable'=>false, 'render'=>fn($r)=>$r['image']
    ? '<img src="'.storageUrl($r['image']).'" class="w-10 h-10 rounded-lg object-cover" alt="">'
    : '<div class="w-10 h-10 rounded-lg gold-gradient flex items-center justify-center text-slate-900 flex-shrink-0"><i class="fa-solid fa-scissors text-sm"></i></div>'],
  ['key'=>'name',     'label'=>'Service', 'sortable'=>true,  'render'=>fn($r)=>'<div class="font-medium text-white/90">'.e($r['name']).'</div><div class="text-white/40 text-xs">'.e($r['category']).'</div>'],
  ['key'=>'price',    'label'=>'Price',   'sortable'=>true,  'render'=>fn($r)=>'<span class="text-gold font-semibold text-sm">'.money((float)$r['price']).'</span>'],
  ['key'=>'duration_minutes','label'=>'Duration','sortable'=>true,'render'=>fn($r)=>'<span class="text-white/60 text-xs">'.$r['duration_minutes'].' min</span>'],
  ['key'=>'active',   'label'=>'Status',  'sortable'=>true,  'render'=>fn($r)=>statusBadge($r['active']?'active':'inactive')],
];
$actions = function($r) {
  return '
    <div class="flex items-center gap-1.5 justify-end">
      <a href="'.url('dashboard/services/'.$r['id']).'" class="glass p-1.5 rounded-lg text-white/50 hover:text-gold transition-colors text-xs" title="View"><i class="fa-regular fa-eye"></i></a>
      <a href="'.url('dashboard/services/'.$r['id'].'/edit').'" class="glass p-1.5 rounded-lg text-white/50 hover:text-blue-400 transition-colors text-xs" title="Edit"><i class="fa-regular fa-pen-to-square"></i></a>
      <form method="POST" action="'.url('dashboard/services/'.$r['id'].'/delete').'" class="inline" data-confirm="Delete \''.$r['name'].'\'?">
        <button class="glass p-1.5 rounded-lg text-white/50 hover:text-red-400 transition-colors text-xs" type="submit"><i class="fa-regular fa-trash-can"></i></button>
      </form>
    </div>';
};
?>

<div class="flex items-center justify-between mb-5">
  <div>
    <h2 class="font-serif text-xl font-semibold text-white">Services</h2>
    <p class="text-white/40 text-xs mt-0.5"><?= number_format($total) ?> total services</p>
  </div>
  <a href="<?= url('dashboard/services/create') ?>" class="btn-gold px-5 py-2.5 rounded-xl text-sm font-semibold inline-flex items-center gap-2">
    <i class="fa-solid fa-plus text-xs"></i> Add Service
  </a>
</div>

<?php include __DIR__ . '/../components/datatable.php'; ?>
