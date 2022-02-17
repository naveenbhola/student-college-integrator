<div class="campus-rep-header">
    <!-- <h2 class="clear-width"><i class="campus-sprite title-border-left"></i>Your College Experience could help others<i class="campus-sprite  title-border-right"></i></h2>
    <div class="camp-connect-section flLt"style="padding-left:35px;">
        <div class="camp-col" style="width:35%;">
                <i class="campus-sprite campus-exp-share"></i>
            <p class="font-18"><strong>Share Your Experience</strong><br />
                                                <span>Talk about the good,<br/> the bad & the missing</span></p>
        </div>
        <div class="camp-col">
                <i class="campus-sprite campus-mentor"></i>
            <p class="font-18"><strong>Play The Mentor</strong><br />
                                                <span>Help students decide <br/>on the right college & course </span></p>
        </div>
        <?php //if($pageType == ''){ ?>
        <div class="camp-col">
        	<i class="campus-sprite campus-review-prizes"></i>
            <p class="font-18"><strong>Review Your College, Get Paytm Cash </strong><br />
			<span>Every selected review will win an</br>assured Rs.100 Paytm cash</span></p>
        </div>
        <?php //} ?>                        
    </div> -->
    <!-- new code -->
    <div class="new--review_head">
        <div id="logo-section" style="padding:0px">
            <a title="Shiksha.com" tabindex="6" href="<?php echo SHIKSHA_HOME; ?>">
                <div class="review-form-icons logo_img"></div>
            </a>
        </div>
        <h1 class="new--review_title">Your College Experience could help others</h1>
        <div class="new--review_box">
            <?php if($pageType == ''){ ?>
            <div class="review--info_bar">
                <div class="review-form-icons review--info_img reviewPatm"></div>
                <div class="right--info_bar">
                  <h2 class="info--lable">Get Paytm Cash</h2>
                   <p class="info--textdata">Every selected review will win an assured Rs.100 paytm Cash</p>
                </div>
            </div>
            <?php } ?>

            <div class="review--info_bar">
                <div class="review-form-icons review--info_img reviewShare"></div>
                <div class="right--info_bar">
                    <h2 class="info--lable">Share Your Experience</h2>
                    <p class="info--textdata">Talk about the Good, the bad &amp; the missing</p>
                </div> 
            </div>

            <div class="review--info_bar">
                <div class="review-form-icons review--info_img reviewPlay"></div>
                <div class="right--info_bar">
                    <h2 class="info--lable">Play The Mentor</h2>
                    <p class="info--textdata">Help students to decide on the right college &amp; course</p>
                </div>
            </div>
        </div>
    </div>
    <!-- END -->
</div>

