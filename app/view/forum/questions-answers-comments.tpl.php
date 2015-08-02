<hr>

<?php if (!empty($question)) :?>
<h2><?=$question->title ?> </h2>

<p>
Fråga
    <?=$question->id ?>
    <?=$question->content ?>
    UserID = <?=$question->user_id ?>
    <?=$question->timestamp ?>
    
</p>    

    <?php if (is_array($questionComments)) : ?>
        <p>
        Frågekommentarer
        <?php foreach ($questionComments as $comment) :?>
            <?=$comment->id ?>
            <?=$comment->question_id ?>
            <?=$comment->content ?>
            <?=$comment->user_id ?>
            <?=$comment->timestamp ?>
        <?php endforeach; ?>    
        </p>
    <?php endif;?>


    <?php if (is_array($answers)) : ?>
        <p>
        Svar
        
        <?php foreach ($answers as $answer) :?>

            <?=$answer->id ?>
            <?=$answer->question_id ?>
            <?=$answer->title ?>
            <?=$answer->content ?>
            <?=$answer->user_id ?>
            <?=$answer->timestamp ?>

            Svarskommentarer
            <?php if (is_array($answerComments)) : ?>
                <?php foreach ($answerComments as $comment) :?>
                
                    <?php if ($comment->answer_id == $answer->id): ?>
                        <?=$comment->id ?>
                        <?=$comment->question_id ?>
                        <?=$comment->answer_id ?>
                        <?=$comment->content ?>
                        <?=$comment->user_id ?>
                        <?=$comment->timestamp ?>
                    <?php endif; ?>
                <?php endforeach; ?>    
            <?php endif; ?>
    
        <?php endforeach; ?>    
        </p>
    <?php endif;?>

<?php endif;?>
