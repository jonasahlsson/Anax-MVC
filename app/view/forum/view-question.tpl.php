
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
                            <li class='tag'><?=$tag->tag_text ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
                
                <?=$this->users->fetchGravatar($question->user_id);?>
                <?=$this->users->fetchName($question->user_id); ?>
                <?=$question->timestamp ?>
            </div>    
            
            <?php if (is_array($questionComments)) : ?>
                <div class='comments'>
                    <!--Question Comments-->
                    <?php foreach ($questionComments as $comment) :?>
                        <div class='comment'>
                            <?=$comment->content ?>
                            - <?=$this->users->fetchName($comment->user_id); ?>
                            <?=$comment->timestamp ?>
                        </div>
                    <?php endforeach; ?>    
                </div>
            <?php endif;?>
        </div>
        
        
        <?php if (is_array($answers)) : ?>
            
            <div class='answers'>
                <!--Answers-->
                <?php foreach ($answers as $answer) :?>
                    <div class='wrap-answer'>
                        <div class='answer'>
                            <p><?=$answer->content ?></p>
                            <?=$this->users->fetchGravatar($answer->user_id) ?>
                            <?=$this->users->fetchName($answer->user_id); ?>
                            <?=$answer->timestamp ?>
                        </div>
                        <!--Comments on Answer-->
                        <?php if (is_array($answerComments)) : ?>
                            <div class='comments'>
                                <?php foreach ($answerComments as $comment) :?>
                                
                                    <?php if ($comment->answer_id == $answer->id): ?>
                                        <div class='comment'>
                                            <?=$comment->content ?>
                                            - <?=$this->users->fetchName($comment->user_id); ?>
                                            <?=$comment->timestamp ?>
                                        </div>    
                                    <?php endif; ?>
                                <?php endforeach; ?>  
                            </div>
                        <?php endif; ?>
                    </div>    
                <?php endforeach; ?>    
            </div>
        <?php endif;?>
    </div>    
<?php endif;?>
