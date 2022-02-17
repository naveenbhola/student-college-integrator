<?php
$call = 0;
if($validateuser == "false"){
	global $callMeWidgetNumbers;
	if(array_key_exists($institute->getId(),$callMeWidgetNumbers)){
		$class2 = "call-icn".$class;
		$title = "I want to talk to a Counselor of this institute";
		$button = "Call Me Now";
		$call = 1; 
	}else{
		$class2 = "inst-icn".$class;
		$title = "I want this institute to counsel me";
		$button = "Submit";
	}
?>
	<div class="pink-box shadow-box section-cont <?=$widget?>" style="overflow:hidden; margin-bottom:20px">
	<div class="sprite-bg cols-title<?=$class?>">
		<span class="sprite-bg <?=$class2?>"></span>
		<p><?=$title?></p>
	</div>
	<div class="padd-10">
	<p style="width:<?=$width?>">Share your details with this institute.<br/>
	<?php
		$this->load->library('user_agent');
		if (!($this->agent->browser() == 'Internet Explorer' and $this->agent->version() <= 6)){
	?>
<!--    <div class="clearFix spacer10"></div>
    <a class="login-fb" href="javascript:void(0);" onclick="fb_login('setCookieForFB(\'fHeader\');callFConnectAndFShare(\'fConnect\');');" ></a>
    <div style="margin:5px 90px;">or</div>-->
	<?php
		}
	?>
	</p>
	<div class="spacer10 clearFix"></div>
	<div style="width:<?=$width?>">
		<form id="form_<?=$widget?>" onsubmit="processResponseForm('<?=$widget?>','showListingPageRecommendationLayer'); return false;" novalidate>
			<ul>
				<?php $this->load->view('listing/listingPage/widgets/responseWidgetCommonMiddle'); ?>
			</ul>
			<ul>
				<li>
					<p>Type in the characters you see in the picture below</p>
					<div class="clearFix spacer10"></div>
					<div>
						<img class="vam" align = "top" src="/CaptchaControl/showCaptcha?width=100&height=34&characters=5&randomkey=<?php echo rand(); ?>&secvariable=secCodeIndex_<?=$widget?>" width="100" height="34"  id = "secureCode_<?=$widget?>"/>
						<input type="text"  style="width:100px" class="universal-txt-field" name = "homesecurityCode_<?=$widget?>" id = "homesecurityCode_<?=$widget?>" validate = "validateSecurityCode" maxlength = "5" minlength = "5" required = "1" caption = "Security Code"/>
						<div style="display:none"><div class="errorMsg" style="padding-left:3px; clear:both; display:block" id="homesecurityCode_<?=$widget?>_error"></div></div>
					</div>
				</li>
				<li>
                	<div class="spacer15 clearFix"></div>
					<button id="submit_<?=$widget?>" style="margin-bottom:5px; vertical-align:middle" type="button" onclick="$j(this).parent().parent().parent().trigger('submit');" class="orange-button"><?=$button?> <span class="btn-arrow"></span></button>
					<?php
					if($responseCount >= 10){
					?>
					<span style="margin-top:10px;color:#444;margin:5px;" wrap="nowrap"><?=$responseCount?>&nbsp;people&nbsp;have&nbsp;already&nbsp;applied</span>
					<?php
					}
					?>
				</li>
			</ul>
			<input type="hidden" value="<?=$institute->getId()?>" id="institute_id_<?=$widget?>">
			<input type="hidden" value="<?=html_escape($institute->getName())?>" id="institute_name_<?=$widget?>">
		</form>
	</div>
	</div>
	<div class="clearFix"></div>
	<?php if($widget == 'listingPageBottom'){ ?> <div class="bottom-girl2"></div> <?php } ?>
	</div>
<?php
}else{
	$firstname = $validateuser[0]['firstname'];
	$mobile = $validateuser[0]['mobile'];
	$cookiestr = $validateuser[0]['cookiestr'];
?>
	<div class="pink-box shadow-box <?=$widget?>"  style="margin-bottom:20px">
		<div class="padd-10">
		<h2 id="defaultHeading_<?=$widget?>" style="width:<?=$width?>">Interested in studying at <?=html_escape($institute->getName())?></h2>
		<table id="finalHeading_<?=$widget?>" width="100%" cellpadding="0" cellspacing="0">
			<tr>
				<td style="vertical-align:middle;width:30px;padding-bottom:10px">
					<img border="0" src="/public/images/cn_chk.gif">
				</td>
				<td style="vertical-align:middle;">
					<h2>
						E-Brochure successfully mailed
					</h2>
				</td>
		</table>
		<div style="width:<?=$width?>">
		<form id="form_<?=$widget?>" onsubmit="processResponseForm('<?=$widget?>','showListingPageRecommendationLayer'); return false;" novalidate>
			<ul>
				<?php $this->load->view('listing/listingPage/widgets/responseWidgetCommonMiddle'); ?>
				<li class="captcha">
					<button id="big-button_<?=$widget?>" type="button" onclick="" class="big-button">Request Free <br />E-Brochure</button>
				</li>
				<?php
				if($responseCount >= 10){
				?>
				<li style="margin-top:10px;height:25px;color:#444">
					<?=$responseCount?> people have already applied
				</li>
				<?php
				}
				?>
			</ul>
			<input type="hidden" value="<?=$institute->getId()?>" id="institute_id_<?=$widget?>">
			<input type="hidden" value="<?=html_escape($institute->getName())?>" id="institute_name_<?=$widget?>">
		</form>
		</div>
		<div class="clearFix"></div>
		
		<?php if($widget == 'listingPageBottom'){ ?> <div class="listing-girl"></div> <?php } ?>
	</div>
	</div>
<?php	
}
?>
<script>
reloadWidget('<?=$widget?>');
var call = <?=$call?>;
if(call){
	recordCallWidgetLoad(<?=$institute->getId()?>,<?=$course?$course->getId():0?>,'<?=$widget?>');
}
</script>
