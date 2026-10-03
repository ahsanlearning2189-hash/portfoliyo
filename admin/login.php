<?php require_once __DIR__.'/../includes/functions.php';
$err='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  if(!csrf_ok()) $err='Session expired. Reload and try again.';
  else{
    $s=db()->prepare('SELECT * FROM users WHERE username=? AND status=1'); $s->execute([trim($_POST['username']??'')]);
    $u=$s->fetch();
    if($u && password_verify($_POST['password']??'',$u['password_hash'])){
      session_regenerate_id(true); $_SESSION['admin_id']=$u['id']; header('Location: dashboard.php'); exit;
    } $err='Wrong username or password.';
  }
}?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Admin login</title><link rel="stylesheet" href="../assets/css/style.css"></head>
<body class="admin"><main class="panel login"><h1>Admin login</h1>
<?php if($err):?><p class="error" role="alert"><?=e($err)?></p><?php endif;?>
<form method="post"><?=csrf_field()?>
<label>Username<input name="username" required autofocus></label>
<label>Password<input type="password" name="password" required></label>
<button class="btn" type="submit">Sign in</button></form></main></body></html>
