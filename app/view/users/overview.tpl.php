<h1><?=$title?></h1>

<h2>Översikt</h2>

<p><?=$content?></p>

<table>
  <tr><th>id</th><th>akronym</th><th>Namn</th><th>e-post</th><th>Status</th><th></th></tr>
  <?php foreach ($users as $user) : ?>
    <?php $status = isset($user->active) ? (isset($user->deleted) ? 'Raderad' : 'Aktiv') : (isset($user->deleted) ? 'Raderad' : 'Inaktiv');?>
    <tr>
      <td><a href='<?=$this->url->create("users/id/{$user->id}")?>'><?=$user->id?></a></td>
      <td><?=$user->acronym?></td>
      <td><?=$user->name?></td>
      <td><?=$user->email?></td>
      <td><?=$status?></td>
      <td><a href='<?=$this->url->create("users/id/{$user->id}")?>'>Redigera</a></td>
      
    </tr>
  <?php endforeach; ?>
</table>


<?php if (isset($links)) : ?>
<ul>
<?php foreach ($links as $link) : ?>
<li><a href="<?=$link['href']?>"><?=$link['text']?></a></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>
