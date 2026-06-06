<?php $success = isset($_GET['success']); ?>
<!-- Hero -->
<section class="pt-36 pb-12 px-6 text-center">
  <span class="section-label mb-3 block">Online Booking</span>
  <h1 class="section-title text-white mb-4">Book Your <em class="text-gold-light not-italic">Appointment</em></h1>
  <div class="section-divider"></div>
  <p class="text-white/50 text-sm mt-4 max-w-lg mx-auto">Fill in the form below and we will confirm your appointment as soon as possible.</p>
</section>

<section class="pb-24 px-6">
  <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

    <!-- Info panel -->
    <div class="space-y-5">
      <div class="glass rounded-2xl p-6">
        <h3 class="font-serif font-semibold text-gold-light text-lg mb-4">How It Works</h3>
        <ol class="space-y-4">
          <?php foreach (['Choose your service','Select date & time','Enter your details','Submit & wait for confirmation'] as $i=>$step): ?>
            <li class="flex items-start gap-3">
              <span class="w-7 h-7 gold-gradient rounded-full flex items-center justify-center text-slate-900 text-xs font-bold flex-shrink-0 mt-0.5"><?= $i+1 ?></span>
              <span class="text-white/65 text-sm"><?= $step ?></span>
            </li>
          <?php endforeach; ?>
        </ol>
      </div>

      <div class="glass rounded-2xl p-6">
        <h3 class="font-serif font-semibold text-gold-light text-base mb-4">Why Book With Us</h3>
        <ul class="space-y-3">
          <?php foreach (['No hidden charges','Experienced professionals','Flexible scheduling','Easy cancellation'] as $item): ?>
            <li class="flex items-center gap-3 text-sm text-white/60">
              <i class="fa-solid fa-check-circle text-gold"></i><?= $item ?>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>

      <div class="glass rounded-2xl p-6">
        <h3 class="font-serif font-semibold text-gold-light text-base mb-3">Need Help?</h3>
        <p class="text-white/50 text-sm mb-4">Call us directly for assistance with your booking.</p>
        <a href="tel:+256700000000" class="flex items-center gap-3 text-white/70 hover:text-gold text-sm transition-colors">
          <i class="fa-solid fa-phone text-gold"></i>+256 700 000 000
        </a>
      </div>
    </div>

    <!-- Booking form -->
    <div class="lg:col-span-2">
      <?php if ($success): ?>
        <div class="glass rounded-2xl p-12 text-center">
          <div class="w-16 h-16 gold-gradient rounded-full flex items-center justify-center mx-auto mb-5 text-slate-900 text-2xl">
            <i class="fa-solid fa-calendar-check"></i>
          </div>
          <h2 class="font-serif text-2xl font-bold text-white mb-3">Appointment Requested!</h2>
          <p class="text-white/60 text-sm max-w-md mx-auto mb-6">
            Thank you! Your appointment request has been received. Our team will confirm it shortly. Please check your email or phone for updates.
          </p>
          <div class="flex flex-wrap gap-3 justify-center">
            <a href="<?= url('book') ?>" class="btn-gold px-6 py-2.5 rounded-full text-sm font-semibold">Book Another</a>
            <a href="<?= url('/') ?>" class="glass px-6 py-2.5 rounded-full text-sm text-white/70 hover:text-gold transition-colors">Back to Home</a>
          </div>
        </div>
      <?php else: ?>
        <div class="glass rounded-2xl p-8">
          <h2 class="font-serif text-xl font-bold text-white mb-6">Fill Your Details</h2>

          <?php if (!empty($errors)): ?>
            <div class="alert alert-error mb-6">
              <i class="fa-solid fa-triangle-exclamation flex-shrink-0 mt-0.5"></i>
              <div><strong class="block mb-1">Please fix the errors below:</strong>
              <ul class="text-xs space-y-0.5"><?php foreach ($errors as $err): ?><li>&bull; <?= e($err) ?></li><?php endforeach; ?></ul></div>
            </div>
          <?php endif; ?>

          <form method="POST" action="<?= url('book') ?>" novalidate>

            <!-- Service & Staff -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
              <div>
                <label class="form-label">Service *</label>
                <select name="service_id" id="booking-service" class="form-select <?= isset($errors['service_id']) ? 'error' : '' ?>" required>
                  <option value="">Select a service</option>
                  <?php foreach ($services as $svc): ?>
                    <option value="<?= $svc['id'] ?>" <?= ($old['service_id']??'')==$svc['id']?'selected':'' ?> data-price="<?= $svc['price'] ?>">
                      <?= e($svc['name']) ?> — <?= money((float)$svc['price']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <?php if (isset($errors['service_id'])): ?><div class="form-error"><i class="fa-solid fa-circle-exclamation text-xs"></i><?= e($errors['service_id']) ?></div><?php endif; ?>
              </div>
              <div>
                <label class="form-label">Preferred Stylist <span class="text-white/35">(optional)</span></label>
                <select name="staff_id" id="booking-staff" class="form-select">
                  <option value="">Any available</option>
                  <?php foreach ($staff as $s): ?>
                    <option value="<?= $s['id'] ?>" <?= ($old['staff_id']??'')==$s['id']?'selected':'' ?>><?= e($s['name']) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <!-- Date & Time -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
              <div>
                <label class="form-label">Date *</label>
                <input type="date" name="appointment_date" id="booking-date" value="<?= e($old['appointment_date'] ?? '') ?>" class="form-input <?= isset($errors['appointment_date']) ? 'error' : '' ?>" required>
                <?php if (isset($errors['appointment_date'])): ?><div class="form-error"><i class="fa-solid fa-circle-exclamation text-xs"></i><?= e($errors['appointment_date']) ?></div><?php endif; ?>
              </div>
              <div>
                <label class="form-label">Time *</label>
                <select name="appointment_time" id="booking-time" class="form-select <?= isset($errors['appointment_time']) ? 'error' : '' ?>" required>
                  <option value="">Select date & service first</option>
                  <?php if (!empty($old['appointment_time'])): ?>
                    <option value="<?= e($old['appointment_time']) ?>" selected><?= fmtTime($old['appointment_time']) ?></option>
                  <?php endif; ?>
                </select>
                <?php if (isset($errors['appointment_time'])): ?><div class="form-error"><i class="fa-solid fa-circle-exclamation text-xs"></i><?= e($errors['appointment_time']) ?></div><?php endif; ?>
              </div>
            </div>

            <!-- Client info -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
              <div>
                <label class="form-label">Your Name *</label>
                <input type="text" name="client_name" value="<?= e($old['client_name'] ?? '') ?>" class="form-input <?= isset($errors['client_name']) ? 'error' : '' ?>" placeholder="Full name" required>
                <?php if (isset($errors['client_name'])): ?><div class="form-error"><i class="fa-solid fa-circle-exclamation text-xs"></i><?= e($errors['client_name']) ?></div><?php endif; ?>
              </div>
              <div>
                <label class="form-label">Phone Number *</label>
                <input type="tel" name="client_phone" value="<?= e($old['client_phone'] ?? '') ?>" class="form-input <?= isset($errors['client_phone']) ? 'error' : '' ?>" placeholder="+256 7xx xxx xxx" required>
                <?php if (isset($errors['client_phone'])): ?><div class="form-error"><i class="fa-solid fa-circle-exclamation text-xs"></i><?= e($errors['client_phone']) ?></div><?php endif; ?>
              </div>
            </div>

            <div class="mb-5">
              <label class="form-label">Email Address *</label>
              <input type="email" name="client_email" value="<?= e($old['client_email'] ?? '') ?>" class="form-input <?= isset($errors['client_email']) ? 'error' : '' ?>" placeholder="your@email.com" required>
              <?php if (isset($errors['client_email'])): ?><div class="form-error"><i class="fa-solid fa-circle-exclamation text-xs"></i><?= e($errors['client_email']) ?></div><?php endif; ?>
            </div>

            <div class="mb-7">
              <label class="form-label">Additional Notes <span class="text-white/35">(optional)</span></label>
              <textarea name="notes" rows="3" class="form-input" placeholder="Any special requests or information for our team..."><?= e($old['notes'] ?? '') ?></textarea>
            </div>

            <button type="submit" class="btn-gold w-full py-4 rounded-xl text-base font-semibold flex items-center justify-center gap-2.5 shadow-xl">
              <i class="fa-regular fa-calendar-check"></i> Confirm Appointment Request
            </button>
            <p class="text-white/30 text-xs text-center mt-3">We will contact you to confirm within 1–2 hours</p>
          </form>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>
