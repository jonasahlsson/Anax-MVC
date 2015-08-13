<h1><?=$title ?> </h1>

<?php if (is_array($users)) :?>
    <ul>
        <?php foreach ($users as $user) :?>
            <li class='user-thumb'>
                <a href='<?=$this->url->create("users/id/{$user->id}") ?>'>
                    <?=$this->users->fetchGravatar($user->id);?>
                    <?=$user->name ?>
                </a>
            </li>
            
        <?php endforeach; ?>        
    </ul>
<?php endif;?>    

