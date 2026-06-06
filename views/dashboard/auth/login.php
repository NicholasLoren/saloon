<div class="w-full max-w-md relative z-10">
  <!-- Logo -->
  <div class="text-center mb-8">
    <div class="w-16 h-16 gold-gradient rounded-2xl flex items-center justify-center mx-auto mb-4 text-slate-900 text-2xl shadow-2xl animate-pulse-gold">
      <i class="fa-solid fa-scissors"></i>
    </div>
    <h1 class="font-serif text-2xl font-bold text-white"><?= SALON_NAME ?></h1>
    <p class="text-white/45 text-sm mt-1">Staff & Admin Portal</p>
  </div>

  <div class="glass rounded-2xl p-8">
    <h2 class="font-serif text-xl font-semibold text-white mb-1">Welcome Back</h2>
    <p class="text-white/45 text-xs mb-6">Sign in to manage your salon</p>

    <?php if (!empty($errors)): ?>
      <div class="alert alert-error mb-5">
        <i class="fa-solid fa-circle-xmark flex-shrink-0"></i>
        <span><?= e(current($errors)) ?></span>
      </div>
    <?php endif; ?>

    <form method="POST" action="<?= url('dashboard/login') ?>" novalidate>
      <div class="mb-4">
        <label class="form-label">Email Address</label>
        <div class="relative">
          <span class="absolute left-3 top-1/2 -translate-y-1/2 text-white/30 text-sm">
            <i class="fa-regular fa-envelope"></i>
          </span>
          <input type="email" name="email" value="<?= e($old['email'] ?? '') ?>"
            class="form-input pl-9 <?= isset($errors['email']) ? 'error' : '' ?>"
            placeholder="admin@matildassalon.com" required autofocus>
        </div>
        <?php if (isset($errors['email']) && $errors['email'] !== 'Invalid email or password.'): ?>
          <div class="form-error"><i class="fa-solid fa-circle-exclamation text-xs"></i><?= e($errors['email']) ?></div>
        <?php endif; ?>
      </div>

      <div class="mb-6">
        <label class="form-label">Password</label>
        <div class="relative">
          <span class="absolute left-3 top-1/2 -translate-y-1/2 text-white/30 text-sm">
            <i class="fa-solid fa-lock"></i>
          </span>
          <input type="password" name="password" id="pw"
            class="form-input pl-9 pr-10 <?= isset($errors['password']) ? 'error' : '' ?>"
            placeholder="••••••••" required>
          <button type="button" onclick="togglePw()" class="absolute right-3 top-1/2 -translate-y-1/2 text-white/30 hover:text-white/60 text-sm transition-colors">
            <i class="fa-regular fa-eye" id="pw-eye"></i>
          </button>
        </div>
        <?php if (isset($errors['password'])): ?>
          <div class="form-error"><i class="fa-solid fa-circle-exclamation text-xs"></i><?= e($errors['password']) ?></div>
        <?php endif; ?>
      </div>

      <button type="submit" class="btn-gold w-full py-3.5 rounded-xl font-semibold text-sm flex items-center justify-center gap-2 shadow-xl">
        <i class="fa-solid fa-right-to-bracket"></i> Sign In
      </button>
    </form>
  </div>

  <p class="text-center text-white/30 text-xs mt-5">
    <a href="<?= url('/') ?>" class="hover:text-gold/70 transition-colors"><i class="fa-solid fa-arrow-left text-[10px] mr-1"></i>Back to Website</a>
  </p>

  <!-- Demo credentials hint -->
  <div class="mt-4 glass rounded-xl p-3.5 text-xs text-white/40 text-center">
    <i class="fa-solid fa-circle-info text-gold/50 mr-1.5"></i>
    Default: <code class="text-gold/70">admin@matildassalon.com</code> / <code class="text-gold/70">admin123</code>
  </div>
</div>

<script>
function togglePw(){
  const pw=document.getElementById('pw'), eye=document.getElementById('pw-eye');
  pw.type=pw.type==='password'?'text':'password';
  eye.className=pw.type==='password'?'fa-regular fa-eye':'fa-regular fa-eye-slash';
}
</script>
