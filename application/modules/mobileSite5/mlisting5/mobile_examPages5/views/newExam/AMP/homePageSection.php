<?php
    if($groupYear)
        $groupYear = ' '.$groupYear;
?>
<section>
    <div class="data-card m-btm">
        <h2 class="color-3 f16 heading-gap font-w6"><?php echo str_replace('Summary','Overview',$examName.$groupYear.' '.ucwords($sectionName));?></h2>
        <div class="card-cmn color-w f14">
            <input type="checkbox" class="read-more-state hide" id="que<?=$keyId?>">
        <div class = 'content__col'>
            <?php if(count($groupList)>1 && $sectionName == 'Summary'){?>
              <p class="f14 color-3 font-w6 change-brdr"><?php echo "Showing details for ". $groupName;?>     
                <a class="font-w4 ga-analytic" data-vars-event-name="CHANGE_COURSE" id="changeCourse" on="tap:change-group" role="button" tabindex="0">Change Course<i class="chnge-brnchico"></i></a>
              </p>
            <?php } ?>
            <div class="f13 color-3 l-18 font-w4 read-more-wrap word-break">
                <div class="f13 color-3 l-16 lt block mbtm_sp"><?php echo html_entity_decode($data);?></div>
            </div>
        <?php if($sectionName == 'Contact Information' && !empty($contactData)){
            $this->load->view('mobile_examPages5/newExam/AMP/contactInfo'); 
        }?>
        </div>
          <label for="que<?=$keyId?>" class="read-more-trigger f13 color-b block font-w4 m-3top ga-analytic" data-vars-event-name="<?php echo strtoupper($sectionName);?>_READ_MORE">Read More</label>
        </div>
    </div>

    <?php if($section == 'homepage'){
        $this->load->view("mcommon5/socialShareThis",array('pageType'=>'ampPage','className'=>'shadow')); 
    }?>
</section>
