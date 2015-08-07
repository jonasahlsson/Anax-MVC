<h2><?=$title ?> </h2>

<?php if (is_array($tags)) :?>
    <ul>
        <?php foreach ($tags as $tag) :?>
            <li class='tag-thumb'>
                <?=$tag->tag_text ?>
                <?=$tag->count ?>
            </li>    
        <?php endforeach; ?>        
    </ul>
<?php endif;?>    
