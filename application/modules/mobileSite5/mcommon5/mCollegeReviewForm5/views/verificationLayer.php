<?php if($pageType != 'campusRep' && $pageType != 'letsIntern'){ ?>
<header class="layer-head">
                <p id="congratsUser"><img src="/public/mobile5/images/mobile_logo1.png"></p>
                <a href="#" class="close-box" onclick = "hideAndRedirect();">&times;</a>
                <div class="clearfix"></div>
            </header>
            
            
            <div class="layer-content" id="successLayer">
               <p class="review-head">Thankyou, <span id="userFirstName"></span>!</p>
               <p class="review-submited">Your college review has been received by us. The review has not been published yet. Our moderation team will go through the review within 30 days. Once your review is published by our team, you will get a confirmation email & Rs 100 will be credited to your Paytm wallet linked to your mobile number in next 30 days.</p>
               
                <div style="margin:0 auto; text-align:center;"></div>

                <div style="margin:0 auto; text-align:center;">
                    <img src="/public/mobile5/images/PayTM.jpg" align="middle" style="margin: 25px 0 25px; background-size:cover;">
                </div>
                
              <!-- <h1 class="h1">Do you Have Uber Account ?</h1>
                

              <div class="accnt-yes-box"  data-enhance="false">
                 <label> <input type="radio" value="YES" class="left-val" name="uberAccount">Yes</label>
                 <p>For Uber credits worth<strong> Rs. 100</strong></p>
              </div>
  
              <div class="accnt-yes-box"  data-enhance="false"><label>
                <input type="radio" value="No" class="left-val" name="uberAccount">No</label>
                <p>For <strong>Rs. 200</strong> off on first 2 Uber rides</p>
              </div> -->
                        
            <input type='hidden' id='revid'/>         
            </div>
            
<?php }else{?>

  <header class="layer-head">
                <p id="congratsUser">Thankyou, <span id="userFirstName"> </span>!</p>
                <a href="#" class="close-box" onclick = "hideAndRedirect();">&times;</a>
                <div class="clearfix"></div>
    </header>
            <div class="layer-content" id="successLayer">
                <p>Your college review has been received by us. The review has not been published yet. Our moderation team will go through the review and inform you when it is published.</p>
                <p style="font-size:13px;">Encourage others to write true and honest college reviews, just like you did.</p>
                        
  
                <div id="socialSharing" class="permalink-share-list-2 clearfix" style="border:0; margin:0;"></div>
              </div>

<?php } ?>