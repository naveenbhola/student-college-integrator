 <!--upcoming mba exams html-->
    <div class="upcoming-states">
    <div class="upcoming-title">Upcoming <?=$upcomingExamHeading;?>Exam Dates</div>
    <div class="upcmng-examlist">
      <?php 
      foreach($widgetData as $widgetKey => $widgetValue){ ?>
         <div class="examslist">

            <?php 
            $gaattr = 'ga-attr="EXAM_LINK_UPCOMING_'.strtoupper($pageType).'_'.$gaDevice.'"';
            $gaattrChild = 'ga-attr="EXAM_CHILD_LINK_UPCOMING_'.strtoupper($pageType).'_'.$gaDevice.'"';
            $gaattrAll = 'ga-attr="VIEW_ALL_EXAM_LINK_UPCOMING_'.strtoupper($pageType).'_'.$gaDevice.'"';
            if($isAmp){
              $gaattr = 'data-vars-event-name="EXAM_LINK_UPCOMING"';
              $gaattrChild = 'data-vars-event-name="EXAM_CHILD_LINK_UPCOMING"';
              $gaattrAll = 'data-vars-event-name="VIEW_ALL_EXAM_LINK_UPCOMING"';
              }?>
            <div class="anchor_holder ga-analytic" <?=$gaattr;?>>
                <a href="<?=$widgetValue['exam_url']?>">
                    <?=$widgetValue['exam_name']?> <?=$widgetValue['group_year']?>
                </a>
            </div>
            <div class="exams-infodata">
               <?php if($widgetValue['startDate'] != $widgetValue['endDate']) { ?><strong><?=$widgetValue['startDate'];?></strong> - <?php } ?> <strong><?=$widgetValue['endDate'];?> :  </strong><?=$widgetValue['eventname'];?>
               <div>
               </div>
            </div>
            <?php if(!empty($widgetValue['possible_child_pages'])) { ?>
                <div class="collections_a">
                    <?php 
                      $prevValueExist = false;
                      foreach($widgetValue['possible_child_pages'] as $childKey => $childValue) { 
                          if($prevValueExist){ ?>
                            <b>|</b>
                      <?php }
                          ?>
                        <a class="quick-links ga-analytic" href="<?=$childValue['page_url'];?>" <?=$gaattrChild;?>><?=$childValue['page_name'];?></a> 
                        
                    <?php $prevValueExist = true;} ?>
                </div>
              <?php } ?>
         </div>
     <?php } ?>
    </div>
    <!--only for mobile case-->
    <div class="viewSectn">
        <a class="button button--orange ga-analytic" href="<?=$allExamUrl;?>" <?=$gaattrAll;?>>View All Exams</a>
    </div>
 </div>