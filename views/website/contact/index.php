<?php $flash = getFlash(); ?>
<!-- Hero -->
<section class="pt-36 pb-16 px-6">
  <div class="max-w-7xl mx-auto text-center">
    <span class="section-label mb-3 block">Get In Touch</span>
    <h1 class="section-title text-white mb-4">Contact <em class="text-gold-light not-italic">Us</em></h1>
    <div class="section-divider"></div>
  </div>
</section>

<?php if ($flash && $flash['type'] === 'success'): ?>
  <div class="max-w-2xl mx-auto px-6 mb-6">
    <div class="alert alert-success"><i class="fa-solid fa-circle-check"></i><?= e($flash['msg']) ?></div>
  </div>
<?php endif; ?>

<section class="py-10 pb-24 px-6">
  <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-5 gap-8">

    <!-- Info -->
    <div class="lg:col-span-2 space-y-5">
      <?php foreach ([
        ['fa-location-dot','Visit Us','Freedom City Mall, Namasuba, Wakiso District, Uganda'],
        ['fa-phone','Call Us','+256 700 000 000 &nbsp;·&nbsp; +256 711 000 000'],
        ['fa-envelope','Email Us','info@matildassalon.com'],
        ['fa-clock','Opening Hours','Mon–Sat: 9:00 AM – 7:00 PM<br>Sunday: 10:00 AM – 5:00 PM'],
      ] as [$icon,$label,$val]): ?>
        <div class="glass rounded-2xl p-6 flex items-start gap-5">
          <div class="w-11 h-11 gold-gradient rounded-xl flex items-center justify-center text-slate-900 flex-shrink-0">
            <i class="fa-solid <?= $icon ?>"></i>
          </div>
          <div>
            <div class="text-white/40 text-xs font-medium tracking-widest uppercase mb-1"><?= $label ?></div>
            <div class="text-white/80 text-sm leading-relaxed"><?= $val ?></div>
          </div>
        </div>
      <?php endforeach; ?>

      <!-- Social -->
      <div class="glass rounded-2xl p-6">
        <div class="text-white/40 text-xs font-medium tracking-widest uppercase mb-3">Follow Us</div>
        <div class="flex gap-3">
          <?php foreach ([['fa-facebook-f','#1877F2'],['fa-instagram','#E1306C'],['fa-x-twitter','#000'],['fa-whatsapp','#25D366']] as [$ico,$bg]): ?>
            <a href="#" class="w-10 h-10 rounded-full flex items-center justify-center text-white text-sm transition-all hover:scale-110" style="background:<?= $bg ?>">
              <i class="fa-brands <?= $ico ?>"></i>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- Form -->
    <div class="lg:col-span-3">
      <div class="glass rounded-2xl p-8">
        <h2 class="font-serif text-2xl font-bold text-white mb-6">Send Us a Message</h2>
        <form method="POST" action="<?= url('contact') ?>" novalidate>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
            <div>
              <label class="form-label">Your Name *</label>
              <input type="text" name="name" value="<?= e($old['name'] ?? '') ?>" class="form-input <?= isset($errors['name']) ? 'error' : '' ?>" placeholder="e.g. Jane Okello" required>
              <?php if (isset($errors['name'])): ?><div class="form-error"><i class="fa-solid fa-circle-exclamation text-xs"></i><?= e($errors['name']) ?></div><?php endif; ?>
            </div>
            <div>
              <label class="form-label">Email Address *</label>
              <input type="email" name="email" value="<?= e($old['email'] ?? '') ?>" class="form-input <?= isset($errors['email']) ? 'error' : '' ?>" placeholder="you@example.com" required>
              <?php if (isset($errors['email'])): ?><div class="form-error"><i class="fa-solid fa-circle-exclamation text-xs"></i><?= e($errors['email']) ?></div><?php endif; ?>
            </div>
          </div>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
            <div>
              <label class="form-label">Phone Number</label>
              <input type="tel" name="phone" value="<?= e($old['phone'] ?? '') ?>" class="form-input" placeholder="+256 7xx xxx xxx">
            </div>
            <div>
              <label class="form-label">Subject</label>
              <select name="subject" class="form-select">
                <option value="">Select subject</option>
                <?php foreach (['General Inquiry','Book Appointment','Complaint','Partnership','Other'] as $s): ?>
                  <option value="<?= $s ?>" <?= ($old['subject']??'')===$s?'selected':'' ?>><?= $s ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="mb-6">
            <label class="form-label">Message *</label>
            <textarea name="message" rows="5" class="form-input <?= isset($errors['message']) ? 'error' : '' ?>" placeholder="Tell us how we can help you..."><?= e($old['message'] ?? '') ?></textarea>
            <?php if (isset($errors['message'])): ?><div class="form-error"><i class="fa-solid fa-circle-exclamation text-xs"></i><?= e($errors['message']) ?></div><?php endif; ?>
          </div>
          <button type="submit" class="btn-gold w-full py-3.5 rounded-xl font-semibold text-sm flex items-center justify-center gap-2">
            <i class="fa-solid fa-paper-plane"></i> Send Message
          </button>
        </form>
      </div>
    </div>
  </div>
</section>
