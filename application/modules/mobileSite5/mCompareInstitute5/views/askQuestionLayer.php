
    <header class="r-h">
    	<div class="flLt" style="width:85%;">
        	<p class="reviewed-ins-title">ASK QUESTIONS</p>
        </div>
    	<a style="display: block;  width:10%" class="remove-tab flRt" id="closeLayerButtonForCompare" href="javascript:void(0);" data-rel="back">&times;</a>
        <div class="clr"></div>
    </header>
    <form novalidate="" action="" method="post" id="postQuestionFromCompareMobile">
    <section>
    <div id="sucessMsg" class="collge-review-list marginTop" style="display:none; color:#0BA5B5;font-size:12px">Submitted Successfully</div>
    <div class="collge-review-list marginTop" id="layerView">
    	<ul>
            <li class="last-child">
            	<div class="review-list-detail">
                	<div class="user-initial" id="campusRepImg"></div>
                    <div class="user-info">
                    	<p class="user-title"><span id="campusRepName" class="spanForCampusRepName"></span> <span>| Current Student</span></p>
                        <p id="courseNameForCampusRep"></p>
                    </div>
                </div>
                <div class="review-list-content">
                       	<textarea id="replyTextForCompare" class="que-textarea" onkeyup="textKey(this);" validate="validateStr" caption="Question" maxlength="250" minlength="2" required="true" validatesinglechar="true"></textarea>
                        <p class="flRt avlb-char">Available characters: 250</p>
                                         <div class="errorPlace Fnt11"><div style="display:none;" class="errorMsg" id="replyTextForCompare_error"></div></div>

                 </div>
                 <a href="javascript:void(0);" id ="post_question_btn_compare" class="green-btn"><span id ="ask_btn_compare">ASK NOW</span></a>
            </li>
        </ul>
    </div>
    </section>
    <input id="instituteId" type="hidden" value="">
    <input id="locationId" type="hidden" value="">
    <input id="courseIdForCompare" type="hidden" value="">
    <input id="categoryId" type="hidden" value="">
    <input id="getmeCurrentCity" type="hidden" value="">
    <input id="getmeCurrentLocaLity" type="hidden" value="">
    </form>
 <?php
        global $shiksha_site_current_url;
        global $shiksha_site_current_refferal;
        $referral = "http://".trim($_SERVER[HTTP_HOST],'/').'/'.trim($_SERVER[REQUEST_URI],'/');
        $referral = preg_replace(array("/http:\/\//i") , "https://", $referral);
        ?>
        <div style="display: none;">
                <form method="post" action="<?=SHIKSHA_HOME?>/muser5/MobileUser/register" id="postQuestionLoginFormCompare" >
                        <input type="hidden" name="current_url" value="<?=url_base64_encode($referral)?>">
                        <input type="hidden" id="referrer_postQuestion" name="referrer_postQuestion" value="<?=base64_encode($referral)?>">
                        <input type="hidden" name="from_where" value="POST_QUESTION_COMPARE_PAGE">
                        <input tye='hidden' name='tracking_keyid' id='tracking_keyid_postQuestion' value=''/>
                </form>
        </div>
