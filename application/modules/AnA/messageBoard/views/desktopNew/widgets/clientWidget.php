    <?php
        if(is_array($links) && count($links)>1){ ?>
                 <div class="qst-l">
                   <h2>Top <?=$links['headingText']?> Colleges you should apply to in this season</h2>
                   <div class="season_col">
                        <?php foreach ($links as $link){
                            if(isset($link['heading'])){ 
                            ?>
                            <div class="srs_col">
                               <p class="clg_title"> <strong><?=$link['heading']?>:</strong> </p>
                               <p class="clg_status"><?=$link['text']?></p>
                               <p class="clck_at"> <a href="<?=$link['link']?>" target="_blank" rel="nofollow"><?=$link['linkText']?></a> </p>
                            </div>
                        <?php }
                        } ?>
                   </div>
                 </div>
    <?php } ?>

