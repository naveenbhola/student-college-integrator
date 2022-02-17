<?php

    $wikiLabelText = $wiki->getLabel();
    
    $wikiDesc = new tidy ();
    $wikiDesc->parseString ($wiki->getDescription(), array ('show-body-only' => true ), 'utf8' );
    $wikiDesc->cleanRepair();
    
    $wikiLabel = new tidy ();
    $wikiLabel->parseString ($wikiLabelText, array ('show-body-only' => true ), 'utf8' );
    $wikiLabel->cleanRepair();

    $wikiDesc = lazyload_content_image(html_entity_decode($wikiDesc));
?>
     <div class="dynamic-content content-tupple <?=$tuppleAdditionalClass ? $tuppleAdditionalClass : ""?>" <?=$sectionId ? "id='".$sectionId."'" : ""?>>
         	<div class="tupple-title"><?=$wikiLabel?></div>
	<p><?=html_entity_decode($wikiDesc)?></p>
     </div>
