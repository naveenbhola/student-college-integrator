
<div class="tabSection" style="margin:30px 0 0">
  <ul class="">
      <li <?php if($pageType=='all'){ ?> class="active"  <?php } ?> data-index="1"><h2><a class="head" href='<?=$baseURL?>' pageType='<?php echo $pageType; ?>' id = "home" onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','<?php echo $GA_Tap_On_All_Tab;?>','<?php echo $GA_userLevel;?>');">All</a></h2></li>
      <li  <?php if($pageType=='discussion'){ ?> class="active"  <?php } ?> data-index="2"><h2><a class="head" href='<?=$baseURL.'?type=discussion'?>' pageType='<?php echo $pageType; ?>' id = "discussion" onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','<?php echo $GA_Tap_On_Disc_Tab;?>','<?php echo $GA_userLevel;?>');">Discussions</a></h2></li>
      <li  <?php if($pageType=='unanswered'){ ?> class="active"  <?php } ?> data-index="3"><h2><a class="head" href='<?=$baseURL.'?type=unanswered'?>' id = "unanswered" pageType='<?php echo $pageType; ?>' onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','<?php echo $GA_Tap_On_Unans_Tab;?>','<?php echo $GA_userLevel;?>');">Unanswered Questions</a></h2></li>
  </ul>
</div>

