<?php require_once 'config.php'; $u = me(); ?>
<!DOCTYPE html><html lang="en"><head><meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= e($title ?? 'Youth Fellowship') ?></title>
<link rel="stylesheet" href="assets/style.css"></head><body>
<header class="top"><a class="logo" href="index.php">Youth Fellowship</a>
<nav><a href="index.php#events">Events</a><a href="index.php#gallery">Gallery</a><a href="quiz.php">Bible Quiz</a>
<?php if ($u): ?>
  <?php if ($u['role']==='admin'): ?><a href="admin.php">Dashboard</a><?php endif; ?>
  <a href="logout.php">Log out (<?= e($u['name']) ?>)</a>
<?php else: ?><a href="login.php">Log in</a><a class="btn" href="register.php">Join us</a><?php endif; ?>
</nav></header>
