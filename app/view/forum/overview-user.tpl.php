<h1><?=$title ?> </h1>

<?php if (is_array($users)) :?>
    <ul>
        <?php foreach ($users as $user) :?>
            <a href='<?=$this->url->create("users/id/{$user->id}") ?>'>
                <li class='question-thumb'>
                    <?=$user->name ?>
                    <br>
                    <?=$this->users->fetchGravatar($user->id);?>
                </li>
            </a>
        <?php endforeach; ?>        
    </ul>
<?php endif;?>    

