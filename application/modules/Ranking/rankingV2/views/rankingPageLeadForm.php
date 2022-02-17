<div id="marketingLocationLayer_ajax">
<?php
$httpReferer = "http://shiksha.com#ranking";
$formPostUrl = '/user/Userregistration/homepageUserRegistration/seccodehome';
?>
<div class="ranking_page_registration_widget_title" style='*height:0.01%'>Find the best institute for yourself</div>
<p style='*height:0.01%'>
<?php if(is_array($Validatelogged)): ?>
	We need a few details from you to suggest you relevant institutes.
<?php else: ?>
	We need a few details from you to suggest you relevant institutes &amp; create your free Shiksha account.
<?php endif; ?>
</p>
<div class="spacer15 clearFix"></div>
<div id ="text102">
	<ul class="find-inst-form">
		<!--li class="pref-row">Study Preference: 
        	<input id ="prefindia_786" name="abroad" type="radio" checked="checked" onclick="loadForm(this.id);" /> India &nbsp;
        	<input id ="prefabroad_786" name="abroad"  onclick="loadForm(this.id);" type="radio"/> Abroad
        </li-->
    </ul>
	<div id="mainForm" style="width:270px;">
		<?php echo Modules::run('registration/Forms/LDB',NULL,'findInstitute',array('registrationSource' => 'RANKING_PAGE_REGISTRATION_WIDGET','referrer' => $httpReferer)); ?>
	</div>
	<!--div id="mainFormAbroad" style="display:none; width:270px;">
		<?php //echo Modules::run('registration/Forms/LDB','studyAbroad', NULL,array('registrationSource' => 'RANKING_PAGE_REGISTRATION_WIDGET','referrer' => $httpReferer)); ?>
	</div-->
</div>

<script type="text/javascript">
function loadForm(type) {
	var url = '';
	if(type == 'prefindia_786') {
		document.getElementById('mainForm').style.marginLeft="0px";
        document.getElementById('mainForm').style.display ="";
        document.getElementById('mainFormAbroad').style.display ="none";
	}		
	if(type == 'prefabroad_786') {
		document.getElementById('mainForm').style.marginLeft="-20px";
        document.getElementById('mainForm').style.display ="none";
        document.getElementById('mainFormAbroad').style.display ="";
	}				
}
</script>