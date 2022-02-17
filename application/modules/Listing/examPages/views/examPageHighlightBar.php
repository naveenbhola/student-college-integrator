<h1 class="exam-name">
	<?=html_escape($pageTitle)?>
	<?php
	if($pageType != 'home'){
		$sectionNamesMapping = $this->config->item("sectionNamesMapping");
		echo ' - '.$sectionNamesMapping[$pageType];
	}
	?>
</h1>