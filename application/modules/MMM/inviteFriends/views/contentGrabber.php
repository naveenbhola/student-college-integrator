    <div align="center">
            <span style="padding-right:130px">
                <label>
                    <input id="emailRadio" onclick="document.getElementById('boxId').style.display='none';document.getElementById('boxIdEmail').style.display='block';document.getElementById('email').style.display='block'; document.getElementById('yahoo').style.display='none'; document.getElementById('gmail').style.display='none'; document.getElementById('orkut').style.display='none'; document.getElementById('linkedin').style.display='none';" type="radio" name="r1" checked /><span  class="fontSize_12p" ><img src="/public/images/mail-iconbig.gif" />&nbsp;Specify Email Ids</span></label>&nbsp; &nbsp;
                <label>
                    <input id="yahooRadio" onclick="document.getElementById('userNameText').innerHTML='Yahoo! Id:';document.getElementById('passwordText').innerHTML='Yahoo! Password:';  document.getElementById('exampleText').innerHTML='(eg. dodo_dodo@yahoo.com)'; document.getElementById('boxId').style.display='block'; document.getElementById('boxIdEmail').style.display='none';document.getElementById('email').style.display='none'; document.getElementById('yahoo').style.display='block'; document.getElementById('gmail').style.display='none'; document.getElementById('orkut').style.display='none';" type="radio" name="r1" /> <img src="/public/images/yahoo.gif" align="absmiddle" /></label>&nbsp; &nbsp;
                <label>
                    <input id="gmailRadio" onclick="document.getElementById('userNameText').innerHTML='Gmail Id:';document.getElementById('passwordText').innerHTML='Gmail Password:';  document.getElementById('exampleText').innerHTML='(eg. dodo.dodo@gmail.com)'; document.getElementById('boxId').style.display='block'; document.getElementById('boxIdEmail').style.display='none';document.getElementById('email').style.display='none'; document.getElementById('yahoo').style.display='none'; document.getElementById('gmail').style.display='block'; document.getElementById('orkut').style.display='none'; document.getElementById('linkedin').style.display='none';" type="radio" name="r1" /> <img src="/public/images/gmail.gif" align="absmiddle" /></label>&nbsp; &nbsp;
                <label>
                    <input id="linkedinRadio" onclick="document.getElementById('userNameText').innerHTML='Linkedin Id:';document.getElementById('passwordText').innerHTML='Linkedin Password:';  document.getElementById('exampleText').innerHTML='(eg. dodo.dodo@gmail.com)';document.getElementById('boxId').style.display='block'; document.getElementById('boxIdEmail').style.display='none';document.getElementById('email').style.display='none'; document.getElementById('yahoo').style.display='none'; document.getElementById('gmail').style.display='none'; document.getElementById('orkut').style.display='none'; document.getElementById('linkedin').style.display='block';" type="radio" name="r1" /> <img src="/public/images/linkedIn.gif" align="absmiddle" /></label>&nbsp; &nbsp;
                <label style="display:<?php echo stripos($_SERVER['REQUEST_URI'], 'alumni') !== false ? 'none' : ''; ?>">
                    <input id="orkutRadio" onclick="document.getElementById('userNameText').innerHTML='Orkut Id:';document.getElementById('passwordText').innerHTML='Orkut Password:';  document.getElementById('exampleText').innerHTML='(eg. dodo.dodo@gmail.com)';document.getElementById('boxId').style.display='block'; document.getElementById('boxIdEmail').style.display='none';document.getElementById('email').style.display='none'; document.getElementById('yahoo').style.display='none'; document.getElementById('gmail').style.display='none'; document.getElementById('orkut').style.display='block'; document.getElementById('linkedin').style.display='none';" type="radio" name="r1" /> <img src="/public/images/orkut.gif" align="absmiddle" /></label>&nbsp;&nbsp;
            </span>
        </div>
        <div align="center">
            <div id="email" style="position:relative;top:2px; width:500px; padding-left:50px" align="left"><img src="/public/images/inviteUpArrow.gif" /></div>
            <div id="yahoo" style="position:relative;top:2px; width:300px; padding-left:50px; display:none" align="left"><img src="/public/images/inviteUpArrow.gif" /></div>
            <div id="gmail" style="position:relative;top:2px; width:150px; padding-left:100px; display:none" align="left"><img src="/public/images/inviteUpArrow.gif" /></div>
            <div id="linkedin" style="position:relative;top:2px; width:70px; padding-left:230px; display:none" align="left"><img src="/public/images/inviteUpArrow.gif" /></div>
            <div id="orkut" style="position:relative;top:2px; width:70px; padding-left:400px; display:none" align="left"><img src="/public/images/inviteUpArrow.gif" /></div>
            <div style="width:550px;">
                <div class="raised_career">
                	<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
                	<div id="boxId" class="boxcontent_careerNoBg" style="display:none">
                		<div class="lineSpace_10">&nbsp;</div>
                		<div>
                			<div id="userNameText" class="float_L fontSize_12p bld txt_align_r" style="width:200px">User Name:&nbsp;</div>
                			<div class="float_L">&nbsp;&nbsp;<input id="contactUserName" type="text" style="width:150px" /></div>
                			<div class="clear_L"></div>
                		</div>
                    <div>
        			<div class="float_L fontSize_12p bld txt_align_r" style="width:200px">&nbsp;</div><div class="float_L">&nbsp;&nbsp;</div>
        			<div id="exampleText" class="float_L fontSize_8p">(eg. dodo@yahoo.com)</div>
        			<div class="clear_L"></div>
        		</div>

        		<div class="lineSpace_10">&nbsp;</div>
    		<div>
			<div id="passwordText" class="float_L fontSize_12p bld txt_align_r" style="width:200px">Password:&nbsp;</div>
			<div class="float_L">&nbsp;&nbsp;<input type="password" id="contactUserpasswd" style="width:150px" /></div>
			<div class="clear_L"></div>
		</div>
		<div id="passwdError" style="display:none">
			<div id="passwordText" class="float_L fontSize_12p bld txt_align_r" style="width:200px">&nbsp;</div>
			<div class="float_L" style="color:red;" class="txt_align_r">&nbsp;&nbsp;Wrong User Name or Password</div>
			<div class="clear_L"></div>
		</div>
		<div class="clear_L"></div>
		<div class="lineSpace_10">&nbsp;</div>
		<div align="center">
			<button class="btn-submit7" value="" type="submit" style="width:100px" onclick="sendInviteCredentials();"><div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Submit</p></div></button>
		</div>
		<div class="lineSpace_10">&nbsp;</div>
	</div>
	
	<div id="boxIdEmail" class="boxcontent_careerNoBg">
		<div class="lineSpace_10">&nbsp;</div>
		<form id="inviteFriendsForm" method="post" onsubmit="new Ajax.Request('/index.php/mail/Mail/sendMail',{ onComplete: inviteComplete , parameters:Form.serialize(this) }); return false;" action="/index.php/mail/Mail/sendMail" id="inviteForm">
   <div  class="mar_full_10p">
      <span style="font-size:12px">Enter email addresses separated by comma or semicolon.</span><br />
 	<textarea style="width:400px;height:70px" name="inviteEmails" id="inviteEmails"></textarea><br />
	 <div class="errorMsg" id="inviteError"></div>
<div class="lineSpace_10">&nbsp;</div>
<div class="lineSpace_10">&nbsp;</div>
<div class="row">
	<div align="center">
		<button class="btn-submit19 w20" type="submit" onclick="return validateInviteEmails();" id="sendInviteBtn">
			<div class="btn-submit19"><p class="btn-submit20 btnTxtBlog" style="padding: 15px 8px 15px 5px;">Send Invite</p></div>
		</button>
	</div>
	<div class="clear_L"></div>
</div>
<div class="lineSpace_10">&nbsp;</div>
</div>
</form>
<script>
function inviteComplete()
{
   	$('inviteFriendsForm').reset();
   	$('responseTextPlace').style.display= 'block';
//    $('responseTextFirst').style.display= 'none';
    $('inviteError').style.display= 'none';
}
function validateInviteEmails()
{
	email_field = $('inviteEmails').value;
	if (email_field.length == 0 )
	{
	        $('inviteError').innerHTML = "Enter email address.";
            $('inviteError').style.display= 'block';
		return false;
	}
	var email = email_field.replace(/,/gi,'|').replace(/\;/gi,'|').split('|');
	for(var i=0;i<email.length; i++) {
	      for(var k=0;k<email.length;k++) {
		    if (i!=k){
			  if(trim(email[i])==trim(email[k])){
			  	$('inviteError').innerHTML =  "Enter different email addresses.";
                $('inviteError').style.display= 'block';
				return false;
			}
		    }
	      }
	}

	for (var i = 0; i < email.length; i++) {
		if (validateEmail((trim(email[i].toLowerCase())),"Emails",500,1)!== true) {
		        $('inviteError').innerHTML  = "Enter valid email addresses separated with commas.";
                $('inviteError').style.display= 'block';
			return false;
		}
	}
	return true;
}

</script>
		<div class="lineSpace_10">&nbsp;</div>
	</div>
	
	<b class="b4b" style="background:#FFFFFF"></b><b class="b3b" style="background:#FFFFFF"></b><b class="b2b" style="background:#FFFFFF"></b><b class="b1b"></b>
</div>	
</div>
</div>
