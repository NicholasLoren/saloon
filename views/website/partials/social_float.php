<!-- Floating Social Button -->
<div id="social-float"
     style="position:fixed;bottom:2rem;right:2rem;z-index:9999;display:flex;flex-direction:column;align-items:center;gap:.6rem;">

  <!-- Social links — sit above the toggle button, hidden by default -->
  <div id="social-links-list" style="display:flex;flex-direction:column;align-items:center;gap:.55rem;">
    <a href="#" class="social-link" style="background:#000;" title="X (Twitter)" aria-label="X Twitter">
      <i class="fa-brands fa-x-twitter"></i>
    </a>
    <a href="#" class="social-link" style="background:#1877F2;" title="Facebook" aria-label="Facebook">
      <i class="fa-brands fa-facebook-f"></i>
    </a>
    <a href="#" class="social-link" style="background:linear-gradient(135deg,#f09433,#e6683c,#dc2743,#cc2366,#bc1888);" title="Instagram" aria-label="Instagram">
      <i class="fa-brands fa-instagram"></i>
    </a>
    <a href="#" class="social-link" style="background:#25D366;" title="WhatsApp" aria-label="WhatsApp">
      <i class="fa-brands fa-whatsapp"></i>
    </a>
  </div>

  <!-- Toggle button -->
  <button id="social-btn" aria-label="Toggle social links" aria-expanded="false"
          style="width:3.25rem;height:3.25rem;border-radius:50%;background:linear-gradient(135deg,#a8893c,#e8c97a);border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:1.3rem;color:#0d0d1a;box-shadow:0 4px 20px rgba(201,168,76,.4),0 2px 8px rgba(0,0,0,.4);transition:transform .25s,box-shadow .25s;flex-shrink:0;">
    <i class="fa-solid fa-comments"></i>
  </button>
</div>

<style>
.social-link {
  width: 2.75rem; height: 2.75rem; border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-size: 1.05rem; color: #fff;
  box-shadow: 0 4px 16px rgba(0,0,0,.45);
  text-decoration: none;
  opacity: 0;
  transform: translateY(10px) scale(.8);
  transition: opacity .2s, transform .2s, box-shadow .2s;
  pointer-events: none;
}
.social-link.show {
  opacity: 1;
  transform: translateY(0) scale(1);
  pointer-events: auto;
}
.social-link:hover {
  transform: translateY(-3px) scale(1.1) !important;
  box-shadow: 0 6px 20px rgba(0,0,0,.5);
}
</style>

<script>
(function () {
  var btn   = document.getElementById('social-btn');
  var links = document.querySelectorAll('#social-links-list .social-link');
  if (!btn) return;

  var open = false;

  btn.addEventListener('click', function () {
    open = !open;
    btn.setAttribute('aria-expanded', open);
    btn.style.transform = open ? 'scale(1.08) rotate(135deg)' : 'scale(1) rotate(0deg)';
    btn.innerHTML = open
      ? '<i class="fa-solid fa-xmark"></i>'
      : '<i class="fa-solid fa-comments"></i>';

    links.forEach(function (el, i) {
      clearTimeout(el._t);
      if (open) {
        // Stagger upward: WhatsApp first (bottom), X last (top)
        el._t = setTimeout(function () { el.classList.add('show'); }, i * 60);
      } else {
        el._t = setTimeout(function () { el.classList.remove('show'); }, (links.length - 1 - i) * 40);
      }
    });
  });
})();
</script>
