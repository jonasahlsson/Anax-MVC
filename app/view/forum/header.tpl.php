<img class='sitelogo' src='<?=$this->url->asset("img/home.png")?>' alt='Logo. A house'/>
<div class='warp-title'>
    <div><span class='sitetitle'><?=$siteTitle?></span></div>
    <div><span class='siteslogan'><?=$siteTagline?></span></div>
    <div id="user-menu">
        <nav id="login-menu">
            
            <?php if(isset($_SESSION['user']['id'])): ?>
                <a href='<?=$this->url->create("users/id/{$_SESSION['user']['id']}") ?>'>
                    <?=$this->users->fetchName($_SESSION['user']['id']) ?>
                    <?=$this->users->fetchGravatar($_SESSION['user']['id'],20) ?>
                </a>
                <a href='<?=$this->url->create("users/logout") ?>'>Logga ut</a>
            <?php else: ?>
                <a href='<?=$this->url->create("users/login") ?>'>Logga in</a>
            <?php endif; ?>    
                
        </nav>
    </div>    
</div>
