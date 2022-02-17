<div class="crs-widget listingTuple" id="amenities">
        <h2 class="head-L2">Infrastructure / Facilities</h2>
        <div class="lcard">
            <div class="crs-amn-list">
                <ul>
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
                            $addClass = 'hideFacilities';
                        }
                        else
                        {
                            $addClass = '';   
                        }
                        ?>
                        <li class="<?php echo $addClass;?> viewAllFac">
                    <?php }
                    if($facilityKey != 'others') {?>
                    
                        <div class="crs-wdgt-col">
                            <a href="javascript:void(0);" class="<?php echo $FACILITY_ID_CSS_ICON_NAME_MAPPING[$facilityKey];?>"> <?php echo $facilityValue;?> </a>
                            <?php if(in_array($facilityValue,json_decode($facilities['viewFacilities']))) {?>
                                <span class="link-blue-small" ga-attr="<?=$GA_Tap_On_View_Details;?>" onclick="openFacilityViewDetailsLayer(<?php echo $listing_id;?>,'<?php echo $facilityKey;?>')">View Details</span>
                            <?php } ?>
                        </div>
                        <?php if($number_per_row == $addLi) { 
                            ?>
                            </li>
                    <?php } ?>
                    <?php  $viewedFacilities++;$addLi++;}}?>
                </ul>
             <?php if(!empty($facilities['facilities']['others']) && count($facilities['facilities']['others']) > 0) {
                if($viewedFacilities >=$facilitiesShowAtFirst )
                        {
                            $addClass = 'hideFacilities';
                        }
                        else
                        {
                            $addClass = '';   
                        }
                ?>
             <div class="oth-fac <?php echo $addClass;?> viewAllFac">
             <?php if(ceil($viewedFacilities / 2) == $numberOfRows) {
                $classAdd = 'hideFacilities';
                    }
                ?>
             <h3 class="<?php echo $classAdd;?> viewAllFac">Other Facilities:</h3>
             
                 <p class="flex-ul">
                 <?php 
                    $otherFacilityInfo = ''; 
                    $displayedRows = ceil($viewedFacilities / 2);
                    foreach ($facilities['facilities']['others'] as $otherKey => $otherValue) { 
                        if($otherKey >= ( ($numberOfRows - $displayedRows) * $otherFacilitiesMaximum))
                        {
                            $addClass = 'hideFacilities';
                        }
                        else
                        {
                            $addClass = '';   
                        }
                        ?>
                            <span class="<?php echo $addClass;?> viewAllFac"><?php echo $otherValue;?></span>
                 <?php } ?>
                </p>
                </div>
                <?php } ?>
                <?php if($viewedFacilities > $facilitiesShowAtFirst || count($facilities['facilities']['others']) > (($numberOfRows - $displayedRows) * $otherFacilitiesMaximum)) {?>
                    <a href="javascript:void(0);" class="link-blue-medium  v-more" id="more-button" ga-attr="<?=$GA_Tap_On_View_All;?>" onclick="viewAllFacilities()">View all</a>
                <?php } ?>
            </div>
        </div>
    </div>
    <script type="text/javascript">
    var viewFacilitiesArray = <?php echo $facilities['viewFacilities']?>;
    </script>