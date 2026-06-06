<?php
$serviceImages = ['Hair'=>'service-hair.jpg','Nails'=>'service-nails.jpg','Skin'=>'service-facial.jpg','Beauty'=>'service-makeup.jpg','Spa'=>'service-spa.jpg'];
$activeCategory = $_GET['cat'] ?? 'all';
?>
<!-- Hero -->
<section class="pt-36 pb-16 px-6 relative overflow-hidden">
  <div class="max-w-7xl mx-auto text-center">
    <span class="section-label mb-3 block">What We Offer</span>
    <h1 class="section-title text-white mb-4">Our <em class="text-gold-light not-italic">Services</em></h1>
    <div class="section-divider"></div>
    <p class="text-white/50 text-sm mt-4 max-w-xl mx-auto">Premium beauty services delivered by certified professionals using the finest products.</p>
  </div>
</section>

<!-- Category filter -->
<section class="pb-8 px-6 sticky top-16 z-30">
  <div class="max-w-7xl mx-auto">
    <div class="glass rounded-2xl p-3 flex flex-wrap gap-2 justify-center">
      <a href="?cat=all" class="px-5 py-2 rounded-xl text-sm font-medium transition-all <?= $activeCategory==='all' ? 'btn-gold' : 'text-white/60 hover:text-gold-light hover:bg-white/5' ?>">
        All Services
      </a>
      <?php foreach ($categories as $cat): ?>
        <a href="?cat=<?= urlencode($cat) ?>" class="px-5 py-2 rounded-xl text-sm font-medium transition-all <?= $activeCategory===$cat ? 'btn-gold' : 'text-white/60 hover:text-gold-light hover:bg-white/5' ?>">
          <?= e($cat) ?>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Services grid -->
<section class="py-10 px-6">
  <div class="max-w-7xl mx-auto">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php foreach ($services as $svc):
        if ($activeCategory !== 'all' && $svc['category'] !== $activeCategory) continue;
        $img = $serviceImages[$svc['category']] ?? 'service-hair.jpg';
      ?>
        <div class="glass rounded-2xl overflow-hidden group glass-hover transition-all">
          <div class="relative overflow-hidden" style="height:200px;">
            <img src="<?= $svc['image'] ? storageUrl($svc['image']) : asset('images/'.$img) ?>" alt="<?= e($svc['name']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
            <div class="absolute top-3 right-3">
              <span class="glass rounded-full px-3 py-1 text-xs text-gold font-medium"><?= e($svc['category']) ?></span>
            </div>
          </div>
          <div class="p-6">
            <h3 class="font-serif font-semibold text-white text-lg mb-2"><?= e($svc['name']) ?></h3>
            <p class="text-white/50 text-sm leading-relaxed mb-5 line-clamp-2"><?= e($svc['description'] ?? '') ?></p>
            <div class="flex items-center justify-between mb-5">
              <span class="text-gold-light font-bold text-lg"><?= money((float)$svc['price']) ?></span>
              <span class="text-white/40 text-xs flex items-center gap-1.5">
                <i class="fa-regular fa-clock"></i><?= $svc['duration_minutes'] ?> min
              </span>
            </div>
            <a href="<?= url('book?service_id='.$svc['id']) ?>" class="btn-gold w-full py-2.5 rounded-xl text-sm font-semibold text-center block">
              <i class="fa-regular fa-calendar-plus mr-1.5"></i> Book This Service
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Booking form embedded -->
<section class="py-20 px-6">
  <div class="max-w-7xl mx-auto">
    <div class="glass rounded-3xl p-10 lg:p-14">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <div>
          <span class="section-label mb-3 block">Book Now</span>
          <h2 class="section-title text-white mb-5 text-left">Reserve Your <em class="text-gold-light not-italic">Appointment</em></h2>
          <p class="text-white/55 text-sm leading-relaxed mb-6">Choose your service, pick a time that works for you, and we will take care of the rest. No account required.</p>
          <ul class="space-y-3 text-sm text-white/60">
            <?php foreach (['Quick & easy online booking','Choose your preferred stylist','Flexible date and time selection','Instant booking confirmation'] as $item): ?>
              <li class="flex items-center gap-3"><i class="fa-solid fa-check-circle text-gold text-base"></i><?= $item ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
        <div>
          <a href="<?= url('book') ?>" class="btn-gold w-full py-4 rounded-2xl text-base font-semibold text-center block mb-3">
            <i class="fa-regular fa-calendar-plus mr-2"></i> Go to Booking Form
          </a>
          <p class="text-white/35 text-xs text-center">Takes less than 2 minutes</p>
        </div>
      </div>
    </div>
  </div>
</section>
