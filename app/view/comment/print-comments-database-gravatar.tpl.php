<hr>

<h2>Kommentarer</h2>
<p>Välkommen till kommentarssida #<?=$page?>
<?php if (is_array($comments)) : ?>
  <div class='comments'>
  <?php foreach ($comments as $comment) : ?>
    <?php if($comment->page == $page) :?>
      <h4><?=fetchGravatar($comment->mail, 40);?> <?=$comment->name?></h4>
      
      <p><?=$comment->content?></p>
      
      <p><?=date("Y M j G:i", $comment->timestamp)?></p>
      
      <p><a href='<?=$this->url->create("comment/edit/{$comment->id}")?>'> Redigera</a></p>
      <p><a href='<?=$this->url->create("comment/remove/{$comment->id}")?>'> Ta bort</a></p>
    <?php endif;?>
  <?php endforeach; ?>
  </div>
<?php endif; ?>
<p style='font-size:x-large'><a href='<?=$this->url->create("comment/index/$page/add")?>'>Skriv en egen kommentar!</a></p>
<p style='font-size:x-large'><a href='<?=$this->url->create("comment/removePage/$page")?>'>Töm flödet</a></p>
<p style='font-size:x-large'><a href='<?=$this->url->create("comment/setup")?>'>Återställ tabell för kommentarer</a></p>