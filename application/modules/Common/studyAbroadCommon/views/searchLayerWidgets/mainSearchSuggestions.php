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
<li class="clearfix <?php echo $class; ?>" <?php echo $dataStr; ?> >
	<!-- <i class="icono-clock"></i> -->
	<span class="getName"><?php echo $title; ?></span>
	<span class="srchType"><?php echo $label; ?></span>
</li>