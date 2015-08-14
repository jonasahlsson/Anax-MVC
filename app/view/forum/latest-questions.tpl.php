<h2><?=$title ?> </h2>

<?php if (is_array($questions)) :?>
    <ul>
        <?php foreach ($questions as $question) :?>
            <li class='question-frontpage'>
                <span class='question-frontpage'><a href='<?=$this->url->create("forum/view/{$question->id}") ?>'><?=$question->title ?></a></span>
                
                    <a href='<?=$this->url->create("users/id/{$question->user_id}") ?>'>
                    <span class='author smaller'><?=$this->users->fetchName($question->user_id); ?></span>
                    </a>    
                <span class='author smaller'><?=$question->timestamp ?></span>
            </li>
        <?php endforeach; ?>        
    </ul>
<?php endif;?>    
