<?php require 'config.php'; require_admin(); $msg='';
$tab = $_GET['tab'] ?? 'home';
if ($_SERVER['REQUEST_METHOD']==='POST') { check_csrf(); $a=$_POST['a'] ?? ''; $id=(int)($_POST['id'] ?? 0);
  switch ($a) {
    case 'add_event':
      $img = upload('image', IMG);
      $pdo->prepare("INSERT INTO events(title,description,event_date,location,image) VALUES(?,?,?,?,?)")
          ->execute([$_POST['title'],$_POST['description'],$_POST['event_date'],$_POST['location'],$img]);
      $msg='Event added.'; break;
    case 'del_event': $pdo->prepare("DELETE FROM events WHERE id=?")->execute([$id]); $msg='Event deleted.'; break;
    case 'add_media':
      $f = upload('file', IMG+VID);
      if ($f) { $t = in_array(pathinfo($f,PATHINFO_EXTENSION),['mp4','webm']) ? 'video':'image';
        $pdo->prepare("INSERT INTO media(title,file,type) VALUES(?,?,?)")->execute([$_POST['title'],$f,$t]); $msg='File uploaded.'; }
      else $msg='Upload failed. Use JPG, PNG, WEBP, GIF, MP4 or WEBM under 25 MB.'; break;
    case 'del_media':
      $s=$pdo->prepare("SELECT file FROM media WHERE id=?"); $s->execute([$id]);
      if ($r=$s->fetch()) { @unlink(__DIR__.'/uploads/'.$r['file']); $pdo->prepare("DELETE FROM media WHERE id=?")->execute([$id]); }
      $msg='File deleted.'; break;
    case 'add_q':
      $pdo->prepare("INSERT INTO games(question,option_a,option_b,option_c,option_d,answer) VALUES(?,?,?,?,?,?)")
          ->execute([$_POST['question'],$_POST['a1'],$_POST['b1'],$_POST['c1'],$_POST['d1'],$_POST['answer']]);
      $msg='Question added.'; break;
    case 'del_q': $pdo->prepare("DELETE FROM games WHERE id=?")->execute([$id]); $msg='Question deleted.'; break;
    case 'set_role':
      if ($id === me()['id']) { $msg='You cannot change your own role.'; break; }
      $role = $_POST['role']==='admin' ? 'admin':'member';
      $pdo->prepare("UPDATE users SET role=? WHERE id=?")->execute([$role,$id]); $msg='Role updated.'; break;
    case 'del_user':
      if ($id === me()['id']) { $msg='You cannot delete yourself.'; break; }
      $pdo->prepare("DELETE FROM users WHERE id=?")->execute([$id]); $msg='User deleted.'; break;
  } }
$count = fn($t) => $pdo->query("SELECT COUNT(*) FROM $t")->fetchColumn();
$T = '<input type="hidden" name="t" value="'.csrf().'">';
$title='Dashboard'; ?>
<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Admin dashboard</title><link rel="stylesheet" href="assets/style.css"></head>
<body class="admin"><aside><h2>Admin</h2>
<?php foreach (['home'=>'Overview','events'=>'Events','media'=>'Media','games'=>'Bible quiz','users'=>'Users'] as $k=>$v): ?>
<a href="?tab=<?= $k ?>" class="<?= $tab===$k?'on':'' ?>"><?= $v ?></a><?php endforeach; ?>
<a href="index.php">View site</a><a href="logout.php">Log out</a></aside>
<main><?php if($msg): ?><p class="ok"><?= e($msg) ?></p><?php endif; ?>

<?php if ($tab==='home'): ?><h1>Overview</h1><div class="stats">
<div><b><?= $count('users') ?></b>Users</div><div><b><?= $count('events') ?></b>Events</div>
<div><b><?= $count('media') ?></b>Media files</div><div><b><?= $count('games') ?></b>Quiz questions</div></div>

<?php elseif ($tab==='events'): ?><h1>Events</h1>
<form method="post" enctype="multipart/form-data" class="form"><?= $T ?><input type="hidden" name="a" value="add_event">
<label>Title<input name="title" required></label>
<label>Date and time<input type="datetime-local" name="event_date" required></label>
<label>Location<input name="location"></label>
<label>Description<textarea name="description" rows="3"></textarea></label>
<label>Picture (optional)<input type="file" name="image" accept="image/*"></label>
<button class="btn">Add event</button></form>
<table><tr><th>Title</th><th>Date</th><th></th></tr>
<?php foreach ($pdo->query("SELECT * FROM events ORDER BY event_date DESC") as $r): ?>
<tr><td><?= e($r['title']) ?></td><td><?= e($r['event_date']) ?></td><td>
<form method="post"><?= $T ?><input type="hidden" name="a" value="del_event"><input type="hidden" name="id" value="<?= $r['id'] ?>">
<button class="link" data-confirm="Delete this event?">Delete</button></form></td></tr><?php endforeach; ?></table>

<?php elseif ($tab==='media'): ?><h1>Media</h1>
<form method="post" enctype="multipart/form-data" class="form"><?= $T ?><input type="hidden" name="a" value="add_media">
<label>Caption<input name="title"></label>
<label>Photo or video<input type="file" name="file" accept="image/*,video/mp4,video/webm" required></label>
<button class="btn">Upload</button></form>
<div class="grid small"><?php foreach ($pdo->query("SELECT * FROM media ORDER BY id DESC") as $m): ?>
<figure class="card"><?php if($m['type']==='video'): ?><video src="uploads/<?= e($m['file']) ?>"></video>
<?php else: ?><img src="uploads/<?= e($m['file']) ?>" alt=""><?php endif; ?>
<figcaption><?= e($m['title']) ?>
<form method="post"><?= $T ?><input type="hidden" name="a" value="del_media"><input type="hidden" name="id" value="<?= $m['id'] ?>">
<button class="link" data-confirm="Delete this file?">Delete</button></form></figcaption></figure><?php endforeach; ?></div>

<?php elseif ($tab==='games'): ?><h1>Bible quiz</h1>
<form method="post" class="form"><?= $T ?><input type="hidden" name="a" value="add_q">
<label>Question<input name="question" required></label>
<label>Option A<input name="a1" required></label><label>Option B<input name="b1" required></label>
<label>Option C<input name="c1"></label><label>Option D<input name="d1"></label>
<label>Correct answer<select name="answer"><option value="a">A</option><option value="b">B</option><option value="c">C</option><option value="d">D</option></select></label>
<button class="btn">Add question</button></form>
<table><tr><th>Question</th><th>Answer</th><th></th></tr>
<?php foreach ($pdo->query("SELECT * FROM games ORDER BY id DESC") as $r): ?>
<tr><td><?= e($r['question']) ?></td><td><?= strtoupper($r['answer']) ?></td><td>
<form method="post"><?= $T ?><input type="hidden" name="a" value="del_q"><input type="hidden" name="id" value="<?= $r['id'] ?>">
<button class="link" data-confirm="Delete this question?">Delete</button></form></td></tr><?php endforeach; ?></table>

<?php elseif ($tab==='users'): ?><h1>Users</h1>
<table><tr><th>Name</th><th>Email</th><th>Role</th><th></th></tr>
<?php foreach ($pdo->query("SELECT * FROM users ORDER BY id") as $r): ?>
<tr><td><?= e($r['name']) ?></td><td><?= e($r['email']) ?></td>
<td><form method="post" class="inline"><?= $T ?><input type="hidden" name="a" value="set_role"><input type="hidden" name="id" value="<?= $r['id'] ?>">
<select name="role" onchange="this.form.submit()" <?= $r['id']==me()['id']?'disabled':'' ?>>
<option value="member" <?= $r['role']==='member'?'selected':'' ?>>Member</option>
<option value="admin" <?= $r['role']==='admin'?'selected':'' ?>>Admin</option></select></form></td>
<td><?php if ($r['id']!=me()['id']): ?><form method="post"><?= $T ?><input type="hidden" name="a" value="del_user"><input type="hidden" name="id" value="<?= $r['id'] ?>">
<button class="link" data-confirm="Delete this user?">Delete</button></form><?php endif; ?></td></tr><?php endforeach; ?></table>
<?php endif; ?></main><script src="assets/app.js"></script></body></html>
