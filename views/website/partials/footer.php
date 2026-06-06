<footer class="mt-20 border-t border-gold/20">
  <div class="glass-dark">
    <div class="max-w-7xl mx-auto px-6 py-14 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">

      <!-- Brand -->
      <div class="lg:col-span-1">
        <div class="flex items-center gap-2.5 mb-4">
          <div class="w-9 h-9 rounded-xl gold-gradient flex items-center justify-center">
            <i class="fa-solid fa-scissors text-slate-900 text-sm"></i>
          </div>
          <span class="font-serif font-bold text-lg">
            <span class="text-gold-light">Matilda's</span>
            <span class="text-white/70 text-sm font-normal ml-1">Salon & Spa</span>
          </span>
        </div>
        <p class="text-white/50 text-sm leading-relaxed mb-4">
          Your premier destination for luxury hair, nail, and beauty services in Namasuba, Kampala. Where beauty meets excellence.
        </p>
        <div class="flex gap-3">
          <a href="#" class="w-9 h-9 rounded-full glass flex items-center justify-center text-gold/70 hover:text-gold hover:bg-gold/10 transition-all text-sm"><i class="fa-brands fa-facebook-f"></i></a>
          <a href="#" class="w-9 h-9 rounded-full glass flex items-center justify-center text-gold/70 hover:text-gold hover:bg-gold/10 transition-all text-sm"><i class="fa-brands fa-instagram"></i></a>
          <a href="#" class="w-9 h-9 rounded-full glass flex items-center justify-center text-gold/70 hover:text-gold hover:bg-gold/10 transition-all text-sm"><i class="fa-brands fa-x-twitter"></i></a>
          <a href="#" class="w-9 h-9 rounded-full glass flex items-center justify-center text-gold/70 hover:text-gold hover:bg-gold/10 transition-all text-sm"><i class="fa-brands fa-whatsapp"></i></a>
        </div>
      </div>

      <!-- Quick Links -->
      <div>
        <h4 class="font-serif font-semibold text-gold-light mb-4 text-base">Quick Links</h4>
        <ul class="space-y-2.5">
          <?php foreach ([['/',        'Home'],['about','About Us'],['services','Our Services'],['faq','FAQs'],['contact','Contact'],['book','Book Appointment']] as [$href,$label]): ?>
            <li>
              <a href="<?= url($href) ?>" class="text-white/55 hover:text-gold text-sm transition-colors flex items-center gap-2">
                <i class="fa-solid fa-chevron-right text-[10px] text-gold/50"></i><?= $label ?>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>

      <!-- Services -->
      <div>
        <h4 class="font-serif font-semibold text-gold-light mb-4 text-base">Our Services</h4>
        <ul class="space-y-2.5">
          <?php foreach (['Hair Styling','Hair Coloring','Braiding','Manicure & Pedicure','Facial Treatment','Makeup'] as $s): ?>
            <li class="text-white/55 text-sm flex items-center gap-2">
              <i class="fa-solid fa-chevron-right text-[10px] text-gold/50"></i><?= $s ?>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>

      <!-- Contact -->
      <div>
        <h4 class="font-serif font-semibold text-gold-light mb-4 text-base">Contact Info</h4>
        <ul class="space-y-3.5 text-sm text-white/60">
          <li class="flex items-start gap-3">
            <i class="fa-solid fa-location-dot text-gold mt-0.5 flex-shrink-0"></i>
            <span>Freedom City Mall, Namasuba<br>Wakiso District, Uganda</span>
          </li>
          <li class="flex items-center gap-3">
            <i class="fa-solid fa-phone text-gold flex-shrink-0"></i>
            <a href="tel:+256700000000" class="hover:text-gold transition-colors">+256 700 000 000</a>
          </li>
          <li class="flex items-center gap-3">
            <i class="fa-regular fa-envelope text-gold flex-shrink-0"></i>
            <a href="mailto:info@matildassalon.com" class="hover:text-gold transition-colors">info@matildassalon.com</a>
          </li>
          <li class="flex items-start gap-3">
            <i class="fa-regular fa-clock text-gold mt-0.5 flex-shrink-0"></i>
            <span>Mon–Sat: 9:00 AM – 7:00 PM<br>Sunday: 10:00 AM – 5:00 PM</span>
          </li>
        </ul>
      </div>
    </div>

    <!-- Bottom bar -->
    <div class="border-t border-white/8 px-6 py-4">
      <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-white/35">
        <span>&copy; <?= date('Y') ?> <?= SALON_NAME ?>. All rights reserved.</span>
        <span>Designed with <i class="fa-solid fa-heart text-gold/60 mx-0.5"></i> for beauty excellence</span>
      </div>
    </div>
  </div>
</footer>
