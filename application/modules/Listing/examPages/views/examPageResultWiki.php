<?php

    $wikiLabelText = $wiki->getLabel();
    
    $wikiDesc = new tidy ();
    $wikiDesc->parseString ($wiki->getDescription(), array ('show-body-only' => true ), 'utf8' );
    $wikiDesc->cleanRepair();
    
    $wikiLabel = new tidy ();
    $wikiLabel->parseString ($wikiLabelText, array ('show-body-only' => true ), 'utf8' );
    $wikiLabel->cleanRepair();

if(isset($sectionPart) == 'interview'){ ?>
<div class="ExamResult-info <?=$seperatorClass ? $seperatorClass : ""?>">
            <p><?=html_entity_decode($wikiDesc)?></p>
</div>

<?php }else{ ?>

<div class="content-tupple  dynamic-content <?=$tuppleAdditionalClass ? $tuppleAdditionalClass : ""?>" <?=$sectionId ? "id='".$sectionId."'" : ""?>>
        <div class="result-declare-list">
            <i class="exam-result-sprite <?=$examSectionIconMapping[$key]?>"></i>
            <div class="result-info-title">
                <strong><?=($wikiLabel == "Student Reaction"?"Students' Reaction":$wikiLabel)?></strong>
            </div>
            <div class="ExamResult-info">
            <p><?=html_entity_decode($wikiDesc)?></p>
            </div>
        </div>       
 </div> 

 <?php  }?>




     