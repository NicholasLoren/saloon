<div class="max-w-2xl">
  <div class="flex items-center gap-3 mb-5">
    <a href="<?= url('dashboard/messages') ?>" class="glass p-2 rounded-xl text-white/50 hover:text-gold transition-colors">
      <i class="fa-solid fa-arrow-left text-sm"></i>
    </a>
    <h2 class="font-serif text-xl font-semibold text-white">Message</h2>
    <span class="text-white/30 text-xs ml-1">#<?= $message['id'] ?></span>
  </div>

  <!-- Sender info -->
  <div class="glass rounded-2xl p-5 mb-4 flex flex-wrap items-start gap-5">
    <div class="w-14 h-14 rounded-full gold-gradient flex items-center justify-center text-slate-900 text-2xl font-bold flex-shrink-0">
      <?= strtoupper(substr($message['name'], 0, 1)) ?>
    </div>
    <div class="flex-1 min-w-0">
      <div class="font-semibold text-white/90 text-base"><?= e($message['name']) ?></div>
      <div class="text-white/45 text-sm"><?= e($message['email']) ?></div>
      <?php if ($message['phone']): ?>
        <div class="text-white/40 text-sm"><?= e($message['phone']) ?></div>
      <?php endif; ?>
    </div>
    <div class="text-right">
      <div class="text-white/35 text-xs"><?= fmtDate($message['created_at']) ?></div>
      <div class="text-white/25 text-xs"><?= date('H:i', strtotime($message['created_at'])) ?></div>
    </div>
  </div>

  <!-- Message body -->
  <div class="glass rounded-2xl overflow-hidden">
    <?php if ($message['subject']): ?>
      <div class="p-4 border-b border-white/8">
        <div class="text-white/35 text-xs uppercase tracking-widest mb-1">Subject</div>
        <div class="font-semibold text-white/90"><?= e($message['subject']) ?></div>
      </div>
    <?php endif; ?>
    <div class="p-5">
      <div class="text-white/35 text-xs uppercase tracking-widest mb-3">Message</div>
      <div class="text-white/75 text-sm leading-relaxed whitespace-pre-wrap"><?= e($message['message']) ?></div>
    </div>
  </div>

  <!-- Actions -->
  <div class="flex flex-wrap items-center gap-3 mt-5">
    <a href="mailto:<?= e($message['email']) ?>?subject=Re: <?= e(rawurlencode($message['subject'] ?? 'Your Enquiry')) ?>"
       class="btn-gold px-6 py-2.5 rounded-xl text-sm font-semibold inline-flex items-center gap-2">
      <i class="fa-regular fa-envelope"></i> Reply via Email
    </a>
    <?php if ($message['phone']): ?>
      <a href="https://wa.me/<?= preg_replace('/\D/', '', $message['phone']) ?>"
         target="_blank"
         class="glass px-5 py-2.5 rounded-xl text-sm text-emerald-400 hover:text-emerald-300 transition-colors inline-flex items-center gap-2">
        <i class="fa-brands fa-whatsapp"></i> WhatsApp
      </a>
    <?php endif; ?>
    <form method="POST" action="<?= url('dashboard/messages/'.$message['id'].'/delete') ?>" data-confirm="Delete this message?" class="ml-auto">
      <button class="glass px-5 py-2.5 rounded-xl text-sm text-red-400/60 hover:text-red-400 transition-colors inline-flex items-center gap-2">
        <i class="fa-regular fa-trash-can"></i> Delete
      </button>
    </form>
  </div>
</div>
