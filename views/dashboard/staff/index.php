<?php
$baseUrl = url('dashboard/staff');
$teamImgs = ['team-1.jpg','team-2.jpg','team-3.jpg'];
$columns = [
  ['key'=>'avatar',   'label'=>'',        'sortable'=>false, 'render'=>function($r) use($teamImgs) {
    static $i=0;
    $img = asset('images/'.$teamImgs[$i%3]); $i++;
    return '<img src="'.$img.'" class="w-10 h-10 rounded-full object-cover" alt="">';
  }],
  ['key'=>'name',     'label'=>'Name',    'sortable'=>true,  'render'=>fn($r)=>'<div class="font-medium text-white/90">'.e($r['name']).'</div><div class="text-white/40 text-xs">'.e($r['email']).'</div>'],
  ['key'=>'specialization','label'=>'Specialization','sortable'=>false,'render'=>fn($r)=>'<span class="text-white/60 text-xs">'.e($r['specialization']??'—').'</span>'],
  ['key'=>'phone',    'label'=>'Phone',   'sortable'=>false, 'render'=>fn($r)=>'<span class="text-white/55 text-xs">'.e($r['phone']??'—').'</span>'],
  ['key'=>'role',     'label'=>'Role',    'sortable'=>false, 'render'=>fn($r)=>($r['role']==='admin'?'<span class="text-xs px-2 py-0.5 rounded-full bg-gold/15 text-gold font-semibold">Admin</span>':'<span class="text-xs px-2 py-0.5 rounded-full bg-white/8 text-white/50">Staff</span>')],
  ['key'=>'available','label'=>'Status',  'sortable'=>true,  'render'=>fn($r)=>statusBadge($r['available']?'active':'inactive')],
];
$actions = function($r) {
  return '
    <div class="flex items-center gap-1.5 justify-end">
      <a href="'.url('dashboard/staff/'.$r['id'].'/edit').'" class="glass p-1.5 rounded-lg text-white/50 hover:text-blue-400 transition-colors text-xs" title="Edit"><i class="fa-regular fa-pen-to-square"></i></a>
      <form method="POST" action="'.url('dashboard/staff/'.$r['id'].'/delete').'" class="inline" data-confirm="Remove \''.$r['name'].'\' from staff?">
        <button class="glass p-1.5 rounded-lg text-white/50 hover:text-red-400 transition-colors text-xs"><i class="fa-regular fa-trash-can"></i></button>
      </form>
    </div>';
};
?>

<div class="flex items-center justify-between mb-5">
  <div>
    <h2 class="font-serif text-xl font-semibold text-white">Staff Members</h2>
    <p class="text-white/40 text-xs mt-0.5"><?= number_format($total) ?> team members</p>
  </div>
  <a href="<?= url('dashboard/staff/create') ?>" class="btn-gold px-5 py-2.5 rounded-xl text-sm font-semibold inline-flex items-center gap-2">
    <i class="fa-solid fa-user-plus text-xs"></i> Add Staff
  </a>
</div>

<?php include __DIR__ . '/../components/datatable.php'; ?>
