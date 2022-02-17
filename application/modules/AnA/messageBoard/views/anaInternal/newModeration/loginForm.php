<div class="row"><!-- Middle Content -->
	<div class="col-md-12">
		<form class="form-horizontal" id="LoginForm_ForAnA" action="/user/Login/submit" onsubmit="if(validateLoginForAnA(this) != true){return false;}; new Ajax.Request('/user/Login/submit',{onSuccess:function(request){javascript:window.location.reload();}, evalScripts:true, parameters:Form.serialize(this)}); return false;" method="post" novalidate="novalidate">
		  <input type="hidden" name="typeOfLoginMade" id="typeOfLoginMade" value="veryQuck" />
		  <input type="hidden" name="mpassword_ForAnA" id="mpassword_ForAnA" value=""/>
		  <div class="form-group">
		    <label for="inputEmail3" class="col-sm-2 col-sm-offset-2 control-label">Login Email Id</label>
		    <div class="col-sm-6">
		      <input type="email" id="username_ForAnA" name="username_ForAnA" validate = "validateEmail" required = "true" caption="email address" maxlength="125" minlength="10" class="form-control" />
		      <div style="display:none;"><div style="margin-top:4px;" class="text-danger" id= "username_ForAnA_error"></div></div>
		    </div>
		  </div>
		  <div class="form-group">
		    <label for="inputPassword3" class="col-sm-2 col-sm-offset-2 control-label">Password</label>
		    <div class="col-sm-6">
		      <input type="password" id="password_ForAnA" name="password_ForAnA" validate="validateStr" minlength="5" maxlength="20" required="true" caption="password" class="form-control" />
		      <div style="display:none"><div style="margin-top:4px;" class="text-danger" id="password_ForAnA_error"></div></div>
		    </div>
		  </div>
		  <div class="form-group">
		    <div class="col-sm-offset-4 col-sm-6">
		      <button type="submit" class="btn btn-default">Login</button>
		    </div>
		  </div>
		  <?php
			$CI = & get_instance();
			$CI->load->library('security');
			$CI->security->setCSRFToken();
			?>
			<input type="hidden" id="shiksha_auth_token" name="<?php echo $CI->security->csrf_token_name;?>" value="<?php echo $CI->security->csrf_hash;?>" />
		</form>
	</div>
</div>
<script>
    addOnBlurValidate($('LoginForm_ForAnA'));
</script>