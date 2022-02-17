<?php 
$isEligibilityTableExist   = false;
$isEligibilityAgeExpExist  = false;
$isEligibilityWorkExpExist = false;
if(!empty($eligibility['table'])){
    $isEligibilityTableExist = true;
}
$heading = '';

if(!empty($eligibility) && $eligibility['showEligibilityWidget']){
if(isset($eligibility['showCutOff'])){
    $heading = 'Eligibility & Cut Off';
}else{
    $heading = 'Eligibility';
}

if($pageName == 'Admission' && $heading){
    $heading = "Eligibility";
}
}


if($eligibility['age_min'] || $eligibility['age_max']){
    $isEligibilityAgeExpExist = true;
}
if($eligibility['work_min'] || $eligibility['work_max']){
    $isEligibilityWorkExpExist = true;
}

  $length = 480; ?>

  <div class="new-row">
    <div class="courses-offered gap listingTuple" id="eligibility">
        <div class="group-card no__pad gap">
                <div class="">
                    <h2 class="head-1 gap"><?=$heading?></h2>
                </div>
            <?php if($isEligibilityTableExist && !(count($eligibility['categories']) == 1 && $eligibility['categories'][0] == 'general') && !empty($eligibility['categories'])){?>
            <div class="course-search gen-cat">
                <p>Viewing info for </p>
                 <div class="dropdown-primary" id="categoryEligibilityOptions">
                 <?php 
                 $gaAttr = ($pageName == 'Admission') ? "ga-attr = 'ELIGIBILITY_DROPDOWN'" : "ga-track='ELIGIBILITY_DROPDOWN_COURSEDETAIL_DESKTOP'";
                 ?>
                    <span class="option-slctd" <?php echo $gaAttr;?>><?php 
                    if($eligibility['eligibilitySelectedCategory']  != '') { echo ucfirst($categoriesNameMapping[$eligibility['eligibilitySelectedCategory']]), " Category"; } ?></span>
                    <span class="icon"></span>
                    <ul class="dropdown-nav" style="display: none;">
                        <?php
                        $categories = $eligibility['categories'];                        
                        foreach ($categories as $category) { 
                            $gaAttr = ($pageName == 'Admission') ? "ga-attr = 'ELIGIBILITY_CATEGORY_".strtoupper($category)."'" : "ga-track='ELIGIBILITY_CATEGORY_".strtoupper($category)."_COURSEDETAIL_DESKTOP'";
                            ?>
                            <li><a value="<?php echo $category; ?>" <?php echo $gaAttr;?>><?php echo ucfirst($categoriesNameMapping[$category]); ?> Category</a></li>
                        <?php }
                        ?>
                    </ul>
                </div>
            </div>
            <?php } ?>
            <?php 
                if($pageName != 'Admission') {
                    ?>
                    <div class="pad__16">
                    <?php
                }
            ?>
                <?php
            if($isEligibilityTableExist)
                $this->load->view('nationalCourse/CoursePage/CourseEligibilityTableWidget');?>

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
                <?php  } ?>
            </div>
            <?php } ?>

            <?php if($eligibility['international_students_desc'] || $eligibility['description']){?>
            <div class="int-stCont">
                <?php if($eligibility['international_students_desc']) {?>
                <div class="int-stBox">
                    <strong>International students eligibility</strong>
                    <p>
                    <?php echo cutStringWithShowMore($eligibility['international_students_desc'],$length,'inter_stud_desc','view more','eligibility');?>                               
                    </p>
                </div>
                <?php } ?>
                <?php if($eligibility['description']) {?>
                <div class="int-stBox">
                    <?php if(!empty($eligibility['table']) || !empty($eligibility['international_students_desc'])){?>
                    <strong>Other eligibility criteria</strong>
                    <?php } ?>
                    <p>
                    <?php echo cutStringWithShowMore($eligibility['description'],$length,'inter_desc','view more','eligibility');?>                                                   
                    </p>
                </div>
                <?php } ?>
            </div>
            <?php if($pageName == 'Admission') {?>
                <input type="hidden" id="gaActionName_eligibility" value="VIEW_MORE_ELIGIBILITY">
            <?php } } ?>

            <?php 
                if($pageName != 'Admission') {
                    ?>
                    </div>
                    <?php
                }
            ?>
            
            <?php if(!empty($predictorData) && $pageName != 'Admission') {
                if($pageName == 'Admission')
                {
                    $gaAttr = "ga-attr = 'FIND_OUT_PREDICTOR'";
                }else{
                    $gaName  = $predictorData[0]['name']." COURSEDETAIL_DESKTOP";
                    $gaAttr = "ga-attr = '".$gaName."'";
                }
                ?>
            
            <div class="findOut-sec">
            <div class="fntot-dv">
                <?php if(count($predictorData) > 1 ) { ?>
                <h2 class="para-3">Want to know your chances of getting into this college? <a href="javascript:void(0)" class="btn-secondary mL10 eli-tool" <?=$gaAttr;?>>Find Now</a></h2>
                <?php } else if($predictorData[0]['name'] =='JEE-Mains') { ?>
                    <h2 class="para-3">Want to know your chances of getting into this college? <a href="<?php echo $predictorData[0]['url']?>" target="_blank" class="btn-secondary mL10" <?=$gaAttr;?>>Predict College</a> <a href="<?php echo $predictorData[0]['rank_predictor_url']?>" target="_blank" class="btn-secondary mL10" <?=$gaAttr;?>>Predict Rank</a></h2> 
                 <?php } else { ?>
                <h2 class="para-3">Want to know your chances of getting into this college? <a href="<?php echo $predictorData[0]['url']?>" target="_blank" class="btn-secondary mL10" <?=$gaAttr;?>>Find Now</a></h2>    
                <?php }  ?>
            </div>    
            </div>
            <?php }

            if(!empty($cutOffData) && $pageName != 'Admission') {
                $gaName  = "CUTOFF_PREDICTOR_COURSEDETAIL_DESKTOP";
                $gaAttr = "ga-attr = '".$gaName."'";
                ?>
            
                <div class="findOut-sec">
                    <div class="fntot-dv">
                        <h2 class="para-3">Check cut-offs for this course and others 
                            <?php 
                            foreach ($cutOffData as $row) {
                                ?>
                                <a href="<?php echo $row['url']?>" target="_blank" class="btn-secondary mL10" <?=$gaAttr;?>><?php echo $row['text']; ?></a>
                                <?php
                            }
                            ?>
                        </h2>
                    </div>    
                </div>
            <?php }  

            if($pageName == 'Admission'){
            ?>
            <div class="box-contnr">
            <div>
               <p class="d-txt">Interested in this course ?</p>
                <div class="side-col">
                    <a class="shrt-txt addToShortlist shrt-list" ga-attr="COURSE_SHORTLIST"><i class="cmn-icons i-shrt"></i>Shortlist</a>
                    <input type="hidden" name="shortlistKeyId" id="shortlistKeyId" value="1101">
                    <a class="btn-primary" href="<?php echo $courseListingUrl;?>" target="_blank" ga-attr="VIEW_COURSE_LINK">View Details</a>
                </div>
            </div>
            <?php if(!empty($onlineFormData) && count($onlineFormData) > 0) {?>
                <div class="dot-col">
                    <p class="cmn-fnt">Applications close on <strong><?php echo $onlineFormData['date'];?></strong> <a href="<?php echo $onlineFormData['url'].'?tracking_keyid=976';?>" target="_blank" ga-attr="APPLY_ONLINE_FORM">Apply Now</a></p>
            </div>
            </div>
    <?php } ?>
            <?php
            }
            ?>

        </div>
    </div>

</div>
         