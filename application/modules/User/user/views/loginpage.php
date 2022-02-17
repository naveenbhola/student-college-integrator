<html>
<head>
<script src = "/public/js/common.js"></script>
<style type = "text/css">
#user
{

font-family:verdana,arial;
font-size:13px; 
padding-left:100px;
}
#text
{
width:50px;
font-family:verdana,arial;
font-size:13px;
position:absolute; 
left:250px;
}
.error
{
font-family:verdana,arial;
font-size:13px;
position:absolute; 
left:250px;
width:1000px;
padding-top:10px;
color:red;
}
#ref
{
font-family:verdana,arial;
font-size:13px;
position:absolute; 
left:350px;
width:1000px;
color:red;
}

<?php
$this->load->helper('form');
?>
</style>
</head>

<body>
<?php
$headerComponents = array(
								//'css'	=>	array('header','raised_all','mainStyle','footer'),
                                'css' => array('user'),
								'js'	=>	array('common','prototype','user'),
								'title'	=>	'User Registration',
								'tabName'	=>	'Register',
								'taburl' =>  '',
								'metaKeywords'	=>'Some Meta Keywords'
							);
		//$this->load->view('common/header', $headerComponents);
?>

<!--<form method=post action="https://localhost/shiksha/validate"/>-->
<?php
$attributes = array('name' => 'login', 'onSubmit' => 'return validateFields(this);');
echo form_open('user/login/submit',$attributes); ?>
<input name = "redirectUrl" id = "redirectUrl" type = "hidden" value = "<?php echo $redirectUrl;?>">
<div id = "userform" style = "position:absolute; top:100px;">
	
<div id = "user">User-name: * &nbsp
<span id = "text" ><input name = "username"  id = "username" type = "text" maxlength = "25" minlength = "2" validate = "validateStr" value = ""></span>
</div>
<div class="formField errorPlace">
<div class="error" id= "username_error"></div>
</div>
<br><br>

<div id = "user">Password: *
<span id = "text" ><input name = "password" id = "password" type = "password" maxlength = "25" minlength = "5" validate = "validateStr" value = ""/>
</div>
<div class="formField errorPlace">
<div class="error" id= "password_error"></div>
</div>
<br><br>

<div id = "user"><input type = "checkbox" name = "remember" id= "remember">Remember me on this computer
<span id = "ref">
<a href="/user/userregistration">New User</a>
</span>
</div>
<br/><br/>
<input type = "submit" name = "submit" value = "Login" style = "position:absolute;left:250px;"
>
</div>
</form>
</body>
</html>


