<?php if($pageType != 'campusRep' && $pageType != 'letsIntern' && $reviewFormName=='collegeReviewRating'){ ?>
<div class="reveiw-layer">
	<div class="mob-review-head">
		    <a href="javascript:void(0);" class="flRt cross-mark" style="position:relative; top:2px;" title="Close" id="closeLayerMark" onclick="window.location='<?php echo SHIKSHA_HOME; ?>'">&times;</a>
          <div class="s">
            <a><img src="/public/images/desktopLogo.png" /></a>
          </div>
          
    </div>
    
     <div class="review-content">
        <div class="left-content">
          <h1>Thank you, <span id="userFirstName"> </span></h1>
          <p class="review-layer-cont">Your college review has been received by us. The review has not been published yet. Our moderation team will go through the review within 30 days. Once your review is published by our team, you will get a confirmation email & Rs 100 will be credited to your Paytm wallet linked to your mobile number in next 30 days.</p>
        </div>
        
        <div class="right-content">
           <div class="review-bck"></div>
        </div>
   
      </div>
      
     <!--  <div class="uber-account">
          <h1>Do you have an Uber Account?</h1>
          <section class="accnt-yes">
            <label><input type="radio"  class="left-val" value="yes" name="uberAccount" />Yes</label>
            <p>For Uber credits worth <strong>Rs. 100</strong></p>
          </section> 
           <section class="accnt-yes" style="float:right">
            <label><input type="radio"  class="left-val" value="no" name="uberAccount" />No</label>
            <p>For <strong>Rs. 200 off</strong> on first 2 Uber rides</p>
          </section>   
       
      </div> -->
         <input type='hidden' id='revid'/> 
</div>

<?php }else{?>
  
  <?php if($pageType == 'letsIntern'){
    $urlToLand = SHIKSHA_ASK_HOME;
  }else{
    $urlToLand = SHIKSHA_HOME;
  }?>
  <a href="javascript:void(0);" class="flRt cross-mark" style="position:relative; top:2px;" title="Close" id="closeLayerMark" onclick="window.location='<?php echo $urlToLand; ?>'">&times;</a>

  <div class="clearfix"></div>
  <p class="verification-title">Thank you, <span id="userFirstName"> </span> !</p>
  <p style="font-size:13px;margin-bottom:10px">Your college review has been received by us. The review has not been published yet. Our moderation team will go through the review and inform you when it is published.</p>
  
  <p style="font-size:13px;">Encourage others to write true and honest college reviews, just like you did.</p>

  <div id="socialSharing" class="permalink-share-list-2 clear-width" style="border:0; margin:8px 0;"></div>
      
        
<?php } ?>
