<?php
// Edit these for your XAMPP setup
const DB_HOST='localhost'; const DB_NAME='portfolio_db'; const DB_USER='root'; const DB_PASS='';
function db(): PDO {
  static $pdo=null;
  if(!$pdo){ $pdo=new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4',DB_USER,DB_PASS,
    [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]); }
  return $pdo;
}
