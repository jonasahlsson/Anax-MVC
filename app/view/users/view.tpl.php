<h1><?=$title?></h1>
 
<pre><?=print_r($user->getProperties())?></pre>

<?php if (isset($links)) : ?>
<ul>
<?php foreach ($links as $link) : ?>
<li><a href="<?=$link['href']?>"><?=$link['text']?></a></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>

 
<p><a href='<?=$this->url->create('users')?>'>Översikt</a></p>