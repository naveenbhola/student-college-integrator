<?php 
$isEligibilityTableExist   = false;
$isEligibilityAgeExpExist  = false;
$isEligibilityWorkExpExist = false;
if(!empty($eligibility['table'])){
    $isEligibilityTableExist = true;
}
$heading = '';
if(isset($eligibility['showCutOff'])){
    $heading = 'Eligibility & Cut Off';
}else{
    $heading = 'Eligibility';
}


if($eligibility['age_min'] || $eligibility['age_max']){
    $isEligibilityAgeExpExist = true;
}
if($eligibility['work_min'] || $eligibility['work_max']){
    $isEligibilityWorkExpExist = true;
}
?>



<div class="crs-widget gap listingTuple" id="eligibility">
    <h2 class="head-L2 more-mg"><?=$heading?></h2>
    <?php  $categories = $eligibility['categories'];            
    
    if(!(count($categories) == 1 && $categories[0] == 'general') && !empty($categories)){ ?>
    <div class="dropdown-primary">
    <?php 
                if($pageName == 'Admission')
                {
                    $gaAttr = "ga-attr = 'ELIGIBILITY_DROPDOWN'";
                }
                else
                {
                    $gaAttr = "ga-attr='ELIGIBILITY_DROPDOWN'";
                }
    ?>
        <input class="option-slctd" id="eligibilityCategoriesSelect_input" readonly value="<?php if($eligibility['eligibilitySelectedCategory']  != '') { echo ucfirst($categoriesNameMapping[$eligibility['eligibilitySelectedCategory']]), " Category"; } else { ?>General Category<?php } ?>" <?php echo $gaAttr;?> >
    </div>
    
    <div class="select-Class">
        <select id="eligibilityCategoriesSelect" style="display:none;">
            <?php             
            foreach ($categories as $category) { 
                if($pageName == 'Admission')
                {
                    $gaAttr = "ga-attr = 'ELIGIBILITY_CATEGORY_".strtoupper($category)."'";
                }
                else
                {
                    $gaAttr = "ga-attr='ELIGIBILITY_CATEGORY_".strtoupper($category)."_COURSEDETAIL_MOBILE'";
                }
                ?>
                <option value="<?php echo $category; ?>" <?=$gaAttr;?>><?php echo ucfirst($categoriesNameMapping[$category]); ?> Category</option>
            <?php
            }
            ?>
        </select>
    </div>
    <?php } ?>
    <div class="lcard">

        <div class="eligibilityMain">
            <?php 
        if($isEligibilityTableExist)
            $this->load->view('mobile_listing5/course/widgets/courseEligibilityTableWidget',array('pageName' => $pageName));?>
        </div>
        
        <?php if($isEligibilityAgeExpExist || $isEligibilityWorkExpExist || $eligibility['description'] || $eligibility['international_students_desc']){?>
        <div class="age-exp-col" <?php if(!$isEligibilityTableExist){?>style="border-top:none;"<?php }?> >
            <?php if($eligibility['age_min'] || $eligibility['age_max']){ ?>
                <section>
                    <label>Age</label>
                    <p>
                    <?php if($eligibility['age_min']){?> 
                        Minimum <?=$eligibility['age_min']?> year<?=($eligibility['age_min'] > 1)?'s':'';?> 
                    <?php } ?> 
                    <?php if($eligibility['age_min'] && $eligibility['age_max']){?> <span>|</span> <?php } ?>
                    <?php if($eligibility['age_max']){?>
                        Maximum <?=$eligibility['age_max']?> year<?=($eligibility['age_max'] > 1)?'s':'';?> 
                    <?php } ?></p>                
                </section>
            <?php } ?>
            <?php if($eligibility['work_min'] || $eligibility['work_max']){ ?>
            <section> 
                <label>Work Experience</label>
                <p>
                <?php if($eligibility['work_min']){?> 
                    Minimum <?=$eligibility['work_min']?> month<?=($eligibility['work_min'] > 1)?'s':'';?> 
                <?php } ?> 
                <?php if($eligibility['work_min'] && $eligibility['work_max']){?> <span>|</span> <?php } ?>
                <?php if($eligibility['work_max']){?>
                    Maximum <?=$eligibility['work_max']?> month<?=($eligibility['work_max'] > 1)?'s':'';?> 
                <?php } ?>
                </p>                
            </section>
            <?php } ?>

            <?php if($eligibility['international_students_desc']) {?>
            <section>
              <label>International students eligibility</label>
              <p><?php echo cutStringWithShowMore($eligibility['international_students_desc'],130,'inter_stud_desc','more','eligibility');?></p>           
            </section>
            <?php } ?>
            <?php if($eligibility['description']) {?>
            <section>
                <?php if(!empty($eligibility['international_students_desc']) || $isEligibilityAgeExpExist || $isEligibilityWorkExpExist || $isEligibilityTableExist) { ?> 
                <label>Other eligibility criteria</label>
                <?php } ?>
                <p><?php echo cutStringWithShowMore($eligibility['description'],130,'inter_desc','more','eligibility');?></p>            
            </section>
            <?php } ?>
        </div>
        <?php } ?>

        
        <?php if($pageName == 'Admission') {?>
            <input type="hidden" id="gaActionName_eligibility" value="VIEW_MORE_ELIGIBILITY">
        <?php } ?>
    </div>
</div>

<?php $this->load->view('mobile_listing5/course/widgets/courseEntryChancesWidget',array('pageName' => 'Admission'));?>
