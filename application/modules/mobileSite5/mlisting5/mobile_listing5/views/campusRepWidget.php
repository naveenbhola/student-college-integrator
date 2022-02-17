<script>
var caPresent = 'true';
</script>
<?php
    $js_enabled = true;
    $isMobile = true;
    if($campusConnectAvailable){
?>
<div id = "campus-connect-sec-id" style="clear:both;">
    <?php echo Modules::run('CA/CADiscussions/getCourseTuple',$course->getId(),$institute->getId(),'campusConnect','',3,true,$isMobile); ?>
    <?php echo Modules::run('CA/CADiscussions/getQuestionForm',$course->getId(),$institute->getId(),true,$isMobile,'',$qtrackingPageKeyId); ?>

    <!-- Show the success message here if cookie is set -->
       <?php  if(isset($_COOKIE['mobile_post_suc_msg']) && $_COOKIE['mobile_post_suc_msg']!=''){ 
		$message = $_COOKIE['mobile_post_suc_msg'];
		switch($_COOKIE['mobile_post_suc_msg']){
			case 'SUQ':     $message = 'You can not answer your own question.'; break;
			case 'MTOA':    $message = 'You can not answer more than once to same question.'; break;
			case 'CAMPUSREPEXISTS':    $message = 'You can not answer on this question.'; break;
			case 'NOREP':   $message = 'You can not answer because you have zero Reputation Index.'; break;
			case 'SAMEANS': $message = "Please don't copy/paste the answers."; break;
			case 'default': $message = $_COOKIE['mobile_post_suc_msg']; break;
		}
	 ?>
               <section id= "thanksMsgAnA" class="top-msg-row">
               <div class="thnx-msg" >
                     <i class="icon-tick"></i>
                     <p><?=$message?></p>
               </div>
               </section>
       <?php 
	       deleteTempUserData('mobile_post_suc_msg');
	      } ?>


    <?php echo Modules::run('CA/CADiscussions/getCourseOverviewQnA',$course->getId(),$institute->getId(),$js_enabled,true,$isMobile,'courseListingPage',$ctrackingPageKeyId,$rtrackingPageKeyId,$atrackingPageKeyId,$tupaTrackingPageKeyId,$tdownTrackingPageKeyId,$fqTrackingPageKeyId);?>
    <a class="button blue small ask-btn" href="javascript:void(0)" onclick="gaTrackEventCustom('COURSELISTING_WEBAnA','ASKYOURQUESTION_BOTTOM_COURSELISTING_WEBAnA','<?php echo $GA_userLevel;?>');$('#tracking_keyid').val('331');$('#form_askQuestion').submit();"><span><h3 style="margin-bottom: 0px;font-weight:auto;">ASK YOUR QUESTION</h3></span></a>
    <?php echo Modules::run('CA/CADiscussions/getCourseLinks',$course->getId(),true,$isMobile); ?>
</div>

<!-- Form to be submitted if user is logged out -->
<?php
global $shiksha_site_current_url;
global $shiksha_site_current_refferal;
$referral = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]".'#ca_aqf';
?>
<div style="display: none;">
        <form method="post" action="<?=SHIKSHA_HOME?>/muser5/MobileUser/register" id="postQuestionLoginForm">
                <input type="hidden" name="current_url" value="<?=url_base64_encode($referral)?>">
                <input type="hidden" id="referrer_postQuestion" name="referrer_postQuestion" value="<?=base64_encode($referral)?>">
                <input type="hidden" name="from_where" value="POST_REPLY_COMMNET_PAGE">
                <input type="hidden" name="tracking_keyid" id="tracking_keyid_postQuestion" value="">
        </form>
</div>

<?php
      $this->load->view('mAnA5/mobile/shareLayer');
    }
?>

<script>
$(window).load(function () {
  handleToastMsg();
	 var urlHash = location.hash.replace("#","");
	 if(urlHash == "ca_aqf") {
	     $('html, body').animate({scrollTop: $('#campus-connect-sec-id').offset().top - 50}, 200);
	 }
});
</script>
