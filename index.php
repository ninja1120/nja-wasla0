<?php
declare(strict_types=1);
session_start();

if (parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) === '/admin') {
    require __DIR__ . '/admin.php';
    exit;
}

$db = new PDO('sqlite:' . __DIR__ . '/wasla.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->exec("CREATE TABLE IF NOT EXISTS applicants (
 id INTEGER PRIMARY KEY AUTOINCREMENT,
 name TEXT NOT NULL, age INTEGER NOT NULL, city TEXT NOT NULL,
 marital_status TEXT NOT NULL, interests TEXT, partner_preferences TEXT,
 contact_type TEXT NOT NULL, contact TEXT NOT NULL, bio TEXT,
 created_at TEXT NOT NULL
)");

$sent = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $age = (int)($_POST['age'] ?? 0);
    $city = trim($_POST['city'] ?? '');
    $status = trim($_POST['marital_status'] ?? 'عزباء');
    $interests = trim($_POST['interests'] ?? '');
    $preferences = trim($_POST['partner_preferences'] ?? '');
    $contactType = trim($_POST['contact_type'] ?? 'البريد الإلكتروني');
    $contact = trim($_POST['contact'] ?? '');
    $bio = trim($_POST['bio'] ?? '');

    if (!$name || $age < 18 || !$city || !$contact) {
        $error = 'من فضلك املئي البيانات المطلوبة بشكل صحيح.';
    } else {
        $stmt = $db->prepare("INSERT INTO applicants
            (name,age,city,marital_status,interests,partner_preferences,contact_type,contact,bio,created_at)
            VALUES (?,?,?,?,?,?,?,?,?,?)");
        $stmt->execute([$name,$age,$city,$status,$interests,$preferences,$contactType,$contact,$bio,date('Y-m-d H:i:s')]);
        $sent = true;
    }
}
?>
<!doctype html>
<html lang="ar" dir="rtl">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>NJA-WASLA</title>
<link rel="stylesheet" href="/style.css">
</head>
<body>
<div class="page">
  <div class="top-glow"></div>
  <header class="brand">
    <div class="logo">NJA-WASLA <span>♥</span></div>
    <div class="subtitle">تعارف بنية الارتباط</div>
  </header>

  <section class="intro">
    <div class="eyebrow">مساحة خاصة ومحترمة للتعارف</div>
    <h1>يمكن تكون <span>البداية</span> هنا.</h1>
    <p>اكتبي معلوماتك ومواصفات الشخص المناسب لك، وهنراجع طلبك بعناية.</p>
  </section>

  <section class="form-card">
    <h2>طلب تعارف</h2>
    <p class="hint">البيانات التي ترسلينها لا تظهر للعامة.</p>

    <?php if ($sent): ?>
      <div class="message success">تم إرسال الطلب بنجاح ❤️</div>
    <?php elseif ($error): ?>
      <div class="message error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" autocomplete="off">
      <div class="fields">
        <label>الاسم أو الاسم المستعار
          <input name="name" required maxlength="100" placeholder="مثال: منار">
        </label>

        <label>العمر
          <input name="age" type="number" min="18" max="80" required placeholder="25">
        </label>

        <label>المدينة
          <input name="city" required maxlength="100" placeholder="عمّان">
        </label>

        <label>الحالة الاجتماعية
          <select name="marital_status">
            <option>عزباء</option><option>مطلقة</option><option>أرملة</option>
          </select>
        </label>

        <label class="full">الاهتمامات
          <input name="interests" placeholder="مثال: قراءة، سفر، موسيقى">
        </label>

        <label class="full">مواصفات الشخص المناسب
          <textarea name="partner_preferences" rows="4" placeholder="اكتبي الصفات التي تهمك..."></textarea>
        </label>

        <label>طريقة التواصل
          <select name="contact_type">
            <option>البريد الإلكتروني</option><option>Telegram</option><option>واتساب</option>
          </select>
        </label>

        <label>بيانات التواصل
          <input name="contact" required maxlength="255" placeholder="وسيلة التواصل">
        </label>

        <label class="full">نبذة عنك
          <textarea name="bio" rows="4"></textarea>
        </label>
      </div>

      <button class="submit">إرسال الطلب <span>♥</span></button>
    </form>
  </section>

  <footer>خصوصيتك مهمة • NJA-WASLA</footer>
</div>
</body>
</html>