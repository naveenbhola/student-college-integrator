<div id="inviteFriends" style="display:none">
<form id="inviteFriendsForm" method="post" onsubmit="new Ajax.Request('/mail/Mail/sendMail',{ onComplete: inviteComplete , parameters:Form.serialize(this) }); return false;" action="/mail/Mail/sendMail" id="inviteForm">
   <div  class="mar_full_10p">
      <span style="font-size:12px">Enter email addresses separated by comma or semicolon.</span><br />
 	<textarea style="width:400px;height:70px" name="inviteEmails" id="inviteEmails"></textarea><br />
	 <div class="errorMsg" id="inviteError"></div>
<div class="lineSpace_10">&nbsp;</div>
<div class="lineSpace_10">&nbsp;</div>
<div class="row">
	<div class="buttr3">
		<button class="btn-submit19 w20" type="submit" onclick="return validateInviteEmails();">
			<div class="btn-submit19"><p class="btn-submit20 btnTxtBlog" style="padding: 15px 8px 15px 5px;">Send Invite</p></div>
		</button>
	</div>
	<div class="buttr3">
	   	<button class="btn-submit19 w20" value="" type="button" onclick="clearOverlay();hideOverlay();">
			<div class="btn-submit19"><p class="btn-submit20 btnTxtBlog" style="padding: 15px 8px 15px 5px;">Cancel</p></div>
		</button>
	</div>
	<div class="clear_L"></div>
</div>
<div class="lineSpace_10">&nbsp;</div>
</div>
</form>
<script>
function clearOverlay()
{
	$('inviteEmails').value = "";
	$('inviteError').innerHTML = "";
}
function inviteComplete()
{
   	clearOverlay();
    document.getElementById('genOverlay').style.display = 'none';
    if((document.getElementById('genOverlayContents').innerHTML != '')&&(overlayParent)) {
        overlayParent.innerHTML = document.getElementById('genOverlayContents').innerHTML;
    }
    document.getElementById('genOverlayContents').innerHTML = '';
    setNoScroll();
//	hideOverlay();
		document.getElementById('addRequestOverlay').style.left = screen.width/2 - 100 +  'px';
		document.getElementById('addRequestOverlay').style.top = screen.height/2 - 100  +  'px';
		document.getElementById('responseforadd').innerHTML = 'Invite Mails Sent';
		document.getElementById('responseforadd').style.display = '';
		document.getElementById('addRequestOverlay').style.display = '';
}
function validateInviteEmails()
{
	email_field = $('inviteEmails').value;
	if (email_field.length == 0 )
	{
	        $('inviteError').innerHTML = "Enter email address.";
		return false;
	}
	var email = email_field.replace(/,/gi,'|').replace(/\;/gi,'|').split('|');
	for(var i=0;i<email.length; i++) {
	      for(var k=0;k<email.length;k++) {
		    if (i!=k){
			  if(trim(email[i])==trim(email[k])){
			  	$('inviteError').innerHTML =  "Enter different email addresses.";
				return false;
			}
		    }
	      }
	}

	for (var i = 0; i < email.length; i++) {
		if (validateEmail((trim(email[i].toLowerCase())),"Emails",500,1)!== true) {
		        $('inviteError').innerHTML  = "Enter valid email addresses separated with commas.";
			return false;
		}
	}
	return true;
}

</script>
</div>
