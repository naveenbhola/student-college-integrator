<?php
if(!empty($rankingPageSources)) {
	?>
	<select class="exam-filter" id="sourceSelection<?=$number?>" onchange="redirectRanking('source','<?=$number?>',event);">
	<?php
	foreach($rankingPageSources as $sourceObject){
		$sourceId = $sourceObject->getId();
		$sourceName = $sourceObject->getName();
		$url = "/" . $current_page_url . "?source_id=" . $sourceId;
		$selected = "";
		if($sourceId == $main_source_id){
			$selected = "selected='selected'";
		}
		?>
		<option <?php echo $selected;?> value="<?php echo $url;?>"><?php echo $sourceName;?></option>
		<?php
	}
	?>
	</select>
	<?php
}
?>