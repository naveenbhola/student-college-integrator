 <div class="wdh100">
	<!-- The banner start here -->
	<div align="center">
			<?php
					$categoryIdForBanner = isset($CategoryList[array_rand($CategoryList)])?$CategoryList[array_rand($CategoryList)]:1;
					$criteriaArray = array(
										'category' => $categoryIdForBanner,
										 'country' => '',
										 'city' => '',
										 'keyword'=>'');
					$bannerProperties = array('pageId'=>'DISCUSSION_DETAIL','pageZone'=>'SIDETOP','shikshaCriteria' => $criteriaArray);
					$this->load->view('common/banner.php', $bannerProperties);
			?>
	</div>
	<!--The banner end here -->
	<div class="spacer10 clearFix"></div>

    <!--Widget for panelExpert
    <?php if( isset($loggedInUserLevel) && $loggedInUserLevel!='Beginner' && $loggedInUserLevel!='Trainee' ){ ?>
    <div class="expert-btn">
         <a href="/messageBoard/MsgBoard/expertOnboard" style="color:#713712;"><span>You are an Expert<br /><strong>Edit your profile</strong></span></a>
    </div>
    <div class="lineSpace_20">&nbsp;</div>
    <?php }else{ ?>
    <div class="expert-btn">
         <a href="/messageBoard/MsgBoard/expertOnboard" style="color:#713712;"><span>Become an expert<br /><strong>Get featured as cafe star</strong></span></a>
    </div>
    <div class="lineSpace_20">&nbsp;</div>
    <?php } ?>
    <!--End_Widget for panelExpert-->

	<div id='topContributorsDiv'>
		<div class="raised_lgraynoBG">
			<b class="b2"></b><b class="b3"></b><b class="b4"></b>
			<div class="boxcontent_skyWithBGW" style="border-left:1px solid #DADADA;border-right:1px solid #DADADA;">
				<div class="defaultAdd lineSpace_5">&nbsp;</div>
				<div align="center"><img src="/public/images/loader.gif"/></div>
				<div class="defaultAdd lineSpace_5">&nbsp;</div>
			</div>
			<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
		</div>
	</div>
	<?php if($fromOthersTopic!='announcement' && $fromOthersTopic!='discussion' && $fromOthersTopic!='review' && $fromOthersTopic!='eventAnA'){ ?>

	<div class="spacer10 clearFix"></div>
	<!-- Start: Code to show the Related institutes -->
	<?php //$this->load->view("listing/relatedInstitutes",array('resultArr' => $relatedListings,'showOther' => false)); ?>
	<!--<div id='relatedInstitutesDiv'>
		<script>getRelatedInstitutes('<?php echo $randomCategory?>');</script>
		<div class="raised_all">
			<b class="b2"></b><b class="b3"></b><b class="b4"></b>
			<div class="boxcontent_all">
				<div class="defaultAdd lineSpace_5">&nbsp;</div>
				<div align="center"><img src="/public/images/loader.gif"/></div>
				<div class="defaultAdd lineSpace_5">&nbsp;</div>
			</div>
			<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
		</div>
		<div class="lineSpace_10">&nbsp;</div>
	</div>-->
	<!-- End: Code to show the Related institutes -->
  
	<?php 
			if($closeDiscussion != 1){ 
				if($WidgetStatus['result'] == 0){
					$alertId = "";
					$alertLable = 'Press Subscribe button below to receive an email alert if a new answer is posted for this question.';
					$buttonAction = "javascript:subscribeAlert(".$topicId.",'','');";
					$buttonLable = 'Subscribe';
				}elseif(isset($WidgetStatus['state']) && ($WidgetStatus['state'] == 'on')){
					$alertId = isset($WidgetStatus['alert_id'])?$WidgetStatus['alert_id']:0;
					$alertLable = 'You are registered to receive an email alert if a new answer is posted on this question. Press Unsubscribe button below to remove this email alert.';
					$buttonAction = "javascript:subscribeAlert(".$alertId.",'off');";
					$buttonLable = 'Unsubscribe';
				}else{
					$alertId = isset($WidgetStatus['alert_id'])?$WidgetStatus['alert_id']:0;
					$alertLable = 'Press Subscribe button below to receive an email alert if a new answer is posted for this question.';
					$buttonAction = "javascript:subscribeAlert(".$alertId.",'on');";
					$buttonLable = 'Subscribe';
				}
	?>
	<input type="hidden" value="<?php echo $alertId; ?>" id="alertId" autocomplete="off">
<!-- Start_Widget_two -->
	<!--<div class="raised_green_h">
		<b class="b1"></b><b class="b2"></b><b class="b3" style="background:#637bc3"></b><b class="b4" style="background:#637bc3"></b>
			<div class="boxcontent_green_h" id="widgetHolder1">
				<div style="line-height:30px;color:#fff;font-size:14px;font-weight:bold;background:#637bc3 url(/public/images/followIcon.png) no-repeat left 5px;padding-left:40px">Follow this question</div>
				<div class="lineSpace_11">&nbsp;</div>
				<div id="alertLabel" class="bld" style="padding:0 10px">
					<?php //echo $alertLable; ?>
				</div>
				<div class="lineSpace_11">&nbsp;</div>
				<div class="row">
					<div class="mar_left_10p">
						<div class="buttr3" id="subscribeButton" style="margin-left:7px">
							<button class="btn-submit13 w3" onClick="<?php //echo $buttonAction; ?>">
							<div class="btn-submit13"><p class="btn-submit14 btnTxtBlog"><?php //echo $buttonLable; ?></p></div>
							</button>
						</div>
					<div class="clear_L">&nbsp;</div>
					</div>
				</div>
				<div class="lineSpace_1">&nbsp;</div>
				<div id="status_msg" class="normaltxt_11p_blk mar_full_10p"></div> 
			</div>				
		<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>		
	</div>-->			
<!-- End_Widget_two -->
	<?php } ?>
	<div class="spacer10 clearFix"></div>
<?php }else{ ?>
        <div class="spacer20 clearFix"></div>
<?php } ?>

<!--       LATEST NEWS WIDGET      -->
<?php if(!empty($latestNews)) echo $this->load->view("messageBoard/latestNews",array('articleWidgetsData' => $latestNews)); ?>
<!--<div  id="find-inst-main">
<div id="find-inst-box">
	<h2>Find the best Institute for you<br />
		<span>We need few details from you to get started</span>
    </h2>
    <span class="close-box" title="Close" onClick="closeRegistrationImage();">&nbsp;</span>
    <?php if(isset($validateuser[0]['displayname'])) $name= $validateuser[0]['displayname'];else $name='Name';?>
    <?php if(array_key_exists('cookiestr',$validateuser[0]) && $validateuser[0]['cookiestr']!='') {$verifiedData = explode('|',$validateuser[0]['cookiestr']);$email= $verifiedData[0];}else{ $email='Email Id';}?>
    <?php if(isset($validateuser[0]['mobile'])) $mobile= $validateuser[0]['mobile'];else $mobile='Mobile Number';?>
    <form method="post" onsubmit="if(validateSignUpQuestionDetialPage() != true){return false;};" >
    	<ul>
	<li><input value="<?php echo $name;?>" class="universal-txt-field" id = "quickname_ForQDP" name = "quickname_ForAnA" type = "text"  onfocus="checkTextElementOnTransition($('quickname_ForQDP'),'focus');" onblur="checkTextElementOnTransition($('quickname_ForQDP'),'blur');" default="Name"/>
	<br/>
	<span style="display:none" class="errorMsg" id="quickname_ForQDP_error"></span>
	</li>
	
	<li><input type="text" id = "quickemail_ForQDP" name = "quickemail_ForAnA"  value="<?php echo $email;?>" class="universal-txt-field" onfocus="checkTextElementOnTransition($('quickemail_ForQDP'),'focus');" onblur="checkTextElementOnTransition($('quickemail_ForQDP'),'blur');" default="Email Id"/>
	<br/>		
	<span style="display:none" class="errorMsg" id="quickemail_ForQDP_error"></span></li>
	<li><input value="<?php echo $mobile;?>" id="quickmobile_ForQDP" name="quickmobile_ForQDP" class="universal-txt-field" type="text" onfocus="checkTextElementOnTransition($('quickmobile_ForQDP'),'focus');" onblur="checkTextElementOnTransition($('quickmobile_ForQDP'),'blur');" default="Mobile Number" maxlength="10"/>
	<br/>
	<span style="display:none" class="errorMsg" id="quickmobile_ForQDP_error"></span></li>
	<li><input type="submit" value="Submit" class="orange-button" /></li>
        </ul>
    </form>
    
</div>

-->

        <div align="center">
                <?php
                        $bannerProperties = array('pageId'=>'DISCUSSION_DETAIL', 'pageZone'=>'SIDE');
                        $this->load->view('common/banner.php', $bannerProperties);
                ?>
        </div>
        <div class="latestNewsBlock">
        <div class="fb-like-box" data-href="http://www.facebook.com/shikshacafe" data-width="<?php echo ($fromOthersTopic!='announcement' && $fromOthersTopic!='discussion' && $fromOthersTopic!='review' && $fromOthersTopic!='eventAnA')?"300":"250"?>" data-show-faces="true" data-border-color="#f2f2f2" data-stream="false" data-header="false"></div>
        </div>
        <div class="clearFix spacer20"></div>
<!--


<div id="find-inst-closed" style="display:none;">
	<h2>Find the best Institute for you<br />
		<span>We need few details from you to get started</span>
    </h2>
    <span class="open-box" onClick="openRegistrationImage();">&nbsp;</span>
</div>

</div>-->
</div>
<!--<script>
function closeRegistrationImage(){ $('find-inst-box').style.display='none';$('find-inst-closed').style.display='block'; }
function openRegistrationImage(){ $('find-inst-box').style.display='block';$('find-inst-closed').style.display='none'; }

</script>-->
