
<?php if (!empty($question)) :?>
    <h2><?=$question->title ?> </h2>

    <div class='dialogue'>
        <div class='wrap-question'>
            <!--Question-->
            <div class='question'>
                <p><?=$question->content ?></p>
                
                <?php if (is_array($tags)) : ?>
                    <ul class='tags'>
                        <?php foreach($tags as $tag): ?>
                            <a href='<?=$this->url->create("forum/view-tag/{$tag->tag_id}") ?>'>
                            <li class='tag'><?=$tag->tag_text ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
                <div class="author right smaller">
                <?=$this->users->fetchGravatar($question->user_id);?>
                <a href='<?=$this->url->create("users/id/{$question->user_id}") ?>'>
                    <?=$this->users->fetchName($question->user_id); ?>
                </a>
                <?=$question->timestamp ?>
                </div>
                <br>
                <a href='<?=$this->url->create("forum/edit-question/{$question->id}") ?>'><span class="edit-link">Redigera</span></a>
            </div>    
            
            <?php if (is_array($questionComments)) : ?>
                <div class='comments'>
                    <!--Question Comments-->
                    <?php foreach ($questionComments as $comment) :?>
                        <div class='comment'>
                            <?=$comment->content ?>
                            - 
                            <a href='<?=$this->url->create("users/id/{$comment->user_id}") ?>'>
                                <?=$this->users->fetchName($comment->user_id); ?>
                            </a>
                            <?=$comment->timestamp ?>
                            <a href='<?=$this->url->create("forum/edit-comment/{$comment->id}") ?>'><span class="edit-link">Redigera</span></a>
                        </div>
                    <?php endforeach; ?>
                    <a href='<?=$this->url->create("forum/comment/{$question->id}") ?>'><span class="edit-link">Kommentera</span></a>
                </div>
            <?php endif;?>
        </div>
        
        
        <?php if (is_array($answers)) : ?>
            <div class='answers'>
                <!--Answers-->
                <?php foreach ($answers as $answer) :?>
                <hr>
                    <div class='wrap-answer'>
                        <div class='answer'>
                            <p><?=$answer->content ?></p>
                            
                            <div class="author right smaller">
                                <?=$this->users->fetchGravatar($answer->user_id) ?>
                                <a href='<?=$this->url->create("users/id/{$answer->user_id}") ?>'>
                                    <?=$this->users->fetchName($answer->user_id); ?>
                                </a>    
                                <?=$answer->timestamp ?>
                            </div>
                            <br>
                            <a href='<?=$this->url->create("forum/edit-answer/{$answer->id}") ?>'><span class="edit-link">Redigera</span></a>
                        </div>
                        <!--Comments on Answer-->
                        <?php if (is_array($answerComments)) : ?>
                            <div class='comments'>
                                <?php foreach ($answerComments as $comment) :?>
                                
                                    <?php if ($comment->answer_id == $answer->id): ?>
                                        <div class='comment'>
                                            <?=$comment->content ?>
                                            - 
                                            <a href='<?=$this->url->create("users/id/{$comment->user_id}") ?>'> 
                                                <?=$this->users->fetchName($comment->user_id); ?>
                                            </a>    
                                            <?=$comment->timestamp ?>
                                            <a href='<?=$this->url->create("forum/edit-comment/{$comment->id}") ?>'><span class="edit-link">Redigera</span></a>
                                        </div>    
                                    <?php endif; ?>
                                <?php endforeach; ?>  
                            <a href='<?=$this->url->create("forum/comment/{$answer->question_id}/{$answer->id}") ?>'><span class="edit-link">Kommentera</span></a>
                            </div>
                        <?php endif; ?>
                    </div>    
                <?php endforeach; ?>    
            </div>
            <a href='<?=$this->url->create("forum/answer/{$question->id}") ?>'>BESVARA FRÅGAN</a>
        <?php endif;?>
    </div>    
<?php endif;?>
