<?php $this->load->view('mcommon5/header',$headerComponent);
?>
<div id="wrapper" data-role="page" class="of-hide" data-enhance="false"> 
	<header id="page-header" class="clearfix">
		<div id="logo-box"><a href="<?=SHIKSHA_HOME?>" class="logo"></a></div>
	</header>
	
	<div class="content-wrap2">
    	<section class="content-child clearfix">
            <p class="unsubs-text">Thanks for your feedback. You have been successfully Unsubscribed from our mailing list. We’ll not deliver any mails to you again..</p>
        
	
		<a href="<?php echo site_url(); ?>" class="refine-btn" style="box-shadow:0 1px 1px #888">Go to Shiksha.com</a>
	
	</section>
	</div>
        <?php $this->load->view('mcommon5/footerLinks');?>
</div>
<?php
$this->load->view('mcommon5/footer');
?>



