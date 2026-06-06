<?php
$baseUrl = url('dashboard/messages');
$columns = [
  ['key'=>'name',    'label'=>'From',    'sortable'=>false, 'render'=>fn($r)=>'<div class="flex items-center gap-3"><div class="w-8 h-8 rounded-full gold-gradient flex items-center justify-center text-slate-900 text-xs font-bold flex-shrink-0">'.strtoupper(substr($r['name'],0,1)).'</div><div><div class="font-medium '.(!$r['is_read']?'text-white':'text-white/70').'">'.e($r['name']).(!$r['is_read']?'<span class="ml-2 w-1.5 h-1.5 rounded-full bg-gold inline-block"></span>':'').'</div><div class="text-white/40 text-xs">'.e($r['email']).'</div></div></div>'],
  ['key'=>'subject', 'label'=>'Subject', 'sortable'=>false, 'render'=>fn($r)=>'<span class="'.(!$r['is_read']?'text-white/80 font-medium':'text-white/50').' text-sm">'.e($r['subject']??'(No subject)').'</span>'],
  ['key'=>'phone',   'label'=>'Phone',   'sortable'=>false, 'render'=>fn($r)=>'<span class="text-white/40 text-xs">'.e($r['phone']??'—').'</span>'],
  ['key'=>'created_at','label'=>'Received','sortable'=>false,'render'=>fn($r)=>'<span class="text-white/35 text-xs">'.fmtDate($r['created_at']).'</span>'],
  ['key'=>'is_read', 'label'=>'Status',  'sortable'=>false, 'render'=>fn($r)=>$r['is_read']?'<span class="text-white/30 text-xs">Read</span>':'<span class="text-xs px-2 py-0.5 rounded-full bg-gold/15 text-gold">New</span>'],
];
$actions = function($r) {
  return '
    <div class="flex items-center gap-1.5 justify-end">
      <a href="'.url('dashboard/messages/'.$r['id']).'" class="glass px-2.5 py-1 rounded-lg text-white/40 hover:text-gold text-xs transition-colors"><i class="fa-regular fa-eye"></i> View</a>
      <form method="POST" action="'.url('dashboard/messages/'.$r['id'].'/delete').'" data-confirm="Delete this message?" class="inline">
        <button class="glass p-1.5 rounded-lg text-white/50 hover:text-red-400 transition-colors text-xs"><i class="fa-regular fa-trash-can"></i></button>
      </form>
    </div>';
};
?>

<div class="flex items-center justify-between mb-5">
  <div>
    <h2 class="font-serif text-xl font-semibold text-white">Messages</h2>
    <p class="text-white/40 text-xs mt-0.5"><?= number_format($total) ?> messages</p>
  </div>
</div>

<?php include __DIR__ . '/../components/datatable.php'; ?>
