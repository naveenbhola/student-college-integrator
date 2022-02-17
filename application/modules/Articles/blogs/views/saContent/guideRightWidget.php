
<?php
if($content['data']['type']!='guide' && isset($content['data']['countryId']) && $content['data']['countryId']>0 && isset($content['data']['guideURL']) && $content['data']['guideURL']!=''){ ?>
<div class="process-box clearwidth" onClick="window.location='<?=$content['data']['guideURL']?>';" style="cursor:pointer;">
    <?php
        $countryName = "";
	$countryName = $content['data']['countryName'];
        if(( $file = file_get_contents( IMGURL_SECURE."/public/images/maps/country-".$content['data']['countryId'].".gif"))){
            $src = IMGURL_SECURE."/public/images/maps/country-".$content['data']['countryId'].".gif";
        }else{
            $src = IMGURL_SECURE."/public/images/maps/country-default.gif";
        }
    ?>
    <h3>Student Guide <?php if($countryName){echo "for ".$countryName;}?></h3>
    <img src="<?=$src?>" alt="map" align="center" />
    <div class="details clearwidth" style="word-wrap: break-word;">
        <p><?=$content['data']['guideSummary']?></p>
        <a href="<?=$content['data']['guideURL']?>" class="flRt">More Info &gt;</a>
    </div>
</div>
<?php } ?>

