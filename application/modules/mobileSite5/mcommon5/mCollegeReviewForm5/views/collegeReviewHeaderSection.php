<div id="wrapper" data-role="page">
    <?php
    	$this->load->view('reviewFormHeader');
    ?>
  <!-- <div class="popup-layer verification-mobile-share" id="socialAppLayer" style="display: none;">
                <?php //$this->load->view('socialLoginLayer'); ?>
  </div> -->
  <div class="popup-layer verification-mobile-share" id="popupBasic" style="display: none;">
  	<?php $this->load->view('verificationLayer'); ?>
  </div>

<?php 	if($pageType=='campusRep'){?>
    <!-- Google Code for College Review Visitors Without Incentive -->
<?php 	}else{?>
	<!-- Google Code for College Review Visitors -->    
<?php 	} ?>
<!-- Remarketing tags may not be associated with personally identifiable information or placed on pages related to sensitive categories. For instructions on adding this tag and more information on the above requirements, read the setup guide: google.com/ads/remarketingsetup -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 1053765138;
<?php 	if($pageType=='campusRep'){?>
    var google_conversion_label = "Umb8CJad_14Qkty89gM";
<?php 	}else{?>
    var google_conversion_label = "W6UNCM7ezl4Qkty89gM";
<?php 	} ?>
var google_custom_params = window.google_tag_params;
var google_remarketing_only = true;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/1053765138/?value=1.00&currency_code=INR&label=W6UNCM7ezl4Qkty89gM&guid=ON&script=0"/>
</div>
</noscript>



<div id="popupBasicBack" data-enhance='false'></div>
	<div data-role="content">
		<form id="reviewForm" action="/CollegeReviewForm/CollegeReviewForm/submitReviewData"  accept-charset="utf-8" method="post" enctype="multipart/form-data"  novalidate="novalidate" name="reviewForm" autocomplete="off">
            <input name="isShikshaInst" id="isShikshaInst" value="<?php if(isset($isShikshaInstitute) && $isShikshaInstitute!=''){ echo $isShikshaInstitute;}else{ echo 'NO';}?>" type="hidden" />
            <input id="qualification_str" value="1" type="hidden" />
			<input name="reviewId" value="<?php echo $reviewId;?>" type="hidden"/>
            <input id="formName" name="formName" value="collegeReviewRating" type="hidden"/>
            <input id="userId" value="<?php echo $userId;?>" type="hidden" name="userId"/>
            <input id="landingPageUrl" value="<?php echo $landingPageUrl;?>" type="hidden"/>
            <input id="reviewerId" value="<?php echo $reviewerId;?>" type="hidden"  name="reviewerId"/>
            <?php if(isset($pageName) && $pageName=='collegeReview'){ ?>
	            <input id="reviewSource" value="<?php if($letsIntern == ''){echo isset($_SERVER['QUERY_STRING'])?htmlentities(strip_tags($_SERVER['QUERY_STRING'])):'';}else{ echo 'utm_source=letsintern&utm_medium=partner&utm_campaign=letsinternreviews';}?>" type="hidden"  name="reviewSource"/>
	            <?php 	if($pageType==''){?>
	            	<input type='hidden' name='utm_content' id="utm_content" value='incentive'>
				<?php 	} ?>
			<?php } ?>


<section class="content-wrap2 clearfix <?php echo ($pageType=='campusRep'?"new-wrap":"")?>">
	<!--subheader-->
	<?php ///$this->load->view('collegeReviewSubHeader');?>
	<!--end-subheader-->
	<!--ul class="review-step-block">
		<li>
			<i class="rev-stepbg share-bg"></i>
			<p>Share Your<br>Experience</p>
		</li>
		<li>
			<i class="rev-stepbg play-bg"></i>
			<p>Play The<br>Mentor</p>
		</li>
		<?php 	//if($pageType==''){?>
	    <li>
			<i class="rev-stepbg write-bg"></i>
			<p>Review Your College,<br> Get Paytm Cash  </p>
		</li>
		<?php //}?>
	</ul-->
	<div class="new--review_head">
	    <h1 class="new--review_title">Your College Experience could help others</h1>
	    <div class="slideWrapper">
	        <ul class="new--review_box">
	            <?php if($pageType == ''){ ?>
	            <li>
		            <div class="review--info_bar">
		                <div class="review-form-icons review--info_img reviewPatm"></div>
		                <div class="right--info_bar">
		                  <h2 class="info--lable">Get Paytm Cash</h2>
		                   <p class="info--textdata">Every selected review will win an assured Rs.100 paytm Cash</p>
		                </div>
		            </div>
	            </li>
	            <?php } ?>
	            <li>
		            <div class="review--info_bar">
		                <div class="review-form-icons review--info_img reviewShare"></div>
		                <div class="right--info_bar">
		                    <h2 class="info--lable">Share Your Experience</h2>
		                    <p class="info--textdata">Talk about the Good, the bad &amp; the missing</p>
		                </div> 
		            </div>
	            </li>
	            <li>
		            <div class="review--info_bar">
		                <div class="review-form-icons review--info_img reviewPlay"></div>
		                <div class="right--info_bar">
		                    <h2 class="info--lable">Play The Mentor</h2>
		                    <p class="info--textdata">Help students to decide on the right college &amp; course</p>
		                </div>
		            </div>
				</li>
	        </ul>        	
	    </div>
	</div>
	<!-- Remove this script if anything will change in banner sildes--> 
	<!-- <script>
		function setSliderWidth(e) {
			var xWidth  = $(window).width() - 24 ;
			$('.new--review_box li').css('width', xWidth+'px');
		}
		$(document).ready(function(){
			setSliderWidth();
		});
		$(window).resize(function(){
			setSliderWidth();
		});
	</script> -->


</section>
