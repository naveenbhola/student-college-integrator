<?php
$buttonBg1 = "#f0f0f0";
$buttonBg2 = "#e2e2e2";
?>
<html>
   <head>
	  <title>
		 Shiksha Health Monitoring Dashboard - Login
	  </title>
	  <style>
		 body {font-family: arial; font-size:16px; color:#444;}
		 .clearFix{clear: both; font-size: 1px;}
		 .inputbox {padding:5px; width:300px; font-size:16px; color:#444; border:1px solid #ccc;}
		 a {text-decoration:none;}
		 .button {
			border-radius: 2px;
			-moz-border-radius: 2px;
			-webkit-border-radius: 2px;
			behavior: url(PIE.htc);
			box-shadow: #e3e3e3 0 1px 1px;
			-moz-box-shadow: 0px 1px 1px rgba(000,000,000,0.1), inset 0px 1px 1px rgba(255,255,255,0.7);
			-webkit-box-shadow: 0px 1px 1px rgba(000,000,000,0.1), inset 0px 1px 1px rgba(255,255,255,0.7);
			behavior: url(PIE.htc);
		 }
		 .small {
			font-family: Arial, Helvetica, sans-serif;
			font-size: 12px;
			font-weight: bold;
			padding: 7px 12px;
			cursor: pointer;
			line-height: 16px;
			margin: 0 0px 0px 15px;
			display: inline-block;
		 }
		 .green {
			margin-right: 0;
			text-shadow: 1px 1px 0px #d6f3ae;
			color: #587930;
			border: 1px solid #86be46;
			background: #a7e059 url('/public/images/arrow.png');
			background: -webkit-gradient(linear, 0 0, 0 100%, from(#76E676) to(#5CD65C));
			background: -webkit-linear-gradient(#76E676, #5CD65C);
			background: -moz-linear-gradient(#76E676, #5CD65C);
			background: -ms-linear-gradient(#76E676, #5CD65C);
			background: -o-linear-gradient(#76E676, #5CD65C);
			background: linear-gradient(#76E676, #5CD65C);
			-pie-background: linear-gradient(#76E676, #5CD65C);
		 }
		 .white {
			text-shadow: 1px 1px 0px #f8f8f8;
			color: #414141;
			border: 1px solid #b3b3b3;
			background: #ededed;
			background: -webkit-gradient(linear, 0 0, 0 100%, from(<?php echo $buttonBg1; ?>) to(<?php echo $buttonBg2; ?>));
			background: -webkit-linear-gradient(<?php echo $buttonBg1; ?>, <?php echo $buttonBg2; ?>);
			background: -moz-linear-gradient(<?php echo $buttonBg1; ?>, <?php echo $buttonBg2; ?>);
			background: -ms-linear-gradient(<?php echo $buttonBg1; ?>, <?php echo $buttonBg2; ?>);
			background: -o-linear-gradient(<?php echo $buttonBg1; ?>, <?php echo $buttonBg2; ?>);
			background: linear-gradient(<?php echo $buttonBg1; ?>, <?php echo $buttonBg2; ?>);
			-pie-background: linear-gradient(<?php echo $buttonBg1; ?>, <?php echo $buttonBg2; ?>);
		 }
		 
		 a.green:hover {color: #587930;}
		 .formerror {margin-top: 8px; color:red; font-size: 12px; display: none;}
		 
	  </style>
	  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
   </head>
   <body>
	  
	  <div style='margin:50px auto 30px auto; text-align: center; width:500px;'><img src='/public/images/appmonitor/monitoring.png' height="60" style="opacity: 0.9" /></div>
	  
	  <div style="background:#f9f9f9; border:1px solid #eee; width:400px; margin:20px auto; padding:50px 50px 25px 50px;">
		 <form method="POST" action="/AppMonitor/Dashboard/Login" id='loginForm' onsubmit="return submitForm()">
		 <div style='float:left; width:100px; margin-top:7px;'>Email</div>
		 <div style='float:left;'><input type='text' name='username' id='email' class='inputbox' />
		 <div class='formerror' id='email_error'>Please enter email</div>
		 </div>
		 <div class="clearFix" style="margin-bottom: 30px;"></div>
		 
		 <div style='float:left;width:100px; margin-top:7px;'>Password</div>
		 <div style='float:left;'><input type='password' name='password' id='password' class='inputbox' />
		 <div class='formerror' id='password_error'>Please enter password</div>
		 </div>
		 <div class="clearFix" style="margin-bottom: 30px;"></div>
		 
		 <div style='float:left; margin-left:86px;'><input type='submit' class='button small white' value='Login' /></div>
		 <div class="clearFix"></div>
		 
		 <div class='formerror' id='error_login' style='margin-left: 100px; margin-top:20px;'></div>
		 
		 <input type="hidden" name="doLogin" value="1" />
		 </form>
	  </div>
	  <script>
		function submitForm()
		{
			var email = $.trim($('#email').val());
			var password = $.trim($('#password').val());
			
			var hasError = false;
			
			if (email) {
			   $('#email_error').hide();
			}
			else {
			   $('#email_error').show();
			   hasError = true;
			}
			
			if (password) {
			   $('#password_error').hide();
			}
			else {
			   $('#password_error').show();
			   hasError = true;
			}
			
			if (hasError) {
			   return false;
			}
			else {
				var shiksha_auth_token = $('#shiksha_auth_token').val();
				   $.post('/AppMonitor/Dashboard/doLogin',{'username':email,'mpassword':(password),'shiksha_auth_token':shiksha_auth_token},function(data) {
					   if(parseInt(data) > 1) {
						   window.location = '/AppMonitor/Dashboard';
					   }
					   else {
						   $('#error_login').html('Incorrect account details. Please enter valid login email Id & password.');
						   $('#error_login').show();
					   }
				   });
				   return false;
			}
		}
	  </script>
	<?php
	$CI = & get_instance();
	$CI->load->library('security');
	$CI->security->setCSRFToken();
	?>
	<input type="hidden" id="shiksha_auth_token" name="<?php echo $CI->security->csrf_token_name;?>" value="<?php echo $CI->security->csrf_hash;?>" />
   </body>
</html>