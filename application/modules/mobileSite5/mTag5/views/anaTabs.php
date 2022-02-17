      <div class="qa-tabs-box">
         <ul id="tablist">
             <li <?php if($pageType=='all'){ ?> class="current"  <?php } ?>><a onclick="gaTrackEventCustom('TAG DETAIL PAGE','ALLTAB_TAGDETAIL_WEBAnA','<?php echo addslashes($data['tagName']);?>',this,'<?=$baseURL?>');" href="<?=$baseURL?>" role="tab" data-toggle="tab" id = "home" pageType='<?php echo $pageType; ?>' ><h2>ALL</h2></a></li>

             <li <?php if($pageType=='discussion'){ ?> class="current" <?php } ?>><a onclick="gaTrackEventCustom('TAG DETAIL PAGE','DISCUSSIONTAB_TAGDETAIL_WEBAnA','<?php echo addslashes($data['tagName']);?>',this,'<?=$baseURL.'?type=discussion'?>');" href="<?=$baseURL.'?type=discussion'?>" role="tab" data-toggle="tab" id = "discussion" pageType='<?php echo $pageType; ?>' ><h2>DISCUSSIONS</h2></a></li>

             <li <?php if($pageType=='unanswered'){ ?> class="current" <?php } ?>><a onclick="gaTrackEventCustom('TAG DETAIL PAGE','UNANSWERED_TAGDETAIL_WEBAnA','<?php echo addslashes($data['tagName']);?>',this,'<?=$baseURL.'?type=unanswered'?>')" href="<?=$baseURL.'?type=unanswered'?>" role="tab" data-toggle="tab" id = "unanswered" pageType='<?php echo $pageType; ?>'><h2>UNANSWERED</h2></a></li>

             <p class="clr"></p>
         </ul>
      </div>
