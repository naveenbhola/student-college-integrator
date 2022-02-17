<?php 
$this->load->view('/mcommon/header'); 
?>
<div id="head-sep"></div>
<div id="head-title">
	<h4 style="padding:5px 0">Sign in</h4>
    <span>&nbsp;</span>
</div>
<?php
	if (getTempUserData('confirmation_message')){?>
		<div style="border:1px solid #fff590; background:#feffcd; padding:10px; font:bold 13px Arial, Helvetica, sans-serif; margin:8px;">
		<?php echo getTempUserData('confirmation_message'); ?>
		</div> 
	<?php } 
?>
<div id="content-wrap">
	<div id="login-cont"><?php $hidden = array('currentUrl' => $current_url, 'referralUrl' => $referral_url); $attributes = array("autocomplete" => "off", 'accept-charset' => 'utf-8'); ?>
	<?=form_open('muser/MobileUser/login_validation',$attributes,$hidden);?>
    	<ul>	
		
        	<li><?php $attributes = array( 'pattern'=>"^(?:[-+âˆ¼=!#$%&amp;'*/?\^`{|}\w]+)(?:\.[-+âˆ¼=!#$%&amp;'*/?\^`{|}\w]+)*@(?:[a-zA-Z0-9][-a-zA-Z0-9]*[a-zA-Z0-9]\.)+[a-zA-Z]{2,6}$",'required'=>'','name'=> 'user_email','value'=>set_value('user_email'),'class'=>"login-field",'maxlength'=>"125");?>
            	<label><?=form_label('E-mail', 'user_email')?></label>
                <div class="field-cont">
		<?=form_email($attributes)?>
	<div style="color:red;font-size:13px;"><?php $err=form_error('user_email');echo strip_tags($err);?></div>
              </div>
            </li>
            
            <li><?php $attributes = array( 'name'=> 'user_pass','value'=>set_value('user_pass'),'class'=>"login-field");?>
      			 <label><?=form_label('Password', 'user_pass')?></label>
                <div class="field-cont">
		<?=form_password($attributes)?>
	<div style="color:red;font-size:13px;"><?php $err=form_error('user_pass');echo strip_tags($err);?> </div>
                 </div>
	<div style="color:red;font-size:13px;"><?php if($status=='2') echo "Login details are incorrect.";?></div>


            </li><?php $attributes = array( 'name'=> 'login','value'=>'Login','class' => 'orange-button');?>
            <li style="padding-top:5px"><?=form_submit($attributes)?></li>
	<!--	<input type="button" value="Login" class="orange-button" /></li> -->
            <li style=""><a href= "<?php echo "/muser/MobileUser/forgot_pass/"?>">Forgot Password?</a></li>
        </ul>
        <div class="clearFix"></div>
</div>
</div>
    <?php 
    deleteTempUserData('confirmation_message');
    ?>
<?php
$this->load->view('/mcommon/footer');
?>
