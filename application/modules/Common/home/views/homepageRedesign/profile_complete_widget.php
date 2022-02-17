<?php if(!empty($_REQUEST['profile_complete_mailer'])) { ?>
<script>
function reloadAfterUnified(response){
    if(response.status == "SUCCESS"){
        if(isUserLoggedIn) {
            new Ajax.Request('/mailer/Mailer/profileCompleteTrigger/');
        }
        if(response.redirectURL){
            window.location = response.redirectURL;
        }
        else {
            window.location = '<?php echo site_url(); ?>';
        }
    }
}

$j(document).ready(function() {
setTimeout(function() {if(isUserLoggedIn) {
    new Ajax.Request('/user/UnifiedRegistration/isLDBUser'+'?rnd='+Math.floor((Math.random()*1000000)+1),
            {   method:'post',
                onSuccess:function(request){
                    unified_registration_is_ldb_user = eval("eval("+request.responseText+")").UserId;
                    unified_registration_ldb_user_pref_id = eval("eval("+request.responseText+")").PrefId;
                    if(unified_registration_is_ldb_user == 'false') {
                        shikshaUserRegistration.setCallback(reloadAfterUnified);
                        var data = {};
                        data["layerTitle"] = 'Complete your profile now';
                        data["layerHeading"] = 'we need a few details from you to suggest you relevant institutes.';
                        data['showBothIndiaAbroadForms'] = '1';
                        shikshaUserRegistration.showUnifiedLayer(data);
                    }
                    else {
                        shortRegistrationFormHeader = 'Thank you';
                        shortRegistrationFormFinaltext = 'You have already completed your profile. You may continue to browse the site.';
                        displayMessage('/common/loadOverlayContent/common-commonThankYou',400,100);
                    }
                }
            }
    );
}},2000);
});

function displayMessage(url,w,h)
{

    try{
        messageObj.setSource(url);
        messageObj.setCssClassMessageBox(false);
        messageObj.setSize(w,h);
        messageObj.setShadowDivVisible(false);
        messageObj.display();
        $('DHTMLSuite_modalBox_contentDiv').style.background = '';
    } catch (ex){
        if (debugMode){
            throw ex;
        } else {
            logJSErrors(ex);
        }
    }
}

function closeMessage()
{
    try{
        c_value_html = '';
        if(messageObj!='null'){
        messageObj.close();
        }
        if ($('helpbubble')) {
            $('helpbubble').style.display='none';
        }
    } catch (ex){
        if (debugMode){
            throw ex;
        } else {
            logJSErrors(ex);
        }
    }
}
</script>
<?php } ?>
