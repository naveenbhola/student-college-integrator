 <?php 
    $wikiObj = $examContent['importantdates']['wiki'][0];
    if(!empty($wikiObj)){
    $impDatesWiki = $wikiObj->getEntityValue();
    }
    if($isHomePage)
    {
        $className = 'data-height-col';
    }
    ?> 
<section>
            <div class="data-card">
                 <?php 
                    if($groupYear)
                        $groupYear = ' '.$groupYear;
                     if($isHomePage)
                     { ?>
                      <h2 class="color-3 f16 heading-gap font-w6"><a href ="<?php echo $snippetUrl[$section]; ?>" data-vars-event-name="IMPORTANT_DATES_VIEW_DETAILS" class="color-3 f16 font-w6 ga-analytic"><?php echo $examName.$groupYear.' '.'Dates' ?></a></h2>
                  <?php }else{
                      ?>
                      <!--<h1 class="color-3 f16 heading-gap font-w6"><?//=$h1?></h1>-->
                  <?php } ?>
                <div class="card-cmn color-w f14">
                  <?php if(count($groupList)>1 && !$isHomePage){?>
              <p class="f14 color-3 font-w6 change-brdr"><?php echo "Showing details for ". $groupName;?>     
                <a class="font-w4 ga-analytic" data-vars-event-name="CHANGE_COURSE" id="changeCourse" on="tap:change-group" role="button" tabindex="0">Change Course<i class="chnge-brnchico"></i></a>
              </p>
            <?php } ?>
		  <p class="m-btm"><?=$datesString?></p>
                  <div class="<?=$className;?>">
                    <div class="ps-rl">
                        <div class="bar-line ps-rl">
                        <?php 
                        foreach ($importantDatesData['dates'] as $key => $value) {
                            foreach ($value as $key1 => $val1) { 
                                $backgroundColor = 'class="crc"';
                                if($val1['isUpcoming']){
                                  $backgroundColor = 'class="crc current"';
                                }
                                if($val1['isOngoing']){
                                  $backgroundColor = 'class="crc ongoing"';                                    
                                } ?>
                                <div class="crc-blck m-15btm ps-rl">
                                 <div <?=$backgroundColor;?> ></div>
                                    <div class="l-cnt i-block v-top"><p class="f13 color-3 font-w6">
                                        <?php if($val1['startDate'] != '0000-00-00'){
                                            echo $val1['startDate'];
                                        }
                                        if(($val1['startDate'] != $val1['endDate']) && ($val1['endDate'] != '0000-00-00')){
                                            echo " - ";
                                            echo $val1['endDate'];
                                        }?></p></div>
                                    <div class="r-cnt i-block v-top">
                                    <p class="f14 color-3 font-w4"><?=$val1['event'];?></p>
                                    <?php 
                                    if($val1['isUpcoming']){ ?>
                                        <span class="f11 color-6 up-clr">UPCOMING</span>
                                    <?php } 
                                    if($val1['isOngoing']){ ?>   
                                        <span class="f11 color-6 on-clr">ONGOING</span>
                                    <?php } ?>
                                    </div>
                                </div>
                        <?php }}   ?>
                        </div>
                    </div>
                  <?php
                if(!empty($impDatesWiki)){ ?>
                  <div class="m-btm mbtm_sp"><?php echo html_entity_decode($impDatesWiki); ?></div>
                <?php } ?>
                </div>
            <?php if($isHomePage) { ?>
                <div class="btn-sec">
                    <a href ="<?php echo $snippetUrl[$section]; ?>" data-vars-event-name="IMPORTANT_DATES_VIEW_DETAILS" class="btn btn-secondary color-w color-b f14 font-w6 m-15top ga-analytic">View Details</a>
                </div>
            <?php } ?>
            </div>
      </div>
</section>
<?php if(!$isHomePage){ $this->load->view("mcommon5/socialShareThis",array('pageType'=>'ampPage','className'=>'shadow'));}?>
