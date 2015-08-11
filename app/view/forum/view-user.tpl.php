<h2><?=$title?></h2>
 
<?php if (isset($user)) :?>
    <div class='user-gravatar'>
        <?=$this->users->fetchGravatar($user->id, 120);?>    
    </div>    
    <div>
        Användarnamn: <?=$user->acronym ?>
        <br>
        Namn: <?=$user->name ?>
        <br>
        <a href='<?=$this->url->create("users/edit/{$user->id}")?>'><span class='edit-link'>Redigera</span></a>
    </div>
    <hr>
    <div class='user-contributions'>
        <?php if (!empty($questions) OR !empty($answers) OR !empty($comments)): ?>
            <h2>Forumaktivitet</h2>
        <?php endif; ?>
        <?php if (!empty($questions)): ?>
        <div class='user-questions'>
            <h3>Frågor</h3>
                <ul>
                    <?php foreach($questions as $question): ?>
                        <a href='<?=$this->url->create("forum/view/{$question->id}") ?>'>
                            <li>
                                <?= $question->title; ?>
                            </li>    
                        </a>    
                    <?php endforeach; ?>
                </ul>
        </div>        
        <hr>
        <?php endif; ?>
        
        <?php if (!empty($answers)): ?>
        <div class='user-answers'>
            <h3>Svar</h3>
                <ul>
                    <?php foreach($answers as $answer): ?>
                    <a href='<?=$this->url->create("forum/view/{$answer->question_id}") ?>'>
                        <li>
                            <?= $answer->content; ?>
                        </li>    
                    </a>    
                    <?php endforeach; ?>
                </ul>
        </div>        
        <hr>
        <?php endif; ?>
        
        <?php if (!empty($comments)): ?>
        <div class='user-comments'>
            <h3>Kommentarer</h3>
                <ul>
                    <?php foreach($comments as $comment): ?>
                    <a href='<?=$this->url->create("forum/view/{$comment->question_id}") ?>'>
                        <li>
                            <?= $comment->content; ?>
                        </li>    
                    </a>    
                    <?php endforeach; ?>
                </ul>
        </div>       
        <hr>
        <?php endif; ?>
            
    
    </div>
    
    
<?php endif;?> 

<p><a href='<?=$this->url->create('users')?>'>VISA ALLA ANVÄNDARE</a></p> 
