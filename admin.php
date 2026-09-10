<?php
declare(strict_types=1);
session_start();

$ADMIN_USER = getenv('ADMIN_USER') ?: 'admin';
$ADMIN_PASS = getenv('ADMIN_PASS') ?: 'CHANGE-ME-NOW';

$db = new PDO('sqlite:' . __DIR__ . '/wasla.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->exec("CREATE TABLE IF NOT EXISTS applicants (
 id INTEGER PRIMARY KEY AUTOINCREMENT,
 name TEXT NOT NULL, age INTEGER NOT NULL, city TEXT NOT NULL,
 marital_status TEXT, interests TEXT, partner_preferences TEXT,
 contact_type TEXT, contact TEXT, bio TEXT, created_at TEXT NOT NULL
)");

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location:/admin');
    exit;
}

if (!isset($_SESSION['admin'])) {
    $bad = false;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (hash_equals($ADMIN_USER, (string)($_POST['user'] ?? '')) &&
            hash_equals($ADMIN_PASS, (string)($_POST['pass'] ?? ''))) {
            $_SESSION['admin'] = true;
            header('Location:/admin');
            exit;
        }
        $bad = true;
    }
?>
<!doctype html><html lang="ar" dir="rtl"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>NJA-WASLA Admin</title>
<style>
*{box-sizing:border-box}body{margin:0;background:#0d090f;color:#fff;font-family:Tahoma,Arial;padding:20px}.login{max-width:420px;margin:12vh auto;background:#19151b;border:1px solid #ffffff16;border-radius:24px;padding:30px;box-shadow:0 30px 80px #0008}h1{text-align:center;margin-top:0}input,button{width:100%;padding:13px;margin:8px 0;border-radius:11px;border:1px solid #ffffff12;background:#09070a;color:#fff}button{background:#ed2868;border:0;font-weight:bold;cursor:pointer}.bad{background:#481827;color:#ffabc2;padding:10px;border-radius:10px;text-align:center}.hint{font-size:11px;color:#777;text-align:center}
</style></head><body><div class="login"><h1>NJA-WASLA ♥</h1><h2>دخول الإدارة</h2><?php if($bad):?><div class=bad>بيانات الدخول غير صحيحة.</div><?php endif;?><form method=post><input name=user placeholder="اسم المستخدم" required><input name=pass type=password placeholder="كلمة المرور" required><button>دخول</button></form><div class=hint>بيانات الدخول من Railway Variables</div></div></body></html>
<?php exit; }

$rows = $db->query('SELECT * FROM applicants ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC);
function h($v){return htmlspecialchars((string)$v,ENT_QUOTES,'UTF-8');}
?>
<!doctype html><html lang="ar" dir="rtl"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>صندوق NJA-WASLA</title>
<style>
*{box-sizing:border-box}body{margin:0;background:#0d090f;color:#fff;font-family:Tahoma,Arial,sans-serif;padding:22px}main{max-width:1050px;margin:auto}
header{display:flex;justify-content:space-between;align-items:center;margin-bottom:22px}header h1{margin:0;font-size:26px}header p{margin:6px 0;color:#777;font-size:11px}.logout{color:#ff709c;text-decoration:none}
.card{background:#19151b;border:1px solid #ffffff14;border-radius:19px;padding:19px;margin:13px 0;box-shadow:0 15px 45px #0004}.top{display:flex;justify-content:space-between;align-items:center;gap:15px}.name{font-size:19px;font-weight:bold}.meta{font-size:11px;color:#90888f;margin-top:5px}.date{font-size:10px;color:#696269}
.preview{color:#aaa;font-size:12px;line-height:1.8;margin:14px 0;max-width:760px}.open{background:#ed2868;color:white;border:0;border-radius:10px;padding:10px 18px;cursor:pointer;font-weight:bold}
.empty{text-align:center;color:#777;padding:40px}
.modal{display:none;position:fixed;inset:0;background:#000b;z-index:20;align-items:center;justify-content:center;padding:18px}.modal.show{display:flex}.modal-box{width:min(760px,100%);max-height:90vh;overflow:auto;background:#19151b;border:1px solid #ffffff1c;border-radius:22px;padding:25px;position:relative}.close{position:absolute;left:18px;top:15px;background:#2a252b;color:#fff;border:0;border-radius:9px;padding:7px 11px;cursor:pointer}.modal h2{margin:0 35px 4px 0}.section{border-top:1px solid #ffffff10;margin-top:16px;padding-top:13px}.row{display:grid;grid-template-columns:150px 1fr;gap:10px;padding:7px 0;font-size:12px}.label{color:#8f878e}.value{color:#eee;white-space:pre-wrap;word-break:break-word}.modal-actions{display:flex;gap:8px;margin-top:20px}.modal-actions button{flex:1;padding:11px;border:0;border-radius:10px;cursor:pointer;background:#ed2868;color:#fff;font-weight:bold}.modal-actions .secondary{background:#302a31}
@media(max-width:600px){.row{grid-template-columns:1fr;gap:3px}.top{align-items:flex-start;flex-direction:column}.modal-box{padding:20px 16px}}
@media print{body{background:#fff;color:#000}.card,header{display:none}.modal{display:flex!important;position:static;background:#fff;padding:0}.modal-box{box-shadow:none;border:0;color:#000;max-height:none;width:100%}.close,.modal-actions{display:none}.label{color:#555}.value{color:#000}}
</style></head><body><main>
<header><div><h1>NJA-WASLA — صندوق الطلبات</h1><p>اضغط «فتح الطلب» لمشاهدة التفاصيل كاملة.</p></div><a class=logout href="?logout=1">خروج</a></header>

<?php if(!$rows): ?><div class="card empty">لا توجد طلبات حتى الآن.</div><?php endif; ?>

<?php foreach($rows as $a): ?>
<article class="card">
  <div class="top">
    <div><div class="name"><?=h($a['name'])?></div><div class="meta"><?=h($a['age'])?> سنة • <?=h($a['city'])?> • <?=h($a['marital_status'] ?: '—')?></div></div>
    <div class="date"><?=h($a['created_at'])?></div>
  </div>
  <div class="preview"><?=h(mb_strimwidth($a['bio'] ?: 'لا توجد نبذة.',0,180,'...','UTF-8'))?></div>
  <button class="open" onclick="openRequest(<?=h($a['id'])?>)">فتح الطلب</button>
</article>

<div class="modal" id="m<?=h($a['id'])?>">
  <div class="modal-box">
    <button class="close" onclick="closeRequest(<?=h($a['id'])?>)">×</button>
    <h2><?=h($a['name'])?></h2>
    <div class="meta">طلب رقم #<?=h($a['id'])?> • <?=h($a['created_at'])?></div>

    <div class="section">
      <div class="row"><div class="label">العمر</div><div class="value"><?=h($a['age'])?> سنة</div></div>
      <div class="row"><div class="label">المدينة</div><div class="value"><?=h($a['city'])?></div></div>
      <div class="row"><div class="label">الحالة الاجتماعية</div><div class="value"><?=h($a['marital_status'] ?: '—')?></div></div>
      <div class="row"><div class="label">الاهتمامات</div><div class="value"><?=h($a['interests'] ?: '—')?></div></div>
      <div class="row"><div class="label">مواصفات الشخص المناسب</div><div class="value"><?=h($a['partner_preferences'] ?: '—')?></div></div>
      <div class="row"><div class="label">طريقة التواصل</div><div class="value"><?=h($a['contact_type'] ?: '—')?></div></div>
      <div class="row"><div class="label">بيانات التواصل</div><div class="value"><?=h($a['contact'])?></div></div>
      <div class="row"><div class="label">النبذة</div><div class="value"><?=h($a['bio'] ?: '—')?></div></div>
    </div>

    <div class="modal-actions">
      <button onclick="window.print()">طباعة / حفظ PDF</button>
      <button class="secondary" onclick="closeRequest(<?=h($a['id'])?>)">إغلاق</button>
    </div>
  </div>
</div>
<?php endforeach; ?>
</main>
<script>
function openRequest(id){document.getElementById('m'+id).classList.add('show');document.body.style.overflow='hidden'}
function closeRequest(id){document.getElementById('m'+id).classList.remove('show');document.body.style.overflow=''}
document.querySelectorAll('.modal').forEach(m=>m.addEventListener('click',e=>{if(e.target===m){m.classList.remove('show');document.body.style.overflow=''}}));
document.addEventListener('keydown',e=>{if(e.key==='Escape'){document.querySelectorAll('.modal.show').forEach(m=>m.classList.remove('show'));document.body.style.overflow=''}});
</script></body></html>
