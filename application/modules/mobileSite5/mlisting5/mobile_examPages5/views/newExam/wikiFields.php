<?php 
 if($isHomePage)
 {
    $className = 'setHeight';
 }
?>
<section id="<?php echo $section;?>">
            <div class="data-card wdg-card m15">
            <?php 
            if($groupYear)
                $groupYear = ' '.$groupYear;
            if($isHomePage)
            { ?>
                <h2 class="color-3 f16 heading-gap font-w6"><a href ="<?php echo $snippetUrl[$section]; ?>" class="color-3" ga-attr="<?php echo str_replace(' ', '_', strtoupper($sectionName));?>_VIEW_ALL"><?php echo $examName.$groupYear.' '.ucwords($sectionName)?></a></h2>
            <?php }else{
            ?>
            <!--<h1 class="color-3 f16 heading-gap font-w6"><?//=$h1?></h1>-->
            <?php } ?>

                <div class="lcard color-w f14 color-3">
                    
                    <div class="<?=$className;?>" id="<?php echo $section.'det';?>">
                    <?php if(count($groupList)>1 && !$isHomePage){?>
                  <p class="f14 color-3 change-brdr">Showing details for <strong class="font-w6"><?php echo $groupName;?> </strong>   
                  <a href="javascript:void(0);" class="font-w4" id="changeCourse" ga-attr="CHANGE_COURSE">Change Course<i class="chnge-brnchico"></i></a>
                  </p>
                <?php }?>    
                    <?php foreach ($sectionData as $key => $curObj) { ?>
                        <?php 
                            $data = $curObj->getEntityValue();
                            if($isHomePage)
                            {
                                $this->htmlSummarizeLogicLib = $this->load->library('examPages/HtmlSummarizeLogicLib');
                                $data = $this->htmlSummarizeLogicLib->summarizeData($data);
                            }
                        ?>
                            <div class="f14__clr3 mbtm_sp"><?php echo html_entity_decode($data); ?></div>
                        <?php } ?>
                     </div>
                        <?php if($isHomePage) { ?>
                            <div class="btn-sec" id="<?php echo $section.'detRm';?>">
                                <a href ="<?php echo $snippetUrl[$section]; ?>" class="btn btn-secondary color-w color-b f14 font-w6 m-15top m-5btm" ga-attr="<?php echo str_replace(' ', '_', strtoupper($sectionName));?>_VIEW_ALL">View Details</a>
                            </div>
                        <?php } ?>
                    </div>
                </div>
</section>
<?php if(!$isHomePage){
    $this->load->view("mcommon5/socialShareThis",array('className'=>'shadow')); 
}?>
