<?php $currentPage = $page ?? ''; ?>
<nav id="main-nav" class="navbar">
  <div class="max-w-7xl mx-auto flex items-center justify-between">

    <!-- Logo -->
    <a href="<?= url('/') ?>" class="flex items-center gap-2.5 group">
      <div class="w-9 h-9 rounded-xl gold-gradient flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform">
        <i class="fa-solid fa-scissors text-slate-900 text-sm"></i>
      </div>
      <span class="font-serif font-bold text-lg tracking-wide">
        <span class="text-gold-light">Matilda's</span>
        <span class="text-white/80 text-sm font-normal ml-1">Salon & Spa</span>
      </span>
    </a>

    <!-- Desktop nav -->
    <ul class="hidden md:flex items-center gap-1">
      <li><a href="<?= url('/') ?>"        class="nav-link <?= $currentPage==='home'     ? 'active':'' ?>">Home</a></li>
      <li><a href="<?= url('about') ?>"    class="nav-link <?= $currentPage==='about'    ? 'active':'' ?>">About</a></li>
      <li><a href="<?= url('services') ?>" class="nav-link <?= $currentPage==='services' ? 'active':'' ?>">Services</a></li>
      <li><a href="<?= url('faq') ?>"      class="nav-link <?= $currentPage==='faq'      ? 'active':'' ?>">FAQ</a></li>
      <li><a href="<?= url('contact') ?>"  class="nav-link <?= $currentPage==='contact'  ? 'active':'' ?>">Contact</a></li>
    </ul>

    <!-- CTA + hamburger -->
    <div class="flex items-center gap-3">
      <a href="<?= url('book') ?>" class="hidden md:inline-flex items-center gap-2 btn-gold px-5 py-2 rounded-full text-sm shadow-md">
        <i class="fa-regular fa-calendar-plus text-xs"></i> Book Now
      </a>
      <a href="<?= url('dashboard/login') ?>" class="hidden md:inline-flex items-center gap-1.5 text-sm text-white/50 hover:text-gold transition-colors px-2">
        <i class="fa-solid fa-gauge-simple-high text-xs"></i> Staff
      </a>

      <!-- Mobile hamburger -->
      <button id="mobile-menu-btn" class="md:hidden glass p-2 rounded-lg" aria-label="Open menu">
        <i class="fa-solid fa-bars text-gold-light"></i>
      </button>
    </div>
  </div>
</nav>

<!-- Mobile drawer -->
<div id="mobile-nav" class="fixed top-0 right-0 z-50 h-screen w-72 glass-dark" tabindex="-1" aria-hidden="true"
     style="transform:translateX(100%);transition:transform .3s ease;will-change:transform;">
  <div class="p-5">
    <div class="flex items-center justify-between mb-8">
      <span class="font-serif text-gold-light font-bold text-lg">Matilda's</span>
      <button id="mobile-nav-close" class="text-white/50 hover:text-white glass p-2 rounded-lg" aria-label="Close menu">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>
    <nav class="flex flex-col gap-1">
      <?php foreach ([['/',        'Home',     'house'],['about','About','info-circle'],['services','Services','sparkles'],['faq','FAQ','circle-question'],['contact','Contact','envelope'],['book','Book Now','calendar-plus']] as [$href, $label, $icon]): ?>
        <a href="<?= url($href) ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl text-white/80 hover:bg-white/10 hover:text-gold-light transition-all text-sm">
          <i class="fa-regular fa-<?= $icon ?> w-4 text-gold"></i><?= $label ?>
        </a>
      <?php endforeach; ?>
    </nav>
  </div>
</div>
<div id="mobile-overlay" class="hidden fixed inset-0 z-40 bg-black/60 backdrop-blur-sm"></div>

<script>
(function(){
  var btn     = document.getElementById('mobile-menu-btn');
  var nav     = document.getElementById('mobile-nav');
  var overlay = document.getElementById('mobile-overlay');
  var close   = document.getElementById('mobile-nav-close');
  function open()  { nav.style.transform='translateX(0)';   overlay.classList.remove('hidden'); nav.setAttribute('aria-hidden','false'); }
  function shut()  { nav.style.transform='translateX(100%)'; overlay.classList.add('hidden');   nav.setAttribute('aria-hidden','true');  }
  btn     && btn.addEventListener('click', open);
  close   && close.addEventListener('click', shut);
  overlay && overlay.addEventListener('click', shut);
  document.addEventListener('keydown', function(e){ if(e.key==='Escape') shut(); });
})();
</script>
