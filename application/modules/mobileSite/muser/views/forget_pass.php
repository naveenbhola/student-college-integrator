<?php 
$this->
load->view('/mcommon/header');
?>
<div id="head-sep"></div>
<div id="head-title">
	<h4 style="padding:5px 0">Forgot Password</h4>
	<span>&nbsp;</span>
</div>

<div id="content-wrap">
	<div id="login-cont">
		<?php 
		$attributes = array("autocomplete" => "off", 'accept-charset' => 'utf-8');
		$hidden = array('currentUrl' => $current_url,'referralUrl' => $referral_url);
		?>
		<?=form_open('muser/MobileUser/forgot_validation',$attributes,$hidden);?>
		<ul>
			<li>
				<?php $attributes = array('name'=>
				'user_email','pattern'=>"^(?:[-+âˆ¼=!#$%&amp;'*/?\^`{|}\w]+)(?:\.[-+âˆ¼=!#$%&amp;'*/?\^`{|}\w]+)*@(?:[a-zA-Z0-9][-a-zA-Z0-9]*[a-zA-Z0-9]\.)+[a-zA-Z]{2,6}$",'required'=>'','value'=>set_value('user_email'),'class'=>"login-field",'maxlength'=>"125");?>
				<label>
					<?=form_label('E-Mail', 'user_email')?></label>
				<div class="field-cont">
					<?=form_email($attributes,'email')?>
					<?php $err=form_error('user_email');?>
					<div style="color:red;font-size:13px;">

						<?php if($err!='')  echo strip_tags($err);
		if($status=='2') echo "This email is not yet registered.";?></div>
				</div>
			</li>

			<?php $attributes = array( 'name'=>
			'login','value'=>'Submit','class' => 'orange-button');?>
			<li style="padding-top:5px">
				<?=form_submit($attributes)?></li>

		</ul>
		<div class="clearFix"></div>
	</div>
</div>
<?php
$this->
load->view('/mcommon/footer');
?>