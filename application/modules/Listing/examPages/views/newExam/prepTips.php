<?php
        $priWikiObj = $examContent['preptips']['wiki'][0];
        $wikiObj = $examContent['preptips']['wiki'][1];
?>

<div class="dflt__card mt__15 examTuple no__pad" id="<?php echo $section;?>">
                    <?php

                        //Display Heading of the Section
                       if($groupYear)
                        $groupYear = ' '.$groupYear;

                       if($isHomePage)
                       {
                        ?>
                        <h2 class="mt__10 f20__clr3"><a href ="<?php echo $snippetUrl['preptips']; ?>" style="color: inherit;" ga-attr="<?php echo str_replace(' ', '_', strtoupper('Prep Tips'))?>_VIEW_ALL"><?php echo $examName.$groupYear.' Preparation Tips & Guide';?></a></h2>
                    <?php }else{
                      ?>
<!--                        <h1 class="mt__10 f20__clr3"><?//=$h1?></h1>-->
                    <?php } ?>

                     <div class="sample__papers clear__space pad__16">

                        <!-- Display Updated On Section -->
                       <div class="data_change">
    <?php if(count($groupList)>1 && !$isHomePage){?>
      <p class="f14__clr3 chngCrs"> Showing details for <strong><?php echo $groupName;?> </strong>            
          <a class="fnt__n" style="cursor:pointer" id="changeCourse">Change Course<i class="chnge-brnchico"></i></a>
      </p>
    <?php } ?>
    </div>

                        <!-- Display First Wiki -->
                          <?php
                            if(!empty($priWikiObj)){
                            $ptWiki1 = $priWikiObj->getEntityValue();
                            $ptwikiLabel = new tidy ();
                            $samplepaperswiki = addTargetBlankInWikiData($ptWiki1);
                            $ptwikiLabel->parseString (htmlspecialchars_decode($samplepaperswiki) , array ('show-body-only' => true ), 'utf8' );
                            $ptwikiLabel->cleanRepair();
                            }

			    $doNotShowFiles = false;
                            if(!empty($ptwikiLabel)){
                                if($isHomePage){
                                        $doNotShowFiles = true;
                                }
                          ?>
                              <p class="sample-txt"><?=$ptwikiLabel?></p>
                          <?php } ?>

                      <div class="<?=$className;?>" id="<?php echo $section.'det';?>">
                          <?php
                          //Display the Files
                          if(!empty($preptipsData) && count($preptipsData) > 0 && !$doNotShowFiles){?>
                          <h2 class="f14 inner-h2">Download <?php echo $examName.' '.'Prep Tips';?></h2> <ul class="sm__paper clear__space">

                          <?php
                          foreach ($preptipsData as $key => $val) {?>
                          <li>
                            <a class="dflt__card i__block dwn-page-esmprpt" ga-attr="DOWNLOAD_PREP_TIPS" data-trackingKey="<?php echo $trackingKeyList['download_prep_tips_page'];?>" data-url="<?=$val['url']?>" data-key="<?=$i?>">
                            <div class="ln-ht sm__div <?php echo getFileType($val['url']);?>">
                              <p class="f14__clr3"><?=$val['fileName']?>
                            </div>
                            <i class="dwn-bg"><span>DOWNLOAD</span></i>
                            </a>
                          </li>
                        <?php }?>
                        </ul>
                        <?php }

                            //Display Second Wiki
                            if(!empty($wikiObj)){
                            $samplepaperswiki = $wikiObj->getEntityValue();
                            $wikiLabel = new tidy ();
                            $samplepaperswiki = addTargetBlankInWikiData($samplepaperswiki);
                            $wikiLabel->parseString (htmlspecialchars_decode($samplepaperswiki) , array ('show-body-only' => true ), 'utf8' );
                            $wikiLabel->cleanRepair();
                            }

                          if(!empty($wikiLabel) && !$isHomePage){ ?>
                            <div class="f14__clr3 mbtm_sp"><?php echo $wikiLabel; ?></div>
                          <?php } ?>
                      </div>
                     <?php if($isHomePage) { ?>
                          <div class="pd__top__10 txt__cntr mtop__10" id="<?php echo $section.'detRm';?>">
                                <a href ="<?php echo $snippetUrl['preptips']; ?>" class="blue__brdr__btn arrow_after" ga-attr="<?php echo str_replace(' ', '_', strtoupper('Prep Tips'))?>_VIEW_ALL">View All</a>
                          </div>
                      <?php } ?>
                     </div>
                   </div>

