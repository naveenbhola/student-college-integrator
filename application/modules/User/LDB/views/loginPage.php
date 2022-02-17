<?php
$buttonBg1 = "#f0f0f0";
$buttonBg2 = "#e2e2e2";
?>
<html>
   <head>
	  <title>
		 Lead Tracking - Login
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
		 
		
		 .formerror {margin-top: 8px; color:red; font-size: 12px; display: none;}
		 
	  </style>
	  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
   </head>
   <body>
	
	 <div style='margin:50px auto 30px auto; text-align: center; width:500px;'><img src='/public/images/leadTracking/TrackingImg.jpg' height="100" style="opacity: 0.9" /></div>

	  <div style="background:#d1e0e0; border:1px solid #eee; width:400px; margin:6px auto; padding:36px 31px 7px;">
		 <div  id='loginForm' >
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
			 
			 <div style='float:left; margin-left:86px;'><input type='submit' onclick='submitForm()' class='button small white' value='Login' /></div>
			 <div class="clearFix"></div>
			 
			 <div class='formerror' id='error_login' style='margin-left: 100px; margin-top:20px;'></div>
			 
			 <input type="hidden" name="doLogin" value="1" />
		 </div>
	  </div>
	
	 <script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('leadTracking'); ?>"></script>

   </body>
</html>