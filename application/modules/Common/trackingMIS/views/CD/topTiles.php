<!-- <h1 style='color:#edc240'><?php echo $overviewHeading[$page]['heading'];?></h1> -->
<div class="row tile_count">
    <?php 
    $topTilesRegular = $topTiles['regular'];
    $compare = $topTiles['compare'];
    $class = "font_22";
    if( ! empty($compare))
    {
        $class = "font_12";
    }
    foreach ($topTilesRegular as $key => $value) {?>
        <div class="animated flipInY col-md-2 col-sm-4 col-xs-4 tile_stats_count">
        <div class="left"></div>
        <div class="right">
            <span class="count_top"><i class="fa fa-user"></i>&nbsp<?php echo $key;?></span>
            <div class="count <?php echo $class;?>" ><?php echo $value; if(array_key_exists($key, $compare))
            {
               echo  '/'.$compare[$key];}
               ?></div>
            <!-- <span class="count_bottom"><i class="green">4% </i> From last Week</span> -->
        </div>
    
    </div>
    <?php }?>
</div>