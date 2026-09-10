<?php
$db=new PDO('sqlite:'.__DIR__.'/wasla.sqlite');$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
$db->exec("CREATE TABLE IF NOT EXISTS applicants(id INTEGER PRIMARY KEY AUTOINCREMENT,name TEXT,age INTEGER,city TEXT,contact TEXT,bio TEXT,status TEXT DEFAULT 'جديد',created_at TEXT)");
if($_SERVER['REQUEST_METHOD']==='POST'){
$name=trim($_POST['name']??'');$age=(int)($_POST['age']??0);$city=trim($_POST['city']??'');$contact=trim($_POST['contact']??'');$bio=trim($_POST['bio']??'');
if($name&&$age>=18&&$city&&$contact){$s=$db->prepare("INSERT INTO applicants(name,age,city,contact,bio,created_at) VALUES(?,?,?,?,?,datetime('now'))");$s->execute([$name,$age,$city,$contact,$bio]);header('Location:/?sent=1');exit;}}
?>
<!doctype html><html lang="ar" dir="rtl"><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>NJA-WASLA</title>
<style>body{margin:0;background:#100b12;color:#fff;font-family:Tahoma;padding:25px}main{max-width:750px;margin:auto}h1{text-align:center;font-size:48px}span{color:#ff4f83}.card{background:#ffffff0b;padding:30px;border-radius:22px}input,textarea,button{width:100%;box-sizing:border-box;margin:8px 0 16px;padding:14px;border-radius:12px;background:#0b080e;color:#fff;border:1px solid #ffffff22}button{background:#e82f6a;border:0;font-weight:bold;cursor:pointer}.ok{background:#123d2d;padding:12px;border-radius:10px}</style>
<main><h1>NJA-WASLA <span>♥</span></h1><div class="card"><h2>طلب تعارف</h2><?php if(isset($_GET['sent']))echo'<div class="ok">تم استلام طلبك بنجاح ❤️</div>';?><form method="post">
<label>الاسم أو الاسم المستعار<input name="name" required></label><label>العمر<input name="age" type="number" min="18" required></label><label>المدينة<input name="city" required></label><label>بيانات التواصل<input name="contact" required></label><label>نبذة عنك<textarea name="bio" rows="5"></textarea></label><button>إرسال الطلب ❤️</button></form></div></main>