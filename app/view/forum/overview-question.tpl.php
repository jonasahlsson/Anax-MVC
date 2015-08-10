<h2><?=$title ?> </h2>

<?php if (is_array($questions)) :?>
    <ul>
        <?php foreach ($questions as $question) :?>
            <li class='question-thumb'>
                <h3><a href='<?=$this->url->create("forum/view/{$question->id}") ?>'>  <?=$question->title ?></a></h3>
                <a href='<?=$this->url->create("forum/view/{$question->id}") ?>'>    
                    <?=$question->content ?>
                </a>    
                <br>
                <?=$this->users->fetchGravatar($question->user_id);?>
                <a href='<?=$this->url->create("users/id/{$question->user_id}") ?>'>
                    <?=$this->users->fetchName($question->user_id); ?>
                </a>    
                <?=$question->timestamp ?>
            </li>
            <hr>
        <?php endforeach; ?>        
    </ul>
<?php endif;?>    
