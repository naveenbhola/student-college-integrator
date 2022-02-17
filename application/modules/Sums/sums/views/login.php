<?php
    $headerComponents = array(
        'css'      =>        array('headerCms','raised_all','mainStyle','footer'),
        'js'         =>            array('user','tooltip','common','prototype','md5'),
		'title'      =>        'SUMS - Login',
        'tabName'          =>        'Register',
        'taburl' =>  SITE_URL_HTTPS.'enterprise/Enterprise/register',
        'metaKeywords'  =>'Some Meta Keywords',
        'product' => '',
        'search'=>false,
	'displayname'=> (isset($sumsUserInfo['validity'][0]['displayname'])?$sumsUserInfo['validity'][0]['displayname']:""),
        'callShiksha'=>1
    );
    $this->load->view('enterprise/headerCMS', $headerComponents);
    $this->load->view('enterprise/cmsTabs',$sumsUserInfo);
?>
<div class="spacer10 clearFix"></div>
<div style="width:300px; padding:20px; border:1px solid #cacaca; margin:0 auto; border-radius:10px">
	<span class="fontSize_16p">SUMS User Login</span>
	<form method="post" onsubmit="$('mpassword1').value = ($('password1').value);new Ajax.Request('/user/Login/submit',{ onSuccess:function(request){redirectToSUMS(request.responseText);}, evalScripts:true, parameters:Form.serialize(this)}); return false;" id="loginForm">
    <input name = "mpassword" id = "mpassword1"  type="hidden" value="" />
    <div class="mar_top_6p">
        <div class="float_L" style="width:100px;">Email Id:</div>
        <div class="float_L"><input type="text" size="20" name="username" id="username" maxlength="125" minlength="5" validate="validateEmail" required="true" /></div>
        <div class="clear_L"></div>
    </div>
	<div class="mar_top_6p">
		<div class="float_L" style="width:100px;">Password:</div>
		<div class="float_L"><input size="20" id="password1" type="password" value="" maxlength="25" minlength="2" validate="validateStr" required="true" /></div>
		<div class="clear_L"></div>
	</div>
    <div style="padding-left:100px">
        <div class="redcolor" id="loginerror"></div>
        <div class="spacer10 clearFix"></div>
        <input type="submit" value="Login">
    </div>
    </form>
</div>
<div class="spacer20 clearFix"></div>


<script>
function redirectToSUMS(response)
{
    var redirectLocation = '<?php echo base64_decode($sendUrl); ?>';
    if (redirectLocation=='')
    {
            redirectLocation = '/sums/Manage/validationQueue/viewTrans'; 
    }
    if (response!='0')
    {
        window.location = redirectLocation;
    }
	else
	{
		$('loginerror').innerHTML = "Login Failed";
	}
}
</script>
</div>
<div class="clearFix"></div>
</div></div>