<?php
	$count = 0;
	foreach($articleWidgetsData as $val) {
		if(!empty($val['artcileTitle']) && $count < 3){
?>
			<li style="width:273px;">
				<a href="<?php echo $val['url'];?>" title="<?=$val['artcileTitle'];?>" style="font-size:14px;">
					<span>
						<?php echo sanitizeArticleTitle($val['artcileTitle'], 52); ?>
					</span>
				</a>
			</li>
<?php
		}
		$count++;
	}
?>
