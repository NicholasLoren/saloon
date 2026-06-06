<!-- Page Hero -->
<section class="pt-36 pb-20 px-6 relative overflow-hidden">
  <div class="absolute inset-0 opacity-20" style="background:radial-gradient(circle at 20% 50%, #1a0a2e, transparent 60%)"></div>
  <div class="max-w-7xl mx-auto relative">
    <span class="section-label mb-3 block text-center">Our Story</span>
    <h1 class="section-title text-white text-center mb-4">About <em class="text-gold-light not-italic">Matilda's Salon</em></h1>
    <div class="section-divider"></div>
    <p class="text-white/55 text-center text-sm mt-4 max-w-xl mx-auto">Where passion for beauty meets professional excellence.</p>
  </div>
</section>

<!-- Story -->
<section class="py-10 px-6">
  <div class="max-w-7xl mx-auto glass rounded-3xl overflow-hidden">
    <div class="grid grid-cols-1 lg:grid-cols-2">
      <div class="relative" style="min-height:480px;">
        <img src="<?= asset('images/about.jpg') ?>" alt="Our Salon" class="w-full h-full object-cover absolute inset-0">
        <div class="absolute inset-0 bg-gradient-to-r from-transparent to-black/30"></div>
      </div>
      <div class="p-10 lg:p-14">
        <h2 class="font-serif text-3xl font-bold text-white mb-6">A Sanctuary of<br><span class="text-gold-light">Beauty & Wellness</span></h2>
        <div class="space-y-4 text-white/60 text-sm leading-relaxed">
          <p>Matilda's Salon &amp; Spa was born from a deep passion for beauty and a commitment to exceptional client experiences. Founded in the heart of Namasuba at Freedom City Mall, we set out to create a space where every visitor feels celebrated and transformed.</p>
          <p>Our team of highly trained stylists, nail technicians, and beauty therapists bring together years of expertise, ongoing education, and an eye for the latest trends. We blend global techniques with an understanding of diverse hair and skin types unique to our clientele.</p>
          <p>From our carefully curated product selection to our warm and welcoming atmosphere, every detail of the Matilda's experience is intentional. We believe beauty services should not just change how you look — they should change how you feel.</p>
        </div>

        <div class="grid grid-cols-2 gap-4 mt-8">
          <?php foreach ([['fa-award','Premium Quality','We use only the finest products'],['fa-users','Expert Team','Certified and experienced professionals'],['fa-shield-heart','Safe & Hygienic','Strict cleanliness standards'],['fa-clock','Flexible Hours','Open 6 days a week']] as [$icon,$title,$desc]): ?>
            <div class="glass rounded-xl p-4">
              <i class="fa-solid <?= $icon ?> text-gold mb-2 text-base block"></i>
              <div class="font-semibold text-white text-xs mb-0.5"><?= $title ?></div>
              <div class="text-white/45 text-xs"><?= $desc ?></div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Values -->
<section class="py-20 px-6">
  <div class="max-w-7xl mx-auto">
    <div class="text-center mb-12">
      <span class="section-label mb-3 block">What We Stand For</span>
      <h2 class="section-title text-white mb-3">Our Values</h2>
      <div class="section-divider"></div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <?php foreach ([
        ['fa-gem','Excellence','We hold ourselves to the highest standards in every treatment, every time. Mediocrity is not in our vocabulary.'],
        ['fa-heart','Care','Every client is treated as a valued guest. Your comfort, needs, and preferences guide everything we do.'],
        ['fa-arrow-trend-up','Innovation','We stay ahead of beauty trends through continuous training and adoption of the latest techniques and products.'],
      ] as [$icon,$title,$desc]): ?>
        <div class="glass rounded-2xl p-8 text-center glass-hover transition-all">
          <div class="w-14 h-14 gold-gradient rounded-2xl flex items-center justify-center mx-auto mb-5 text-slate-900 text-xl">
            <i class="fa-solid <?= $icon ?>"></i>
          </div>
          <h3 class="font-serif text-xl font-semibold text-white mb-3"><?= $title ?></h3>
          <p class="text-white/55 text-sm leading-relaxed"><?= $desc ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Team -->
<?php if (!empty($team)): ?>
<section class="py-20 px-6">
  <div class="max-w-7xl mx-auto">
    <div class="text-center mb-12">
      <span class="section-label mb-3 block">The People Behind the Magic</span>
      <h2 class="section-title text-white mb-3">Our Expert Team</h2>
      <div class="section-divider"></div>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      <?php $imgs = ['team-1.jpg','team-2.jpg','team-3.jpg']; $i=0;
      foreach ($team as $member): ?>
        <div class="glass rounded-2xl overflow-hidden group">
          <div class="relative overflow-hidden" style="height:260px;">
            <img src="<?= asset('images/' . ($imgs[$i%3])) ?>" alt="<?= e($member['name']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
            <div class="absolute bottom-0 left-0 right-0 p-5">
              <h3 class="font-serif font-bold text-white text-base"><?= e($member['name']) ?></h3>
              <p class="text-gold text-xs mt-0.5"><?= e($member['specialization'] ?? 'Beauty Specialist') ?></p>
            </div>
          </div>
          <?php if ($member['bio']): ?>
            <div class="p-5">
              <p class="text-white/55 text-xs leading-relaxed"><?= e(mb_substr($member['bio'],0,100)) ?>...</p>
            </div>
          <?php endif; ?>
        </div>
      <?php $i++; endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- CTA -->
<section class="py-20 px-6">
  <div class="max-w-4xl mx-auto glass rounded-3xl p-10 text-center">
    <h2 class="font-serif text-3xl font-bold text-white mb-4">Experience the Matilda's Difference</h2>
    <p class="text-white/55 text-sm mb-8 max-w-md mx-auto">Come visit us at Freedom City and let us show you what exceptional beauty care truly looks like.</p>
    <a href="<?= url('book') ?>" class="btn-gold px-8 py-3.5 rounded-full font-semibold inline-flex items-center gap-2 text-sm">
      <i class="fa-regular fa-calendar-plus"></i> Book Your Visit
    </a>
  </div>
</section>
