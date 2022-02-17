<?php if(!empty($_REQUEST['mailerId']) && !empty($_REQUEST['mailId']) && !empty($_REQUEST['mailReportSpam'])) { ?>
<script>
$j(document).ready(function() {
setTimeout(function() {
	if(isUserLoggedIn) {
		var mailerId = '<?php echo $_REQUEST['mailerId']; ?>';
		var mailId = <?php echo $_REQUEST['mailId']; ?>;
		displayMessageBox('/mailer/Mailer/mailerReportSpam/'+mailerId+'/'+mailId+'/',500,100);
	}
},1000);
});

function displayMessage(url,w,h) {
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

function closeMessage() {
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

function showReportSpamReasons() {
    $j('#confirm_reportSpam').hide();
    $j('#form_reportSpam').show();
}

function reloadAfterReportSpam() {
    window.location = '<?php echo site_url(); ?>';
}

function submitReportSpam() {
	$j('#loader').show();
	$j('#form_reportSpam').find('button').attr('disabled','disabled');
	if(validateReportSpamReason()) {
		recordReportSpam();
	}
	else {
		alert('Please reason for report spam');
		$j('#loader').hide();
		$j('#form_reportSpam').find('button').removeAttr('disabled');
	}
}
function recordReportSpam() {
	var formobj = $('form_reportSpam');
	new Ajax.Request( formobj.action,
			{	method:'post',
				onSuccess:function(request){
					alert('Thanks for your feedback. The mailer has been successfully reported spam. We’ll not deliver any similar mailers to you again.');
					closeReportSpamLayer();
				},
				onFailure: function(){ alert('Something went wrong...'); window.location.reload(); },
				parameters:$j(formobj).serialize()
			}
	);
}

function validateReportSpamReason() {
    var formobj = $('form_reportSpam');
    var reasons = formobj['reportSpamReasons[]'];
    var values = [];
    for (var i = 0; i < reasons.length; i++) {
      if (reasons[i].checked) {
        values.push(reasons[i].value);
      }
    }
    
    if (values.length == 0) {
        return false;
    }
    return true;
}

function closeReportSpamLayer() {
	$('DHTMLSuite_modalBox_transparentDiv').style.display = 'none';
	closeMessage();
	reloadAfterReportSpam();
}
</script>
<?php } ?>
