<?php
    $instituteUrl = $instituteObj->getUrl();
?>

  <amp-lightbox id="change-location" class="" layout="nodisplay" scrollable>

         <div class="lightbox">
         <a class="cls-lightbox color-f font-w6 t-cntr" on="tap:change-location.close" role="button" tabindex="0">×</a>
            <div class="m-layer">

                     <div class="min-div color-w catg-lt">
                        <div class="f14 color-3 bck1 pad10 font-w6">
                        Select a Branch
                      </div>
                        
                         <ul class="color-6">
                            <amp-accordion disable-session-states class="border-btm1 search-loc">
                                <?php
                                    foreach ($seeAllBranchesData['loctionsWithLocality'] as $locObjects) {
                                        $firstEle = reset($locObjects);
                                        $cityId   = $firstEle->getCityId();
                                ?>
                              <section expanded class="seats-drop">
                                <h4 class="color-w f14 pad8 font-w6 color-3"><?php echo $seeAllBranchesData['cityData'][$cityId]['name'];?></h4>
                                <div class="res-col loc-layer">
                                      <?php
                                           foreach ($locObjects as $locObj) {
                                              $localityId = $locObj->getLocalityId();
                                              $cityId     = $locObj->getCityId();

                                              $locationUrl = $instituteUrl."?city=".$cityId;
                                              if($localityId)
                                                 $locationUrl.="&locality=".$localityId;
                                      ?>
                                           <li><a href="<?php echo $locationUrl;?>" class="block"><?php echo $locObj->getLocalityName();?></a></li>
                                      <?php
                                           }
                                      ?>
                                </div>
                              </section>
                              <?php } ?>
                          </amp-accordion>
                        <?php 
                               if($seeAllBranchesData['otherLocations']){

                                foreach ($seeAllBranchesData['otherLocations'] as $otherLocObj) {

                                  $localityId  = $otherLocObj->getLocalityId();
                                  $cityId      = $otherLocObj->getCityId();
                                  $locationUrl = $instituteUrl."?city=".$cityId;
                                  if($localityId)
                                     $locationUrl.="&locality=".$localityId;
                        ?>
                            <li><a href="<?php echo $locationUrl;?>" class="block"><p><?php echo $otherLocObj->getCityName();?></p></a></li>
                            <?php } } ?>
                        </ul>
                   </div>
                 </div>
         </div>
      </amp-lightbox>     
