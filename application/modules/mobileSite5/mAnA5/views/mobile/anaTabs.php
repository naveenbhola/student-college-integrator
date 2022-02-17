      <div class="qa-tabs-box">
         <ul id="tablist">
             <li <?php if($pageType=='home'){ ?> class="current"  <?php } ?>><a href="<?=SHIKSHA_ASK_HOME?>" role="tab" data-toggle="tab" id = "home" pageType='<?php echo $pageType; ?>' ><h1>Q&A HOME</h1></a><span></span></li>
             <li <?php if($pageType=='discussion'){ ?> class="current" <?php } ?>><a href="<?=SHIKSHA_ASK_HOME_URL.'/discussions'?>" role="tab" data-toggle="tab" id = "discussion" pageType='<?php echo $pageType; ?>' ><h1>DISCUSSIONS</h1></a><span></span></li>
             <li <?php if($pageType=='unanswered'){ ?> class="current" <?php } ?>><a href="<?=SHIKSHA_ASK_HOME_URL.'/unanswers'?>" role="tab" data-toggle="tab" id = "unanswered" pageType='<?php echo $pageType; ?>'><h1>UNANSWERED</h1></a></li>
             <p class="clr"></p>
         </ul>
      </div>
