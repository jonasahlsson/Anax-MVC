<hr>

<?php if (!empty($question)) :?>
<h2><?=$question->title ?> </h2>

<div class='dialogue'>
    <div class='wrap-question'>
        <!--Question-->
        <div class='question'>
            <p><?=$question->content ?></p>
            
            <?=$this->users->fetchGravatar($question->user_id);?>
            UserID: <?=$question->user_id ?>
            Timestamp: <?=$question->timestamp ?>
            QuestionID: <?=$question->id ?>
        </div>    
        
        <?php if (is_array($questionComments)) : ?>
            <div class='comments'>
                <!--Question Comments-->
                <?php foreach ($questionComments as $comment) :?>
                    <div class='comment'>
                        <?=$comment->content ?>
                        QuestionId: <?=$comment->question_id ?>                        
                        UserID: <?=$comment->user_id ?>
                        Timestamp: <?=$comment->timestamp ?>
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
                        QuestionID: <?=$answer->question_id ?>
                        <?=$this->users->fetchGravatar($answer->user_id) ?>
                        UserID: <?=$answer->user_id ?>
                        Timestamp: <?=$answer->timestamp ?>
                    </div>
                    <!--Comments on Answer-->
                    <?php if (is_array($answerComments)) : ?>
                        <div class='comments'>
                            <?php foreach ($answerComments as $comment) :?>
                            
                                <?php if ($comment->answer_id == $answer->id): ?>
                                    <div class='comment'>
                                        <?=$comment->content ?>
                                        QuestionID: <?=$comment->question_id ?>
                                        AnswerID: <?=$comment->answer_id ?>
                                        UserID: <?=$comment->user_id ?>
                                        Timestamp: <?=$comment->timestamp ?>
                                    </div>    
                                <?php endif; ?>
                            <?php endforeach; ?>  
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>    
                </div>
            </div>
        </div>
    <?php endif;?>
</div>    
<?php endif;?>
