<?php $title='Log in'; require 'header.php'; $err='';
if ($_SERVER['REQUEST_METHOD']==='POST') { check_csrf();
  $x=$pdo->prepare("SELECT * FROM users WHERE email=?"); $x->execute([strtolower(trim($_POST['email']))]);
  $row=$x->fetch();
  if ($row && password_verify($_POST['password'],$row['password'])) {
    session_regenerate_id(true);
    $_SESSION['user']=['id'=>$row['id'],'name'=>$row['name'],'role'=>$row['role']];
    header('Location: '.($row['role']==='admin'?'admin.php':'index.php')); exit;
  } $err='Email or password is incorrect.'; } ?>
<main class="wrap narrow"><h2>Log in</h2>
<?php if(isset($_GET['new'])): ?><p class="ok">Account created. Log in below.</p><?php endif; ?>
<?php if($err): ?><p class="alert"><?= e($err) ?></p><?php endif; ?>
<form method="post" class="form"><input type="hidden" name="t" value="<?= csrf() ?>">
<label>Email<input type="email" name="email" required></label>
<label>Password<input type="password" name="password" required></label>
<button class="btn">Log in</button></form></main>
<?php require 'footer.php'; ?>
