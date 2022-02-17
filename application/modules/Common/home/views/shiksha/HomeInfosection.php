<?php
	if(!(is_array($validateuser) && $validateuser != "false")) {
        $onRedirect = base64_encode(SHIKSHA_ASK_HOME_URL.'/messageBoard/MsgBoard/askQuestion/ ');
		$onClick = "showuserLoginOverLay(this,'HOMEPAGE_SHIKSHAMAIN_MIDDLEPANEL_ASKQUESTION','redirect','".$onRedirect."'); return false;";
	} else if($validateuser[0]['quicksignuser'] == 1){
		$base64url = base64_encode(SHIKSHA_HOME);
		$onClick = 'javascript:location.href = \'/user/Userregistration/index/'.$base64url.'/1\';';
	} else {
		$onClick = 'location.href = \''.SHIKSHA_ASK_HOME_URL.'/messageBoard/MsgBoard/askQuestion\';';
	} 
?>

<div style="width:49%" class="float_R">		
	<div class="raised_lgraynoBG">
		<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
		<div class="boxcontent_lgraynoBG">
		  <div class="pps4 float_R" style="height:112px; padding:0;margin-right:5px"></div>
		  <div class="mar_full_10p" style="margin-right:105px">
				<div class="lineSpace_5">&nbsp;</div>
				<div><h3><span class="myHeadingControl bld">MBA Learning Options</span></h3></div>
				<br class="lineSpace_15" />
				<div class="quesAnsBullets"><a href="/search/index?keyword=MBA+correspondence&location=&searchType=course&cat_id=-1&countOffsetSearch=25&startOffSetSearch=0&subCategory=-1&subLocation=-1&cityId=-1&cType=correspondence&courseLevel=-1&subType=&showCluster=-1&channelId=home_page" class="fontSize_12p" title="Distance Learning MBA">Distance Learning MBA</a></div>
				<br class="lineSpace_10" />
				<div class="quesAnsBullets"><a href="/search/index?keyword=MBA+Part+time&location=&searchType=course&cat_id=-1&countOffsetSearch=25&startOffSetSearch=0&subCategory=-1&subLocation=-1&cityId=-1&cType=Part-time&courseLevel=-1&subType=&showCluster=-1&channelId=home_page" class="fontSize_12p" title="Part-Time MBA">Part-Time MBA</a></div>
				<br class="lineSpace_10" />
				<div class="quesAnsBullets"><a href="/search/index?keyword=Executive+MBA&location=&searchType=course&cat_id=-1&countOffsetSearch=25&startOffSetSearch=0&subCategory=-1&subLocation=-1&cityId=-1&cType=-1&courseLevel=-1&subType=&showCluster=-1&channelId=home_page" class="fontSize_12p" title="Executive MBA">Executive MBA</a></div>
				<br class="lineSpace_10" />
		   </div>
		  <div class="clear_R"></div>
		</div>
	  <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
	</div>
	<div class="lineSpace_10">&nbsp;</div>
	<div class="raised_lgraynoBG">
		<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
		<div class="boxcontent_lgraynoBG">
		  <div class="pps2 float_L" style="top:7px"></div>
		  <div class="mar_full_10p" style="margin-left:110px">
				<div class="lineSpace_5">&nbsp;</div>
				<div><h4><span class="myHeadingControl bld">Ask &amp; Answer</span></h4></div>
				<div class="lineSpace_5">&nbsp;</div>
				<div class="fontSize_12p">Got questions about higher education? Get answers from Shiksha experts and members</div>
				<div class="lineSpace_5">&nbsp;</div>
				<span><a href="#" class="fontSize_12p bld" onclick="<?php echo $onClick; ?>return false;" title="Ask Now">Ask Now</a></span>
				<div class="lineSpace_5">&nbsp;</div>
				<div class="fontSize_12p">Share your knowledge, help Shiksha members &amp; earn Shiksha points</div>
				<div class="lineSpace_5">&nbsp;</div>
				<span><a href="<?php echo SHIKSHA_ASK_HOME; ?>" class="fontSize_12p bld" title="Answer Now & Earn Points">Answer Now &amp; Earn Points</a></span>
		   </div>
				<br class="lineSpace_10" />
		  <div class="clear_L"></div>
		</div>
	  <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
	</div>	
	<div class="lineSpace_10">&nbsp;</div>
		<?php $this->load->view('home/shiksha/HomeRightPanelTestPrepAds');?>
<!--	<div class="raised_lgraynoBG">
		<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
		<div class="boxcontent_lgraynoBG">
		  <div class="pps1 float_L" style="height:88px; padding:0; margin-top:8px; margin-left:5px"></div>
		  <div class="mar_full_10p">
				<div class="lineSpace_5">&nbsp;</div>
				<div><h3><span class="myHeadingControl bld">Shiksha Groups</span></h3></div>
				<br class="lineSpace_10" />
				<div class="fontSize_12p">Interact with applicants, students, alumni &amp; faculty of an institute</div>
				<br class="lineSpace_10" />
				<span><a href="<?php echo SHIKSHA_GROUPS_HOME; ?>" class="fontSize_12p bld" title="Join your Institute Group Now">Join your Institute Group Now</a></span>
		   </div>
				<br class="lineSpace_10" />
		  <div class="clear_L"></div>
		</div>
	  <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
	</div>-->
</div>
