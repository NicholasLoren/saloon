<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Setup — Matilda's Salon</title>
  <style>
    *{box-sizing:border-box;margin:0;padding:0}
    body{font-family:system-ui,sans-serif;background:linear-gradient(135deg,#0d0d1a,#1a0a2e,#0a1628);min-height:100vh;display:flex;align-items:center;justify-content:center;color:#f0ede8;padding:1rem}
    .card{background:rgba(255,255,255,.06);border:1px solid rgba(201,168,76,.2);backdrop-filter:blur(16px);border-radius:16px;padding:2.5rem;max-width:680px;width:100%}
    h1{font-size:1.6rem;font-weight:700;color:#e8c97a;margin-bottom:.25rem}
    h2{font-size:.9rem;font-weight:600;color:#c9a84c;margin:1.5rem 0 .5rem;text-transform:uppercase;letter-spacing:.08em}
    .step{padding:.5rem 0;border-bottom:1px solid rgba(255,255,255,.06);display:flex;align-items:center;gap:.75rem;font-size:.875rem}
    .ok{color:#86efac}.err{color:#fca5a5}.warn{color:#fcd34d}
    .icon{font-size:1rem;flex-shrink:0;width:1.2rem}
    .btn{display:inline-block;background:linear-gradient(135deg,#a8893c,#c9a84c);color:#0d0d1a;padding:.7rem 2rem;border-radius:50px;font-weight:700;font-size:.875rem;text-decoration:none;margin-top:1.5rem;border:none;cursor:pointer}
    code{background:rgba(255,255,255,.1);padding:2px 6px;border-radius:4px;font-size:.8em}
    pre{background:rgba(0,0,0,.3);border-radius:8px;padding:1rem;overflow-x:auto;font-size:.8rem;color:#86efac;margin:.5rem 0}
    .sub{color:rgba(240,237,232,.45);font-size:.8rem;margin-top:.25rem}
  </style>
</head>
<body>
<div class="card">
  <h1>&#9999; Matilda's Salon Setup</h1>
  <p class="sub">Database initialisation &amp; seed data</p>

<?php
require_once __DIR__ . '/config/db.php';


$steps   = [];
$success = true;

// 1. Connect without DB
$_host = DB_HOST;
$_port = DB_PORT;
$_user = DB_USER;
$_pass = DB_PASS;
$_name = DB_NAME;

try {
    $pdo0 = new PDO(
        "mysql:host={$_host};port={$_port};charset=utf8mb4",
        $_user, $_pass,
        [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]
    );
    $steps[] = ['ok', 'Connected to MySQL server.'];
} catch (PDOException $e) {
    $steps[] = ['err', 'Cannot connect to MySQL: ' . $e->getMessage()];
    $success = false;
}

if ($success) {
    // 2. Create DB
    $pdo0->exec("CREATE DATABASE IF NOT EXISTS `{$_name}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo0->exec("USE `{$_name}`");
    $steps[] = ['ok', 'Database <code>db_7b6b0389</code> ready.'];

    // 3. Create tables
    $tables = [
        "users" => "CREATE TABLE IF NOT EXISTS users (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100) NOT NULL, email VARCHAR(100) UNIQUE NOT NULL, password VARCHAR(255) NOT NULL, phone VARCHAR(20), role ENUM('admin','staff') NOT NULL DEFAULT 'staff', created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB",
        "services" => "CREATE TABLE IF NOT EXISTS services (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100) NOT NULL, description TEXT, price DECIMAL(10,2) NOT NULL, duration_minutes INT NOT NULL DEFAULT 60, category VARCHAR(50) DEFAULT 'General', image VARCHAR(255), active TINYINT(1) DEFAULT 1, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB",
        "staff" => "CREATE TABLE IF NOT EXISTS staff (id INT AUTO_INCREMENT PRIMARY KEY, user_id INT NOT NULL, specialization VARCHAR(100), bio TEXT, available TINYINT(1) DEFAULT 1, FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE) ENGINE=InnoDB",
        "staff_availability" => "CREATE TABLE IF NOT EXISTS staff_availability (id INT AUTO_INCREMENT PRIMARY KEY, staff_id INT NOT NULL, day_of_week TINYINT NOT NULL, start_time TIME NOT NULL, end_time TIME NOT NULL, FOREIGN KEY (staff_id) REFERENCES staff(id) ON DELETE CASCADE) ENGINE=InnoDB",
        "appointments" => "CREATE TABLE IF NOT EXISTS appointments (id INT AUTO_INCREMENT PRIMARY KEY, client_name VARCHAR(100) NOT NULL, client_email VARCHAR(100) NOT NULL, client_phone VARCHAR(20), service_id INT NOT NULL, staff_id INT DEFAULT NULL, appointment_date DATE NOT NULL, appointment_time TIME NOT NULL, status ENUM('pending','confirmed','completed','cancelled') DEFAULT 'pending', notes TEXT, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY (service_id) REFERENCES services(id), FOREIGN KEY (staff_id) REFERENCES staff(id) ON DELETE SET NULL) ENGINE=InnoDB",
        "payments" => "CREATE TABLE IF NOT EXISTS payments (id INT AUTO_INCREMENT PRIMARY KEY, appointment_id INT NOT NULL, amount DECIMAL(10,2) NOT NULL, payment_method ENUM('cash','mobile_money','card') DEFAULT 'cash', status ENUM('pending','paid','refunded') DEFAULT 'pending', invoice_number VARCHAR(30) UNIQUE, notes TEXT, paid_at TIMESTAMP NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY (appointment_id) REFERENCES appointments(id)) ENGINE=InnoDB",
        "inventory" => "CREATE TABLE IF NOT EXISTS inventory (id INT AUTO_INCREMENT PRIMARY KEY, product_name VARCHAR(100) NOT NULL, category VARCHAR(50), quantity DECIMAL(10,2) DEFAULT 0, unit VARCHAR(20) DEFAULT 'pcs', reorder_level DECIMAL(10,2) DEFAULT 5, cost_price DECIMAL(10,2) DEFAULT 0, supplier VARCHAR(100), last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP) ENGINE=InnoDB",
        "promotions" => "CREATE TABLE IF NOT EXISTS promotions (id INT AUTO_INCREMENT PRIMARY KEY, title VARCHAR(100) NOT NULL, description TEXT, discount_percent DECIMAL(5,2) DEFAULT 0, start_date DATE, end_date DATE, active TINYINT(1) DEFAULT 1, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB",
        "faqs" => "CREATE TABLE IF NOT EXISTS faqs (id INT AUTO_INCREMENT PRIMARY KEY, question TEXT NOT NULL, answer TEXT NOT NULL, category VARCHAR(50) DEFAULT 'General', active TINYINT(1) DEFAULT 1, sort_order INT DEFAULT 0) ENGINE=InnoDB",
        "contact_messages" => "CREATE TABLE IF NOT EXISTS contact_messages (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100) NOT NULL, email VARCHAR(100) NOT NULL, phone VARCHAR(20), subject VARCHAR(100), message TEXT NOT NULL, is_read TINYINT(1) DEFAULT 0, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB",
        "notifications" => "CREATE TABLE IF NOT EXISTS notifications (id INT AUTO_INCREMENT PRIMARY KEY, type ENUM('appointment','message','payment','system') DEFAULT 'system', title VARCHAR(150) NOT NULL, body TEXT, url VARCHAR(255), is_read TINYINT(1) DEFAULT 0, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB",
    ];
    foreach ($tables as $name => $sql) {
        try { $pdo0->exec($sql); $steps[] = ['ok', "Table <code>$name</code> created."]; }
        catch (\Exception $e) { $steps[] = ['err', "Table $name: " . $e->getMessage()]; }
    }

    // 4. Seed admin
    $adminPass = password_hash('admin123', PASSWORD_DEFAULT);
    $check = $pdo0->query("SELECT id FROM users WHERE email='admin@matildassalon.com'")->fetch();
    if (!$check) {
        $pdo0->prepare("INSERT INTO users (name,email,password,phone,role) VALUES (?,?,?,?,?)")
             ->execute(['Admin', 'admin@matildassalon.com', $adminPass, '+256700000001', 'admin']);
        $steps[] = ['ok', 'Admin user created: <code>admin@matildassalon.com</code> / <code>admin123</code>'];
    } else {
        $steps[] = ['warn', 'Admin user already exists (skipped).'];
    }

    // Seed staff
    $staffPass = password_hash('staff123', PASSWORD_DEFAULT);
    $staffCheck = $pdo0->query("SELECT id FROM users WHERE email='sarah@matildassalon.com'")->fetch();
    if (!$staffCheck) {
        $pdo0->prepare("INSERT INTO users (name,email,password,phone,role) VALUES (?,?,?,?,?)")
             ->execute(['Nakato Sarah', 'sarah@matildassalon.com', $staffPass, '+256700000002', 'staff']);
        $sarahId = (int)$pdo0->lastInsertId();
        $pdo0->prepare("INSERT INTO staff (user_id,specialization,bio,available) VALUES (?,?,?,1)")
             ->execute([$sarahId, 'Hair Styling & Braiding', '5+ years expert stylist specialising in natural hair and braiding.']);
        $sId = (int)$pdo0->lastInsertId();
        $av = $pdo0->prepare("INSERT INTO staff_availability (staff_id,day_of_week,start_time,end_time) VALUES (?,?,?,?)");
        for ($d=1; $d<=6; $d++) $av->execute([$sId, $d, '09:00:00', '18:00:00']);
        $steps[] = ['ok', 'Staff user created: <code>sarah@matildassalon.com</code> / <code>staff123</code>'];
    } else {
        $steps[] = ['warn', 'Staff user already exists (skipped).'];
    }

    // Seed services
    $svcCount = (int)$pdo0->query("SELECT COUNT(*) FROM services")->fetchColumn();
    if ($svcCount === 0) {
        $ins = $pdo0->prepare("INSERT INTO services (name,description,price,duration_minutes,category) VALUES (?,?,?,?,?)");
        foreach ([
            ['Haircut & Styling','Professional haircut with wash and blow dry',25000,60,'Hair'],
            ['Hair Coloring','Full hair colour treatment with deep conditioning',80000,120,'Hair'],
            ['Hair Relaxer','Chemical hair relaxer with treatment and blow dry',55000,120,'Hair'],
            ['Box Braids','Various braiding styles — box braids, cornrows, twists',60000,180,'Hair'],
            ['Manicure','Classic manicure with nail polish of choice',20000,45,'Nails'],
            ['Pedicure','Classic pedicure with foot soak and nail polish',25000,60,'Nails'],
            ['Gel Nails','Long-lasting gel nail application and design',45000,90,'Nails'],
            ['Facial Treatment','Deep cleansing facial with exfoliation and mask',50000,90,'Skin'],
            ['Eyebrow Threading','Precise eyebrow shaping and threading',10000,20,'Beauty'],
            ['Makeup Application','Full makeup application for events and occasions',70000,60,'Beauty'],
        ] as $r) $ins->execute($r);
        $steps[] = ['ok', '10 services seeded.'];
    } else {
        $steps[] = ['warn', 'Services already exist (skipped).'];
    }

    // Seed inventory
    $invCount = (int)$pdo0->query("SELECT COUNT(*) FROM inventory")->fetchColumn();
    if ($invCount === 0) {
        $ins = $pdo0->prepare("INSERT INTO inventory (product_name,category,quantity,unit,reorder_level,cost_price,supplier) VALUES (?,?,?,?,?,?,?)");
        foreach ([
            ['Hair Shampoo (500ml)','Hair Care',15,'bottles',5,8000,'Beauty Supplies Ltd'],
            ['Hair Conditioner (500ml)','Hair Care',12,'bottles',5,9000,'Beauty Supplies Ltd'],
            ['Hair Colour Kit','Hair Color',8,'kits',3,25000,'Salon Pro UG'],
            ['Relaxer Kit','Hair Care',10,'kits',3,20000,'Salon Pro UG'],
            ['Nail Polish Set','Nails',20,'bottles',5,3000,'Nail World UG'],
            ['Acetone Remover','Nails',5,'liters',2,5000,'Nail World UG'],
            ['Facial Cream','Skin Care',8,'tubes',3,15000,'Skin Glow UG'],
            ['Face Wash','Skin Care',6,'bottles',3,12000,'Skin Glow UG'],
            ['Cotton Wool','Supplies',4,'packs',2,2000,'General Supplies'],
            ['Disposable Gloves','Supplies',100,'pairs',20,500,'General Supplies'],
        ] as $r) $ins->execute($r);
        $steps[] = ['ok', '10 inventory items seeded.'];
    } else {
        $steps[] = ['warn', 'Inventory already populated (skipped).'];
    }

    // Seed FAQs
    $faqCount = (int)$pdo0->query("SELECT COUNT(*) FROM faqs")->fetchColumn();
    if ($faqCount === 0) {
        $ins = $pdo0->prepare("INSERT INTO faqs (question,answer,category,active,sort_order) VALUES (?,?,?,1,?)");
        foreach ([
            ['How do I book an appointment?','Simply visit our website, go to the Book page, select your service, pick a date and time, fill in your contact details and submit. We will confirm within a few hours.','Booking',1],
            ['Do I need to create an account to book?','No account is needed. Our booking is guest-friendly — just fill in your name, email, and phone number.','Booking',2],
            ['Can I choose my preferred stylist?','Yes! When booking, you can select a specific stylist or choose "Any Available" and we will assign the best match.','Booking',3],
            ['How will I be notified of my appointment confirmation?','We will contact you via phone or email once your appointment is confirmed.','Booking',4],
            ['What services do you offer?','We offer hair styling, coloring, braiding, relaxers, manicure, pedicure, gel nails, facials, eyebrow threading, and makeup application. Visit our Services page for full details.','Services',1],
            ['How much do services cost?','Prices vary by service. Visit our Services page to see full pricing. Hair services start from UGX 25,000 and nail services from UGX 20,000.','Services',2],
            ['Where are you located?','We are located at Freedom City Mall, Namasuba, Wakiso District, Uganda.','General',1],
            ['What are your opening hours?','Monday to Saturday: 9:00 AM – 7:00 PM. Sunday: 10:00 AM – 5:00 PM.','General',2],
            ['Do you accept walk-ins?','Yes, we welcome walk-ins subject to availability. However, we recommend booking in advance to secure your preferred time.','General',3],
        ] as $r) $ins->execute($r);
        $steps[] = ['ok', '9 FAQs seeded.'];
    } else {
        $steps[] = ['warn', 'FAQs already populated (skipped).'];
    }

    // Seed promotions
    $promoCount = (int)$pdo0->query("SELECT COUNT(*) FROM promotions")->fetchColumn();
    if ($promoCount === 0) {
        $ins = $pdo0->prepare("INSERT INTO promotions (title,description,discount_percent,start_date,end_date,active) VALUES (?,?,?,?,?,1)");
        $ins->execute(["First Visit Discount","Get 20% off your very first service at Matilda's Salon!",20,date('Y-m-d'),date('Y-m-d',strtotime('+60 days'))]);
        $ins->execute(['Weekend Special','Book any service on Saturday and enjoy 15% off.',15,date('Y-m-d'),date('Y-m-d',strtotime('+90 days'))]);
        $steps[] = ['ok', '2 promotions seeded.'];
    } else {
        $steps[] = ['warn', 'Promotions already populated (skipped).'];
    }

    $steps[] = ['ok', '<strong>Setup complete!</strong> Your database is ready.'];
}
?>

  <h2>Setup Results</h2>
  <?php foreach ($steps as [$type, $msg]): ?>
    <div class="step <?= $type ?>">
      <span class="icon"><?= $type==='ok'?'✓':($type==='err'?'✗':'⚠') ?></span>
      <span><?= $msg ?></span>
    </div>
  <?php endforeach; ?>

  <?php if ($success): ?>
    <h2>Default Credentials</h2>
    <pre>Admin : admin@matildassalon.com  /  admin123
Staff : sarah@matildassalon.com  /  staff123</pre>

    <p style="margin-top:1.25rem;font-size:.85rem;color:rgba(240,237,232,.5)">
      <strong style="color:#fcd34d">Security notice:</strong> Change default passwords after first login.<br>
      Delete or restrict access to <code>setup.php</code> in production.
    </p>

    <div style="display:flex;gap:.75rem;flex-wrap:wrap;margin-top:1.5rem">
      <a href="<?= htmlspecialchars(getenv('BASE_URL') ?: '') ?>/" class="btn">&#127968; Go to Website</a>
      <a href="<?= htmlspecialchars(getenv('BASE_URL') ?: '') ?>/dashboard/login" class="btn" style="background:rgba(255,255,255,.1);color:#e8c97a">&#9881; Go to Dashboard</a>
    </div>
  <?php else: ?>
    <p style="margin-top:1rem;color:#fca5a5;font-size:.875rem">
      Fix the errors above, update <code>config/db.php</code> if needed, and refresh this page.
    </p>
  <?php endif; ?>
</div>
</body>
</html>
