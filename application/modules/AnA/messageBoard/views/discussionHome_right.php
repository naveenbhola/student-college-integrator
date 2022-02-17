<input type="hidden" value="1" id="alertId">
<?php
	$userId = isset($validateuser[0]['userid'])?$validateuser[0]['userid']:'';
	$displayName = isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:'';
?>

<div style="display:block; ">
	<!--Start_UserInfo-->
	<?php 
		//$dataForRankAndReputation = array('reputationPoints' => $reputationPoints,'rank' => $rank);
		//if(isset($leaderBoardInfo)){ $this->load->view('messageBoard/userLeaderBoard', $dataForRankAndReputation);		
		//echo "<div class='lineSpace_20'>&nbsp;</div>"; }
	?>
    <?php if($userId!='' && $userId>0){ ?>
    <div id="userLeaderBoardDiv">
    <script>
           var jsForWidget = new Array();
           addWidgetToAjaxList('/UserLeaderBoard/UserLeaderBoardWidget/index','userLeaderBoardDiv',jsForWidget);
    </script>
    </div>
    <?php } ?>
	<!--End_UserInfo-->
	<!--Start_VCard widget-->
	<?php if(isset($cardStatus)) { ?>
<!--	<div>
		  <div class="raised_all">
			  <b class="b2"></b><b class="b3"></b><b class="b4"></b>
			  <div class="boxcontent_all" style="padding: 3px 3px 3px 3px;">
				<div class="widgetVcard">
					<?php if($cardStatus=='0'){ ?>
					<span><a href="/messageBoard/MsgBoard/vcardForm">Create Your Exclusive Shiksha V-Card here</a></span><br><span style="color:#696969;font-size:11px">and increase your fan following</span>
					<?php }else if($cardStatus=='1'){ ?>
					<span><a href="/messageBoard/MsgBoard/vcardForm">Update Your Exclusive Shiksha V-Card here</a></span><br><span style="color:#696969;font-size:11px">and increase your fan following</span>
					<?php } ?>
				</div>
			  </div>
			  <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
		  </div>
	</div>
	<div class="lineSpace_10">&nbsp;</div>-->
	<?php } ?>
	<!--End_VCard widget-->

    <!--Widget for panelExpert
    <?php if(isset($leaderBoardInfo['msgArray'][0]['isAnAExpert']) && $leaderBoardInfo['msgArray'][0]['isAnAExpert']=='1'){ ?>
    <div class="expert-btn">
         <a href="/messageBoard/MsgBoard/expertOnboard" style="color:#713712;"><span>You are a Shiksha expert<br /><strong>Edit your profile</strong></span></a>
    </div>
    <div class="lineSpace_20">&nbsp;</div>
    <?php }else{ ?>
    <div class="expert-btn">
         <a href="/messageBoard/MsgBoard/expertOnboard" style="color:#713712;"><span>Become an expert<br /><strong>Get featured as cafe star</strong></span></a>
    </div>
    <div class="lineSpace_20">&nbsp;</div>
    <?php } ?>
    End_Widget for panelExpert-->

	<!--Widget for panelExpert-->
	 <div class="ana-experts-main">
        <div class="ana-experts">
        <h2>Ask &amp; Answer<br><a href="/messageBoard/MsgBoard/advisoryBoard">Panel of Experts</a></h2>
        </div>
    	</div>
    
	<div class="lineSpace_20">&nbsp;</div>
	<!--End_Widget for panelExpert-->

	<!--Start_Top Contributors-->
	<div id='topContributorsDiv'>
		<div class="cafe-star-pannel">
			
			<div class="boxcontent_lgraynoBG">
				<div class="defaultAdd lineSpace_5">&nbsp;</div>
				<div align="center"><img src="/public/images/loader.gif"/></div>
				<div class="defaultAdd lineSpace_5">&nbsp;</div>
			</div>
			
		</div>
	</div>
	<!--End_Top Contributors-->

</div>
<div class="clearFix spacer20"></div>
<div class="cafe-star-pannel">
<div class="fb-like-box" data-href="http://www.facebook.com/shikshacafe" data-width="300" data-show-faces="true" data-border-color="#fff" data-stream="false" data-header="false"></div>
</div>
<div class="clearFix spacer20"></div>
