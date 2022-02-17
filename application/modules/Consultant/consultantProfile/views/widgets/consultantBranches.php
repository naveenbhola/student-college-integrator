<?php
function str_lreplace($search, $replace, $subject)
{
    $pos = strrpos($subject, $search);
    if($pos !== false)
    {
        $subject = substr_replace($subject, $replace, $pos, strlen($search));
    }
    return $subject;
}

$logoImage = str_lreplace(".", "_m.", $consultantObj->getLogo());
?>
<div class="consultant-widget">
    <div class="college-logo-sec">
        <img src="<?=$logoImage?>" width="96" height="56" alt="<?=htmlentities($consultantObj->getName())?>" title="<?=htmlentities($consultantObj->getName())?>">
    </div>
    <strong>
        <h2 class="cons-branch-title">Office Branches (<?=count($consultantObj->getConsultantLocations())?>)</h2>
        <div class="clearfix"></div>
    </strong>
    <div class="branch-detail-sec"><?php
    $totalLocations = count($locations);    
    $outerLastRecordClass = '';
    $count = 1;
    foreach($locations as $key => $locationObjArray) {
        if($totalLocations == $count) {
            $outerLastRecordClass = ' last-branch-item';
        }
        $count++;        
        
        if($headOfcCityId == $locationObjArray[0]->getCityId()) {
            $arrowClass = "branch-dwn-icon";
            $detailSectionVisibilityStyle = "";
        } else {
            $arrowClass = "branch-side-icon";
            $detailSectionVisibilityStyle = "style='display:none'";
        }
    ?>
        <div class="branch-info<?=$outerLastRecordClass?>">
            <a href="javascript:void(0);" onclick="toggleLocationSections(this)" class="branch-title"><i class="consultant-sprite <?=$arrowClass?>"></i><?=$locationObjArray[0]->getCityName()?> (<?=count($locationObjArray)?>)</a>
            <div class="branch-content-sec" <?=$detailSectionVisibilityStyle?>><?php
                $totalLocalitiesCount = count($locationObjArray);
                $lastRecordClass = "";
                foreach($locationObjArray as $innerKey => $locationObj) {
                    if($totalLocalitiesCount == ($innerKey + 1)) {
                        $lastRecordClass = 'style="border:0 none;"';
                    }
                    ?>
                    <div class="branch-contact-row" <?=$lastRecordClass?>>
                        <h3 class="place-name"><?=$locationObj->getLocalityName()?></h3>
                        <p><?=$locationObj->getLocationAddress().", ".$locationObj->getLocalityName()?></p>
                        <p><?=$locationObj->getCityName().($locationObj->getPinCode() == ""? "" : (" - ".$locationObj->getPinCode()) )?></p><?php
                        if($hasValidSubscription && $locationObj->getDisplayPRINumber() != "") {
                        ?>
                            <p>Phone. <?=$locationObj->getDisplayPRINumber(TRUE)?></p>
                        <?php  }
                        $latLongCoordinates = $locationObj->getLatLongCoordinates();  
                        if($latLongCoordinates['latitude'] != "" && $latLongCoordinates['longitude'] != "") {
                        ?>
                        <a href="http://www.google.com/maps/place/<?=$latLongCoordinates['latitude']?>,<?=$latLongCoordinates['longitude']?>" class="get-direction-btn" target="_blank"><i class="consultant-sprite location-icon"></i>View on Google Maps</a>
                        <?php
                        }
                        ?>
                    </div><?php
                }   // End of foreach($locationObjArray as $innerKey => $locationObj).
            ?>
            </div>
        </div><?
    }   // End of for($i = 0; $i < $totalLocations; $i++) .
    ?>
    </div>
</div>