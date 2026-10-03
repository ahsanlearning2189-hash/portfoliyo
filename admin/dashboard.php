<?php require_once __DIR__.'/../includes/auth_check.php'; $pdo=db(); $msg='';
if($_SERVER['REQUEST_METHOD']==='POST' && csrf_ok()){
  $a=$_POST['action']??'';
  if($a==='add'||$a==='edit'){
    $t=trim($_POST['title']??''); $d=trim($_POST['description']??''); $c=$_POST['category']??'web';
    if(!in_array($c,['web','php','uiux','3d','database'],true)) $c='web';
    if($t===''||$d===''){ $msg='Title and description are required.'; }
    else{
      $img=upload_image('image'); $tech=trim($_POST['tech']??''); $live=trim($_POST['live_url']??''); $gh=trim($_POST['github_url']??''); $feat=isset($_POST['featured'])?1:0;
      if($a==='add'){ $pdo->prepare('INSERT INTO projects(title,description,category,technologies,image,live_url,github_url,featured) VALUES(?,?,?,?,?,?,?,?)')->execute([$t,$d,$c,$tech,$img,$live,$gh,$feat]); $msg='Project added.'; }
      else{ $id=(int)$_POST['id'];
        $pdo->prepare('UPDATE projects SET title=?,description=?,category=?,technologies=?,live_url=?,github_url=?,featured=?,image=COALESCE(?,image) WHERE id=?')->execute([$t,$d,$c,$tech,$live,$gh,$feat,$img,$id]); $msg='Project updated.'; }
    }
  } elseif($a==='delete'){ $pdo->prepare('DELETE FROM projects WHERE id=?')->execute([(int)$_POST['id']]); $msg='Project deleted.'; }
  elseif($a==='del_msg'){ $pdo->prepare('DELETE FROM contact_messages WHERE id=?')->execute([(int)$_POST['id']]); $msg='Message deleted.'; }
}
$edit=null; if(isset($_GET['edit'])){ $s=$pdo->prepare('SELECT * FROM projects WHERE id=?'); $s->execute([(int)$_GET['edit']]); $edit=$s->fetch(); }
$counts=[]; foreach(['projects','contact_messages','blog_posts','testimonials'] as $t) $counts[$t]=$pdo->query("SELECT COUNT(*) FROM $t")->fetchColumn();
$projects=$pdo->query('SELECT * FROM projects ORDER BY id DESC')->fetchAll();
$messages=$pdo->query('SELECT * FROM contact_messages ORDER BY id DESC LIMIT 20')->fetchAll();
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Dashboard</title><link rel="stylesheet" href="../assets/css/style.css"></head><body class="admin">
<main class="adm"><header class="adm-top"><h1>Dashboard</h1><span><a href="../index.php">View site</a> · <a href="logout.php">Log out</a></span></header>
<?php if($msg):?><p class="ok" role="status"><?=e($msg)?></p><?php endif;?>
<section class="stats"><?php foreach(['projects'=>'Projects','contact_messages'=>'Messages','blog_posts'=>'Blog posts','testimonials'=>'Testimonials'] as $k=>$l):?>
<div class="panel"><b><?=$counts[$k]?></b><span><?=$l?></span></div><?php endforeach;?></section>
<section class="panel"><h2><?=$edit?'Edit project':'Add project'?></h2>
<form method="post" enctype="multipart/form-data"><?=csrf_field()?>
<input type="hidden" name="action" value="<?=$edit?'edit':'add'?>"><?php if($edit):?><input type="hidden" name="id" value="<?=$edit['id']?>"><?php endif;?>
<label>Title<input name="title" required value="<?=e($edit['title']??'')?>"></label>
<label>Description<textarea name="description" rows="3" required><?=e($edit['description']??'')?></textarea></label>
<label>Category<select name="category"><?php foreach(['web','php','uiux','3d','database'] as $c):?><option value="<?=$c?>" <?=($edit['category']??'')===$c?'selected':''?>><?=$c?></option><?php endforeach;?></select></label>
<label>Technologies (comma separated)<input name="tech" value="<?=e($edit['technologies']??'')?>"></label>
<label>Live URL<input name="live_url" value="<?=e($edit['live_url']??'')?>"></label>
<label>GitHub URL<input name="github_url" value="<?=e($edit['github_url']??'')?>"></label>
<label>Image (JPG, PNG, WebP, max 2 MB)<input type="file" name="image" accept="image/jpeg,image/png,image/webp"></label>
<label class="chk"><input type="checkbox" name="featured" <?=!empty($edit['featured'])?'checked':''?>> Featured</label>
<button class="btn" type="submit"><?=$edit?'Save changes':'Add project'?></button></form></section>
<section class="panel"><h2>Projects</h2><div class="tbl"><table><tr><th>Title</th><th>Category</th><th></th></tr>
<?php foreach($projects as $p):?><tr><td><?=e($p['title'])?></td><td><?=e($p['category'])?></td><td>
<a href="?edit=<?=$p['id']?>">Edit</a>
<form method="post" class="inline" onsubmit="return confirm('Delete this project?')"><?=csrf_field()?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?=$p['id']?>"><button class="link">Delete</button></form></td></tr><?php endforeach;?></table></div></section>
<section class="panel"><h2>Messages</h2><?php if(!$messages):?><p>No messages yet.</p><?php endif;?>
<?php foreach($messages as $m):?><article class="msg"><b><?=e($m['name'])?></b> &lt;<?=e($m['email'])?>&gt; — <?=e($m['subject'])?><p><?=nl2br(e($m['message']))?></p>
<form method="post" class="inline"><?=csrf_field()?><input type="hidden" name="action" value="del_msg"><input type="hidden" name="id" value="<?=$m['id']?>"><button class="link">Delete</button></form></article><?php endforeach;?></section>
</main></body></html>
