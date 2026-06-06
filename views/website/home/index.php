<!-- ── HERO ───────────────────────────────────────────────── -->
<section class="hero" id="hero">
  <div class="hero-bg" style="background-image:url('<?= asset('images/hero.jpg') ?>')"></div>
  <div class="hero-overlay"></div>
  <div class="hero-content w-full">
    <div class="max-w-7xl mx-auto px-6 pt-32 pb-20">
      <div class="max-w-2xl">
        <span class="section-label mb-4 block">Welcome to <?= SALON_NAME ?></span>
        <h1 class="font-serif text-5xl md:text-7xl font-bold leading-tight mb-6">
          Luxury Beauty.<br>
          <span style="-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;background-image:linear-gradient(135deg,#c9a84c,#e8c97a);display:inline;">Redefined.</span>
        </h1>
        <p class="text-white/65 text-lg md:text-xl leading-relaxed mb-10 max-w-lg">
          Experience world-class hair, nail, and skin treatments at Freedom City, Namasuba. Your transformation begins here.
        </p>
        <div class="flex flex-wrap gap-4">
          <a href="<?= url('book') ?>" class="btn-gold px-8 py-3.5 rounded-full text-base font-semibold shadow-xl inline-flex items-center gap-2.5">
            <i class="fa-regular fa-calendar-plus"></i> Book Appointment
          </a>
          <a href="<?= url('services') ?>" class="glass px-8 py-3.5 rounded-full text-base font-medium text-white/80 hover:text-gold-light inline-flex items-center gap-2.5 transition-all hover:bg-white/10">
            <i class="fa-regular fa-sparkles"></i> Explore Services
          </a>
        </div>

        <!-- Quick stats -->
        <div class="flex flex-wrap gap-8 mt-14 pt-10 border-t border-white/10">
          <?php foreach ([['500+','Happy Clients'],['10+','Services Available'],['5+','Expert Stylists'],['5★','Star Rating']] as [$num,$label]): ?>
            <div>
              <div class="font-serif text-3xl font-bold text-gold-light"><?= $num ?></div>
              <div class="text-white/50 text-sm mt-0.5"><?= $label ?></div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
  <!-- Scroll indicator -->
  <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
    <a href="#services" class="text-white/30 hover:text-gold transition-colors">
      <i class="fa-solid fa-chevron-down text-lg"></i>
    </a>
  </div>
</section>

<!-- ── SERVICES PREVIEW ──────────────────────────────────── -->
<section class="py-24 px-6" id="services">
  <div class="max-w-7xl mx-auto">
    <div class="text-center mb-14">
      <span class="section-label mb-3 block">What We Offer</span>
      <h2 class="section-title text-white mb-3">Our Signature Services</h2>
      <div class="section-divider"></div>
      <p class="text-white/50 mt-4 max-w-xl mx-auto text-sm">From precision cuts to luxurious spa treatments — every service is crafted to perfection.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
      <?php
      $serviceImages = [
        'Hair'   => 'service-hair.jpg',
        'Nails'  => 'service-nails.jpg',
        'Skin'   => 'service-facial.jpg',
        'Beauty' => 'service-makeup.jpg',
        'Spa'    => 'service-spa.jpg',
      ];
      $shown = 0;
      foreach ($services as $svc):
        if ($shown >= 8) break;
        $imgKey = $svc['category'] ?? 'Hair';
        $img    = $serviceImages[$imgKey] ?? 'service-hair.jpg';
        $shown++;
      ?>
        <a href="<?= url('services') ?>" class="service-card glass rounded-2xl overflow-hidden group block" style="min-height:240px;position:relative;">
          <img src="<?= asset('images/' . $img) ?>" alt="<?= e($svc['name']) ?>" class="w-full h-full object-cover absolute inset-0 group-hover:scale-105 transition-transform duration-500" style="min-height:240px;">
          <div class="overlay absolute inset-0 rounded-2xl"></div>
          <div class="absolute bottom-0 left-0 right-0 p-5 z-10">
            <div class="text-xs text-gold font-medium tracking-wider uppercase mb-1"><?= e($svc['category']) ?></div>
            <h3 class="font-serif text-white font-semibold text-base leading-snug mb-2"><?= e($svc['name']) ?></h3>
            <div class="flex items-center justify-between">
              <span class="text-gold-light font-bold text-sm"><?= money((float)$svc['price']) ?></span>
              <span class="text-white/50 text-xs"><i class="fa-regular fa-clock mr-1"></i><?= $svc['duration_minutes'] ?> min</span>
            </div>
          </div>
        </a>
      <?php endforeach; ?>
    </div>

    <div class="text-center mt-10">
      <a href="<?= url('services') ?>" class="btn-gold px-8 py-3 rounded-full text-sm font-semibold inline-flex items-center gap-2">
        View All Services <i class="fa-solid fa-arrow-right text-xs"></i>
      </a>
    </div>
  </div>
</section>

<!-- ── ABOUT TEASER ──────────────────────────────────────── -->
<section class="py-24 px-6">
  <div class="max-w-7xl mx-auto">
    <div class="glass rounded-3xl overflow-hidden">
      <div class="grid grid-cols-1 lg:grid-cols-2">
        <div class="relative overflow-hidden" style="min-height:420px;">
          <img src="<?= asset('images/about.jpg') ?>" alt="About Matilda's Salon" class="w-full h-full object-cover absolute inset-0">
          <div class="absolute inset-0 bg-gradient-to-r from-transparent to-black/40"></div>
          <!-- Floating badge -->
          <div class="absolute bottom-8 left-8 glass rounded-2xl px-5 py-4 text-center">
            <div class="font-serif text-3xl font-bold text-gold-light">5+</div>
            <div class="text-white/60 text-xs mt-0.5">Years of Excellence</div>
          </div>
        </div>
        <div class="p-10 lg:p-14 flex flex-col justify-center">
          <span class="section-label mb-4 block">Our Story</span>
          <h2 class="section-title text-white mb-6 text-left">Beauty is Our<br><em class="text-gold-light not-italic">Passion</em></h2>
          <p class="text-white/60 text-sm leading-relaxed mb-6">
            Matilda's Salon &amp; Spa was founded with a single vision: to bring world-class beauty services to Kampala. Nestled at Freedom City, Namasuba, we are a sanctuary where skilled stylists, innovative techniques, and premium products converge to redefine your beauty experience.
          </p>
          <p class="text-white/60 text-sm leading-relaxed mb-8">
            Whether you are a first-time visitor or a returning client, we treat every appointment as an opportunity to exceed expectations. Your comfort, satisfaction, and natural radiance are always our priority.
          </p>
          <div class="grid grid-cols-3 gap-4 mb-8">
            <?php foreach ([['<i class="fa-solid fa-award text-gold text-lg mb-2"></i>','Premium','Quality'],['<i class="fa-solid fa-star text-gold text-lg mb-2"></i>','Expert','Stylists'],['<i class="fa-solid fa-leaf text-gold text-lg mb-2"></i>','Natural','Products']] as [$icon,$t1,$t2]): ?>
              <div class="glass rounded-xl p-4 text-center">
                <?= $icon ?><div class="text-xs font-semibold text-white/80"><?= $t1 ?></div><div class="text-xs text-white/40"><?= $t2 ?></div>
              </div>
            <?php endforeach; ?>
          </div>
          <a href="<?= url('about') ?>" class="btn-gold px-8 py-3 rounded-full text-sm font-semibold inline-flex items-center gap-2 self-start">
            Learn More <i class="fa-solid fa-arrow-right text-xs"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ── OUR TEAM ──────────────────────────────────────────── -->
<?php if (!empty($team)): ?>
<section class="py-24 px-6">
  <div class="max-w-7xl mx-auto">
    <div class="text-center mb-14">
      <span class="section-label mb-3 block">The Experts</span>
      <h2 class="section-title text-white mb-3">Meet Our Team</h2>
      <div class="section-divider"></div>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
      <?php $imgs = ['team-1.jpg','team-2.jpg','team-3.jpg']; $ti=0;
      foreach (array_slice($team,0,4) as $member): ?>
        <div class="glass rounded-2xl overflow-hidden group text-center">
          <div class="relative overflow-hidden" style="height:220px;">
            <img src="<?= asset('images/' . ($imgs[$ti%3])) ?>" alt="<?= e($member['name']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
          </div>
          <div class="p-5">
            <h3 class="font-serif font-semibold text-white text-base mb-0.5"><?= e($member['name']) ?></h3>
            <p class="text-gold text-xs font-medium"><?= e($member['specialization'] ?? 'Beauty Specialist') ?></p>
          </div>
        </div>
      <?php $ti++; endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ── PROMOTIONS ────────────────────────────────────────── -->
<?php if (!empty($promotions)): ?>
<section class="py-20 px-6">
  <div class="max-w-7xl mx-auto">
    <div class="text-center mb-12">
      <span class="section-label mb-3 block">Special Offers</span>
      <h2 class="section-title text-white mb-3">Current Promotions</h2>
      <div class="section-divider"></div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
      <?php foreach ($promotions as $promo): ?>
        <div class="glass rounded-2xl p-6 glass-hover transition-all relative overflow-hidden">
          <div class="absolute top-0 right-0 w-32 h-32 rounded-full opacity-10" style="background:var(--gold);transform:translate(30%,-30%);"></div>
          <div class="inline-flex items-center gap-2 glass rounded-full px-3 py-1.5 mb-4">
            <i class="fa-solid fa-tag text-gold text-xs"></i>
            <span class="text-gold font-bold text-sm"><?= number_format((float)$promo['discount_percent'],0) ?>% OFF</span>
          </div>
          <h3 class="font-serif font-semibold text-white text-xl mb-2"><?= e($promo['title']) ?></h3>
          <p class="text-white/55 text-sm leading-relaxed mb-4"><?= e($promo['description']) ?></p>
          <?php if ($promo['end_date']): ?>
            <p class="text-gold/60 text-xs flex items-center gap-1.5">
              <i class="fa-regular fa-clock"></i> Valid until <?= fmtDate($promo['end_date']) ?>
            </p>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ── GALLERY ───────────────────────────────────────────── -->
<section class="py-24 px-6">
  <div class="max-w-7xl mx-auto">
    <div class="text-center mb-12">
      <span class="section-label mb-3 block">Our Work</span>
      <h2 class="section-title text-white mb-3">Gallery</h2>
      <div class="section-divider"></div>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
      <?php for ($i=1;$i<=6;$i++): ?>
        <div class="rounded-xl overflow-hidden group <?= $i<=2 ? 'lg:col-span-2 row-span-2' : '' ?>" style="<?= $i<=2 ? 'height:320px;' : 'height:150px;' ?>">
          <img src="<?= asset('images/gallery-'.$i.'.jpg') ?>" alt="Gallery <?= $i ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
        </div>
      <?php endfor; ?>
    </div>
  </div>
</section>

<!-- ── BOOK CTA ──────────────────────────────────────────── -->
<section class="py-24 px-6">
  <div class="max-w-5xl mx-auto">
    <div class="glass rounded-3xl p-10 lg:p-16 text-center relative overflow-hidden">
      <div class="absolute inset-0 opacity-15" style="background:radial-gradient(circle at 30% 50%, #c9a84c, transparent 60%), radial-gradient(circle at 70% 50%, #1a0a2e, transparent 60%)"></div>
      <div class="relative z-10">
        <i class="fa-regular fa-calendar-star text-4xl text-gold mb-6 block"></i>
        <h2 class="font-serif text-4xl md:text-5xl font-bold text-white mb-5">Ready for Your<br><em class="text-gold-light not-italic">Transformation?</em></h2>
        <p class="text-white/60 text-base max-w-xl mx-auto mb-10">
          Book your appointment today and let our expert team craft your perfect look. Walk in as you are, leave as your best self.
        </p>
        <a href="<?= url('book') ?>" class="btn-gold px-10 py-4 rounded-full text-base font-semibold shadow-2xl inline-flex items-center gap-3">
          <i class="fa-regular fa-calendar-plus"></i> Book Your Appointment
        </a>
        <p class="text-white/35 text-xs mt-4">No account needed · Instant confirmation</p>
      </div>
    </div>
  </div>
</section>
