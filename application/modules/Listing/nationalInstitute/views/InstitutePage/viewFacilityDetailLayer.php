
        <div class="group-card pop-div height-cs">
            <a class="cls-head" id="cl-s" onclick="unBindArrowKeysOnLayer();closeFacilityLayer();"><i class="close"></i></a>
            <div class="caraousal-slider infra-layr">
            <h1 class="head-1 other-f"><?php echo "Infrastructure/Facilities"?></h1>
             <div class="scroller__div">
                <ul class="infra-slider" id="ul-slide">
                    <?php foreach ($facilityInfo as $facilityKey => $facilityValue) { 
                        if($facilityValue['has_facility'] !== 0) {?>
                    <li id="facility_<?php echo $facilityValue['facility_id'];?>">
                        <div id="infra-layr-id">      
                            <div class="facilityTinyBar" id="facilityTinyBar">
                            <i class="infra__img <?php echo $facilityValue['facility_name'];?>"></i>
                                    <div class="infra__txt">
                                      <strong class="titl"><?php echo $facilityValue['facility_name'];?></strong>
                                    <?php if(!empty($facilityValue['description'])) { ?>
                                        <?php echo nl2br(makeURLAsHyperlink(htmlentities($facilityValue['description'])));?>
                                    
                                    <?php } if(!empty($facilityValue['childFacility']['Mandatory Hostel']['has_facility'])){?>
                                    <p class="head-8">Note : Staying in hostel is mandatory</p>
                                    <?php } if(!empty($facilityValue['childFacility'])) {?>
                                    <p class="head-s-12" id="avail-fac">Available facilities :</p>
                                    <div class="">
                                        <ul class="flex-ul childFac-ul">
                                             <?php foreach ($facilityValue['childFacility'] as $childKey => $childValue) { if($childKey != 'Mandatory Hostel' && (!empty($childValue['has_facility']) || in_array($facilityKey, array('Sports Complex','Labs')))) { ?>
                                            <li class="para-1 para-12">
                                                <div class="mlst">
                                                    <strong><?php echo nl2br(makeURLAsHyperlink(htmlentities($childKey)));?></strong>
                                                    <ul>
                                                        <?php foreach ($childValue['additionalInfo'] as $addkey => $addValue) { 
                                                        $dispayValue = '';
                                                        if(!empty($addValue['name']))
                                                            $dispayValue .= $addValue['name'];
                                                        if(in_array($addValue['name'], array('Number of Rooms','Number of beds')))
                                                        {
                                                            if(!empty($dispayValue))
                                                                $dispayValue .= ' - ';
                                                            if(!empty($addValue['value']))
                                                                $dispayValue .= $addValue['value'];
                                                        }
                                                        ?>
                                                        <li><?php echo !empty($dispayValue)?nl2br(makeURLAsHyperlink(htmlentities($dispayValue))):'';?></li>
                                                        <?php }?>
                                                    </ul>
                                                </div>
                                            </li>
                                            <?php } }?>
                                        </ul>
                                    </div>
                                    <?php } ?>
                                    </div>
                            </div>   
                        </div>
                    </li>
                <?php } }?>
                </ul>
                </div>
            </div>
        </div>
