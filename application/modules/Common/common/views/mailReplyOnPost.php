<p>Dear <?php echo $commenterOwnerName; ?>,</p>

<p><?php echo $replyOwnerName;?> has replied to your comment.</p>
<p><b>Your Comment:</b> <?php echo nl2br($commentTxt);?></p>
<p><b><?php echo $replyOwnerName;?>'s reply:</b>  <?php echo $replyTxt?>

<p>Please <a href="<?php echo $seoUrl; ?>">click here</a> to visit the complete thread or to reply to <?php echo $replyOwnerName?>.</p>
<p>If you find it objectionable or inappropriate, please <a href="<?php echo $seoUrl;?>">click here to Report it as abuse</a> and help keep Shiksha.com clean.</p>
<br/>
<p>Best regards,<br />Shiksha.com team</p>