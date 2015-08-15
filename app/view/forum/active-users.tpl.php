<article>
    <div class='top-users'>
        <h2><?=$title ?> </h2>
        <ul>    
            <?php if (is_array($askers)) :?>
            <li class='top-users'>    
                <h3>Frågvisa användare</h3>
                <ol>
                    <?php foreach ($askers as $asker) :?>
                            <li class='user-frontpage'>
                                <a href='<?=$this->url->create("users/id/{$asker->user_id}") ?>'>
                                <?=$this->users->fetchName($asker->user_id);?>
                                <br>
                                <?=$this->users->fetchGravatar($asker->user_id);?>
                                </a>
                            </li>
                    <?php endforeach; ?>        
                </ol>
            </li>
            <?php endif;?>
            <li class='top-users'>    
                <h3>Behjälpliga användare</h3>
                <ol>
                    <?php foreach ($answerers as $answerer) :?>
                        
                        <li class='user-frontpage'>
                            <a href='<?=$this->url->create("users/id/{$answerer->user_id}") ?>'>
                                <?=$this->users->fetchName($answerer->user_id);?>
                                <br>
                                <?=$this->users->fetchGravatar($answerer->user_id);?>
                            </a>
                        </li>
                        
                    <?php endforeach; ?>        
                </ol>
            </li>    
            <li class='top-users'>
                <h3>Kommentarsglada användare</h3>
                <ol>
                    <?php foreach ($commentators as $commentator) :?>
                        
                        <li class='user-frontpage'>
                            <a href='<?=$this->url->create("users/id/{$commentator->user_id}") ?>'>
                                <?=$this->users->fetchName($commentator->user_id);?>
                                <br>
                                <?=$this->users->fetchGravatar($commentator->user_id);?>
                            </a>
                        </li>
                    <?php endforeach; ?>        
                </ol>
            </li>
        </ul>
    </div>

        
</article>
