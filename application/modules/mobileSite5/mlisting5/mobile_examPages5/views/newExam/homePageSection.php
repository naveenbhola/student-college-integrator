<?php
    if($groupYear)
        $groupYear = ' '.$groupYear;
?>
<section>
    <div class="data-card m-btm wdg-card">
        <h2 class="color-3 f16 heading-gap font-w6"><?php echo str_replace('Summary','Overview',$examName.$groupYear.' '.ucwords($sectionName));?></h2>
        <div class="lcard color-w f14">
            <?php if(count($groupList)>1 && $sectionName == 'Summary'){?>
        <p class="f14 color-3 change-brdr">Showing details for <strong class="font-w6"><?php echo $groupName;?> </strong>   
          <a href="javascript:void(0);" class="font-w4" id="changeCourse" ga-attr="CHANGE_COURSE">Change Course<i class="chnge-brnchico"></i></a>
        </p>
      <?php }?> 
        <div class="setHeight" id="<?=preg_replace('/\\s/','',$sectionName).'WikiId'?>">
                <div class="f13 color-3 l-18 font-w4 read-more-wrap word-break">
                    <div class="color-3 l-16 lt mbtm_sp">
                    <?php echo html_entity_decode($data);?>
                    </div>
        </div>
        <?php if($sectionName == 'Contact Information' && !empty($contactData)){
            $this->load->view('mobile_examPages5/newExam/contactInfo'); 
        }?>
        </div>
        <div style="display:none;" id="<?=preg_replace('/\\s/','',$sectionName).'WikiIdRm'?>">
        <a href="javascript:void(0);" class=" f14 color-b font-w6 m-top block viewMoreHP" divToShow="<?=preg_replace('/\\s/','',$sectionName).'WikiId'?>" ga-attr="<?php echo strtoupper($sectionName);?>_READ_MORE" >Read More</a>
        </div>
        </div>
    </div>

    <?php if($section == 'homepage'){
        $this->load->view("mcommon5/socialShareThis",array('className'=>'shadow')); 
    }?>

</section>
