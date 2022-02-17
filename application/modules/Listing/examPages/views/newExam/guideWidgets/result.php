<?php
$resultWikiObj = $examContent['results']['wiki'][0];

if(!empty($resultWikiObj)){
    $resultWiki = $resultWikiObj->getEntityValue();
    $wikiLabel = new tidy ();
    $wikiLabel->parseString (htmlspecialchars_decode($resultWiki) , array ('show-body-only' => true ), 'utf8' );
    $wikiLabel->cleanRepair();
}
if($isHomePage)
{
  $className = 'exm-dtl';
}
?>
<div class="clg-blok dflt__card">
      <h2><?php echo $sectionNameMapping[$section]?></h2>
      <div class="<?=$className;?>">
            <p class="f16__clr3 fnt__sb"><?php echo $resultData['eventName'].' : '; ?><?php echo $resultData['startDate']; 
                if(!empty($resultData['endDate']) && $resultData['startDate'] != $resultData['endDate'] ) {
                echo " - ";echo $resultData['endDate'];
                } ?>
            </p>
        <?php  if(!empty($wikiLabel)){ ?>
        <p class="f14__clr3"><?php echo $wikiLabel; ?></p>
        <?php } ?>
      </div>
 </div>