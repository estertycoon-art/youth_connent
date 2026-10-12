<?php $title='Youth Fellowship'; require 'header.php';
$events = $pdo->query("SELECT * FROM events WHERE event_date >= NOW() ORDER BY event_date LIMIT 6")->fetchAll();
$media  = $pdo->query("SELECT * FROM media ORDER BY id DESC LIMIT 12")->fetchAll(); ?>
<section class="hero"><h1>Young, loved, and sent out together.</h1>
<p>Join our youth family for worship, friendship, and growing in the Word.</p>
<a class="btn" href="quiz.php">Play the Bible quiz</a></section>

<section id="events" class="wrap"><h2>Upcoming events</h2>
<?php if (!$events): ?><p class="muted">No events scheduled yet. Check back soon.</p><?php endif; ?>
<div class="grid"><?php foreach ($events as $ev): ?>
  <article class="card">
    <?php if ($ev['image']): ?><img src="uploads/<?= e($ev['image']) ?>" alt=""><?php endif; ?>
    <h3><?= e($ev['title']) ?></h3>
    <p class="muted"><?= date('D, j M Y \a\t g:i A', strtotime($ev['event_date'])) ?> &middot; <?= e($ev['location']) ?></p>
    <p><?= nl2br(e($ev['description'])) ?></p>
  </article><?php endforeach; ?></div></section>

<section id="gallery" class="wrap"><h2>Gallery</h2>
<?php if (!$media): ?><p class="muted">Photos and videos will appear here.</p><?php endif; ?>
<div class="grid small"><?php foreach ($media as $m): ?>
  <figure class="card">
  <?php if ($m['type']==='video'): ?><video controls src="uploads/<?= e($m['file']) ?>"></video>
  <?php else: ?><img src="uploads/<?= e($m['file']) ?>" alt="<?= e($m['title']) ?>"><?php endif; ?>
  <figcaption><?= e($m['title']) ?></figcaption></figure><?php endforeach; ?></div></section>
<?php require 'footer.php'; ?>
