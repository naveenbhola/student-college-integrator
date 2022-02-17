		<script>displaySuggestionHeading = false;</script>
		<?php
		if(isset($answerSuggestions[$answerId]) && count($answerSuggestions[$answerId])>0 ){ ?>
			<div class="suggested-course-cont" style="font-size:11px;" id="autoSuggestionsDisplayDiv<?=$answerId?>">
				<strong>The expert recommends following institutes</strong>
				<ul>
				    <?php foreach ($answerSuggestions[$answerId] as $suggestion){ 
					  if(isset($suggestion[1]) && $suggestion[1]!=''){
					?>
					    <script>displaySuggestionHeading = true;</script>
					    <?php $suggestion[2] .= '?cpgs='.$subCatID; ?>
					    <li><div><a class ='lllinks' href='<?=$suggestion[2]?>' target="_blank" onClick="trackEventByGA('LinkClick','SUGGESTED_COLLEGE_CLICKED_LIST'); return true;"><?=$suggestion[1]?></a></div></li>
				    <?php }
					} ?>
				</ul>
			</div>
            <div class="clear_B">&nbsp;</div>
		<?php } ?>
		<script>
		//This check is in case the Institutes are suggested but none of them are valid/live. So, we will hide the Institute suggestion display div
		if(!displaySuggestionHeading){
			if($('autoSuggestionsDisplayDiv<?=$answerId?>')) $('autoSuggestionsDisplayDiv<?=$answerId?>').style.display = 'none';
		}
		</script>
