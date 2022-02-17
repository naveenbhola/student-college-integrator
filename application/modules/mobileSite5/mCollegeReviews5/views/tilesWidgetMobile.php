
<?php 
    if(count($primaryTile)>0){
?>
    <h2 class="pop-review-title" style="padding-left:0;margin-top:10px;">Review Collections</h2>
    <?php
        foreach($primaryTile as $ptiles){
            $url = ($ptiles['url'] !='') ? $ptiles['url'] : $ptiles['seoUrl']; 
    ?>
    <section class="content-section top-gap-box" style="padding-left:0;">
        <a class="tileFinder" href="<?php echo $url;?>" style="display: block;" tileimg="<?php echo MEDIAHOSTURL.$ptiles['mImage'];?>">
            <div class="txtWthImgsBx bordRdus3px adjustHeight" style="position: relative;">
                <span class="gradient-rv"></span>
                <h2 class="gradient-hd"><?php echo ucfirst($ptiles['title']);?></h2>
            </div>
        </a>
    </section>
<?php       
        }
    }
?>
            