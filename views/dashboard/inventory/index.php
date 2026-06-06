<?php
$baseUrl = url('dashboard/inventory');
$columns = [
  ['key'=>'product_name','label'=>'Product','sortable'=>true,'render'=>fn($r)=>'<div class="font-medium text-white/90">'.e($r['product_name']).'</div><div class="text-white/40 text-xs">'.e($r['category']??'—').'</div>'],
  ['key'=>'quantity',   'label'=>'Qty',    'sortable'=>true, 'render'=>fn($r)=>'<span class="font-semibold '.((float)$r['quantity']<=(float)$r['reorder_level']?'text-red-400':'text-white/80').'">'.$r['quantity'].' '.e($r['unit']).'</span>'.((float)$r['quantity']<=(float)$r['reorder_level']?'<span class="text-red-400/70 text-xs ml-2"><i class="fa-solid fa-triangle-exclamation"></i> Low</span>':'')],
  ['key'=>'reorder_level','label'=>'Reorder At','sortable'=>true,'render'=>fn($r)=>'<span class="text-white/45 text-xs">'.$r['reorder_level'].' '.$r['unit'].'</span>'],
  ['key'=>'cost_price', 'label'=>'Cost',   'sortable'=>true, 'render'=>fn($r)=>'<span class="text-gold text-sm">'.money((float)$r['cost_price']).'</span>'],
  ['key'=>'supplier',   'label'=>'Supplier','sortable'=>false,'render'=>fn($r)=>'<span class="text-white/50 text-xs">'.e($r['supplier']??'—').'</span>'],
  ['key'=>'last_updated','label'=>'Updated','sortable'=>true, 'render'=>fn($r)=>'<span class="text-white/35 text-xs">'.date('d M Y',strtotime($r['last_updated'])).'</span>'],
];
$actions = function($r) {
  return '
    <div class="flex items-center gap-1.5 justify-end">
      <a href="'.url('dashboard/inventory/'.$r['id'].'/edit').'" class="glass p-1.5 rounded-lg text-white/50 hover:text-blue-400 transition-colors text-xs"><i class="fa-regular fa-pen-to-square"></i></a>
      <form method="POST" action="'.url('dashboard/inventory/'.$r['id'].'/delete').'" class="inline" data-confirm="Delete \''.$r['product_name'].'\'?">
        <button class="glass p-1.5 rounded-lg text-white/50 hover:text-red-400 transition-colors text-xs"><i class="fa-regular fa-trash-can"></i></button>
      </form>
    </div>';
};
?>

<div class="flex items-center justify-between mb-5">
  <div>
    <h2 class="font-serif text-xl font-semibold text-white">Inventory</h2>
    <p class="text-white/40 text-xs mt-0.5"><?= number_format($total) ?> items</p>
  </div>
  <a href="<?= url('dashboard/inventory/create') ?>" class="btn-gold px-5 py-2.5 rounded-xl text-sm font-semibold inline-flex items-center gap-2">
    <i class="fa-solid fa-plus text-xs"></i> Add Item
  </a>
</div>

<?php include __DIR__ . '/../components/datatable.php'; ?>
