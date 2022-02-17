<!--Start_of_Top_Contributes-->
<div class="cafe-star-pannel">
	<div class="spacer10 clearFix"></div>
	<h2 class="Fnt16 bld mlr10">Ask & Answer stars <img src="/public/images/cafeStar.gif" border=0 align="absmiddle"/></h2>
  <div class="spacer10 clearFix"></div>
      <div class="boxcontent_skyWithBGW">
	  <?php if($tc == "1"){ ?>
	  <div class="pt5" style="background:#ececec;border-top:1px solid #EDEDED; border-bottom:2px solid #fff;padding:7px;">
	      <h3 class="Fnt12 bld float_L" style="">Top Contributors</h3>
	      <?php 
		    $topContributtos = (isset($topContributtingAndExpertPanel[0]) && is_array($topContributtingAndExpertPanel[0]))?$topContributtingAndExpertPanel[0]['mostcontributing']:array();
		    if(count($topContributtos)==0) {$showMessage = "No top contributors"; $showLink = false;}
		    else if(count($topContributtos)>0 && count($topContributtos)<10) {$showMessage = "1 - ".count($topContributtos); $showLink = false;}
		    else {$showMessage = "1 - 10"; $showLink=true;}
		    $categoryId = (isset($catId))?$catId:1;
	      ?>
	      <div class="float_R Fnt11" id="topContributorsPage"><span id='topCWaitingDiv' style='display:none'><img src='/public/images/working.gif' border='0' align='absmiddle'/></span> <?php echo $showMessage;?> 
			<?php if($showLink){ ?><a href="javascript:void(0);" onClick="showContributorsList(1,<?php echo $categoryId;?>);">Next &#187;</a>
			<?php } ?></div>
	      <div class="clear_L">&nbsp;</div>
	  </div>
	  <div class="mlr10">
	      <div id="contributorList">
		    <?php
		    $data['showContributor'] = 1;
		    $data['showParticipant'] = 0;
		    $data['topContributtingAndExpertPanel'] = $topContributtingAndExpertPanel;
		    $this->load->view('messageBoard/topContributorsList',$data); 
		?>
	      </div>
	      <input type="hidden" id="startTopContributorList" name="startTopContributorList" value="0"/>
	      <input type="hidden" id="isWeekly" name="isWeekly" value="1"/>
	  </div>
	  <?php } ?>
	  <?php if($tp == "1"){ ?>
	  <div class="pt5" style="background:#ececec;border-bottom:2px solid #fff;padding:7px;" id="topParticipantsDiv">
	      <h3 class="Fnt12 bld float_L">Top Participants</h3>
	      <?php 
		    $topContributtosP = (isset($topContributtingAndExpertPanel[0]) && is_array($topContributtingAndExpertPanel[0]))?$topContributtingAndExpertPanel[0]['mostcontributingParticipate']:array();
		    if(count($topContributtosP)==0) {$showMessage = "No top participants"; $showLink = false;}
		    else if(count($topContributtosP)>0 && count($topContributtosP)<10) {$showMessage = "1 - ".count($topContributtosP); $showLink = false;}
		    else {$showMessage = "1 - 10"; $showLink=true;}
	      ?>
	      <div class="float_R Fnt11" id="topContributorsPageP"><span id='topCWaitingDivP' style='display:none'><img src='/public/images/working.gif' border='0' align='absmiddle'/></span> <?php echo $showMessage;?> <?php if($showLink){ ?><a href="javascript:void(0);" onClick="showContributorsListP(1);">Next &#187;</a><?php } ?></div>
	      <div class="clear_L">&nbsp;</div>
	  </div>
	  <div class="mlr10" >
	      <div id="contributorListP">
		    <?php
		    $data1['showContributor'] = 0;
		    $data1['showParticipant'] = 1;
		    $data1['topContributtingAndExpertPanel'] = $topContributtingAndExpertPanel;

		    $this->load->view('messageBoard/topContributorsList',$data1); 
		?>
	      </div>
	      <input type="hidden" id="startTopContributorListP" name="startTopContributorListP" value="0"/>
	      <input type="hidden" id="isWeeklyP" name="isWeeklyP" value="1"/>
	  </div>
	  <?php } ?>
<!-- 	  <div class="lineSpace_30 mlr10"><a href="/shikshaHelp/ShikshaHelp/upsInfo" class="fs11"><b>How do I feature here?</b></a></div> -->
      </div>
</div>

<!--End_of_Top_Contributes-->

