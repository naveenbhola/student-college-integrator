<?php

$showSteps                       = 'YES';
$collegeHeadingText              = 'College Details';
$reviewWrap                      = '';
//$aboutCollegeHeadingText       = 'Write about your UG College';
$anonymousPosition               = 'top';
$submitButtonPosition            = 'top';
$showDescriptionTextAsSubHeading = 'YES';
$showPersonalSection             = 'NO';
//$descriptionText = "Describe your college to someone who has never heard of it. You know your college best and know what's important - faculty, placement, infrastructure. Talk about the good, the bad and the missing. You could also write about what's interesting - eating joints, the student crowd, college fests. And anything else that you think prospective students need to know.";
$rateSectionHeading = "Rate your College on the following parameters";
$buttonText  = "Submit";
if(isset($reviewFormName) && $reviewFormName=='collegeReviewRating'){
        $showSteps                       = 'NO';
        $collegeHeadingText              = 'Your College Details';
        $reviewWrap                      = 'review-wrap';
        //$aboutCollegeHeadingText       = 'Describe Your College';
        $anonymousPosition               = 'bottom';
        $showDescriptionTextAsSubHeading = 'NO';
        //$descriptionText               = "Describe your college to some one who has never heard of it. The good, bad and the missing (You know your college best. You could write about what's important - faculty, placement, infrastructure. You could write about what's interesting - eating joints, the student crowd, college fests. And anything else that you think prospective students need to know.)";
        $submitButtonPosition            = 'bottom';
        $showPersonalSection             = 'YES';
        $rateSectionHeading              = "Rate Your College On The Following Parameters";
        $landingPageUrl                  = SHIKSHA_HOME;
        $buttonText                      = "Submit Review";
}

$header_description = "Let others know about your college by rating and submitting the reviews. Share your college experiences with others to help them decide on selecting best college and course.";

$this->load->view('CollegeReviewForm/collegeReviewFormHeader',array('header_description'=>$header_description))
?>

        <?php if(isset($reviewFormName) && $reviewFormName=='collegeReviewRating'){ ?>
			<div id="popupBasic" style="display: none; width:800px; position:fixed; z-index:1;margin:auto; padding:2px 25px;">
        <?php }else{ ?>
            <div id='popupBasic' class="verification-share-layer" style="display: none; width:620px; position:fixed; z-index:1;margin:auto; padding:2px 25px;">
        <?php } ?>
				<?php $this->load->view('CollegeReviewForm/submitLayer'); ?>

		 <script>
            var title = "<?php echo $rateSectionHeading; ?>";
            setReviewRatingTitle(title);
         </script> 

		</div>

        <?php $this->load->view('CollegeReviewForm/collegeReviewTrackingCode',array('googleConversionLabel' => $googleConversionLabel));?>
		
        <div id="popupBasicBack"></div>

<!--         <div id="header">
            <div id="logo-section" style="padding:0px">
            	<a title="Shiksha.com" tabindex="6" href="<?php //echo SHIKSHA_HOME; ?>"><img border="0" title="Shiksha.com" alt="Shiksha.com" src="/public/images/desktopLogo.png" style="margin:0 0 10px 5px;"></a>
            </div> 
      </div>
 -->
                <div id="connect-wrapp" style="padding:0;">
                <form id="reviewForm" action="/CollegeReviewForm/CollegeReviewForm/submitReviewData"  accept-charset="utf-8" method="post" enctype="multipart/form-data"  novalidate="novalidate" name="reviewForm">
                    
                    <?php if(isset($reviewFormName) && $reviewFormName=='collegeReviewRating'){ ?>
                    <input id="reviewSource" value="<?php echo isset($_SERVER['QUERY_STRING'])?htmlentities(strip_tags($_SERVER['QUERY_STRING'])):'';?>" type="hidden"  name="reviewSource"/>
                    <?php } ?>
                    
				<?php $this->load->view('CollegeReviewForm/collegeReviewHiddenFields',array('showPersonalSection'=>$showPersonalSection, 'pageType' => $pageType));

                    if(isset($reviewFormName) && $reviewFormName=='collegeReviewRating'){
						$this->load->view('CollegeReviewForm/headerSection',array('pageType' =>$pageType));
					}else{
						$this->load->view('CA/campusRepOnBoardHeader');
					}
                ?>

                <div class="connect-form clear-width <?php  echo $reviewWrap; ?>" <?php if($showSteps=='NO'){ ?>style="margin:0;"<?php } ?>>
				<?php if($showSteps=='YES'){ ?>
                        <div class="form-steps">
                        	<a href="#"><span>1</span>Tell US about yourself</a>
                            <a class="campus-sprite  step-border"></a>
                            <a href="#" class="active"><span>2</span>Review your College </a>
                        </div>
	        	<?php } ?>

                <?php $this->load->view('CollegeReviewForm/collegeReviewCourseSection',array('collegeHeadingText'=>$collegeHeadingText,'rateSectionHeading'=>$rateSectionHeading,'pageType' =>$pageType)); ?>

                <?php if($submitButtonPosition == 'top'){ ?>
                    <a style="height: 42px;" href="javascript:void(0);" class="orange-button continue-btn" onclick="submitReviewForm();" id="reviewSubmitButton">Submit </a>
                                  &nbsp;<span id="waitingDiv" style="display:none"><img src='/public/images/workiajayng.gif' border=0 align=""></span>
                    <div class="clearFix"></div>
                <?php } ?>
            </div>
  </div>

    <?php if($showPersonalSection == 'YES'){ 
			 $this->load->view('CollegeReviewForm/collegeReviewPersonalForm',array('submitButtonPosition'=>$submitButtonPosition,'buttonText'=>$buttonText,"firstname" => $firstname, "lastname"=>$lastname, "email" => $email , "mobile" => $mobile, "pageType" => $pageType,"incentiveFlag" => $incentiveFlag));
            } ?>
    </form>
</div>
                
<div class="clearFix"></div>
        
<?php
	$this->load->view('common/footer');
?>
<script>
var innoExcelScript = "<?php echo INNO_EXCEL_SCRIPT; ?>";
var flagToCheckIfAtleastOnSelect = false;		
</script>
<?php foreach ($ratingParams as $key => $value) { ?>
    <script>
        markStarRating('<?php echo $value;?>','<?php echo $key;?>');      
    </script>
<?php 
}

if($anonymousFlag=='YES'){
?>
<script>
$j('#anonymousFlag').attr('checked','true');
</script>
<?php
}
?>
<script>
    $j('#footer').hide();
    try{
	//	addOnFocusToopTipOnline(document.getElementById('CAProfileForm'));
		//addOnFocusToopTipOnline(document.getElementById('qualificationForm_1'));
	    addOnBlurValidate(document.getElementById('reviewForm'));
	} catch (ex) {
	}

    var RIGHT_CLICK_DISABLED_ON_REVIEW_PAGE = "<?=RIGHT_CLICK_DISABLED_ON_REVIEW_PAGE?>";
    var isShikshaInstitute = "<?=$isShikshaInstitute?>",
        selectedlocationId = '',
        selectedCourseId = '';

    $j(document).ready(function () {
        collegeReviewReadyCalls();
        <?php if($isShikshaInstitute == 'YES'){?>
            selectedlocationId = '<?=$selectedlocationId;?>';
            selectedCourseId   = '<?=$selectedCourseId;?>';
            setTimeout(function(){updateCollegeAutosuggestorData('<?=base64_encode($instituteName)?>','<?=$instituteIdentifier?>');},500);
        <?php } ?>
    });
</script>
<style>
    .suggestion-box{color:red;position: absolute;background: #fff;z-index: 99;border:1px solid #ccc;width:500px;border-width: 0px 1px 1px 1px; padding: 0px;}
    .suggestion-box  li{padding: 10px 16px 6px; border-bottom: 1px solid #F7F7F7;margin-bottom: 0px !important}
    .suggestion-box .suggestion-box-active-option {background: #F9F9F9 none repeat scroll 0% 0% !important;color: #000;list-style: outside none none;}
    .suggestion-box li span {display: block;color: #999;font-size: 12px;font-weight: 400;line-height: 20px;}
    .suggestion-box  li .suggestion-box-normal-option {background: #FFF none repeat scroll 0% 0%;}
</style>
