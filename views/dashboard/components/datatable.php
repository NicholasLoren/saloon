<?php
/**
 * Reusable DataTable Component
 *
 * Required variables:
 *   $columns  — array of ['key'=>'', 'label'=>'', 'sortable'=>true, 'render'=>fn($row)=>string (optional)]
 *   $rows     — array of row data
 *   $total    — total record count
 *   $curPage  — current page number
 *   $perPage  — items per page
 *   $search   — current search string
 *   $sort     — current sort column
 *   $dir      — current sort direction ('asc'|'desc')
 *   $baseUrl  — base URL for this table (e.g. '/dashboard/appointments')
 *   $actions  — optional callable($row) returning action HTML
 *   $filters  — optional additional filter HTML string (e.g. status select)
 */

$sort       = $sort ?? '';
$dir        = $dir  ?? 'asc';
$filters    = $filters ?? '';
$totalPages = $total > 0 ? (int)ceil($total / $perPage) : 1;
$from       = $total > 0 ? (($curPage-1)*$perPage)+1 : 0;
$to         = min($curPage*$perPage, $total);
$qp         = array_filter(['sort'=>$sort,'dir'=>$dir,'search'=>$search], fn($v)=>$v!=='');
?>

<!-- Search & filter bar -->
<div class="flex flex-wrap gap-3 items-center justify-between mb-4">
  <div class="flex flex-1 flex-wrap gap-3 items-center min-w-0">
    <!-- Search -->
    <div class="relative flex-1 min-w-[200px] max-w-xs">
      <span class="absolute left-3 top-1/2 -translate-y-1/2 text-white/30 text-xs pointer-events-none">
        <i class="fa-solid fa-magnifying-glass"></i>
      </span>
      <input type="text" id="dt-search" value="<?= e($search) ?>"
        class="form-input pl-8 py-2 text-sm"
        placeholder="Search...">
    </div>
    <!-- Extra filters slot -->
    <?php if (!empty($filters)) echo $filters; ?>
  </div>
  <div class="text-white/35 text-xs whitespace-nowrap">
    <?= $from ?>–<?= $to ?> of <?= number_format($total) ?>
  </div>
</div>

<!-- Table -->
<div class="glass rounded-2xl overflow-hidden">
  <div class="overflow-x-auto">
    <table class="data-table">
      <thead>
        <tr>
          <?php foreach ($columns as $col): ?>
            <th>
              <?php if ($col['sortable'] ?? false): ?>
                <?php
                $newDir = ($sort === $col['key'] && $dir === 'asc') ? 'desc' : 'asc';
                $qs     = http_build_query(array_merge($qp, ['sort'=>$col['key'],'dir'=>$newDir,'page'=>1]));
                ?>
                <a href="<?= $baseUrl ?>?<?= $qs ?>" class="flex items-center gap-1 hover:text-gold-light transition-colors">
                  <?= e($col['label']) ?>
                  <?= sortIcon($col['key'], $sort, $dir) ?>
                </a>
              <?php else: ?>
                <?= e($col['label']) ?>
              <?php endif; ?>
            </th>
          <?php endforeach; ?>
          <?php if (!empty($actions)): ?><th class="text-right">Actions</th><?php endif; ?>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($rows)): ?>
          <tr>
            <td colspan="<?= count($columns) + (!empty($actions) ? 1 : 0) ?>" class="text-center text-white/35 py-12">
              <i class="fa-regular fa-folder-open text-2xl mb-2 block opacity-40"></i>
              No records found<?= $search ? ' for "'.e($search).'"' : '' ?>.
            </td>
          </tr>
        <?php else: foreach ($rows as $row): ?>
          <tr>
            <?php foreach ($columns as $col): ?>
              <td>
                <?php if (isset($col['render']) && is_callable($col['render'])): ?>
                  <?= ($col['render'])($row) ?>
                <?php else: ?>
                  <?= e((string)($row[$col['key']] ?? '—')) ?>
                <?php endif; ?>
              </td>
            <?php endforeach; ?>
            <?php if (!empty($actions)): ?>
              <td class="text-right whitespace-nowrap">
                <?= $actions($row) ?>
              </td>
            <?php endif; ?>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Pagination -->
<?php if ($totalPages > 1): ?>
  <div class="flex items-center justify-between mt-4 flex-wrap gap-3">
    <div class="text-white/35 text-xs">Page <?= $curPage ?> of <?= $totalPages ?></div>
    <div class="pagination">
      <?php
      $prevQs = http_build_query(array_merge($qp, ['page' => max(1, $curPage-1)]));
      $nextQs = http_build_query(array_merge($qp, ['page' => min($totalPages, $curPage+1)]));
      ?>
      <a href="<?= $baseUrl ?>?<?= $prevQs ?>" class="page-btn <?= $curPage<=1 ? 'disabled' : '' ?>">
        <i class="fa-solid fa-chevron-left text-[10px]"></i>
      </a>

      <?php
      $window = 2;
      $pages  = [];
      if ($curPage > $window+1)  { $pages[] = 1; if ($curPage > $window+2) $pages[] = '...'; }
      for ($p = max(1,$curPage-$window); $p <= min($totalPages,$curPage+$window); $p++) $pages[] = $p;
      if ($curPage < $totalPages-$window) { if ($curPage < $totalPages-$window-1) $pages[] = '...'; $pages[] = $totalPages; }

      foreach ($pages as $p):
        if ($p === '...'):
          echo '<span class="page-btn disabled">…</span>';
        else:
          $qs = http_build_query(array_merge($qp, ['page'=>$p]));
      ?>
          <a href="<?= $baseUrl ?>?<?= $qs ?>" class="page-btn <?= $p==$curPage ? 'active' : '' ?>"><?= $p ?></a>
      <?php endif; endforeach; ?>

      <a href="<?= $baseUrl ?>?<?= $nextQs ?>" class="page-btn <?= $curPage>=$totalPages ? 'disabled' : '' ?>">
        <i class="fa-solid fa-chevron-right text-[10px]"></i>
      </a>
    </div>
  </div>
<?php endif; ?>
