<?php
require_once __DIR__.'/../config/database.php';
if(session_status()===PHP_SESSION_NONE) session_start();
function e($s){return htmlspecialchars((string)$s,ENT_QUOTES,'UTF-8');}
function csrf_token(){ if(empty($_SESSION['csrf'])) $_SESSION['csrf']=bin2hex(random_bytes(32)); return $_SESSION['csrf']; }
function csrf_ok(){ return isset($_POST['csrf'],$_SESSION['csrf']) && hash_equals($_SESSION['csrf'],$_POST['csrf']); }
function csrf_field(){ return '<input type="hidden" name="csrf" value="'.csrf_token().'">'; }
function upload_image(string $field): ?string {
  if(empty($_FILES[$field]['name']) || $_FILES[$field]['error']!==UPLOAD_ERR_OK) return null;
  $f=$_FILES[$field]; if($f['size']>2*1024*1024) return null;
  $mime=(new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name']);
  $ok=['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'];
  if(!isset($ok[$mime])) return null;
  $name=bin2hex(random_bytes(8)).'.'.$ok[$mime];
  return move_uploaded_file($f['tmp_name'],__DIR__.'/../assets/uploads/'.$name)?$name:null;
}
