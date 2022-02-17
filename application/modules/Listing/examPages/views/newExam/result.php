<?php
$resultWikiObj = $examContent['results']['wiki'][0];

if(!empty($resultWikiObj)){
    $resultWiki = $resultWikiObj->getEntityValue();
    if($isHomePage)
    {
      $this->htmlSummarizeLogicLib = $this->load->library('examPages/HtmlSummarizeLogicLib');
      $resultWiki = $this->htmlSummarizeLogicLib->summarizeData($resultWiki);
    }
    $wikiLabel = new tidy ();
    $resultWiki = addTargetBlankInWikiData($resultWiki);
    $wikiLabel->parseString (htmlspecialchars_decode($resultWiki) , array ('show-body-only' => true ), 'utf8' );
    $wikiLabel->cleanRepair();
}
if($isHomePage)
{
  $className = 'setHeight';
}
?>
<div class="dflt__card mt__15 examTuple no__pad" id="<?php echo $section;?>">
       <?php 
         if($groupYear)
            $groupYear = ' '.$groupYear;
         if($isHomePage)
         { ?>
          <h2 class="mt__10 f20__clr3"><a href ="<?php echo $snippetUrl['results']; ?>" style="color:inherit;" ga-attr="<?php echo str_replace(' ', '_', strtoupper('Results'))?>_VIEW_DETAILS"><?php echo $examName.$groupYear.' '.'Results'?></a></h2>
      <?php }else{?>
<!--          <h1 class="mt__10 f20__clr3"><?//=$h1?></h1>-->
      <?php } ?>
      <div class="<?=$className;?> f14__clr3 pad__16 " id="<?php echo $section.'det';?>">
        <div class="data_change">
    <?php if(count($groupList)>1 && !$isHomePage){?>
      <p class="f14__clr3 chngCrs"> Showing details for <strong><?php echo $groupName;?> </strong>            
          <a class="fnt__n" style="cursor:pointer" id="changeCourse">Change Course<i class="chnge-brnchico"></i></a>
      </p>
    <?php } ?>
    </div>
        <?php if(!empty($resultData['startDate'])){?>
            <p class="f16__clr3 fnt__sb m-5btm"><?php echo $resultData['eventName'].' : '; ?><?php echo $resultData['startDate']; 
                if(!empty($resultData['endDate']) && $resultData['startDate'] != $resultData['endDate'] ) {
                echo " - ";echo $resultData['endDate'];
                } ?>
            </p>
        <?php } if(!empty($wikiLabel)){ ?>
        <div class="f14__clr3 mbtm_sp"><?php echo $wikiLabel; ?></div>
        <?php } ?>
      </div>
      <?php if($isHomePage) { ?>
        <div class="pd__top__10 txt__cntr mtop__10 pad__16" id="<?php echo $section.'detRm';?>">
              <a href ="<?php echo $snippetUrl['results']; ?>" class="blue__brdr__btn arrow_after" ga-attr="<?php echo str_replace(' ', '_', strtoupper('Results'))?>_VIEW_DETAILS">View Details</a>
        </div>
      <?php } ?>
 </div>
