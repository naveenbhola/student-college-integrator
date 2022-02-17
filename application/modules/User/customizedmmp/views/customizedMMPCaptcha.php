    <div id="customized_mmp_captacha">
        <div style="padding:0px 10px 0 10px;text-align:left" id="captcha">
            <div>Type in the character you see in the picture below</div>
                <div class="lineSpace_5">&nbsp;</div>
            <div>
                <div style="width:35%;line-height:18px;" class="float_L">
                    <div class="txt_align_r" style="padding-right:5px;">
                        <img align = "absbottom" src="<?php echo SHIKSHA_HOME;?>/CaptchaControl/showCaptcha?width=100&height=34&characters=5&randomkey=<?php echo rand(); ?>&secvariable=seccodehome" width="100" height="34" id = "secureCode"/>
                    </div>
                </div>
                <div style="width:63%;float:right;padding-top:5px;">
                    <input type="text" blurMethod='trackEventByGA("EnterCaptcha","captcha entered by user");'
                        style="width:65%;font-size:12px"
                        name = "homesecurityCode"
                        id = "homesecurityCode"
                        validate = "validateSecurityCode"
                        maxlength = "5"
                        minlength = "5"
                        required = "1"
                        caption = "Security Code"
                    />
                </div>
                <div class="errorPlace" style="display:none;">
                    <div  style="float:right;width:63%;*padding-left:4px" class="errorMsg" id= "homesecurityCode_error"></div>
                </div>
            </div>
            <div class="lineSpace_5">&nbsp;</div>
        </div>
        <div class="lineSpace_10">&nbsp;</div>
        <div class="clear_L clear_R withClear">&nbsp;</div>
        <div class="lineSpace_10">&nbsp;</div>
    </div>