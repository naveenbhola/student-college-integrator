<div class="process-box clearwidth">
    <?php
        $countryName = $countryName = reset($universityObj->getLocations())->getCountry()->getName();
        if(( $file = file_get_contents( SHIKSHA_HOME."/public/images/maps/country-".reset($universityObj->getLocations())->getCountry()->getId().".gif"))){
            $src = SHIKSHA_HOME."/public/images/maps/country-".reset($universityObj->getLocations())->getCountry()->getId().".gif";
        }else{
            $src = SHIKSHA_HOME."/public/images/maps/country-"."default".".gif";
        }
    ?>
    <h2 class="font-14">Student Guide <?php if($countryName){echo "for ".$countryName;}?></h2>
    <?php   $contentUrl = "";
            if($visaGuide['contentURL'] != '')
            {
                if(0 === strpos($visaGuide['contentURL'],'http')){
                    $contentUrl = $visaGuide['contentURL'];
                }else{
                    $contentUrl = "http://".$visaGuide['contentURL'];
                }
            }else{
                $contentUrl = "javascript:void(0)";
            }
    ?>
    <a target="_blank" href="<?=$contentUrl?>" onclick="studyAbroadTrackEventByGA('ABROAD_<?=strtoupper($listingType)?>_PAGE', 'rightColumn', 'visaGuideMap');"><img src="<?=$src?>" alt="map" align="center" /></a>
    <div class="details dyanamic-content clearwidth" style="word-wrap: break-word;">
        <p><?=$visaGuide['summary']?></p>
        <a target="_blank" href="<?=$contentUrl?>" class="flRt">More Info &gt;</a>   
    </div>    
</div>