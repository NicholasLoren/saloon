<div class="max-w-2xl">
  <div class="flex items-center gap-3 mb-5">
    <a href="<?= url('dashboard/payments') ?>" class="glass p-2 rounded-xl text-white/50 hover:text-gold transition-colors">
      <i class="fa-solid fa-arrow-left text-sm"></i>
    </a>
    <h2 class="font-serif text-xl font-semibold text-white">Invoice <?= e($payment['invoice_number']) ?></h2>
    <?= statusBadge($payment['status']) ?>
  </div>

  <!-- Invoice card -->
  <div class="glass rounded-2xl overflow-hidden">
    <!-- Header -->
    <div class="p-7 border-b border-white/8" style="background:linear-gradient(135deg,rgba(201,168,76,.06),transparent)">
      <div class="flex items-start justify-between">
        <div>
          <div class="flex items-center gap-2 mb-1">
            <div class="w-7 h-7 gold-gradient rounded-lg flex items-center justify-center text-slate-900 text-xs">
              <i class="fa-solid fa-scissors"></i>
            </div>
            <span class="font-serif font-bold text-gold-light"><?= SALON_NAME ?></span>
          </div>
          <div class="text-white/40 text-xs">Freedom City, Namasuba, Kampala</div>
        </div>
        <div class="text-right">
          <div class="text-white/35 text-xs mb-0.5">Invoice Number</div>
          <div class="font-mono font-bold text-gold text-sm"><?= e($payment['invoice_number']) ?></div>
          <div class="text-white/35 text-xs mt-1"><?= date('d M Y', strtotime($payment['created_at'])) ?></div>
        </div>
      </div>
    </div>

    <!-- Client & Service -->
    <div class="p-7">
      <div class="grid grid-cols-2 gap-5 mb-7">
        <div>
          <div class="text-white/35 text-xs uppercase tracking-wider mb-2">Billed To</div>
          <div class="font-semibold text-white/90"><?= e($payment['client_name']) ?></div>
          <div class="text-white/50 text-xs mt-0.5"><?= e($payment['client_email']) ?></div>
          <div class="text-white/50 text-xs"><?= e($payment['client_phone']??'') ?></div>
        </div>
        <div>
          <div class="text-white/35 text-xs uppercase tracking-wider mb-2">Appointment</div>
          <div class="text-white/70 text-sm"><?= e($payment['service_name']) ?></div>
          <div class="text-white/45 text-xs mt-0.5"><?= fmtDate($payment['appointment_date'] ?? '') ?></div>
          <div class="text-white/45 text-xs"><?= fmtTime($payment['appointment_time'] ?? '') ?></div>
        </div>
      </div>

      <!-- Amount table -->
      <div class="glass rounded-xl overflow-hidden mb-6">
        <table class="data-table">
          <thead><tr><th>Description</th><th class="text-right">Amount</th></tr></thead>
          <tbody>
            <tr><td class="text-white/75"><?= e($payment['service_name']) ?></td><td class="text-right font-semibold"><?= money((float)$payment['service_price'] ?? $payment['amount']) ?></td></tr>
          </tbody>
          <tfoot>
            <tr class="border-t border-gold/20">
              <td class="font-bold text-gold-light">Total</td>
              <td class="text-right font-bold text-gold-light text-base"><?= money((float)$payment['amount']) ?></td>
            </tr>
          </tfoot>
        </table>
      </div>

      <?php if ($payment['notes']): ?>
        <div class="glass rounded-xl p-4 mb-5">
          <div class="text-white/35 text-xs mb-1">Notes</div>
          <p class="text-white/60 text-sm"><?= nl2br(e($payment['notes'])) ?></p>
        </div>
      <?php endif; ?>

      <!-- Mark paid -->
      <?php if ($payment['status'] === 'pending'): ?>
        <div class="glass rounded-xl p-5">
          <h3 class="font-semibold text-white text-sm mb-3">Mark as Paid</h3>
          <form method="POST" action="<?= url('dashboard/payments/'.$payment['id'].'/pay') ?>" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[160px]">
              <label class="form-label">Payment Method</label>
              <select name="payment_method" class="form-select py-2 text-sm">
                <option value="cash">Cash</option>
                <option value="mobile_money">Mobile Money</option>
                <option value="card">Card</option>
              </select>
            </div>
            <button type="submit" class="btn-gold px-6 py-2.5 rounded-xl text-sm font-semibold inline-flex items-center gap-2">
              <i class="fa-solid fa-check-circle"></i> Mark Paid
            </button>
          </form>
        </div>
      <?php elseif ($payment['status'] === 'paid'): ?>
        <div class="alert alert-success">
          <i class="fa-solid fa-circle-check"></i>
          <span>Paid on <?= $payment['paid_at'] ? date('d M Y H:i', strtotime($payment['paid_at'])) : 'N/A' ?> via <?= ucfirst($payment['payment_method']) ?></span>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
