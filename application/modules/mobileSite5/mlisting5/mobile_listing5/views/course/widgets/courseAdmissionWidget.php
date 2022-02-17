<div class="crs-widget gap listingTuple" id="admissions">
    <h2 class="head-L2">
        <?php if(!empty($admissions) && !empty($importantDatesData['importantDates'])){
                    echo "Admissions";
               }
              else if(!empty($admissions)){
                  echo "Admission Process";
              }
              else if(!empty($importantDatesData['importantDates'])){
                  echo "Important Dates";
              }
        ?>
    </h2>
    <div class="lcard">
        <?php if(!empty($admissions)){
          if(!empty($importantDatesData['importantDates'])){
          ?>
          <h3 class="admisn">Admission Process</h3>
          <?php } if($pageName == 'Admission') {?>
              <input type="hidden" id="gaActionName_Admission" value="VIEW_MORE_ADMISSION">
          <?php } ?>
            <?php  for($i=1;$i<=min(3,count($admissions));$i++){
                      if(!empty($admissions[$i])){
            ?>
                        <div class="ad-stage">
                            <?php if(count($admissions)>1){?>
                            <p class="stage-titl"><?php echo nl2br($admissions[$i]['admission_name']);?></p>
                            <?php } ?>
                            <p class="admn-txt" id="<?php echo "admissionStage".$i; ?>"><?php echo cutStringWithShowMore($admissions[$i]['admission_description'],160,'admissionStage'.$i,'more','admission');?> </p>
                        </div>
            <?php     }
                    }
            ?>
                
        <?php if(count($admissions)>3){
          if($pageName == 'Admission')
          {
            $gaAttr= "ga-attr= 'VIEW_COMPLETE_ADMISSION'";
          }
		  else
		  {
			$gaAtttr ="ga-attr='ADMISSION_VCP_TOP_COURSEDETAIL_MOBILE'";
	  	  }
          ?>
            <a href="javascript:void(0)" class="link-blue-medium  v-more" id="admissionProcess" <?=$gaAttr;?>>View complete process</a>
            <div class="fix-admsn" style="display:none" id ="admisssionProcessLayer">
             <div class="prcs-col">
              <div class="head-fix">
                <h3 class="para-L1">Admission Process</h3>
              </div>
              <div class="stages-datas">
                <ul>
                    <?php foreach($admissions as $key => $admissionStage){?>
                            <li>
                              <div class="ad-stage">
                                  <p class="stage-titl"><?php echo nl2br($admissionStage['admission_name']);?></p>
                                  <p class="admn-txt"><?php echo nl2br(makeURLAsHyperlink(htmlentities($admissionStage['admission_description'])));?></p>
                              </div>
                            </li>
                    <?php } ?>
                </ul>
              </div>
             </div> 
            </div> 
        <?php } }?>
        <?php 
            if(count($importantDatesData['importantDates']) > 0){ 
                $showBorderClass = 'imp-date';
                if(empty($admissions)) {
                    $showBorderClass = '';
                }
              ?>

                <div class="<?=$showBorderClass?>">
                  <?php if($showBorderClass != '') { ?>
                          <h3 class="admisn">Important Dates</h3>
                  <?php } ?>
                  <?php 
                    $this->load->view('mobile_listing5/course/widgets/courseImportantDatesWidget',array('pageName' => 'Admission'));
                  ?>
                </div>
      <?php      }
        ?>
    </div>
</div>
