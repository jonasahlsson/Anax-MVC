<h1><?=$title ?> </h1>

<?php if (is_array($questions)) :?>
    <ul>
        <?php foreach ($questions as $question) :?>
            <li class='question-thumb'>
                <div class = 'question-thumb-title'>
                    <a href='<?=$this->url->create("forum/view/{$question->id}") ?>'><?=$question->title ?></a>
                </div>
                <div class="author smaller">
                    <?=$this->users->fetchGravatar($question->user_id);?>
                    <a href='<?=$this->url->create("users/id/{$question->user_id}") ?>'>
                        <?=$this->users->fetchName($question->user_id); ?>
                    </a>    
                    <?=$question->timestamp ?>
                </div>    
                <?php if (is_array($question->tags)): ?>
                <div class='question-thumb-tags'>
                    <ul>
                        <?php foreach($question->tags as $tag): ?>
                        <a href='<?=$this->url->create("forum/view-tag/{$tag->tag_id}") ?>'> #<?=$tag->tag_text ?></a>
                        <?php endforeach; ?>
                    </ul>    
                    <?php endif ?>
                </div>
                
            </li>
            
        <?php endforeach; ?>        
    </ul>
    
<?php endif;?>    
