<?php
   $headerComponents = array(
      'js'=>array('common'),
      'css'=>array('shikshaLayout')
	);
	$this->load->view('common/headerWithoutHTML', $headerComponents);
?>

<div id="logo-section">
   
      <h1 class="shik-logo" style="padding-top: 5px;">
	 <a href='<?php echo site_url();?>' tabindex="6" title="Shiksha.com">
	    
	    <img src='<?php echo SHIKSHA_HOME; ?>/public/images/nshik_ShikshaLogo1.gif' alt="Shiksha.com" title="Shiksha.com" border="0" class="flLt"/>
	 </a>
      </h1>
</div>
   <div style="padding: 0px 0px 0px 0px; font-weight: 700;"/>


<div style="line-height:20px">&nbsp;</div>
<!--
<div style="margin:0 20px">
   <div class="welcomeIcon">
			<div>
				<span class="fontSize_13p bld">Welcome </span><span class="fontSize_13p bld OrgangeFont"><?php echo $name;?></span>
			</div>
			<div style="height:13px;"></div>	
		</div>
</div>
-->


<div style="padding:25px; margin:20px 20px 300px 20px; background:#ffffcd; border:1px solid #fdf391; color:#000; font:normal 18px/23px Arial, Helvetica, sans-serif">
        Thanks for signing up with Shiksha.com, your account has been successfully created.
		<span style="margin-top:20px; display: block">
		To help you with institute creation, someone from our team will contact you to understand your requirements.<br />
		Meanwhile if you want to contact us, you can email us at <a href="mailto: sales@shiksha.com">sales@shiksha.com</a>.<br>
		</span>
		<span style="font-size:14px; margin-top:20px; display: block">
	    Institutes inside India can call us at our local city office. <a href="<?php echo SHIKSHA_HOME; ?>/shikshaHelp/ShikshaHelp/contactUs" target="_new">Click here</a> to see details of our local offices.<br/>
	    Institutes outside India can call us at our Toll Free number: 1800-717-1094
		</span>
</div>



<?php
	$this->load->view('common/footerNew.php');
?>

