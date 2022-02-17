<?php $this->load->view('/mcommon/header'); ?>
<div id="head-sep"></div>
<div id="head-title">
	<h4 style="padding:5px 0">Join now for free</h4>
    <span>&nbsp;</span>
</div>

<div id="content-wrap">
	<div id="login-cont"><?php $hidden = array('currentUrl' => $current_url, 'referralUrl' => $referral_url); $attributes = array("autocomplete" => "off", 'accept-charset' => 'utf-8');?>
	<?=form_open('muser/MobileUser/register_validation',$attributes,$hidden)?>
    	<ul>	
            
            <li><?php $attributes = array( 'required' => '' , 'name'=> 'user_first_name','value'=>set_value('user_first_name'),'class'=>"login-field",'maxlength'=>"50",'minlength '=>"1");?>
      			 <label><?=form_label('First Name', 'user_first_name')?></label>
                <div class="field-cont">
			<?=form_input($attributes)?>
			<div style="color:red;font-size:13px;"><?php $err=form_error('user_first_name');echo strip_tags($err);?> </div>
                 </div>
	    </li>
        <li><?php $attributes = array( 'required' => '' , 'name'=> 'user_last_name','value'=>set_value('user_last_name'),'class'=>"login-field",'maxlength'=>"50",'minlength '=>"1");?> 
                             <label><?=form_label('Last Name', 'user_last_name')?></label> 
                    <div class="field-cont"> 
                            <?=form_input($attributes)?> 
                            <div style="color:red;font-size:13px;"><?php $err=form_error('user_last_name');echo strip_tags($err);?> </div> 
                   </div> 
              </li> 
     	  <li><?php $attributes = array('pattern'=>"^(?:[-+âˆ¼=!#$%&amp;'*/?\^`{|}\w]+)(?:\.[-+âˆ¼=!#$%&amp;'*/?\^`{|}\w]+)*@(?:[a-zA-Z0-9][-a-zA-Z0-9]*[a-zA-Z0-9]\.)+[a-zA-Z]{2,6}$",'required'=>'','name'=> 'user_email','value'=>set_value('user_email'),'class'=>"login-field",'maxlength'=>"125");?>
            	<label><?=form_label('E-mail', 'user_email')?></label>
                <div class="field-cont">
			<?=form_email($attributes)?>
			<div style="color:red;font-size:13px;"><?php $err=form_error('user_email');echo strip_tags($err);?></div>
              </div>
           </li>

	  <li><?php $attributes = array( "pattern"=>"\d{10}","required"=>"", 'name'=> 'user_mobile','value'=>set_value('user_mobile'),'class'=>"login-field",'maxlength'=>"10");?>
            	<label><?=form_label('Mobile', 'user_mobile')?></label>
                <div class="field-cont">
			<?=form_mobile($attributes)?>
			<div style="color:red;font-size:13px;"><?php $err=form_error('user_mobile');echo strip_tags($err);?></div>
              	</div>
           </li>
            	<div style="color:red;font-size:13px;"><?php if($errorMessage=="User already exists"){echo $errorMessage.", please  ";?><a href="/muser/MobileUser/login/">Login </a><?php }?></div>
           </li><?php $attributes = array( 'name'=> 'login','value'=>'Join now for free','class' => 'orange-button');?>
            <li><?=form_submit($attributes)?></li>
	    <li>
		<small>By clicking on the above button, I agree to the <a href= "/shikshaHelp/ShikshaHelp/termCondition">terms of services</a> and
		<a title="Privacy" href="<?php echo SHIKSHA_HOME; ?>/mcommon/MobileSiteStatic/privacy">Privacy</a></small>
            </li>
        </ul>
        <div class="clearFix"></div>
    </div>
</div>
<?php $this->load->view('/mcommon/footer');?>
