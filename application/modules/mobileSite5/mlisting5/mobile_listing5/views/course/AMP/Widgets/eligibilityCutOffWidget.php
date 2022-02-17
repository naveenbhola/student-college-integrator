<?php 
  $isEligibilityTableExist   = false;
  $isEligibilityAgeExpExist  = false;
  $isEligibilityWorkExpExist = false;
  if(!empty($eligibility['showEligibilityWidget'])){
      $isEligibilityTableExist = true;
  }
 
  $heading = '';
  if(isset($eligibility['showCutOff'])){
      $heading = 'Eligibility & Cut Off';
      $gaEvent = 'Eligibility_CUT_OFF';
  }else{
      $heading = 'Eligibility';
      $gaEvent = 'Eligibility';
  }
  if($eligibility['age_min'] || $eligibility['age_max']){
    $isEligibilityAgeExpExist = true;
  }
  if($eligibility['work_min'] || $eligibility['work_max']){
      $isEligibilityWorkExpExist = true;
  }
  if($eligibility['international_students_desc'] || $eligibility['description']){
      $isInternationalStudentDataExist = true;
  }
?>

 <section>
             <div class="data-card m-5btm pos-rl" id="eligibility">
                 <h2 class="color-3 f16 heading-gap font-w6 pos-rl"><?=$heading?></h2> 
              <?php if(!(count($eligibility['categoryData']) == 1 && array_search('general', array_keys($eligibility['categoryData'])) == 0) && !empty($eligibility['categoryData'])){ ?>   
                 <div class="dropdown-primary" on="tap:cat-list" role="button" tabindex="0">
                     <span class="option-slctd block color-6 f12 font-w6 ga-analytic" id="optnSlctd" data-vars-event-name="<?php echo $gaEvent;?>">Choose Category</span>

                 </div>
              <?php } ?>

                 <div class="card-cmn color-w">
                 <?php if($isEligibilityTableExist) {
                      $borderClass = 'border-top';
                      $this->load->view('course/AMP/Widgets/eligibilityTableWidget');
                  }else{
                      $borderClass = '';
                  } ?>
                  <?php if($isEligibilityAgeExpExist || $isEligibilityWorkExpExist || $isInternationalStudentDataExist){?>
                      <div class="age-exp-col padb0 <?=$borderClass;?> ">
                        <?php if($eligibility['age_min'] || $eligibility['age_max']){ ?>
                             <section class="cut-off-widget">
                                 <label class="block f14 color-6 font-w6">Age</label>
                                 <p class="f14 color-3 font-w4">
                                    <?php if($eligibility['age_min']){?> 
                                        Minimum: <?=$eligibility['age_min']?> year<?=($eligibility['age_min'] > 1)?'s':'';?>                                  
                                    <?php } ?>
                                    <?php if($eligibility['age_min'] && $eligibility['age_max']){?> <span>|</span> <?php } ?>
                                    <?php if($eligibility['age_max']){?>
                                        Maximum: <?=$eligibility['age_max']?> year<?=($eligibility['age_max'] > 1)?'s':'';?> 
                                    <?php } ?>
                                  </p>
                             </section>
                        <?php } ?>
                        <?php if($eligibility['work_min'] || $eligibility['work_max']){ ?>
                             <section class="cut-off-widget">
                                 <label class="block f14 color-6 font-w6">Work experience</label>
                                 <p class="f14 color-3 font-w4">
                                      <?php if($eligibility['work_min']){?> 
                                          Minimum: <?=$eligibility['work_min']?> month<?=($eligibility['work_min'] > 1)?'s':'';?> 
                                      <?php } ?> 
                                      <?php if($eligibility['work_min'] && $eligibility['work_max']){?> 
                                            <span>|</span> 
                                      <?php } ?>
                                      <?php if($eligibility['work_max']){?>
                                          Maximum <?=$eligibility['work_max']?> month<?=($eligibility['work_max'] > 1)?'s':'';?> 
                                      <?php } ?>
                                    </p>
                             </section>
                        <?php } ?>
                         <?php if($eligibility['international_students_desc']) { ?>
                            <section class="cut-off-widget">
                              <label class="block f14 color-6 font-w6">International students eligibility</label>
                              <input type="checkbox" class="read-more-state hide" id="inter_stud_desc">
                              <p class="read-more-wrap word-break"><?php echo cutStringWithShowMoreInAMP($eligibility['international_students_desc'],130,'inter_stud_desc','more',true,false);?></p>           
                            </section>
                          <?php } ?>   
                          <?php if($eligibility['description']) {?>
                              <section class="cut-off-widget">
                                  <?php if(!empty($eligibility['international_students_desc']) || $isEligibilityAgeExpExist || $isEligibilityWorkExpExist || $eligibility['tableDataExist']) { //|| $isEligibilityTableExist ?> 
                                    <label class="block f14 color-6 font-w6">Other eligibility criteria</label>
                                  <?php } ?>
                                  <input type="checkbox" class="read-more-state hide" id="inter_desc">
                                  <p class="read-more-wrap word-break"><?php echo cutStringWithShowMoreInAMP($eligibility['description'],130,'inter_desc','more',true,false);?></p>            
                              </section>
                          <?php } ?>
                      </div>
                  <?php } ?>
                  </div>
            </div>

         </section>

         <?php 
            $this->load->view('course/AMP/Widgets/coursePredictorLayer');
         ?>

 <!---General cat -->
 <?php  $categories = $eligibility['categoryData'];?>        
  <amp-lightbox id="cat-list" class="" layout="nodisplay" scrollable>
      <div class="lightbox" on="tap:cat-list.close" role="button" tabindex="0">
          <a class="cls-lightbox color-f font-w6 t-cntr" >&times;</a>
          <div class="m-layer">
              <div class="min-div color-w catg-lt">
                  <ul class="color-6">
                    <?php foreach($categories as $category => $value) {?>

                        <li><label for="eligi_<?=$category;?>" class="block"><?php echo ucfirst($categoriesNameMapping[$category]); ?> Category</label></li>
                    <?php } ?>
                  </ul>
              </div>
          </div>
      </div>
  </amp-lightbox>