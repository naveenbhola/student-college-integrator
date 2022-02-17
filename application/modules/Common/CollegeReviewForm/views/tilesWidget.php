<?php
if(count($primaryTile)>0){
    foreach($primaryTile as $ptiles){
        if($currentTileId == $ptiles['tileId']){
            continue;
        }
        $url = ($ptiles['url'] !='') ? $ptiles['url'] : $ptiles['seoUrl'];
?>
<a class="tileFinder" href="<?php echo $url;?>">
    <div class="txtWthImgsBx txtWthImgsBx1 bordRdus3px mbot10px" style=" position: relative;">
    <div class="gradient-rv">
        <img class="lazy" data-original="<?php echo MEDIAHOSTURL.$ptiles['dImage'];?>" alt="<?=ucfirst($ptiles['title'])?>" />
    </div>
      <h2 class="tileText gradient-hd" style="color:#fff !important; font-size:20px;padding:10px 10px 0;"><?php echo ucfirst($ptiles['title']);?></h2>
    </div>
</a>
<?php
    }
}
?>

<?php
if(count($secondaryTile)>0){
    foreach($secondaryTile as $ptiles){
        if($currentTileId == $ptiles['tileId']){
            continue;
        }
        $url = ($ptiles['url'] !='') ? $ptiles['url'] : $ptiles['seoUrl'];
?>
<a class="tileFinder" href="<?php echo $url;?>">
    <div class="txtWthImgsBx txtWthImgsBx1 bordRdus3px mbot10px" style="position: relative;">
    <div class="gradient-rv">
        <img class="lazy" data-original="<?php echo MEDIAHOSTURL.$ptiles['dImage'];?>" alt="<?=ucfirst($ptiles['title'])?>" />
    </div>
      <h2 class="tileText gradient-hd" style="color:#fff !important; font-size:20px;padding:10px 10px 0;"><?php echo ucfirst($ptiles['title']);?></h2>
    </div>
</a>
<?php
    }
}
?>

