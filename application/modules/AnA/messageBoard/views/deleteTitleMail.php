<div width="600">
Dear <?php echo  $displayName; ?>,<br/>

<p>Thanks for your submission of the following title. Unfortunately, at this moment your submission cannot be accepted by the moderators as it does not add relevance to the context of the question.</p>

<p><b><?php echo nl2br($msgTitle);?></b></p>
<p>We value your contribution and strongly encourage you to try submitting a different title which is relevant and easily understandable.</p>
<?php if($Url){ ?><p>To go to the question, click on the link below.<br/><a href="<?php echo $Url;?>">Click Here</a> <?php } ?></p>
<br/>
Thank you,<br/>
Shiksha.com team
</div>

