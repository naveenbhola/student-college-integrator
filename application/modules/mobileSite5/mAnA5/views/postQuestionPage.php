<?php
$this->load->view('/mcommon5/header');
$displayData['headerTitle'] = 'Post a question to current students';

?>
<style>
.disabled {
   pointer-events: none;
   cursor: default;
}
</style>
<div id="wrapper" data-role="page" >
<?php $this->load->view('anaHeader',$displayData);?>   
<div data-enhance="false">
<form id="postQuestionFrom" method="post" action="" novalidate="">

<section class="content-wrap2 clearfix">
	<article class="req-bro-box shortlist-box">
    	<div class="">
            <div class="">
                <textarea validatesinglechar="true"  required="true" minlength="2" maxlength="140" caption="Question" validate="validateStr" class="post-que-area" id="replyText" onkeyup="textKey(this);" name="replyText" placeholder="Format your questions right to help us provide appropriate answers"></textarea>
                <p style="font-size:12px"><span id="replyText_counter">0</span> out of 140 character</p>
                <div class="errorPlace Fnt11"><div id="replyText_error" class="errorMsg"  style="display:none;"></div></div>
                <a id="_post_question_btn" class="button blue que-btn" href="javascript:void(0);" style="margin-top:20px" onclick="validatePostQuestion(document.getElementById('postQuestionFrom'),'<?=$trackingPageKeyId?>');"><span id="ask_btn">Ask Now</span></a> 
            </div>
        </div>
        <div class="clearfix"></div>  
    </article>
</section>

                <input type="hidden" value="<?php echo $instituteId;?>" id="instituteId">
                <input type="hidden" value="<?php echo $locationId;?>" id="locationId">
                <input type="hidden" value="<?php echo $courseId;?>" id="courseId">
                <input type="hidden" value="<?php echo $categoryId;?>" id="categoryId">
                <input type="hidden" value="<?php echo $getmeCurrentCity;?>" id="getmeCurrentCity">
                <input type="hidden" value="<?php echo $getmeCurrentLocaLity;?>" id="getmeCurrentLocaLity">

</form>
</div>
<?php
global $shiksha_site_current_url;
global $shiksha_site_current_refferal;
$referral = (isset($coursePageURL) && $coursePageURL !='') ? $coursePageURL.'#ca_aqf' : $shiksha_site_current_refferal.'#ca_aqf';
?>
<div style="display: none;">
        <form method="post" action="<?=SHIKSHA_HOME?>/muser5/MobileUser/register" id="postQuestionLoginForm">
                <input type="hidden" name="current_url" value="<?=url_base64_encode($referral)?>">
                <input type="hidden" id="referrer_postQuestion" name="referrer_postQuestion" value="<?=base64_encode($referral)?>">
                <input type="hidden" name="from_where" value="POST_QUESTION_PAGE">
                <input type="hidden" name="tracking_keyid" id="tracking_keyid" value=''/>
        </form>
</div>

<?php $this->load->view('/mcommon5/footerLinks'); ?>
</div>
<?php $this->load->view('/mcommon5/footer');?>
</body>
</html>