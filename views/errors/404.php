<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>404 — Page Not Found | Matilda's Salon</title>
  <style>
    *{box-sizing:border-box;margin:0;padding:0}
    body{font-family:system-ui,Georgia,serif;background:linear-gradient(135deg,#0d0d1a,#1a0a2e,#0a1628);min-height:100vh;display:flex;align-items:center;justify-content:center;color:#f0ede8;padding:2rem;text-align:center}
    .num{font-size:7rem;font-weight:900;line-height:1;background:linear-gradient(135deg,#c9a84c,#e8c97a);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;margin-bottom:1rem;letter-spacing:-.04em}
    h1{font-size:1.5rem;font-weight:600;color:#fff;margin-bottom:.75rem}
    p{color:rgba(240,237,232,.45);font-size:.9rem;margin-bottom:2rem;max-width:380px;line-height:1.6}
    .btns{display:flex;gap:.75rem;justify-content:center;flex-wrap:wrap}
    .btn-gold{display:inline-flex;align-items:center;gap:.5rem;background:linear-gradient(135deg,#a8893c,#c9a84c);color:#0d0d1a;padding:.7rem 1.75rem;border-radius:50px;font-weight:700;font-size:.875rem;text-decoration:none}
    .btn-glass{display:inline-flex;align-items:center;gap:.5rem;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.12);color:rgba(240,237,232,.6);padding:.7rem 1.75rem;border-radius:50px;font-size:.875rem;text-decoration:none}
    .btn-glass:hover{color:rgba(240,237,232,.85)}
  </style>
</head>
<body>
  <div>
    <div class="num">404</div>
    <h1>Page Not Found</h1>
    <p>The page you are looking for does not exist or has been moved.</p>
    <div class="btns">
      <a href="<?= defined('BASE_URL') ? BASE_URL : '/' ?>" class="btn-gold">&#8962; Go Home</a>
      <a href="javascript:history.back()" class="btn-glass">&#8592; Go Back</a>
    </div>
  </div>
</body>
</html>
