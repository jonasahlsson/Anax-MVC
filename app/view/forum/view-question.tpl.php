
<?php if (!empty($question)) :?>
    <h2><?=$question->title ?> </h2>

    <div class='dialogue'>
        <div class='wrap-question'>
            <!--Question-->
            <div class='vote'>
                <a href='<?=$this->url->create("forum/vote/1/{$question->id}/{$this->users->isLoggedIn()}/1") . "?url=" . $this->request->getRoute() ?>' title='rösta +1'><i class="fa fa-thumbs-o-up"></i></a>
                <?= $vote->showVoteSum(1, $question->id) ?>
                <a href='<?=$this->url->create("forum/vote/1/{$question->id}/{$this->users->isLoggedIn()}/-1") . "?url=" . $this->request->getRoute() ?>' title='rösta -1'><i class="fa fa-thumbs-o-down"></i></a>
            </div>    
            <div class='question'>
                <?=$question->content ?>
                
                <?php if (is_array($tags)) : ?>
                    <ul class='tags'>
                        <?php foreach($tags as $tag): ?>
                            <li class='tag tag-thumb'>
                                <a href='<?=$this->url->create("forum/view-tag/{$tag->tag_id}") ?>'><i class="fa fa-tag"></i> <?=$tag->tag_text ?></a>
                            </li>
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
                <?php if($this->users->verifyLogin($question->id)): ?>
                    <a href='<?=$this->url->create("forum/edit-question/{$question->id}") ?>'><span class="edit-link">Redigera</span></a>
                <?php endif; ?>
            </div>    
            
            <?php if (is_array($questionComments)) : ?>
                <div class='comments'>
                    <!--Question Comments-->
                    <?php foreach ($questionComments as $comment) :?>
                        <div class='comment' id='comment-<?= $comment->id ?>'>
                            <?=$comment->content ?>
                            - 
                            <a href='<?=$this->url->create("users/id/{$comment->user_id}") ?>'>
                                <?=$this->users->fetchName($comment->user_id); ?>
                            </a>
                            <?=$comment->timestamp ?>
                            
                            <?php if($this->users->verifyLogin($comment->user_id)): ?>
                                <a href='<?=$this->url->create("forum/edit-comment/{$comment->id}") ?>'><span class="edit-link">Redigera</span></a>
                            <?php endif; ?>
                            
                            <span class="vote">
                                <a href='<?=$this->url->create("forum/vote/3/{$comment->id}/{$this->users->isLoggedIn()}/1") . "?url=" . $this->request->getRoute() . "#comment-" . $comment->id ?>' title='rösta +1'><i class="fa fa-thumbs-o-up"></i></a>
                                <?= $vote->showVoteSum(3, $comment->id) ?>
                                <a href='<?=$this->url->create("forum/vote/3/{$comment->id}/{$this->users->isLoggedIn()}/-1") . "?url=" . $this->request->getRoute() . "#comment-" . $comment->id ?>' title='rösta -1'><i class="fa fa-thumbs-o-down"></i></a>
                            </span>

                            
                        </div>
                    <?php endforeach; ?>
                    <a href='<?=$this->url->create("forum/comment/{$question->id}") ?>'><span class="edit-link">Kommentera</span></a>
                </div>
            <?php endif;?>
        </div>
        
        
        <?php if (is_array($answers)) : ?>
            <div class='answers'>
                <!--Answers-->
                <?= count($answers) ?> svar | <i class="fa fa-sort"></i> <a href='<?=$this->url->create("forum/view/{$question->id}") ?>'><span class="sort-order">ranking</span></a> <a href='<?=$this->url->create("forum/view/{$question->id}/date") ?>'><span class="sort-order">datum</span></a>
                <hr>
                <?php foreach ($answers as $answer) :?>
                
                    <div class='wrap-answer' id='answer-<?= $answer->answer_id ?>'>
                        <div class="vote">
                            <?php if ($question->accepted_answer === $answer->answer_id): ?>
                                <span title="accepterat svar"><i class="fa fa-check fa-2x"></i></span>
                            <?php elseif($this->users->verifyLogin($question->user_id)): ?>
                                    <a href='<?=$this->url->create("forum/accept-answer/{$answer->question_id}/{$answer->answer_id}") ?>'><span class="accept-link">Acceptera svar</span></a>
                            <?php endif; ?>
                            
                            <a href='<?=$this->url->create("forum/vote/2/{$answer->answer_id}/{$this->users->isLoggedIn()}/1") . "?url=" . $this->request->getRoute() . "#answer-" . $answer->answer_id ?>' title='rösta +1'><i class="fa fa-thumbs-o-up"></i></a>
                            <?= $vote->showVoteSum(2, $answer->answer_id) ?>
                            <a href='<?=$this->url->create("forum/vote/2/{$answer->answer_id}/{$this->users->isLoggedIn()}/-1") . "?url=" . $this->request->getRoute() . "#answer-" . $answer->answer_id ?>' title='rösta -1'><i class="fa fa-thumbs-o-down"></i></a>
                        </div>

                        <div class='answer'>
                            <?=$answer->content ?>
                            
                            <div class="author right smaller">
                                <?=$this->users->fetchGravatar($answer->user_id) ?>
                                <a href='<?=$this->url->create("users/id/{$answer->user_id}") ?>'>
                                    <?=$this->users->fetchName($answer->user_id); ?>
                                </a>    
                                <?=$answer->timestamp ?>
                            </div>
                            <br>
                            <?php if($this->users->verifyLogin($answer->user_id)): ?>
                                <a href='<?=$this->url->create("forum/edit-answer/{$answer->answer_id}") ?>'><span class="edit-link">Redigera</span></a>
                            <?php endif; ?>    
                        </div>
                        <!--Comments on Answer-->
                        <?php if (is_array($answerComments)) : ?>
                            <div class='comments'>
                                <?php foreach ($answerComments as $comment) :?>
                                
                                    <?php if ($comment->answer_id == $answer->answer_id): ?>
                                        <div class='comment' id='comment-<?= $comment->id ?>'>
                                            <?=$comment->content ?>
                                            - 
                                            <a href='<?=$this->url->create("users/id/{$comment->user_id}") ?>'> 
                                                <?=$this->users->fetchName($comment->user_id); ?>
                                            </a>    
                                            <?=$comment->timestamp ?>
                                            
                                            <span class="vote">
                                                <a href='<?=$this->url->create("forum/vote/3/{$comment->id}/{$this->users->isLoggedIn()}/1") . "?url=" . $this->request->getRoute() . "#comment-" . $comment->id ?>' title='rösta +1'><i class="fa fa-thumbs-o-up"></i></a>
                                                <?= $vote->showVoteSum(3, $comment->id) ?>
                                                <a href='<?=$this->url->create("forum/vote/3/{$comment->id}/{$this->users->isLoggedIn()}/-1") . "?url=" . $this->request->getRoute() . "#comment-" . $comment->id ?>' title='rösta -1'><i class="fa fa-thumbs-o-down"></i></a>
                                            </span>

                                            
                                            <?php if($this->users->verifyLogin($comment->user_id)): ?>
                                                <a href='<?=$this->url->create("forum/edit-comment/{$comment->id}") ?>'><span class="edit-link">Redigera</span></a>
                                            <?php endif; ?>
                                        </div>    
                                    <?php endif; ?>
                                <?php endforeach; ?>  
                            <a href='<?=$this->url->create("forum/comment/{$answer->question_id}/{$answer->answer_id}") ?>'><span class="edit-link">Kommentera</span></a>
                            </div>
                        <?php endif; ?>
                    </div>
                <hr>
                <?php endforeach; ?>    
            </div>
            <a href='<?=$this->url->create("forum/answer/{$question->id}") ?>'>BESVARA FRÅGAN</a>
        <?php endif;?>
    </div>    
<?php endif;?>
