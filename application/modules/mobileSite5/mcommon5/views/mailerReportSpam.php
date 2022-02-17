<?php
$this->load->view('mcommon5/header',$headerComponent);
?>
<div id="wrapper" data-role="page" class="of-hide" data-enhance="false"> 
	
	<header id="page-header" class="clearfix">
		<div id="logo-box"><a href="<?=SHIKSHA_HOME?>" class="logo"></a></div>
	</header>
	
	<div class="content-wrap2">
    	<section class="content-child clearfix">
            <p class="unsubs-text">Are you sure the mailer sent to you was spam?</p>
        
		<a href="<?php echo site_url(); ?>" class="refine-btn flLt" style="width: 49%; box-shadow:0 -1px 1px #888">NO</a>
		<a href="/mcommon5/MobileSiteHome/recordMailerReportSpam/<?php list($text,$mailerId) = explode('-',$_REQUEST['mailerId']); echo $mailerId; ?>/<?php echo $_REQUEST['mailId']; ?>" class="cancel-btn flRt" style="width: 49%; box-shadow:0 -1px 1px #888">Yes</a>
		
	    <div class="unsubs-btn-box">
		<a href="<?php echo site_url(); ?>" class="refine-btn" style="box-shadow:0 1px 1px #888">Go to Shiksha.com</a>
	    </div>
		
	</section>
	</div>
        <?php $this->load->view('mcommon5/footerLinks');?>
</div>
<?php
$this->load->view('mcommon5/footer');
?>
