<?php
        $widget = '';
        if($blogObj->getType() == 'boards'){
                $widget = 'BOARDS_BOTTOM_STICKY_WIDGET';
        }
        else if($blogObj->getType() == 'coursesAfter12th'){
                $widget = 'CA12_BOTTOM_STICKY_WIDGET';
        }
?>
<div class="sticky-dv">
   <label for="chckbx" class="rc__circle"><i></i></label>
   <input type="checkbox" class="custom-chckbx ga-analytic" id="chckbx" data-vars-event-name="BSB_click_arrow">
   <div class="stcky-cont">
      <div class="nav__sec">
            <p class="title__txt">
                Get Admission Updates and Exam Alerts on Shiksha
            </p>
            <p>
                <a data-vars-event-name="BSB_REGISTRATION" href="<?php echo SHIKSHA_HOME;?>/muser5/UserActivityAMP/getRegistrationAmpPage?fromwhere=<?php echo $pageType;?>&entityId=<?php echo $blogId; ?>&entityType=<?php echo $entityType;?>&pos=<?php echo $pos;?>&actionType=<?php echo $actionType;?>&widget=<?=$widget?>" class="ga-analytic nav__signup">Sign Up</a>
            </p>
        </div>
    </div>
</div>


