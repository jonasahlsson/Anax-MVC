<h2><?=$title ?> </h2>

<?php if (is_array($questions)) :?>
    <ul>
        <?php foreach ($questions as $question) :?>
            <li>
                <h3><?=$question->title ?> </h3>
                <p>
                    <?=$question->content ?>
                </p>
                
                <p>
                <?=$this->users->fetchName($question->user_id); ?>
                <?=$question->timestamp ?>
                </p>
            </li>
        <?php endforeach; ?>    
    </ul>
<?php endif;?>
