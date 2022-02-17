<?php ob_start('compress'); ?>
<?php $this->load->view('/mcommon5/header'); ?>


<div id = "wrapper" data-enhance="false" data-role="page">
  <header id="page-header" data-role="header"  >
        <div class="head-group">
             <a href="javascript:void(0);" data-rel="back" class="head-icon-b"><i class="icon-arrow-left"></i></a>
              <h1>Log In on Shiksha</h1>
        </div>

 </header>
 <div class="content-wrap2">
	<section class="content-child clearfix">
		 <?php
        if (getTempUserData('confirmation_message')){?>
                <div class="msg-box">
                <?php echo getTempUserData('confirmation_message'); ?>
                </div> 
        <?php } 
?>

	<p class="login-title">Please enter your email id and password to log in</p>
	
<?php $hidden = array('currentUrl' => $current_url, 'referralUrl' => $referral_url); $attributes = array("autocomplete" => "off", 'accept-charset' => 'utf-8','onsubmit' =>"return validateForm()",'novalidate'=>"novalidate"); ?>
	<?=form_open('../muser5/MobileUser/login_validation',$attributes,$hidden);?>
    	    <ol class="form-item">
		
        	<li id ="user_email_cont"><?php $attributes = array( 'pattern'=>"^(?:[-+âˆ¼=!#$%&amp;'*/?\^`{|}\w]+)(?:\.[-+âˆ¼=!#$%&amp;'*/?\^`{|}\w]+)*@(?:[a-zA-Z0-9][-a-zA-Z0-9]*[a-zA-Z0-9]\.)+[a-zA-Z]{2,6}$",'required'=>'','name'=> 'user_email','value'=>set_value('user_email'),'class'=>"login-field",'maxlength'=>"125",'id'=>'user_email','oninvalid'=>"$('#user_email_cont').addClass('error');");?>
            	  <?=form_label('Login Email Id', 'user_email')?>
                    <div class="textbox"><?=form_email($attributes)?> </div>
	            <div class="errorMsg" id="user_email_error"><?php $err=form_error('user_email');echo strip_tags($err);?></div>
             
               </li>
            
	        <li id ="user_pass_cont"><?php $attributes = array( 'name'=> 'user_pass','value'=>set_value('user_pass'),'class'=>"login-field",'id'=>"user_pass",'required'=>'','oninvalid'=>"$('#user_pass_cont').addClass('error'); ");?>
      	          <?=form_label('Password', 'user_pass')?>
        	       <div class="textbox"><?=form_password($attributes)?></div>
		        <div class="errorMsg"  id="user_pass_error"><?php $err=form_error('user_pass');echo strip_tags($err);?> 
   	                 <?php if($status=='2') { echo 'Login details are incorrect.'; }?>
			</div>
               </li>

                    <?php $attributes = array( 'name'=> 'login','value'=>'Log In','class' => 'r-btn');?>
                    <?php if((!empty($_GET['source']) && $_GET['source'] == 'ICP') || (!empty($source) && $source == 'ICP')){ ?>
                        <input type="hidden" value="ICP" name="ICP" />
                    <?php } ?>
                        <input type="hidden" value="<?php if(!empty($pageSource)){echo $pageSource;}else{ echo base64_encode(SHIKSHA_HOME); } ?>" name="pageSource" />
                        <?php if(!empty($tracking_keyid)){ ?>
                          <input type="hidden" value="<?php echo $tracking_keyid; ?>" name="tracking_keyid" />
                        <?php } ?>

                        <?php if(!empty($callBackType)){ ?>
                          <input type="hidden" value="<?php echo $callBackType; ?>" name="callBackType" />
                        <?php } ?>

                        <?php if(!empty($clientCourseId)){ ?>
                          <input type="hidden" value="<?php echo $clientCourseId; ?>" name="clientCourseId" />
                        <?php } ?>

                        <?php if(!empty($courseArray)){ ?>
                          <input type="hidden" value="<?php echo $courseArray; ?>" name="courseArray" />
                        <?php } ?>

                        <?php if(!empty($from_where)){ ?>
                          <input type="hidden" value="<?php echo $from_where; ?>" name="from_where" />
                        <?php } ?>

                        <?php if(!empty($pageName)){ ?>
                          <input type="hidden" value="<?php echo $pageName; ?>" name="pageName" />
                        <?php } ?>

                        <?php if(!empty($institute_id)){ ?>
                          <input type="hidden" value="<?php echo $institute_id; ?>" name="institute_id" />
                        <?php } ?>
                        <?php if(!empty($formCallBack)){ ?>
                          <input type="hidden" value="<?php echo $formCallBack; ?>" name="formCallBack" />
                        <?php } ?>


               <li class="login-btn"><?=form_submit($attributes)?> 
	       </li>

	       <li>
                        <p class="fs12"><a href="../muser5/MobileUser/forgot_pass">Forgot password?</a></p>
               </li>

               <li>
                        <p class="fs12"><a href="../muser5/MobileUser/register">New User? Register here.</a></p>
               </li>


	  </ol>
	</form>
        </section>
</div>
<?php
if(isset($_GET['email']) && $_GET['email']!=''){
  echo "<script>$('#user_email').val('".$_GET['email']."'); $('#user_pass').focus();</script>";
}
?>

<?php $this->load->view('/mcommon5/footerLinks'); ?>
<?php $this->load->view('/mcommon5/footer'); ?>
</div>

  <?php deleteTempUserData('confirmation_message'); ?>
  

<script>
function validateForm(){
var formValid=true;
  var filter = /^((([a-z]|[A-Z]|[0-9]|\-|_)+(\.([a-z]|[A-Z]|[0-9]|\-|_)+)*)@((((([a-z]|[A-Z]|[0-9])([a-z]|[A-Z]|[0-9]|\-){0,61}([a-z]|[A-Z]|[0-9])\.))*([a-z]|[A-Z]|[0-9])([a-z]|[A-Z]|[0-9]|\-){0,61}([a-z]|[A-Z]|[0-9])\.)[\w]{2,4}|(((([0-9]){1,3}\.){3}([0-9]){1,3}))|(\[((([0-9]){1,3}\.){3}([0-9]){1,3})\])))$/
if($('#user_email').val()==''){
	 showError('user_email','The Login Email Id field is required');
         formValid = false;
}
else if(!filter.test($('#user_email').val())) {
       	 showError('user_email','The Login Email Id field must contain valid email address.');
	 formValid= false;
}
else{
         hideError('user_email');
}
if($('#user_pass').val()==''){
	showError('user_pass','Please enter your password');
	formValid = false;
}
else{
	hideError('user_pass');
}
return formValid;
}

function showError(id,caption){
$('#'+id+'_cont').addClass('error'); 
$('#'+id+'_error').html(caption);
$('#'+id+'_error').show();
}

function hideError(id){ 
$('#'+id+'_cont').removeClass('error'); 
$('#'+id+'_error').html('');
$('#'+id+'_error').hide();
}	
</script>
<?php ob_end_flush(); ?>


<?php
$CI = & get_instance();
$CI->load->library('security');
$CI->security->setCSRFToken();
?>
<input type="hidden" id="shiksha_auth_token" name="<?php echo $CI->security->csrf_token_name;?>" value="<?php echo $CI->security->csrf_hash;?>" />