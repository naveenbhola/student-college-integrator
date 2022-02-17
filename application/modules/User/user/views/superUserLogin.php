<!DOCTYPE html>
<html>
<head>
	<title>Super User Login</title>
</head>
<body>
<form action='/user/Login/loginAsSuperUser' onsubmit="return validateEmail();" method="post">

<center>
<h3>
Welcome <?=$data[0]['displayname']?>
</h3>
<div id = "notExist" style="font-size: medium; color :red;font-family:Trebuchet MS,Arial,Helvetica;font-size:15px;padding: 0 0 5px 20px;">
<?php
if($msg)
   echo $msg;

?>
</div>
<label for="email" id="labelEmail" style="display:inline-block;font-family:Trebuchet MS,Arial,Helvetica;font-size:14px;padding-top:3px;
vertical-align:top;width:100px;">User Email:</label>
<div style="display:inline-block; height:50px;">
<input type="email" class="form-control" name="user_email" id="user_email" onblur="validateEmail()" placeholder="Enter User Email" style="width: 252px" />
<span id="email_error" style="display:none;text-align:left;color: red;font-family:Trebuchet MS,Arial,Helvetica; padding:5px;font-size:13px;"> </span>
</div>
<input type="submit" value="Submit" style="display:block;" />
</center>
</form>
</body>
<script type="text/javascript">
	function validateEmail() { 
		var value = document.getElementById('user_email').value; 
		if(value == "" ||  typeof value == 'undefined'){
			document.getElementById('notExist').style.display = "none";
			document.getElementById('email_error').innerHTML = "Email can't be empty";
			document.getElementById('email_error').style.display = "block";
			return false;
		}else if(value) {
	    var filter = /^((([a-z]|[A-Z]|[0-9]|\-|_)+(\.([a-z]|[A-Z]|[0-9]|\-|_)+)*)@((((([a-z]|[A-Z]|[0-9])([a-z]|[A-Z]|[0-9]|\-){0,61}([a-z]|[A-Z]|[0-9])\.))*([a-z]|[A-Z]|[0-9])([a-z]|[A-Z]|[0-9]|\-){0,61}([a-z]|[A-Z]|[0-9])\.)[\w]{2,4}|(((([0-9]){1,3}\.){3}([0-9]){1,3}))|(\[((([0-9]){1,3}\.){3}([0-9]){1,3})\])))$/
	      if(!filter.test(value)) {
	      	document.getElementById('notExist').style.display = "none";
	      	document.getElementById('email_error').innerHTML = "Please enter a valid email id ";
			document.getElementById('email_error').style.display = "block";
			return false;
	      }
	  }
	  document.getElementById('notExist').style.display = "none";
	  document.getElementById('email_error').style.display = "none";
	  return true;
	}
</script>
</html>
