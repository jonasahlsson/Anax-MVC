<h2><?=$title ?> </h2>

<?php if (is_array($tags)) :?>
    <ul>
        <?php foreach ($tags as $tag) :?>
            
            <li class='tag-thumb'>
                <a href='<?=$this->url->create("forum/view-tag/{$tag->tag_id}") ?>'>
                    <?=$tag->tag_text ?>
                    <?=$tag->count ?>
                </a>    
            </li>    
        <?php endforeach; ?>        
    </ul>
<?php endif;?>    
