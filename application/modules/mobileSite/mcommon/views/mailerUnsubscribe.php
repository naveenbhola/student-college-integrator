<?php $this->load->view('header'); ?>
<div id="head-sep"></div>
<div id="content-wrap" style="margin: 10px 10px 30px">
	<div class="unsubscribe-box">
		<p>Are you sure you want to unsubscribe this mail? You will not receive important communication from us.</p>
		<a href="<?php echo site_url(); ?>" class="gray-button" style="width:auto; margin:0 10px 0 0; display:inline">No</a>
		<a href="/mcommon/MobileSiteHome/Unsubscribe/<?php echo $_REQUEST['encodedMail']; ?>/">Yes</a>
	</div>
	<a href="<?php echo site_url(); ?>" class="gray-button btn-style">Go to Shiksha.com</a>
</div>
<?php $this->load->view('footer'); ?>