      <!--promotinalwidget-->
      <div class="promtn-widget">
         <div class="prmtn-card">
	     <?php 
       $gaPage = "";
       $gaActionAsk = "";
       $gaActionHome = "";
       if($pageType == 'question'){
          $gaPage = "QUESTION DETAIL PAGE";
          $gaActionAsk = "ASK_WIDGET_QUESTIONDETAIL_WEBAnA";
          $gaActionHome = "HOME_WIDGET_QUESTIONDETAIL_WEBAnA";
       } else {
          $gaPage = "DISCUSSION DETAIL PAGE";
          $gaActionAsk = "ASK_WIDGET_DISCUSSIONDETAIL_WEBAnA";
          $gaActionHome = "HOME_WIDGET_DISCUSSIONDETAIL_WEBAnA";
       }
		//In case the question owner and logged in user are not same, only then display the Ask question widget
		if($userIdOfLoginUser!=$data['entityDetails']['userId']){ ?>
             <div class="qstn-carrer">
               <h2>Have a <strong>question </strong>related to your <strong>career & education?</strong></h2>
	       <a onclick="sendGAWebAnA('<?=$gaPage;?>','<?=$gaActionAsk;?>');populateQuesDiscLayer('question','<?php echo $qtrackingPageKeyId;?>')" href="#questionPostingLayerOneDiv" data-inline="true" data-rel="dialog" data-transition="fade" class="p-btn u-btns" id="ASK_NOW_PBUTTON">Ask Now</a>
               <p class="h-line-b"><span class="h-or">or</span></p>
             </div>
	     <?php } ?>
             <div class="qstn-carrer">
               <h2>See what <strong>others like you</strong> are <strong>asking & answering  </strong></h2>
               <a onclick="sendGAWebAnA('<?=$gaPage;?>','<?=$gaActionHome;?>',this,'<?=SHIKSHA_ASK_HOME?>')" style="color: black;" href="<?=SHIKSHA_ASK_HOME?>" class="home-btn u-btns">Go to my personalized homepage</a>
              
             </div>
         </div>
      </div>