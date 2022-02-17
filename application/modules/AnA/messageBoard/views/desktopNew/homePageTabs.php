<div class="tabSection">
  <ul class="">
      <li <?php if($pageType=='home'){ ?> class="active"  <?php } ?> data-index="1"><h3><a class="head" href='<?=SHIKSHA_ASK_HOME;?>' onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','QnAHOME_TAB_HOMEPAGE_DESKAnA','<?php echo $GA_userLevel;?>');">Q&A Home</a></h3></li>
      <li  <?php if($pageType=='discussion'){ ?> class="active"  <?php } ?> data-index="2"><h3><a class="head" href='<?=SHIKSHA_ASK_HOME_URL."/discussions";?>' onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','DISCUSSIONS_TAB_HOMEPAGE_DESKAnA','<?php echo $GA_userLevel;?>');">Discussions</a></h3></li>
      <li  <?php if($pageType=='unanswered'){ ?> class="active"  <?php } ?> data-index="3"><h3><a class="head" href='<?=SHIKSHA_ASK_HOME_URL."/unanswers";?>' onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','UNANSWERED_TAB_HOMEPAGE_DESKAnA','<?php echo $GA_userLevel;?>');">Unanswered Questions</a></h3></li>
  </ul>
</div>
