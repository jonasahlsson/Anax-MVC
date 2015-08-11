<h2><?=$title ?> </h2>

<?php if (is_array($tags)) :?>
    <ul>
        <?php foreach ($tags as $tag) :?>
            <a href='<?=$this->url->create("forum/view-tag/{$tag->tag_id}") ?>'>
                <li class='tag-thumb'>
                    <?=$tag->tag_text ?>
                    <?=$tag->count ?>
                </li>    
            </a>    
        <?php endforeach; ?>        
    </ul>
<?php endif;?>    
