    <?php
        if(is_array($links) && count($links)>1){ ?>
            <div class="qdp-container">
              <div class="season_col">
                <h2 class="topic_titl">TOP <?=strtoupper($links['headingText'])?> COLLEGES YOU SHOULD APPLY TO IN THIS SEASON</h2>
               <div class="list_div">
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
            </div>
    <?php } ?>

