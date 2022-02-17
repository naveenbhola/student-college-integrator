<div class="installApp-banner" id="installAppBanner">
    <div class="install-app-fields">
        <ul id="_form" style="display:none;">
        <form id="formToSubmitPhoneNo" action="" onsubmit="if(validateFields(this) != true){ return false ; } else { submitPhoneNoForApp('<?php echo $sessionID?>');return false;}" method="post">
            <li><input caption="mobile number" type="text" class="phone-field" id='phoneNumberForAppLink'  required="true"  validate="validateMobileInteger" minlength="10" maxlength="10" value="Enter 10 digit mobile number" />
            <li><a id="getSMSBtn" onclick="$j('form#formToSubmitPhoneNo').submit();" href="javascript:void(0);" class="submit-btn">Submit</a></li>
        </form>
        <div style="display:none;clear: both;margin-top:4px;">
            <div class='errorMsg' style="color:#3E4847; font-size:10px;display: none;float: left;" id= 'phoneNumberForAppLink_error'></div></div>
        </li>
            
        </ul>
    </div>
    <a href="javascript:void(0);" onclick="closeAPPBannerForDesktop('installAppBanner','<?php echo $sessionID?>');" class="banner-rmv-mark">&times;</a>
</div>
<div class="layer-bg" id="layerBg" style="display:none;"></div>
<style>.disabled {pointer-events: none;cursor: default;}</style>
<script type="text/javascript">
var appClose = '<?php echo $appCloseCookieName;?>';
var appSessionId = '<?php echo $sessionID;?>';
</script>