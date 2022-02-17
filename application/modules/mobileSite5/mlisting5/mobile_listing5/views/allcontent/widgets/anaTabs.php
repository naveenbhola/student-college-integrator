      <div class="qa-tabs-box padBottom">
         <ul>
             <li <?php if($contentType=='question'){ ?> class="current"  <?php } ?>><a href="<?=$base_url?>" role="tab" data-toggle="tab" id = "home" contentType='<?php echo $contentType; ?>' ga-attr="QUESTIONS_TAB"><h3>QUESTIONS</h3></a><span></span></li>
             <li <?php if($contentType=='discussion'){ ?> class="current" <?php } ?>><a href="<?=$base_url.'?type=discussion'?>" role="tab" data-toggle="tab" id = "discussion" contentType='<?php echo $contentType; ?>' ga-attr="DISCUSSIONS_TAB"><h3>DISCUSSIONS</h3></a><span></span></li>
             <li <?php if($contentType=='unanswered'){ ?> class="current" <?php } ?>><a href="<?=$base_url.'?type=unanswered'?>" role="tab" data-toggle="tab" id = "unanswered" contentType='<?php echo $contentType; ?>' ga-attr="UNANSWERED_TAB"><h3>UNANSWERED</h3></a></li>
             <p class="clr"></p>
         </ul>
      </div>


