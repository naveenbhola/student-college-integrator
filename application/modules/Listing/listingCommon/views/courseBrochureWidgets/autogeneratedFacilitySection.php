<div class="cmn-card mb2">
     <h2 class="f20 clor3 mb2 f-weight1">Infrastucture</h2>
     <div class="mb2">
                <ul class="infra -ul">
                    <?php global $FACILITY_ID_CSS_ICON_NAME_MAPPING;
                    foreach($facilities['facilities'] as $facilityKey => $facilityValue) { if($facilityKey != 'others') {?>
                      <li> <a class="<?php echo $FACILITY_ID_CSS_ICON_NAME_MAPPING[$facilityKey];?>"><?php echo $facilityValue?></a></li>   
                      <?php }} ?>
                </ul>
     </div>
     <div class="mb2">
       <div class="view-dtls-box">
          <?php foreach ($facilityInfo as $facilityKey => $facilityValue) { 
                        if($facilityValue['has_facility'] !== 0) { ?>
                    <div class="ad-stage">
                        <div class="pad20" id="infra-layr-id">    
                        <p class="stage-titl"><?=$facilityKey;?></p>
                        <p class=""></p>
                        <div class="facilityTinyBar" id="facilityTinyBar">
                            <?php if(!empty($facilityValue['description'])) { ?>
                            <p class="ad-txt">
                                <?php echo nl2br(htmlentities($facilityValue['description']));?>
                            </p>
                            <?php } if(!empty($facilityValue['childFacility']['Mandatory Hostel']['has_facility'])){?>
                            <p class="ad-txt"> Note: Staying in hostel is mandatory</p>
                            <?php } if(!empty($facilityValue['childFacility'])) {?>
                            <?php if($facilityKey != 'Hostel') {?>
                            <p class="head-s-12 margin20" id="avail-fac">Available facilities</p>
                            <?php } ?>
                                <?php foreach ($facilityValue['childFacility'] as $childKey => $childValue) { if($childKey != 'Mandatory Hostel' && (!empty($childValue['has_facility']) || in_array($facilityKey, array('Sports Complex','Labs')))) { 
                                    $dispayValueArr = array();
                                  ?>
                                      <?php foreach ($childValue['additionalInfo'] as $addkey => $addValue) { 
                                              $dispayValue = '';
                                              if(!empty($addValue['name'])) {
                                                  $dispayValue .= $addValue['name'];
                                              }
                                              if(in_array($addValue['name'], array('Number of Rooms','Number of beds'))) {
                                                  if(!empty($dispayValue))
                                                      $dispayValue .= ' - ';
                                                  if(!empty($addValue['value']))
                                                      $dispayValue .= $addValue['value'];
                                              }
                                              if(!empty($dispayValue)) {
                                                  $dispayValueArr[] = $dispayValue;
                                              }
                                            }
                                       ?>
                                  <p class="ad-txt"><span class="f-semi"><?php echo nl2br(htmlentities($childKey));?></span> <?= (!empty($dispayValueArr)) ? ': '.implode(', ', $dispayValueArr) : ''?></p>
                                <?php } }?>
                            <?php } ?>
                         </div>
                         </div>
                    </div>
                <?php } } ?>

       </div>
     </div>
     <?php 
            $otherFacilityInfo = ''; 
            foreach ($facilities['facilities']['others'] as $otherKey => $otherValue) { 
                 if(!empty($otherFacilityInfo))
                 {
                    $otherFacilityInfo .= ' | ';
                 }
                 $otherFacilityInfo .= $otherValue;
            }
           if(!empty($otherFacilityInfo)) { ?>
           <p class="f16 clor3 f-semi">Other facilities : <span class="clor3 f16 f-semi"><?php echo $otherFacilityInfo;?></span></p>
           <?php } ?>
</div>