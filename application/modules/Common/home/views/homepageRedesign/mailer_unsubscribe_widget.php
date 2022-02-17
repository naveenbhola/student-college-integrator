<?php if(!empty($_REQUEST['encodedMail']) && !empty($_REQUEST['mailerUnsubscribe'])) { ?>
<script>
$j(document).ready(function() {
setTimeout(function() {
	if(isUserLoggedIn) {
		var encodedMail = '<?php echo $_REQUEST['encodedMail']; ?>';
		displayMessageBox('/mailer/Mailer/mailerUnsubscribe/'+encodedMail+'/',500,100);
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

function reloadAfterUnsubscribe() {
	window.location = '<?php echo site_url(); ?>';
}

function Unsubscribe(encodedMail) {
	var unsubscribeUrl = '/mailer/Mailer/Unsubscribe/'+encodedMail+'/';
	new Ajax.Request( unsubscribeUrl,
			{	method:'post',
				onSuccess:function(request){
					alert('Thanks for your feedback. You have been successfully Unsubscribed from our mailing list. We’ll not deliver any mails to you again.');
					closeUnsubscribeLayer();
				},
				onFailure: function(){ alert('Something went wrong...'); window.location.reload(); }
			}
	);
}

function closeUnsubscribeLayer() {
	$('DHTMLSuite_modalBox_transparentDiv').style.display = 'none';
	closeMessage();
	reloadAfterUnsubscribe();
}
</script>
<?php } ?>
