<h2><?=$title ?> </h2>

<?php if (is_array($questions)) :?>
    <ul>
        <?php foreach ($questions as $question) :?>
            <li class='question-thumb'>
                <?=$question->title ?>
                <?=$question->content ?>
                <br>
                <?=$this->users->fetchGravatar($question->user_id);?>
                <?=$this->users->fetchName($question->user_id); ?>
                <?=$question->timestamp ?>
            </li>    
        <?php endforeach; ?>        
    </ul>
<?php endif;?>    
