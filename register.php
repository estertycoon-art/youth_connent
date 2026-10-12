<?php $title='Join us'; require 'header.php'; $err='';
if ($_SERVER['REQUEST_METHOD']==='POST') { check_csrf();
  $n=trim($_POST['name']); $em=strtolower(trim($_POST['email'])); $p=$_POST['password'];
  if (!$n || !filter_var($em, FILTER_VALIDATE_EMAIL) || strlen($p)<8) $err='Enter your name, a valid email, and a password of 8+ characters.';
  else {
    $x=$pdo->prepare("SELECT id FROM users WHERE email=?"); $x->execute([$em]);
    if ($x->fetch()) $err='That email is already registered.';
    else {
      // The very first account becomes the admin; everyone after is a member.
      $role = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn()==0 ? 'admin' : 'member';
      $pdo->prepare("INSERT INTO users(name,email,password,role) VALUES(?,?,?,?)")
          ->execute([$n,$em,password_hash($p,PASSWORD_DEFAULT),$role]);
      header('Location: login.php?new=1'); exit;
    } } } ?>
<main class="wrap narrow"><h2>Create your account</h2>
<?php if($err): ?><p class="alert"><?= e($err) ?></p><?php endif; ?>
<form method="post" class="form"><input type="hidden" name="t" value="<?= csrf() ?>">
<label>Full name<input name="name" required></label>
<label>Email<input type="email" name="email" required></label>
<label>Password<input type="password" name="password" minlength="8" required></label>
<button class="btn">Create account</button></form></main>
<?php require 'footer.php'; ?>
