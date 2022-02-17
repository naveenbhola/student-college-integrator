<div class="hlp-popup nopadng">
    <a href="javascript:void(0);" class="hlp-rmv" data-rel= 'back'>&times;</a>
    <div class="glry-div amen-div">
        <div class="hlp-info">
            <div class="loc-list-col">
            <div class="prm-lst">
            <?php 
            $replaceHostelRoomText = array('Number of Rooms' => 'Rooms','Number of beds' => 'Beds');
            foreach($facilityInfo as $facilityKey => $facilityValue) {?>
                    <div class="amen-box" id="facility_<?php echo $facilityValue['facility_id']?>">
                        <strong><?php echo $facilityValue['facility_name'];?></strong>
                        <?php if(!empty($facilityValue['description'])) {?>
                        <p class="para-L3"><?php echo nl2br(makeURLAsHyperlink(htmlentities($facilityValue['description'])));?></p>
                        <?php } if(!empty($facilityValue['childFacility']['Mandatory Hostel']['has_facility'])){?>
                            <p class="head-8 note">note : Staying in hostel is mandatory</p>

                          <?php }
                        if(!empty($facilityValue['childFacility']) && count($facilityValue['childFacility']) > 0)
                        { ?>
                            <?php if($facilityValue['facility_name'] != 'Hostel' ) {?>
                            <span class="ament-info" id="avail-fac_<?php echo $facilityValue['facility_id'];?>">Available Facilities</span>
                            <?php } ?>
                                <ul class="flex-ul childFac-ul_<?php echo $facilityValue['facility_id'];?>">
                                        <?php 
                                    foreach ($facilityValue['childFacility'] as $childKey => $childValue) {
                                        if($childKey != 'Mandatory Hostel' && (!empty($childValue['has_facility']) || in_array($facilityKey, array('Sports Complex','Labs')))) {
                                        ?>
                                    <li>
                                       <div class="mlst">
                                        <strong class="amen-txt"><?php echo nl2br(makeURLAsHyperlink(htmlentities($childValue['facility_name'])));?></strong>
                                        
                                        <?php foreach ($childValue['additionalInfo'] as $addkey => $addValue) { 
                                                if($addkey == 0)
                                                { 
                                                    $lastElement = count($childValue['additionalInfo']);?>
                                                    <ul>

                                               <?php  }
                                            
                                                $dispayValue = '';
                                                if(!empty($addValue['name']))
                                                {
                                                    $dispayValue .= $addValue['name'];
                                                }
                                                if(in_array($addValue['name'], array('Number of Rooms','Number of beds')))
                                                {
                                                    if(!empty($dispayValue))
                                                    {
                                                        $dispayValue .= ' - ';
                                                    }

                                                    if(!empty($addValue['value']))
                                                        $dispayValue .= $addValue['value'];

                                                    
                                                    //$dispayValue .= $replaceHostelRoomText[$addValue['name']];

                                                }
                                                ?>
                                                        <li class="child-ul"><?php echo !empty($dispayValue)?nl2br(makeURLAsHyperlink(htmlentities($dispayValue))):'';?></li>
                                                        <?php 
                                                         if($addkey == $lastElement)
                                                            { ?>
                                                                </ul>
                                                           <?php  } ?>
                                                <?php }?>

                                               </div>
                                              </li>     
                                           
                                        <?php } }
                                        ?>
                                </ul>
                                                
                        <?php } ?>            
                        </div>
            
                    <?php } ?>
                    </div>
            </div>
        </div>
    </div>
</div>      
