<?php
$path     = trim(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH), '/');
$segments = array_values(array_filter(explode('/', $path)));
// segments: ['dashboard', 'appointments', '5', 'edit']

// No breadcrumb on the dashboard home itself
if (count($segments) <= 1) return;

$sectionMeta = [
    'appointments' => ['Appointments', 'fa-calendar-check'],
    'services'     => ['Services',     'fa-sparkles'],
    'staff'        => ['Staff',        'fa-user-tie'],
    'inventory'    => ['Inventory',    'fa-box-seam'],
    'payments'     => ['Payments',     'fa-credit-card'],
    'clients'      => ['Clients',      'fa-users'],
    'faqs'         => ['FAQs',         'fa-circle-question'],
    'promotions'   => ['Promotions',   'fa-tag'],
    'messages'     => ['Messages',     'fa-envelope'],
];

$section = $segments[1] ?? '';
[$sectionLabel, $sectionIcon] = $sectionMeta[$section] ?? [ucfirst($section), 'fa-folder'];

// Build crumb trail
$crumbs = [
    ['label' => 'Dashboard', 'url' => url('dashboard'), 'icon' => 'fa-gauge-simple-high'],
];

if (count($segments) === 2) {
    // e.g. /dashboard/appointments — section index is current page
    $crumbs[] = ['label' => $sectionLabel, 'url' => null, 'icon' => $sectionIcon];
} else {
    // e.g. /dashboard/appointments/create  or  /dashboard/appointments/5/edit
    $crumbs[] = ['label' => $sectionLabel, 'url' => url('dashboard/' . $section), 'icon' => $sectionIcon];
    $crumbs[] = ['label' => $title ?? 'Details', 'url' => null, 'icon' => null];
}
?>
<nav aria-label="Breadcrumb" class="mb-4">
  <ol class="flex items-center flex-wrap gap-x-1.5 gap-y-1">
    <?php foreach ($crumbs as $i => $crumb): ?>
      <?php if ($i > 0): ?>
        <li aria-hidden="true" class="text-white/20 select-none">
          <i class="fa-solid fa-chevron-right" style="font-size:.6rem;"></i>
        </li>
      <?php endif; ?>
      <li>
        <?php if ($crumb['url']): ?>
          <a href="<?= $crumb['url'] ?>"
             class="inline-flex items-center gap-1.5 text-xs text-white/40 hover:text-gold transition-colors">
            <?php if ($crumb['icon']): ?>
              <i class="fa-solid <?= $crumb['icon'] ?>" style="font-size:.65rem;"></i>
            <?php endif; ?>
            <?= e($crumb['label']) ?>
          </a>
        <?php else: ?>
          <span class="inline-flex items-center gap-1.5 text-xs text-white/80 font-medium">
            <?php if ($crumb['icon']): ?>
              <i class="fa-solid <?= $crumb['icon'] ?> text-gold" style="font-size:.65rem;"></i>
            <?php endif; ?>
            <?= e($crumb['label']) ?>
          </span>
        <?php endif; ?>
      </li>
    <?php endforeach; ?>
  </ol>
</nav>
