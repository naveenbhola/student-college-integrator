<?php	
$this->load->helper('form');

	$headerComponents = array(
								'css'	=>	array('common','user','studyAbroadCommon'),
								//'js'	=>	array('common','prototype','user','tooltip','utils'),
								'title'	=>	'Forgot Password',
								'tabName'	=>'',
								//'taburl' =>  site_url('user/MyShiksha'),	
								'metaKeywords'	=>'',
								'product' => '',
								'displayname'=> "",
								'callShiksha'=>1
								
							);
		//$this->load->view('common/homepage', $headerComponents);
		$this->load->view('common/studyAbroadHeader', $headerComponents);
?>
<div class="home-wrapper clearfix" style="width: 600px;margin: 0 auto">
<form method="post" style="width: 100%;border: 1px solid #eee;margin: 0" id = "ForgotForm" onsubmit="new Ajax.Request('<?php echo base_url()."user/Userregistration/resetPassword"?>',{onSuccess:function(request){javascript:showResetPasswordResponse(request.responseText,this.form)}, onFailure:function(request){javascript:showResetPasswordResponse(request.responseText,this.form)}, evalScripts:true, parameters:Form.serialize(this)}); return false;" action='<?php echo base_url()."user/Userregistration/resetPassword"?>'>

	<div class="lineSpace_13">&nbsp;</div>
<input type = "hidden" value = "<?php echo $uname?>" id = "uname" name  = "uname"/>
	<div class="row">
		<div style="display: inline; float:left; width:100%;text-align:center">
			<div class="bld OrgangeFont fontSize_14p" style = "padding-left:20px">Forgot Password&nbsp;</div>
			<div class="clear_L"></div>
		

		
				<div class="row errorPlace" style="margin-top:2px;">
					<div class="r1_1">&nbsp;</div>
				<div id = "forgot_error" name = "forgot_error"  class = "errorMsg r2_2">
					<div class="clear_L"></div>	
				</div>
				</div>
	</div>			
	</div>
	<div class="lineSpace_20" style="border-bottom: 1px solid #eee;">&nbsp;</div>
	<div class="row">
		<div style="display: inline; float:left; width:100%;margin-top: 24px;">
				<div class="r1_1 bld" style="width:40%;">Login Email Id:&nbsp;<span class="redcolor">*</span>&nbsp;</div>
				<div class="r2_2" style="width:60%;">
						<input class = "txt_1"  type="text" name = "email"  id = "email" tip="email_id" style = "width:200px" maxlength = "125" validate = "validateEmail" required = "1" caption = "login email"/>
				</div>
				<div class="clear_L"></div>			
				<div class="row errorPlace" style="margin-top:2px;">
					<div class="r1_1" style="width:40%;">&nbsp;</div>
					<div class="r2_2 errorMsg" style="width:60%;" id= "email_error"></div>
					<div class="clear_L"></div>		
				</div>				
		</div>
	</div>
	<div class="lineSpace_20">&nbsp;</div>

<div class="row">
            <div style="display: inline; float:left; width:100%">
				<div class="r1_1 bld" style="width:40%;">New Password:&nbsp;<span class="redcolor">*</span>&nbsp;</div>
				<div class="r2_2" style="width:60%;">
					<input class = "txt_1" name = "passwordr" id = "passwordr" type = "password" style = "width:200px" tip="password_id" maxlength = "20" minlength = "5" validate = "validateStr" required = "1" caption = "password" />
				</div>
				<div class="clear_L"></div>		
				<div class="row errorPlace" style="margin-top:2px;">
					<div class="r1_1" style="width:40%;">&nbsp;</div>
					<div class="r2_2 errorMsg" style="width:60%;" id= "passwordr_error"></div>
				</div>		
	    	</div>			
	</div>
	<div class="lineSpace_20">&nbsp;</div>

<div class="row">
            <div style="display: inline; float:left; width:100%">
				<div class="r1_1 bld" style="width:40%;">Confirm New Password:&nbsp;<span class="redcolor">*</span>&nbsp;</div>
				<div class="r2_2" style="width:60%;">
					<input class = "txt_1" name = "confirmpassword" id = "confirmpassword" type = "password" style = "width:200px" tip="password_id" maxlength = "20" minlength = "5" validate = "validateStr"  required = "1" caption = "confirm password"/>
				</div>
				<div class="clear_L"></div>		
				<div class="row errorPlace" style="margin-top:2px;">
					<div class="r1_1" style="width:40%;">&nbsp;</div>
					<div class="r2_2 errorMsg" style="width:60%;" id= "confirmpassword_error"></div>
				</div>		
	    	</div>			
	</div>
	<div class="lineSpace_20">&nbsp;</div>

	<div class="row">
		<div style="display: inline; float:left; width:100%">
			<div class="r1_1" style="width:40%;">&nbsp;</div>
			<div class="r2_2" style="width:60%;">
				<div class="buttr3">
					<button class="btn-submit7 w21" value="" type="submit" id = "submitbutton" onclick = "return validateforgot(this.form)">
						<div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Submit</p></div>
					</button>
				</div>
				<div class="clear_L"></div>
			</div>
			<div class="clear_L"></div>
		</div>		
	</div>
	<div class="lineSpace_18">&nbsp;</div>
	<div class="lineSpace_20">&nbsp;</div>
	<div class="lineSpace_18">&nbsp;</div>
</form>
</div>
<!--end Footer-->
<?php 
$footerComponents = array(
	'js'                => array('common','prototype','user','tooltip','utils','json2')
);
$this->load->view('common/studyAbroadFooter',$footerComponents);
?>
<!--End_Footer-->
<script>
addOnBlurValidate(document.getElementById('ForgotForm'));
$j(document).ready(function(){
	$j('.home-wrapper').height($j(window).height()-($j('#header').height()));
});
</script>
