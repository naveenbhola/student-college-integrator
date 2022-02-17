<?php 
    $locationName = $currentLocationObj->getCityName();
    if($currentLocationObj->getLocalityName()){
        $locationName = $currentLocationObj->getLocalityName().", ".$locationName;
    }
    $topCardData = array_chunk($topCardData['instituteImportantData'], 2,true);
    $GA_Tap_On_CTA = 'DBROCHURE';
    if($pageType == 'scholarships'){ 
        $dBrochureStickyTrackingKeyId = 1559;
    }
    else  {
        $dBrochureStickyTrackingKeyId = 1084;
    }
    $currentPageType = 'allContentPage';
    $subText = ($pageType == 'scholarships') ? ' - Scholarships' : $totalCountSubtext;
?>

<div class="gap">
    <div class="lcard clg-panel">
        <div class="clg-panel-head">
            <h1 class="head-L1"><a href="<?=$instituteUrl;?>"><?php echo htmlentities($instituteNameWithLocation);?> <?php echo $subText; ?></a></h1>
            <p class="para-L3"><i class="clg-sprite crs-loc"></i><?php echo $locationName;?></p>
        </div>
        <div class="clg-detail">
             <ul>
            <?php
                foreach ($topCardData as $widgetData) {
                    
           echo "<li>";
                    foreach ($widgetData as $key=>$value) {
            ?>
                    <div class="clg-col">
                        <span><?php echo $value;?></span>
                        <?php
                             if(array_key_exists($key, $instituteToolTipData))
                                echo ' <a><i class="clg-sprite clg-inf" onclick=\'openHelpTextLayer("help_'.$key.'");\'></i></a>';
                        ?>
                    </div>
                <?php    
                    }
                    echo "</li>";
                }
            ?>
            </ul>
        </div>
        <a class="btn primary" ga-attr="<?=$GA_Tap_On_CTA;?>" cta-type="<?php echo CTA_TYPE_EBROCHURE;?>" onclick="downloadCourseBrochure('<?php echo $listing_id?>','<?php echo $dBrochureStickyTrackingKeyId;?>',{'pageType':'<?php echo $pageType;?>','listing_type':'<?php echo $listing_type;?>','callbackFunctionParams':{'pageType':'<?php echo $currentPageType;?>','thisObj':this}});">Request Brochure</a>
        <?php if(!empty($seoHeadingText)) {     ?>
        <div class="addtnl-col">
         <!--  <p class="data-heading">Sample Text</p> -->
          <div>
                <h2 class="txt-later"> <?php echo $seoHeadingText; ?> </h2>
             </h2>
          </div>
       </div>
    <?php } ?>
    </div>
</div>
 <input type="hidden" name="pageType" id="pageType" value="<?php echo $pageType;?>">

