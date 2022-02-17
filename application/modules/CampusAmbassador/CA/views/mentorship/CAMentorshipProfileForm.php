<?php
$quickSignUser = is_array($validateuser)?$validateuser[0]['quicksignuser']:0;
$formData = array();
if(isset($validateuser[0]['cookiestr'])) {
	$cookieStr = $validateuser[0]['cookiestr'];
	$cookieArray = explode('|',$cookieStr);
	$formData['email'] = $cookieArray[0];
	$formData['firstname'] = htmlspecialchars($validateuser[0]["firstname"]);
	$formData['lastname'] = htmlspecialchars($validateuser[0]["lastname"]);
	$formData['mobile'] = htmlspecialchars($validateuser[0]["mobile"]);
}else {
	$formData['email'] = "";
	$formData['firstname'] = "";
	$formData['lastname'] = "";
	$formData['mobile'] = "";
}
$this->load->view('campusRepHeader');
if($status=='draft'){
	$mainHeading = 'We have received your application.';
	$bodyText = "Thank you! We've received your application for the campus connect program. We'll contact you soon regarding your selection.<br/>In the meantime, you can help prospective ".$programName." students by answering their doubts and questions.";
}
if($status=='rejected'){
	$mainHeading = 'Your profile has been rejected.';
	$bodyText = 'For further information contact us at campusconnect.shiksha@gmail.com';
}
if($status=='deleted'){
	$mainHeading = 'Your profile has been deleted.';
	$bodyText = 'For further information contact us at campusconnect.shiksha@gmail.com';

}
?>
<div class="thankyou-layer" id="popupBasic" style="display: none;">

<h5><?php echo $mainHeading;?></h5>
<p><?php echo $bodyText;?></p>
<?php
if($status == 'draft')
{
?>
      <div class="mentor-ok-btn"><a href="javascript:void(0);" class="" onclick="redirectUserAfterSubmission('<?php echo $redirectionURL;?>')">OK</a></div>
<?php
}
?>
</div>
<div id="popupBasicBack">	
</div>
		<div id="header" class="clearFix">
		<div id="logo-section">
		<a title="Shiksha.com" tabindex="6" href="https://www.shiksha.com">
			<i class="icons ic_logo"></i>
		</a>
		</div>
		</div>
                <div id="connect-wrapp" style="padding:0;">
			<?php         
			$this->load->view('mentorship/campusRepOnBoardHeader');
			?>
			<div class="connect-form clear-width">
				<?php $this->load->view('mentorship/MentorOnbordingForm', $formData); ?>
			</div>
                </div>
                
                <div class="clearFix"></div>
        <!--/div-->

<?php 
	$this->load->view('common/footer');
?>

<?php              
$this->load->view('campusRepOnBoardHeaderAnimation');
if(isset($showLayer) && $showLayer=='YES'){ ?>
	<script>
	    showLayer('fromMentorForm');
	</script>
<?php }
?>
<style>
    .suggestion-box{color:red;position: absolute;background: #fff;z-index: 99;border:1px solid #ccc;width:280px;border-width: 0px 1px 1px 1px; padding: 0px;}
    .suggestion-box  li{padding: 10px 16px 6px; border-bottom: 1px solid #F7F7F7;margin-bottom: 0px !important}
    .suggestion-box .suggestion-box-active-option {background: #F9F9F9 none repeat scroll 0% 0% !important;color: #000;list-style: outside none none;}
    .suggestion-box li span {display: block;color: #999;font-size: 12px;font-weight: 400;line-height: 20px;}
    .suggestion-box  li .suggestion-box-normal-option {background: #FFF none repeat scroll 0% 0%;}
</style>
<script>
	var programId  = '<?php echo $programId?>';
	var entityName = '<?php echo $entityName?>';
	var entityId   = '<?php echo $entityId?>';
	$j('#footer').hide();
	var mentorShipPageFlag = true;
	try{
	    addOnBlurValidate(document.getElementById('MentorProfileForm'));
	} catch (ex) {
	}  
	$j(document).mouseup(function (e) {
        //hide exam container when clicked outside the container
        var container = $j("#dummy_input");
        if (!container.is(e.target) // if the target of the click isn't the container...
            && container.has(e.target).length === 0) // ... nor a descendant of the container
        {
            handleCampusProfileInputKeysForInstituteSuggestor();
            setTimeout(function() {autoSuggestorInstanceArray.autoSuggestorInstanceInstitute.hideSuggestionContainer()}, 1);
        }

        //disable search input box when clicked outside
    });  
</script>
</body>
</html>
