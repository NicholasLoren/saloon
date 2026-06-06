/* ── Navbar scroll effect ──────────────────────────────────────── */
(function () {
  const nav = document.getElementById('main-nav');
  if (!nav) return;
  const update = () => nav.classList.toggle('scrolled', window.scrollY > 40);
  window.addEventListener('scroll', update, { passive: true });
  update();
})();

/* ── Flash auto-dismiss ────────────────────────────────────────── */
(function () {
  document.querySelectorAll('[data-flash]').forEach(el => {
    setTimeout(() => {
      el.style.transition = 'opacity .4s';
      el.style.opacity = '0';
      setTimeout(() => el.remove(), 400);
    }, 4500);
  });
})();

/* ── Sidebar toggle (mobile) ───────────────────────────────────── */
(function () {
  const toggle  = document.getElementById('sidebar-toggle');
  const sidebar = document.getElementById('sidebar');
  const overlay = document.getElementById('sidebar-overlay');
  if (!toggle || !sidebar) return;

  const close = () => { sidebar.classList.remove('open'); overlay?.classList.add('hidden'); };
  toggle.addEventListener('click', () => {
    sidebar.classList.toggle('open');
    overlay?.classList.toggle('hidden');
  });
  overlay?.addEventListener('click', close);
})();

/* ── DataTable live search ─────────────────────────────────────── */
(function () {
  const input = document.getElementById('dt-search');
  if (!input) return;
  let timer;
  input.addEventListener('input', () => {
    clearTimeout(timer);
    timer = setTimeout(() => {
      const url   = new URL(window.location.href);
      const val   = input.value.trim();
      if (val) url.searchParams.set('search', val); else url.searchParams.delete('search');
      url.searchParams.delete('page');
      window.location.href = url.toString();
    }, 450);
  });
})();

/* ── Status filter ─────────────────────────────────────────────── */
(function () {
  const sel = document.getElementById('dt-status');
  if (!sel) return;
  sel.addEventListener('change', () => {
    const url = new URL(window.location.href);
    if (sel.value) url.searchParams.set('status', sel.value); else url.searchParams.delete('status');
    url.searchParams.delete('page');
    window.location.href = url.toString();
  });
})();

/* ── Booking: load available time slots via AJAX ───────────────── */
(function () {
  const serviceEl = document.getElementById('booking-service');
  const staffEl   = document.getElementById('booking-staff');
  const dateEl    = document.getElementById('booking-date');
  const timeEl    = document.getElementById('booking-time');
  if (!serviceEl || !dateEl || !timeEl) return;

  async function loadSlots() {
    const service = serviceEl.value;
    const date    = dateEl.value;
    if (!service || !date) return;

    const staff = staffEl ? staffEl.value : '';
    const url   = `/api/slots?service_id=${service}&date=${date}${staff ? '&staff_id=' + staff : ''}`;

    timeEl.innerHTML = '<option value="">Loading...</option>';
    timeEl.disabled = true;

    try {
      const res  = await fetch(url);
      const data = await res.json();
      timeEl.innerHTML = '<option value="">Select time</option>';
      if (data.slots.length === 0) {
        timeEl.innerHTML = '<option value="">No slots available</option>';
      } else {
        data.slots.forEach(slot => {
          const [h, m]  = slot.split(':');
          const hr      = parseInt(h);
          const ampm    = hr >= 12 ? 'PM' : 'AM';
          const disp    = `${hr > 12 ? hr - 12 : (hr === 0 ? 12 : hr)}:${m} ${ampm}`;
          timeEl.innerHTML += `<option value="${slot}">${disp}</option>`;
        });
      }
    } catch {
      timeEl.innerHTML = '<option value="">Error loading slots</option>';
    } finally {
      timeEl.disabled = false;
    }
  }

  // Set min date to tomorrow
  const tomorrow = new Date();
  tomorrow.setDate(tomorrow.getDate() + 1);
  dateEl.min = tomorrow.toISOString().split('T')[0];

  serviceEl.addEventListener('change', loadSlots);
  dateEl.addEventListener('change', loadSlots);
  if (staffEl) staffEl.addEventListener('change', loadSlots);
})();

/* ── Image preview ─────────────────────────────────────────────── */
(function () {
  document.querySelectorAll('input[type=file][data-preview]').forEach(input => {
    const preview = document.getElementById(input.dataset.preview);
    if (!preview) return;
    input.addEventListener('change', () => {
      const file = input.files[0];
      if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = e => { preview.src = e.target.result; preview.classList.remove('hidden'); };
        reader.readAsDataURL(file);
      }
    });
  });
})();

/* ── Confirm delete ────────────────────────────────────────────── */
document.querySelectorAll('[data-confirm]').forEach(el => {
  el.addEventListener('click', e => {
    if (!confirm(el.dataset.confirm || 'Are you sure?')) e.preventDefault();
  });
});

/* ── Dismiss alerts ────────────────────────────────────────────── */
document.querySelectorAll('[data-dismiss]').forEach(el => {
  el.addEventListener('click', () => {
    const target = document.getElementById(el.dataset.dismiss);
    if (target) { target.style.opacity='0'; setTimeout(()=>target.remove(),300); }
  });
});
