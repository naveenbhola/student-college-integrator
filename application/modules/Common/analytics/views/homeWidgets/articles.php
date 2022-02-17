<?php 
if(empty($popular_articles['articles']))
    return;
?>
<div class="row">

            <div class="anl-card clearfix">
                <div class="col-md-12">
                    <div class="row">
                        <div class="anl-crd-blk clearfix">
                            <div class="col-md-8 col-sm-12 clearfix">
                                <h2 class="pull-left">Trending Articles</h2>
                            </div>
                            <div class="col-md-4 col-sm-12"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <?php 
                            $counter = 1;
                            foreach ($popular_articles['articles'] as $key => $value) {
                        ?>
                            <div class="que-area clearfix">
                                <div class="qu-label"><span class="qu-blk"><?php echo $counter++;?>.</span></div>
                                <div><p class="qu-det"><a href="<?php echo $value['link'];?>" target="<?php echo $linkTarget;?>"><?php echo $value['name'];?></a></p></div>
                            </div>
                        <?php
                            }
                        ?>
                    </div>
                </div>
            </div>
                    
</div>