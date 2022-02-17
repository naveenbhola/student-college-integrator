<div class="normaltxt_11p_blk_arial float_R">
	<span>
		<?php if ($page['next']!= ""): ?>
		<a href="#" onclick="showMail('<?php echo $type; ?>',<?php echo $page['next'];?>);">Previous</a> <span style="color:#CCCCCC">|</span>
		<?php else: ?>
		Previous <span style="color:#CCCCCC">|</span>
		<?php endif; ?>
		<?php if ($page['previous']!= ""): ?>
		<a href="#" onclick="showMail('<?php echo $type; ?>',<?php echo $page['previous'];?>);">Next</a> <span style="color:#CCCCCC">|</span>
		<?php else: ?>
		Next <span style="color:#CCCCCC">|</span>
		<?php endif; ?>		
		<a href="#" onclick="getMails(document.getElementById('<?php echo $type."_link"; ?>'));">Back to Message</a>
	</span>
</div>