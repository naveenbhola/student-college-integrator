<?php
    if(isset($examChildren) && is_array($examChildren) && (count($examChildren) > 0) ) {
?>
<div align="justify" class="lineSpace_16" style="padding:0 5px">
    <div class="fontSize_12p mar_bottom_10p">
        <div class="OrgangeFont bld fontSize_14p">Further Readings</div>
	    <div class="lineSpace_10">&nbsp;</div>
    	<ul class="FurtherReading">
			<?php 	
				foreach($examChildren as $examChild) {
					$examChildName = $examChild['blogTitle'];
					$examChildUrl = $examChild['url'];
			?>
	        <li>
				<a href="<?php echo $examChildUrl; ?>" title="<?php echo $examChildName; ?>" class="fontSize_12p"><?php echo $examChildName; ?></a>
            </li>
			<?php
				}
			?>
		</ul>
	</div>
</div>
<?php }
?>
