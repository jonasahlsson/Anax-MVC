<hr>

<h2>Kommentarer</h2>
<p>Välkommen till kommentarssida #<?=$page?>
<?php if (is_array($comments)) : ?>
<div class='comments'>
<?php foreach ($comments as $id => $comment) : ?>
<h4><?=$comment['name']?></h4>

<p><?=$comment['content']?></p>

<p><?=date("Y M j G:i", $comment['timestamp'])?></p>

<p><a href='<?=$this->url->create("comment/edit?page=$page&commentKey=$id")?>'> Redigera</a></p>

<?php endforeach; ?>
</div>
<?php endif; ?>