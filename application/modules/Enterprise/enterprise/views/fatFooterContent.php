<?php
	for($i=1;$i<=5;$i++){
?>
<div class="fat-footer-section">
	<h4>Column <?=$i?></h4>
	<div class="child-section">
		<label class="localityPref<?=$i?>">Show locality coloumn:</label>
		<div class="fat-fields-cont localityPref<?=$i?>" onclick="setColumnAsLocality(<?=$i?>);">
			<input type="radio" name="localityPref<?=$i?>" value="Yes" /> Yes &nbsp;&nbsp;&nbsp;
			<input type="radio" name="localityPref<?=$i?>" value="No" /> No 
		</div>
		
		<div class="spacer10 clearFix"></div>
		
		<label class="replaceCity<?=$i?>">Replace City/State:</label>
		<div class="fat-fields-cont">
			<ul id="fieldContainer<?=$i?>">
				<li class="replaceCity<?=$i?>">
					<input type="radio" name="replacecity<?=$i?>" value="Yes" /> Yes &nbsp;&nbsp;&nbsp;
					<input type="radio" name="replacecity<?=$i?>" value="No" /> No 
				</li>
				
				<li id="add-field<?=$i?>"><strong class="add-field"><a href="#" onclick="addField(<?=$i?>); return false;">+ Add Field</a></strong></li>
				<li id="localityAnchor<?=$i?>" style="display:none">
					<div class="column-fields">
						<label>Anchor Text(use "location"): </label>
						<input type="text" class="universal-txt-field" value="" id="anchor-text-<?=$i?>" />
					</div>
				</li>
			</ul>
		</div>
	</div>
</div>
<?php
	}
?>
		<input type="button" value="Save Footer" class="orange-button" onclick="saveFooter();" />
		
<script>
footerFields = <?=$fatFooter?>;
</script>