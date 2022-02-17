<div class="cmn-card mb2 eligibility-sec">
    <h2 class="f20 clor3 mb2 f-weight1">Eligibility & Cut-Off</h2>
    <?php foreach ($eligibility['categoryData'] as $eligibilityCategory => $eligibilityCategoryData) { 

      ?>
        <?php 
        if(!empty($categoriesNameMapping[$eligibilityCategory])) { ?>
          <h3 class="prt-title mb2 f16"><?=$categoriesNameMapping[$eligibilityCategory];?></h3>
        <?php } ?>
        <table class="table table-bordered crs-tble">
            <thead class="thead-default">
                <tr>
                    <th width="20%">
                        <h3 class="f16 clor6 f-semi">Qualification</h3></th>
                    <?php if($eligibilityCategoryData['showEligibilityVal'] == true) { ?>
                            <th width="12%">
                                <h3 class="f16 clor6 f-semi">Minimum Eligibility to Apply</h3>
                            </th>
                    <?php } ?>
                    <?php if($eligibilityCategoryData['showCutOff'] == true) { ?>
                            <th width="15%">
                                <h3 class="f16 clor6 f-semi">Cut-Offs <?php if($eligibilityCategoryData['cutOffYear']) {?><span class="yr-spn">(<?=$eligibilityCategoryData['cutOffYear'];?>)</span><?php } ?></h3>
                            </th>
                    <?php } ?>
                    <?php  if(isset($eligibilityCategoryData['showEligibilityAdditionalInfo']) && $eligibilityCategoryData['showEligibilityAdditionalInfo']){?>
                            <th width="40%"><h3 class="f16 clor6 f-semi">Additional Details</h3></th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody class="tbody-default">
            <?php foreach ($eligibilityCategoryData['table'] as $key => $val) { ?>
                  <tr>
                      <td><strong><?php echo $val['qualification']; ?></strong></td>
                      <?php if($eligibilityCategoryData['showEligibilityVal'] == true) { ?>
                              <td><?php echo $val['eligibility']; ?></td>
                      <?php } ?>
                      <?php if($eligibilityCategoryData['showCutOff'] == true) { ?>
                              <td>
                                  <?php if(is_array($val['cutoff'])) {
                                          $cutoffstr = array();
                                          foreach ($val['cutoff'] as $cut) {
                                              if($val['quotaCount'][$cut['quota']] > 1){
                                                  ob_start();
                                                  ?>                            
                                                  <?php
                                                      $tooltipText = ''; 
                                                      krsort($eligibilityCategoryData['examCutoffData'][$val['qualification']]);                                                 
                                                      foreach ($eligibilityCategoryData['examCutoffData'][$val['qualification']] as $round => $roundData) {
                                                          foreach ($roundData as $quota => $value) {  
                                                              $quotaName = $quota;
                                                              if(stripos($quota,'Related_states') !== false){
                                                                  $temp = explode(':',$quota);
                                                                  $quota = $temp[0];
                                                              }
                                                              if($quotaName == $cut['quota']){
                                                                $tooltipText .= "Round ".($round+1)." : ".$value."<br/>";
                                                              }
                                                          }                                                        
                                                      }
                                                  ?>
                                                  <div class="tp-block">
                                                      <i class="info-icn" infodata = "<?=$tooltipText?>" infopos="right"></i>
                                                  </div>
                                                  
                                                  <?php
                                                  $temp = ob_get_clean();
                                              }
                                              else {
                                                  $temp = '';
                                              }
                                              $cutoffstr[] = $cut['cutoffstr'].$temp;
                                          }
                                          echo implode($cutoffstr,'<br>');
                                      }
                                      else{                                        
                                          echo $val['cutoff'];
                                      }
                                  ?>
                              </td>
                              <?php 
                          }
                      ?>
                      <?php  if(isset($eligibilityCategoryData['showEligibilityAdditionalInfo']) && $eligibilityCategoryData['showEligibilityAdditionalInfo']){?>
                      <td><?php echo nl2br(htmlentities($val['additionalInfo'])); ?></td>
                      <?php }?>
                  </tr>
            <?php } ?>
            </tbody>
        </table>
<?php } ?>
  
    <?php 
      $isEligibilityAgeExpExist  = false;
      $isEligibilityWorkExpExist = false;
      if($eligibility['age_min'] || $eligibility['age_max']){
          $isEligibilityAgeExpExist = true;
      }
      if($eligibility['work_min'] || $eligibility['work_max']){
          $isEligibilityWorkExpExist = true;
      }
    ?>
        <!--- Age/ Work Experience Section Starts here-->
        <?php if($isEligibilityAgeExpExist || $isEligibilityWorkExpExist){?>
                <div class="age-exp-sec">
                  <?php if($isEligibilityAgeExpExist){ ?>
                    <div class="age-exp-col">
                        <label>Age</label>
                        <p>
                        <?php if($eligibility['age_min']){?> 
                            Minimum <?=$eligibility['age_min']?> year<?=($eligibility['age_min'] > 1)?'s':'';?> 
                        <?php } ?> 
                        <?php if($eligibility['age_min'] && $eligibility['age_max']){?> | <?php } ?> 
                        <?php if($eligibility['age_max']){?>
                            Maximum <?=$eligibility['age_max']?> year<?=($eligibility['age_max'] > 1)?'s':'';?>
                        <?php } ?>
                        </p>
                    </div>
                  <?php } ?>
                  <?php if($isEligibilityWorkExpExist){ ?>
                    <div class="age-exp-col">
                        <label>Work Experience</label>
                        <p>
                        <?php if($eligibility['work_min']){?> 
                            Minimum <?=$eligibility['work_min']?> month<?=($eligibility['work_min'] > 1)?'s':'';?> 
                        <?php } ?> 
                        <?php if($eligibility['work_min'] && $eligibility['work_max']){?> | <?php } ?> 
                        <?php if($eligibility['work_max']){?>
                            Maximum <?=$eligibility['work_max']?> month<?=($eligibility['work_max'] > 1)?'s':'';?>
                        <?php } ?>
                        </p>                        
                    </div>
                  <?php } ?>
                </div>
        <?php } ?>
        <!--- Age/ Work Experience Section Ends here-->

         <!--International students eligibility starts here-->
        <?php if($eligibility['international_students_desc'] || $eligibility['description']){?>
                <div class="int-stCont">
                  <?php if($eligibility['international_students_desc']) {?>
                    <div class="int-stBox">
                        <strong>International students eligibility</strong>
                        <p><?=nl2br(htmlentities($eligibility['international_students_desc']));?></p>
                    </div>
                  <?php } ?>
                  <?php if($eligibility['description']) {?>
                    <div class="int-stBox">
                      <?php if($eligibility['tableDataExist'] || !empty($eligibility['international_students_desc'])){?>
                        <strong>Other eligibility criteria</strong>
                      <?php } ?>
                        <p><?=nl2br(htmlentities($eligibility['description']));?></p>
                    </div>
                  <?php } ?>
                </div>
        <?php } ?>
        <!--International students eligibility ends here-->
    </div>