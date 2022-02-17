<?php 
 $prepTipsTrackingKeyId = $trackingKeys['download_prep_tips_page'];
 $wikiObj1 = $examContent['preptips']['wiki'][0];
 $wikiObj2 = $examContent['preptips']['wiki'][1];
 $isShowFiles = true;
?>
 <section>
            <div class="data-card wdg-card m15">
                <?php
                if($groupYear)
                    $groupYear = ' '.$groupYear;
                if($isHomePage)
                { ?>
                <h2 class="color-3 f16 heading-gap font-w6"><a href ="<?php echo $snippetUrl[$section]; ?>" class="color-3" ga-attr="PREP_TIPS_VIEW_ALL"><?php echo $examName.$groupYear.' '.'Preparation Tips'?></a></h2>
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
                            <?php 
                            if(!empty($wikiObj1)){
                                $preptipswiki1 = $wikiObj1->getEntityValue();
                            }
                            ?>
                            <?php
                                if(!empty($preptipswiki1)){ 
                                    if($isHomePage)
                                    {
                                        $isShowFiles = false;
                                    }
                                    ?>
                                     <div class="f14__clr3 mbtm_sp"><?php echo html_entity_decode($preptipswiki1); ?></div>
                            <?php } ?>
                    <ul class="cmn-lst">
                        <?php if(!empty($preptipsData) && count($preptipsData) > 0 && $isShowFiles){ ?>
                          <h2 class="f14 inner-h2">Download <?php echo $examName.' '.'Preparation Tips';?></h2>  <?php
                            foreach ($preptipsData as $key => $val) {?>
                            <li data-link="<?=$val['url'];?>" class="preptipdownload" data-tracking="<?=$prepTipsTrackingKeyId;?>" ga-attr="DOWNLOAD_PREP_TIPS_PAPER" data-no = "<?='preptips_'.($key+1)?>">
                                <p class="pos-rl <?php echo getFileType($val['url']);?>"><?=$val['fileName']?>
                                </p>
                                <i class="dwn-bg"><span>DOWNLOAD</span></i>
                            </li>
                        <?php }} ?>
                        
                        
                    </ul>
                    <?php 
                            if(!empty($wikiObj2)){
                                $preptipswiki2 = $wikiObj2->getEntityValue();
                            }
                            ?>
                            <?php
                                if(!empty($preptipswiki2) && !$isHomePage){ ?>
                                     <div class="f14__clr3 mbtm_sp"><?php echo html_entity_decode($preptipswiki2); ?></div>
                            <?php } ?>
                </div>
                <?php if($isHomePage) {
                    ?>
                    <div class="btn-sec" id="<?php echo $section.'detRm';?>">
                        <a href ="<?php echo $snippetUrl[$section]; ?>" class="btn btn-secondary color-w color-b f14 font-w6 m-15top m-5btm" ga-attr="PREP_TIPS_VIEW_ALL">View All</a>
                    </div>
                <?php } ?>
               </div>
              </div>
          </section>
<?php if(!$isHomePage){
    $this->load->view("mcommon5/socialShareThis",array('className'=>'shadow')); 
}?>
