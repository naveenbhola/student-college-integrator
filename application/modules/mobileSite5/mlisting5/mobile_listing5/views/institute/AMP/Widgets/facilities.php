<section>
 <div class="data-card m-btm" id="amenities">
    <h2 class="color-3 f16 heading-gap font-w6">Infrastructure / Facilities</h2>
       <div class="card-cmn color-w">
       <input type="checkbox" class="read-more-state hide" id="post-fclt">
        <ul class="facilty-ol read-more-wrap">
                <?php 
                $GA_Tap_On_View_Details = 'VIEW_DETAILS_FACILITY';
                $GA_Tap_On_View_All = 'VIEW_ALL_FACILITY';
                $viewedFacilities = 0;
                $numberOfRows = 4;
                $number_per_row = 2;
                $addLi = 0;
                $firstLi = true;
                $facilitiesShowAtFirst = 8;
                $otherFacilitiesMaximum = 3;
                global $FACILITY_ID_CSS_ICON_NAME_MAPPING;
                foreach($facilities['facilities'] as $facilityKey => $facilityValue) {
                    if($number_per_row == $addLi || $firstLi) { 
                        $firstLi = false;
                        $addLi = 0;
                        if($viewedFacilities >=$facilitiesShowAtFirst )
                        {
                            $addClass = 'read-more-target';
                        }
                        else
                        {
                            $addClass = '';   
                        }
                        ?>
                        <li class="<?php echo $addClass;?>">
                    <?php }
                    if($facilityKey != 'others') {?>
                    
                        <div class="i-block v-top icons"> <a class="library pos-rl <?php echo $FACILITY_ID_CSS_ICON_NAME_MAPPING[$facilityKey];?>"><?=$facilityValue?></a>
                            <?php if(in_array($facilityValue, json_decode($facilities['viewFacilities'])))
                                {
                                ?>
                                        <span class="block f11 color-b ga-analytic" on="tap:view-am<?=$facilityKey?>" role="button" tabindex="0" data-vars-event-name="<?=$GA_Tap_On_View_Details;?>"> View Details </span>
                                <?php
                                }
                                ?>
                        </div>
                        <?php if($number_per_row == $addLi) { 
                            ?>
                            </li>
                    <?php } ?>
                    <?php  $viewedFacilities++;$addLi++;}}?>
             <?php if(!empty($facilities['facilities']['others']) && count($facilities['facilities']['others']) > 0) {
                if($viewedFacilities >=$facilitiesShowAtFirst )
                        {
                            $classAdd = 'read-more-target';
                        }
                        else
                        {
                            $classAdd = '';   
                        }
                ?>
             <div class="oth-fac <?php echo $classAdd;?>">
             <?php if(ceil($viewedFacilities / 2) == $numberOfRows) {
                $classAdd = 'read-more-target';
                    }
                ?>

			<?php
                        if(!empty($facilities['facilities']['others'])){
                        ?>
			<div class="m-top <?php echo $classAdd;?>">
                          <h3 class="color-6 f14 m-top font-w4">Other Facilities:</h3>
                            <div class="flex-ul m-5top">
                 <?php 
                    $otherFacilityInfo = ''; 
                    $displayedRows = ceil($viewedFacilities / 2);
                    foreach ($facilities['facilities']['others'] as $otherKey => $otherValue) { 
                        if($otherKey >= ( ($numberOfRows - $displayedRows) * $otherFacilitiesMaximum))
                        {
                            $addClass = 'read-more-target';
                        }
                        else
                        {
                            $addClass = '';   
                        }
                        ?>
			    <span class="i-block color-3 f14 <?=$addClass?>"><?=$otherValue?></span>
                 <?php } ?>
		                </div>
			</div>			
			<?php } ?>	
        </div>
        <?php } ?>
        </ul>
		<?php if($viewedFacilities > $facilitiesShowAtFirst || count($facilities['facilities']['others']) > (($numberOfRows - $displayedRows) * $otherFacilitiesMaximum)) {?>
                    <label for="post-fclt" class="read-more-trigger color-b t-cntr f14 color-b block font-w6 v-arr ga-analytic" data-vars-event-name="<?=$GA_Tap_On_View_All;?>">View all</label>
                <?php } ?>

            </div>
        </div>
    </div>
</section>
<?php echo modules::run('mobile_listing5/InstituteMobile/facilityViewDetailsLayerAMP', $listing_id, json_decode($facilities['viewFacilities'])); ?>
