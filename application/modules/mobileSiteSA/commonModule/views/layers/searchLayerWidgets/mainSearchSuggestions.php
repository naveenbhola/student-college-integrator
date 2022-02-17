<?php 
	switch($class)
	{
		case 'uSug':$dataStr = 'univId="'.$univSuggestion['saAutosuggestUnivId'].'"';
					break;
		case 'cSug':$dataStr = 'data="'.$courseSuggestion.'"';
					break;	
		case 'eSug':$dataStr = 'examId="'.$examNameIdMap[1].'"';
					break;
		default: break;		
	}
?>
<li class="<?php echo $class; ?>" <?php echo $dataStr; ?> >
	<div class="suggstr-box">
			<p class="src-cTitle"><?php echo $title; ?></p>
			<p class="src-lbl"><?php echo $label; ?></p>
			<div class="src-clr"></div>
	</div>
</li>

		