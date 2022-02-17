<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="https://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Shiksha.com</title>
<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("common_new"); ?>" type="text/css" rel="stylesheet" />
<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("registration"); ?>" type="text/css" rel="stylesheet" />
<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("customMarketingPage"); ?>" type="text/css" rel="stylesheet" />
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('common'); ?>"></script>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('header'); ?>"></script>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('ajax-api'); ?>"></script>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('userRegistration'); ?>"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript">
$j = jQuery.noConflict();
</script>

</head>

<body>
<!--Start_Heeader-->
<div class="headerBG">
<div class="W984" style="padding-top:13px;">
	<div class="logoImg">&nbsp;</div>
    <div class="capImg">
    	<div style="height:67px;"></div>
        <div style="width:305px; margin:0 auto;">
        	<div class="fb_IC"></div>
            <div class="fbTxt">For interesting updates join us on facebook/shikshacafe</div>
        </div>
    </div>
    <div class="clrFix"></div>
</div>
</div>
<!--End_Heeader-->
<div class="W984">
<!--start_leftColumn-->
<div id="leftColumn">
	<div class="W343">This is the launchpad/platform connecting you to your best suited education destination.</div>
    <div class="planeImg">&nbsp;</div>
    <div style="height:380px;">&nbsp;</div>
    <div class="rightIMg">Know the How, What, Why, Where &amp; When –</div>
    <div id="knowWrap">
    	<div id="knowTransp"></div>
    	<div id="textDiv">
        	<ul>
            	<li>
                	<div class="book_IC">&nbsp;</div>
                    <p>Explore 40,000 Courses</p>
                </li>
                <li class="space15"></li>
                <li>
                	<div class="badge_IC">&nbsp;</div>
                    <p>Find 6000+ Institutes</p>
                </li>
                <li class="space15"></li>
                <li>
                	<div class="copy_IC">&nbsp;</div>
                    <p>Discover 100+ Careers</p>
                </li>
                <li class="space15"></li>
                <li>
                	<div class="bell_IC">&nbsp;</div>
                    <p>Ask 900+ Experts</p>
                </li>
            </ul>            
        </div>
    </div>
    <div><img src="/public/images/customMarketingPage/bottomBlue.gif" width="464" height="12" /></div>
    <div style="height:87px;"></div>
</div>
<!--end_leftColumn-->
<!--start_rightColumn-->
<div id="rightColumn">
<!--Start_FormContainer-->
<div id="FormWrapper">
	<div class="W355">
	<div class="student_IC">&nbsp;</div>
    <h3>Find the best institute for yourself</h3>
    <div class="clrFix" style="height:6px;"></div>
    <div class="T12">We need a few details from you to suggest you relevant institutes &amp; create <br />your free Shiksha account. </div>
    <div class="space15"></div>
    </div>
    <div>
    <!--Start_Form_here-->
    <div id ="text102">
		<ul class="find-inst-form">
			<li class="pref-row">Study Preference: 
				<input id ="prefindia" name="abroad" type="radio" checked="checked" onclick="loadForm(this.id);" /> India &nbsp;
				<input id ="prefabroad" name="abroad"  onclick="loadForm(this.id);" type="radio"/> Abroad
			</li>
		</ul>
		<?php
		$currentPageURL = getCurrentPageURL();
		?>
		<div id="mainForm" style="width:406px;">
				<?php echo Modules::run('registration/Forms/LDB',NULL,NULL,array('registrationSource' => 'MARKETING_FORM','referrer' => $currentPageURL)); ?>
		</div>
		<div id="mainFormAbroad" style="display:none; width:406px;">
				<?php echo Modules::run('registration/Forms/LDB','studyAbroad',NULL,array('registrationSource' => 'MARKETING_FORM','referrer' => $currentPageURL)); ?>
		</div>

	</div> 
    <!--End_Form_here-->
    </div>
</div>
<!--End_FormContainer-->
<div class="handImg">&nbsp;</div>
<div class="W329">
<div>See you not just reach but enjoy the journey on your career success highway…</div>
<div align="center" style="margin-top:-5px;"><img src="/public/images/customMarketingPage/welcomeTxt.gif" width="262" height="75" /></div>
</div>
</div>
<!--end_rightColumn-->
<div class="clrFix"></div>
</div>
<script>
	function loadForm(id) {
		if (id == 'prefindia') {
			document.getElementById('mainForm').style.display = '';
			document.getElementById('mainFormAbroad').style.display = 'none';
		}
		else {
			document.getElementById('mainForm').style.display = 'none';
			document.getElementById('mainFormAbroad').style.display = '';
		}
	}
</script>
</body>
</html>
