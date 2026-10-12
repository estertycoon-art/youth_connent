<?php $title='Bible Quiz'; require 'header.php';
$q=$pdo->query("SELECT id,question,option_a a,option_b b,option_c c,option_d d,answer FROM games")->fetchAll(); ?>
<main class="wrap narrow"><h2>Bible quiz</h2>
<?php if(!$q): ?><p class="muted">No questions yet. An admin can add some from the dashboard.</p><?php else: ?>
<div id="quiz" data-q="<?= e(json_encode($q)) ?>"></div><?php endif; ?></main>
<?php require 'footer.php'; ?>
