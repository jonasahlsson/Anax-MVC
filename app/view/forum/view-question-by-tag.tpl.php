<h2><?=$title ?> </h2>

<?php if (is_array($questions)) :?>
    <ul>
        <?php foreach ($questions as $question) :?>
            <li>
                <h3><a href='<?=$this->url->create("forum/view/{$question->question_id}") ?>'><?=$question->title ?> </a></h3>
                <p>
                    <a href='<?=$this->url->create("forum/view/{$question->question_id}") ?>'><?=$question->content ?></a>
                </p>
                
                <div class='author'>
                    <a href='<?=$this->url->create("users/id/{$question->user_id}") ?>'><?=$this->users->fetchName($question->user_id); ?></a>
                    <?=$question->timestamp ?>
                </div>
            </li>
        <?php endforeach; ?>    
    </ul>
<?php endif;?>
