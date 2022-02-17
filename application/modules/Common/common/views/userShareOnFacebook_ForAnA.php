
<div style="display:none;" id="userShareOnFacebook_ForAnA">

    <div id="userShareOnFacebook_Text" style="margin-top:5px;margin-bottom:10px;" >&nbsp;</div>
    <div class="mar_left_5p"><input type="checkbox" value="0" id="automaticFShare" name="automaticFShare" onClick="setAutoShareVal();"/>Automatically share from next time</div>
    <div style="margin-top:5px;margin-bottom:10px;">
        <div class="lineSpace_10">&nbsp;</div>
    <!--<input type="button" class="fbBtn" value="Proceed" onClick="show();trackEventByGA(\'LinkClick\',\'FB_PROCEED_BUTTON\');  window.setTimeout(function(){window.location.reload();}, 2000); "/>-->
         <div class="float_L mr10">
            <div id="fbButton">
             <fb:login-button scope="email,user_checkins,offline_access,read_stream,publish_stream" on-login="callFConnectAndFShare(autoShareVal);">Share on Facebook</fb:login-button> 
            <!--<a class="header-fb" href="javascript:void(0);" onclick="callFConnectAndFShare(autoShareVal);"></a>-->
            </div>
          </div>
          <div class="float_L pt5">
            &nbsp;&nbsp;<a href="javascript:void(0);" id="noThanksLink" onClick="redirectOnThanksClick();">No Thanks</a>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onClick="donShowAgain();">Don't Show Again</a>
          </div>
          <div class="clear_L">&nbsp;</div>
    </div>
</div>


  <script>
    </script>
