<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=church_youth;charset=utf8mb4', 'root', '', [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);
function e($s){ return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }
function me(){ return $_SESSION['user'] ?? null; }
function require_admin(){ if (!me() || me()['role'] !== 'admin') { header('Location: login.php'); exit; } }
function csrf(){ return $_SESSION['t'] ?? ($_SESSION['t'] = bin2hex(random_bytes(16))); }
function check_csrf(){ if (!hash_equals($_SESSION['t'] ?? '', $_POST['t'] ?? '')) die('Invalid request'); }
// Safe upload: checks real MIME type, size, and uses a random file name.
function upload($field, $types){
  $f = $_FILES[$field] ?? null;
  if (!$f || $f['error'] !== UPLOAD_ERR_OK || $f['size'] > 25*1024*1024) return null;
  $mime = (new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name']);
  if (!isset($types[$mime])) return null;
  $name = bin2hex(random_bytes(8)).'.'.$types[$mime];
  return move_uploaded_file($f['tmp_name'], __DIR__.'/uploads/'.$name) ? $name : null;
}
const IMG = ['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp','image/gif'=>'gif'];
const VID = ['video/mp4'=>'mp4','video/webm'=>'webm'];
