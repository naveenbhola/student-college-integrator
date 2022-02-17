<?php
    $instituteUrl = $instituteObj->getUrl();
?>
<div class="popup_layer lyr1" id="location-layer" style="display:none;">
        <div class="hlp-popup nopadng">
            <a href="javascript:void(0);" class="hlp-rmv" data-rel ='back' >&times;</a>
            <div class="glry-div amen-box">
                <div class="hlp-info">
                                
                <div class="cont loc-layer">
                    <div class="hed">
                        Select a Branch
                    </div>
                    <div class="loc-list-col">
                    <ul class="prm-lst">
                    <?php
                        foreach ($seeAllBranchesData['loctionsWithLocality'] as $locObjects) {
                            $firstEle = reset($locObjects);
                            $cityId   = $firstEle->getCityId();
                    ?>
                        <li>
                            <p class="tgl-lnk">
                                <?php echo $seeAllBranchesData['cityData'][$cityId]['name'];?>
                                <i class="arw-icn active"></i>
                            </p>
                            <ul class="scn-lst" style="display: block;">
                            <?php
                                 foreach ($locObjects as $locObj) {
                                    $localityId = $locObj->getLocalityId();
                                    $cityId     = $locObj->getCityId();

                                    $locationUrl = $instituteUrl."?city=".$cityId;
                                    if($localityId)
                                       $locationUrl.="&locality=".$localityId;
                            ?>
                                 <li><a href="<?php echo $locationUrl;?>"><?php echo $locObj->getLocalityName();?></a></li>
                            <?php
                                 }
                            ?>
                            </ul>
                        </li>
                    <?php
                        }
                        if($seeAllBranchesData['otherLocations']){

                            foreach ($seeAllBranchesData['otherLocations'] as $otherLocObj) {

                              $localityId  = $otherLocObj->getLocalityId();
                              $cityId      = $otherLocObj->getCityId();
                              $locationUrl = $instituteUrl."?city=".$cityId;
                              if($localityId)
                                 $locationUrl.="&locality=".$localityId;
                    ?>
                        <li><a href="<?php echo $locationUrl;?>"><p><?php echo $otherLocObj->getCityName();?></p></a></li>
                    <?php
                            }
                        }
                    ?>
                    </ul>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>