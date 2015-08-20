<h1><?=$title ?> </h1>

<?php if (is_array($questions)) :?>
    <ul>
        <?php foreach ($questions as $question) :?>
            <li class='question-thumb'>
                <div class='vote'>
                    <?= $vote->showVoteSum(1, $question->id) ?>
                    Röster
                    <?= $question->answerCount->count_answer ?>
                    Svar
                </div>
                <div class = 'question-thumb-title'>
                    <a href='<?=$this->url->create("forum/view/{$question->id}") ?>'> <h3><?=$question->title ?> </h3> </a>
                    <a href='<?=$this->url->create("forum/view/{$question->id}") ?>'><?=trim_text($question->content, 149) ?></a>
                </div>
                <div class="author smaller right">
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
                            <li class='tag tag-thumb'>
                                <a href='<?=$this->url->create("forum/view-tag/{$tag->tag_id}") ?>'><i class="fa fa-tag"></i> <?=$tag->tag_text ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>    
                    <?php endif ?>
                </div>
                
            </li>
            
        <?php endforeach; ?>        
    </ul>
    
<?php endif;?>    
