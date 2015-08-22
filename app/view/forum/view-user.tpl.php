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
        Profil: <?=$user->profile ?>
        <br>
        <?php if($this->users->verifyLogin($user->id)): ?>
            <a href='<?=$this->url->create("users/edit/{$user->id}")?>'><span class='edit-link'>Redigera</span></a>
        <?php endif; ?>
    </div>
    <hr>
    <div class='user-contributions'>
        <h2>Forumaktivitet</h2>
        <div class='user-score'>
            <i class="fa fa-trophy"></i> <?=$this->ForumController->userScore($user->id) ?> Rankingpoäng
        </div>
        <hr>
        <?php if (!empty($questions)): ?>
        <div class='user-questions'>
            <h3>Frågor</h3>
                <ul class="fa-ul">
                    <?php foreach($questions as $question): ?>
                        <li>
                            <a href='<?=$this->url->create("forum/view/{$question->id}") ?>'>
                                <i class="fa-li fa fa-question"></i><?= $question->title; ?>
                            </a>        
                        </li>
                    <?php endforeach; ?>
                </ul>
        </div>        
        <hr>
        <?php endif; ?>
        
        <?php if (!empty($answers)): ?>
        <div class='user-answers'>
            <h3>Svar</h3>
                <ul class="fa-ul">
                    <?php foreach($answers as $answer): ?>
                    <li>
                        <a href='<?=$this->url->create("forum/view/{$answer->question_id}/#answer-{$answer->id}") ?>'>
                            <i class="fa-li fa fa-exclamation"></i> <?= trim_text($answer->content, 149); ?>
                        </a>    
                    </li>
                    <?php endforeach; ?>
                </ul>
        </div>        
        <hr>
        <?php endif; ?>
        
        <?php if (!empty($comments)): ?>
        <div class='user-comments'>
            <h3>Kommentarer</h3>
                <ul class="fa-ul">
                    <?php foreach($comments as $comment): ?>
                    <li>
                        <a href='<?=$this->url->create("forum/view/{$comment->question_id}/#comment-{$comment->id}") ?>'>
                            <i class="fa-li fa fa-commenting"></i> <?= trim_text($comment->content, 149); ?>
                        </a>
                    </li>    
                    <?php endforeach; ?>
                </ul>
        </div>       
        <hr>
        <?php endif; ?>
        
        <?php if (!empty($voteCount[0]->sum)): ?>
            <div class='user-votes'>
                <h3>Lagda röster</h3>
                <ul class="fa-ul">
                    <li><i class="fa-li fa fa-thumbs-o-up"></i> <?=$voteCount[0]->pos_votes ?></li>
                    <li><i class="fa-li fa fa-thumbs-o-down"></i> <?=$voteCount[0]->neg_votes ?></li>
                </ul>
            </div>       
            <hr>
        <?php endif; ?>    
    
    </div>
    
    
<?php endif;?> 

<p><a href='<?=$this->url->create('users')?>'>VISA ALLA ANVÄNDARE</a></p> 
