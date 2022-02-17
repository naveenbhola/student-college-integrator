<?php
    if(isset($examChildren) && is_array($examChildren) && (count($examChildren) > 0) ) {
?>
<div>
	
    <div class="row">
		<div class="bookTestPre">
			<div style="line-height:13px">&nbsp;</div>
			<div class="testPreHeading OrgangeFont fontSize_16p bld" style="line-height:40px; margin-left:76px;padding-left:5px;border-left:none">Related Articles For <?php echo $acronym; ?></div>
		</div>
	</div>
	<div class="lineSpace_10">&nbsp;</div>
	<div>
			<?php 	
				foreach($examChildren as $examChild) {
					$examChildName = $examChild['blogTitle'];
					$examChildUrl = $examChild['url'];
			?>
				<div class="testpreArrow float_L">
					<a href="<?php echo $examChildUrl; ?>" title="<?php echo $examChildName; ?>">
						<?php echo $examChildName; ?>
					</a>
				</div>
			<?php
				}
			?>
			<div class="clear_L"></div>
	</div>
</div>
<?php
    }
?> 
