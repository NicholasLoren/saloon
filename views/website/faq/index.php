<!-- Hero -->
<section class="pt-36 pb-16 px-6">
  <div class="max-w-7xl mx-auto text-center">
    <span class="section-label mb-3 block">Help Center</span>
    <h1 class="section-title text-white mb-4">Frequently Asked <em class="text-gold-light not-italic">Questions</em></h1>
    <div class="section-divider"></div>
    <p class="text-white/50 text-sm mt-4 max-w-lg mx-auto">Everything you need to know about our salon. Can't find what you're looking for? <a href="<?= url('contact') ?>" class="text-gold hover:text-gold-light">Contact us</a>.</p>
  </div>
</section>

<section class="py-10 pb-24 px-6">
  <div class="max-w-3xl mx-auto">
    <?php if (empty($grouped)): ?>
      <div class="glass rounded-2xl p-12 text-center">
        <i class="fa-regular fa-circle-question text-4xl text-gold/40 mb-4 block"></i>
        <p class="text-white/50">No FAQs available yet. Check back soon!</p>
      </div>
    <?php else: ?>
      <?php foreach ($grouped as $category => $faqs): ?>
        <div class="mb-8">
          <h2 class="font-serif text-xl font-semibold text-gold-light mb-4 flex items-center gap-3">
            <span class="w-8 h-px bg-gold/40"></span><?= e($category) ?><span class="flex-1 h-px bg-gold/20"></span>
          </h2>
          <div id="accordion-<?= preg_replace('/\W/','_', $category) ?>" data-accordion="collapse" class="space-y-3">
            <?php foreach ($faqs as $i => $faq): ?>
              <div class="glass rounded-2xl overflow-hidden">
                <h3>
                  <button type="button"
                    class="flex items-center justify-between w-full p-5 text-left"
                    data-accordion-target="#faq-body-<?= $faq['id'] ?>"
                    aria-expanded="false"
                    aria-controls="faq-body-<?= $faq['id'] ?>">
                    <span class="font-medium text-white/90 text-sm pr-4"><?= e($faq['question']) ?></span>
                    <i class="fa-solid fa-chevron-down text-gold/60 text-xs flex-shrink-0 transition-transform duration-200"></i>
                  </button>
                </h3>
                <div id="faq-body-<?= $faq['id'] ?>" class="hidden">
                  <div class="px-5 pb-5 text-white/60 text-sm leading-relaxed border-t border-white/8 pt-4">
                    <?= nl2br(e($faq['answer'])) ?>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>

    <!-- Still have questions -->
    <div class="glass rounded-2xl p-8 text-center mt-10">
      <i class="fa-regular fa-comment-question text-3xl text-gold mb-4 block"></i>
      <h3 class="font-serif text-xl font-semibold text-white mb-2">Still have questions?</h3>
      <p class="text-white/50 text-sm mb-5">Our team is happy to help. Reach out to us directly.</p>
      <a href="<?= url('contact') ?>" class="btn-gold px-8 py-3 rounded-full text-sm font-semibold inline-flex items-center gap-2">
        <i class="fa-regular fa-envelope"></i> Contact Us
      </a>
    </div>
  </div>
</section>
