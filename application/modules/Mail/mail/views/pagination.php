<?php
$from = $pageNo * $noOfMessages +1;
$to = $from + $noOfMessages -1; 
if($to>$totalMails)
	$to =  $totalMails;
if ($totalMails==0)
	$from = 0;
$totalPages= ceil($totalMails/$noOfMessages)-1;
?>

<?php if($to > 0) {?>
<div class="normaltxt_11p_blk_arial float_R">
	<span>Message <?php echo $from." - ".$to." of ".$totalMails; ?></span> 
    <?php if($totalMails > $noOfMessages) { ?>
	<span>
	<?php if ($pageNo!=0): ?>
		<a href="#" onclick="getMailsByPage('<?php echo $type;?>',0);">First</a> <span style="color:#CCCCCC">|</span>
	<?php else: ?>
		First <span style="color:#CCCCCC">|</span>
	<?php endif; ?>
	<?php if ($pageNo!=0): ?> 
		<a href="#" onclick="getMailsByPage('<?php echo $type;?>',<?php echo $pageNo-1;?>);">Previous</a> <span style="color:#CCCCCC">|</span>
	<?php else: ?>
		Previous <span style="color:#CCCCCC">|</span>
	<?php endif; ?> 
	<?php if ($pageNo<$totalPages): ?>
		<a href="#" onclick="getMailsByPage('<?php echo $type;?>',<?php echo $pageNo+1;?>);">Next</a> <span style="color:#CCCCCC">|</span>
	<?php else: ?>
		Next <span style="color:#CCCCCC">|</span>
	<?php endif; ?>
	<?php if ($pageNo<$totalPages): ?>
		<a href="#" onclick="getMailsByPage('<?php echo $type;?>',<?php echo $totalPages;?>);">Last</a>
	<?php else: ?>
		Last
	<?php endif; ?>
	</span>
    <?php }?>
</div>
<?php } ?>
