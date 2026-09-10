<?php
declare(strict_types=1);
session_start();
$ADMIN_USER=getenv('ADMIN_USER') ?: 'admin';
$ADMIN_PASS=getenv('ADMIN_PASS') ?: 'CHANGE-ME-NOW';
$db=new PDO('sqlite:'.__DIR__.'/wasla.sqlite');$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
$db->exec("CREATE TABLE IF NOT EXISTS applicants(id INTEGER PRIMARY KEY AUTOINCREMENT,name TEXT,age INTEGER,city TEXT,marital_status TEXT,interests TEXT,partner_preferences TEXT,contact_type TEXT,contact TEXT,bio TEXT,created_at TEXT)");
if(isset($_GET['logout'])){session_destroy();header('Location:/admin');exit;}
if(!isset($_SESSION['admin'])){if($_SERVER['REQUEST_METHOD']==='POST'&&hash_equals($ADMIN_USER,(string)($_POST['user']??''))&&hash_equals($ADMIN_PASS,(string)($_POST['pass']??''))){$_SESSION['admin']=1;header('Location:/admin');exit;}?>
<!doctype html><html lang="ar" dir="rtl"><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>NJA-WASLA Admin</title><style>
body{margin:0;background:#0d090f;color:#fff;font-family:Cairo,Tahoma,Arial;padding:20px}.box{max-width:400px;margin:12vh auto;background:#19151b;border:1px solid #ffffff18;border-radius:22px;padding:28px}h1{text-align:center}input,button{width:100%;box-sizing:border-box;padding:13px;margin:7px 0;border-radius:10px;border:1px solid #ffffff12;background:#09070a;color:#fff}button{background:#ed2868;border:0;font-weight:bold;cursor:pointer}.note{font-size:11px;color:#777}
</style><div class=box><h1>NJA-WASLA ♥</h1><h2>دخول الإدارة</h2><form method=post><input name=user placeholder="اسم المستخدم" required><input name=pass type=password placeholder="كلمة المرور" required><button>دخول</button></form><p class=note>غيّر ADMIN_USER و ADMIN_PASS من Variables في Railway.</p></div><?php exit;}
$rows=$db->query('SELECT * FROM applicants ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC);
function h($v){return htmlspecialchars((string)$v,ENT_QUOTES,'UTF-8');}
?><!doctype html><html lang="ar" dir="rtl"><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>صندوق NJA-WASLA</title><style>
*{box-sizing:border-box}body{margin:0;background:#0d090f;color:#fff;font-family:Cairo,Tahoma,Arial;padding:20px}main{max-width:1050px;margin:auto}header{display:flex;justify-content:space-between;align-items:center;margin-bottom:18px}header h1{margin:0;font-size:25px}header p{color:#777;font-size:11px}.logout{color:#ff6c9b;text-decoration:none}.card{background:#19151b;border:1px solid #ffffff14;border-radius:18px;padding:19px;margin:12px 0}.top{display:flex;justify-content:space-between;gap:15px}.top b{font-size:18px}.meta{color:#918992;font-size:11px}.detail{border-top:1px solid #ffffff0c;margin-top:14px;padding-top:10px;line-height:1.9;font-size:12px;color:#c9c1c7}.actions{display:flex;justify-content:flex-end;gap:8px}.actions button{background:#ed2868;color:#fff;border:0;border-radius:9px;padding:9px 14px;cursor:pointer}@media print{.actions,header .logout{display:none}body{background:white;color:black}.card{color:black;border:1px solid #ccc}}
</style><main><header><div><h1>NJA-WASLA — صندوق الطلبات</h1><p>لوحة الإدارة الخاصة</p></div><a class=logout href="?logout=1">خروج</a></header>
<?php if(!$rows):?><div class=card>لا توجد طلبات حتى الآن.</div><?php endif;?>
<?php foreach($rows as $a):?><article class=card><div class=top><div><b><?=h($a['name'])?></b><div class=meta><?=h($a['age'])?> سنة • <?=h($a['city'])?> • <?=h($a['marital_status'])?></div></div><div class=meta><?=h($a['created_at'])?></div></div>
<div class=detail><div><b>الاهتمامات:</b> <?=h($a['interests']?:'—')?></div><div><b>المواصفات:</b> <?=nl2br(h($a['partner_preferences']?:'—'))?></div><div><b>التواصل:</b> <?=h($a['contact_type'])?> — <?=h($a['contact'])?></div><div><b>النبذة:</b> <?=nl2br(h($a['bio']?:'—'))?></div></div>
<div class=actions><button onclick="print()">طباعة / PDF</button></div></article><?php endforeach;?></main></html>