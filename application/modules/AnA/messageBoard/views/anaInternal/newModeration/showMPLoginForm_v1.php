<?php $this->load->view('anaInternal/newModeration/mpHeader'); ?>
<?php
if($validateuser == 'false')
{
	$this->load->view('anaInternal/newModeration/loginForm');
}
else
{
?>
<div class="row">
<p class="lead">You are not authorized. Go to <a href="<?=SHIKSHA_HOME?>">Homapage</a></p>
</div>
<?php
}
?>
<?php  ?>
<?php $this->load->view('anaInternal/newModeration/mpFooter'); ?>