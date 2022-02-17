<div class="wrapperFxd">
<?php 
		$headerComponents = array(
								'css'	=>	array(
											'header',
											'mainStyle',
											'raised_all',
											'footer'
										),
								'js'	=>	array(
											'header',
											'common',
											'user'										),
								'title'	=>	'Shiksha :: Alumni Feedback',
								'metaDescription' => 'Alumni Feedback',
								'metaKeywords'	=>'ALumni Feedback',
							);
                    $this->load->view('common/homepage_simple', $headerComponents); 
?>
    <div class="lineSpace_10">&nbsp;</div>
<div style="margin:0 10px">
	<div style="background:#fffdd6;border:1px solid #facb9d;line-height:30px;display:block;opacity:1;filter:alpha(opacity=10); -moz-opacity:1" id="statusInfo" >
        <a style="margin:0 10px 0 0;float:right" onclick="this.parentNode.style.display= 'none';" href="#">Hide</a>
        &nbsp; &nbsp; &nbsp;<b class="fontSize_16p">The mail has been sent successfully to <?php echo $numEmailsSent;?> recipient<?php echo $numEmailsSent > 1 ? 's' : '';  ?>. </b> <a href="/alumni/AlumniSpeakFeedBack/postFeedback/<?php echo $inviteToken; ?>">Invite More</a>
        <div style="clear:right"></div>
    </div>
    <div>
      <div class="lineSpace_5">&nbsp;</div>		
    </div>
    Go To <a href="/">Shiksha.com</a>
</div>

    <div>
      <div class="lineSpace_25">&nbsp;</div>		
    </div>
    <div>
      <div class="lineSpace_25">&nbsp;</div>		
    </div>
    <div>
      <div class="lineSpace_25">&nbsp;</div>		
    </div>
    <div>
      <div class="lineSpace_25">&nbsp;</div>		
    </div>
    <div>
      <div class="lineSpace_25">&nbsp;</div>		
    </div>
    <div>
      <div class="lineSpace_25">&nbsp;</div>		
    </div>
    <div>
      <div class="lineSpace_25">&nbsp;</div>		
    </div>
    <div>
      <div class="lineSpace_25">&nbsp;</div>		
    </div>
    <div>
      <div class="lineSpace_25">&nbsp;</div>		
    </div>
    <div>
      <div class="lineSpace_25">&nbsp;</div>		
    </div>
</div>
<?php
$this->load->view('common/footer');
?>
<script>
//dissolveElement(document.getElementById('statusInfo'));
</script>
