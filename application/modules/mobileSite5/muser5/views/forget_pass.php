<?php  $this->load->view('/mcommon5/header'); ?>

<div id = "wrapper" data-enhance="false" data-role="page">
  <header id="page-header" data-role="header">
        <div class="head-group">
            <a href="javascript:void(0);" data-rel="back" class="head-icon-b"><i class="icon-arrow-left"></i></a>

            <h3>Forgot Password</h3>
        </div>
 </header>

<div class="content-wrap2">
	<section class="content-child clearfix">
                <p class="login-title">Please enter your login email id</p>
		<?php 
		$attributes = array("autocomplete" => "off", 'accept-charset' => 'utf-8','onsubmit' =>"return validateForm()",'novalidate'=>'novalidate');
		$hidden = array('currentUrl' => $current_url,'referralUrl' => $referral_url);
		?>
		<?=form_open('../muser5/MobileUser/forgot_validation',$attributes,$hidden);?>
		<ol class="form-item">
			<li id="user_email_cont">
				<?php $attributes = array('id'=>'user_email','name'=>
				'user_email','pattern'=>"^(?:[-+âˆ¼=!#$%&amp;'*/?\^`{|}\w]+)(?:\.[-+âˆ¼=!#$%&amp;'*/?\^`{|}\w]+)*@(?:[a-zA-Z0-9][-a-zA-Z0-9]*[a-zA-Z0-9]\.)+[a-zA-Z]{2,6}$",'required'=>'','value'=>set_value('user_email'),'class'=>"login-field",'maxlength'=>"125",'oninvalid'=>"$('#user_email_cont').addClass('error');");?>
				
					<?=form_label('E-Mail', 'user_email')?>
				<div class="textbox">
					<?=form_email($attributes,'email')?>
 				</div>
				<div id="user_email_error" class="errorMsg">
					<?php $err=form_error('user_email');?>
					<?php if($err!='')  echo strip_tags($err);
					if($status=='2') echo "This email is not yet registered.";?>
				</div>
		
			</li>
		<?php $attributes = array( 'name'=> 'login','value'=>'Submit','class' => 'r-btn');?>
            		<li class="login-btn"><?=form_submit($attributes)?> 
  			</li>
		</ol>
		</form>
	</section>
</div>
<?php $this->load->view('/mcommon5/footerLinks'); ?>
<?php $this->load->view('/mcommon5/footer'); ?>
</div>
<script>
function validateForm(){
          var formValid=true;
          var filter = /^((([a-z]|[A-Z]|[0-9]|\-|_)+(\.([a-z]|[A-Z]|[0-9]|\-|_)+)*)@((((([a-z]|[A-Z]|[0-9])([a-z]|[A-Z]|[0-9]|\-){0,61}([a-z]|[A-Z]|[0-9])\.))*([a-z]|[A-Z]|[0-9])([a-z]|[A-Z]|[0-9]|\-){0,61}([a-z]|[A-Z]|[0-9])\.)[\w]{2,4}|(((([0-9]){1,3}\.){3}([0-9]){1,3}))|(\[((([0-9]){1,3}\.){3}([0-9]){1,3})\])))$/
        if($('#user_email').val()==''){
                showError('user_email','The Email Id field is required');
                formValid = false;
        }
        else if(!filter.test($('#user_email').val())) {
                showError('user_email','The Email Id field must contain valid email address.');
                formValid=false;
        }
        else{
               hideError('user_email');
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
