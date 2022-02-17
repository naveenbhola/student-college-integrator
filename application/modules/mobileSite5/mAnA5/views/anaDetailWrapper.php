<?php global $shiksha_site_current_url;global $shiksha_site_current_refferal ;?>
<style>
.disabled {
   pointer-events: none;
   cursor: default;
}
</style>
<div id="wrapper" data-role="page" style="min-height: 413px;padding-top: 40px;">	
<?php
    //Check if user came directly on this page. If the referrer is not shiksha, we have to display the Hamburger menu
    $displayHamburger = false;
    if(!$_SERVER['HTTP_REFERER']){  //If no referer is defined, show Hamburger menu
            $displayHamburger = true;
    }
    else if( strpos($_SERVER['HTTP_REFERER'],'shiksha') === false){ //If referer is not from Shiksha, show Hamburger menu
            $displayHamburger = true;
    }
    
    if($displayHamburger){
	
        echo Modules::run('mcommon5/MobileSiteHamburgerV2/getWrapperHtmlForHamburger','mypanel');
	
    }
        echo Modules::run('mcommon5/MobileSiteHamburger/getRightPanel','myrightpanel');
    ?>

    <header id="page-header"  class="header ui-header-fixed" data-role="header" data-tap-toggle="false" data-position="fixed">
     <?php echo Modules::run('mcommon5/MobileSiteHamburger/getMainHeader',$displayHamburger);?>
    </header>
<?php $this->load->view('anaHeader');?>
    <div data-role="content">
        <?php 
            $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'leaderboard'));
        ?>
        <div data-enhance="false">
            <input type="hidden" name="startCount" id="startCount" value="<?php echo $startCount;?>" />
            <input type="hidden" name="offsetCount" id="offsetCount" value="<?php echo $offsetCount;?>" />
            <input type="hidden" name="questionId" id="questionId" value="<?php echo $questionId;?>" />
            <input type="hidden" name="totalAnswerCount" id="totalAnswerCount" value="<?php echo $totalAnswerCount;?>" />
            <?php $this->load->view('questionSection');?>
            <?php $this->load->view('answerCommentSection');?>
            <?php if($totalAnswerCount>3){ ?>
            <a href="javascript:void(0);" class="load-more" onclick="loadMoreData();" id="loadMore">Load More Answers...</a>
            <?php } ?>
            <!--<section class="content-wrap2 clearfix">
                    <article class="req-bro-box clearfix">
                    <div class="ans-icon">A</div>
                    <div class="camp-qna-details">
                        <p>Eligibility - Candidates applying for admission to the Two Year Full-Time MBA must hold a Bachelor's Degree in any discipline after 12 years of formal schooling with at least 50% marks (SC/ST:- Passing marks; OBC/PwD/CW- 45%) or equivalent
            to CGPA, as per CAT 2013 advertisement requirement. Candidates appearing for the final year examination of Bachelor's
            Degree examination may also apply.Admission to MBA (Two-year Full Time)at FMS will be based on personal interview,
            group discussion and CAT-2013 score.</p>
                    <p class="posted-info clearfix">
                        <span class="flLt"><label>Posted by:</label> Sneha Motwani<a href="#" class="current-stu-btn">CURRENT STUDENT</a></span>
                        <span class="flRt">4 months ago </span>
                    </p>
                    <div class="posted-ans-detail">
                        <p>Could you please give the names of a few companies visiting campus for recruitment. Also what was the average/highest salary offered on campus last year? as per CAT 2013 advertisement requirement. Candidates appearing for the final year examination 
                        </p>
                        <p class="posted-info clearfix">
                            <span class="flLt"><label>Posted by:</label> Sneha Motwani</span>
                            <span class="flRt">4 months ago </span>
                        </p>
                    </div>
                    <a class="button blue small flLt" href="#"><span>Comment</span></a>
                    <div class="clearfix"></div>
                    </div>
               </article>
            </section>-->
            <?php echo Modules::run('mAnA5/AnAController/loadOtherQuestionSectionForCourse',$courseId,$questionId);?>
            <?php $isMobile = true;?>
            <?php echo Modules::run('CA/CADiscussions/getCourseTuple',$courseObj->getId(),$insObj->getId(),'campusConnect','',3,true,$isMobile); ?>
            <?php echo Modules::run('CA/CADiscussions/getQuestionForm',$courseObj->getId(),$insObj->getId(),true,$isMobile,'',$qtrackingPageKeyId);?>
            </article>
            </section>
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
                        <input tye='hidden' name='tracking_keyid' id='tracking_keyid_postQuestion' value=''/>
                </form>
        </div>
        <?php deleteTempUserData('mobile_post_suc_msg');?>
        <script> setTimeout(function(){$('#answerSuccessMsg').fadeOut();},5000)</script>
        <?php $this->load->view('/mcommon5/footerLinks'); ?>
        <?php $this->load->view('/mcommon5/footer');?>
    </div>
</div>