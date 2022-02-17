<?php $wikiObj = $examContent['samplepapers']['wiki'][0]; ?>
<div class="dflt__card mt__15 examTuple no__pad" id="<?php echo $section;?>">
                    <?php 
                       if($groupYear)
                        $groupYear = ' '.$groupYear;
		
                       if($isHomePage)
                       {
                        ?>
                        <h2 class="mt__10 f20__clr3"><a href ="<?php echo $snippetUrl['samplepapers']; ?>" style="color: inherit;" ga-attr="<?php echo str_replace(' ', '_', strtoupper('Sample Paper'))?>_VIEW_ALL"><?php echo $examName.$groupYear.' '.$samplePaperHeading;?></a></h2>
                    <?php }else{
                      ?>
                        <!--<h1 class="mt__10 f20__clr3"><?//=$h1?></h1>-->
                    <?php } ?>
                     <div class="sample__papers clear__space pad__16">
                      <div class="data_change">
    <?php if(count($groupList)>1 && !$isHomePage){?>
      <p class="f14__clr3 chngCrs"> Showing details for <strong><?php echo $groupName;?> </strong>            
          <a class="fnt__n" style="cursor:pointer" id="changeCourse">Change Course<i class="chnge-brnchico"></i></a>
      </p>
    <?php } ?>
    </div>
                      <p class="sample-txt">Download previous year <?php echo $examName;?> question papers and sample papers. Use them for practice and improve your speed and accuracy. You can download <?php echo $examName;?> previous year question papers and the preparation guides for free.</p>

<?php if($examId == '6244'){ ?>
<table>
<tbody>
<tr>
<td>
<h3><strong><a href="https://www.shiksha.com/b-tech/resources/jee-main-rank-predictor" target="_blank">Predict your JEE Main 2018 ranks with Shiksha's
newly launched JEE Main rank predictor tool</a></strong></h3>
</td>
</tr>
</tbody>
</table>
<?php } ?>

                      <div class="<?=$className;?>" id="<?php echo $section.'det';?>">
                          <?php 
                            if(!empty($wikiObj)){
                            $samplepaperswiki = $wikiObj->getEntityValue();
                            $wikiLabel = new tidy ();
                            $samplepaperswiki = addTargetBlankInWikiData($samplepaperswiki);
                            $wikiLabel->parseString (htmlspecialchars_decode($samplepaperswiki) , array ('show-body-only' => true ), 'utf8' );
                            $wikiLabel->cleanRepair();
                            }
                          if(!empty($samplePaperData) && count($samplePaperData) > 0){?>
                          <h2 class="f14 inner-h2">Download <?php echo $examName.' '.'Question Papers';?></h2> <ul class="sm__paper clear__space">                  

                          <?php 
                          foreach ($samplePaperData as $key => $val) {?>
                          <li>
                            <a class="dflt__card i__block dwn-page-esmpr" ga-attr="DOWNLOAD_SAMPLE_PAPER" data-trackingKey="<?php echo $trackingKeyList['download_sample_paper_page'];?>" data-url="<?=$val['url']?>" data-key="<?=$i?>">
                            <div class="ln-ht sm__div <?php echo getFileType($val['url']);?>">
                              <p class="f14__clr3"><?=$val['fileName']?>
                            </div>
                            <i class="dwn-bg"><span>DOWNLOAD</span></i>
                            </a>
                          </li>
                        <?php }?>
                        </ul>
                        <?php } 
                          if(!empty($guidePaperData) && count($guidePaperData) > 0){ ?>
                            <h2 class="f14 inner-h2">Download <?php echo $examName.' '.'Prep Guides';?></h2>   <ul class="sm__paper clear__space">                    
                            <?php  
                            foreach ($guidePaperData as $key1 => $val1) {?>
                            <li>
                              <a class="dflt__card i__block dwn-esmpr-eguide" ga-attr="DOWNLOAD_PREP_GUIDE" data-trackingKey="<?php echo $trackingKeyList['download_prep_guide'];?>" data-url="<?=$val1['url']?>" data-key="<?=$i?>">
                              <div class="ln-ht sm__div <?php echo getFileType($val1['url']);?>">
                                <p class="f14__clr3"><?=$val1['fileName']?>
                              </div>
                              <i class="dwn-bg"><span>DOWNLOAD</span></i>
                              </a>
                            </li>
                          <?php } ?> </ul>
                          <?php } ?>
                        <?php
                          if(!empty($wikiLabel) && !$isHomePage){ ?>
                            <div class="f14__clr3 mbtm_sp"><?php echo $wikiLabel; ?></div>
                          <?php } ?>
                      </div>
                     <?php if($isHomePage) { ?>
                          <div class="pd__top__10 txt__cntr mtop__10" id="<?php echo $section.'detRm';?>">
                                <a href ="<?php echo $snippetUrl['samplepapers']; ?>" class="blue__brdr__btn arrow_after" ga-attr="<?php echo str_replace(' ', '_', strtoupper('Sample Paper'))?>_VIEW_ALL">View All</a>
                          </div>
                      <?php } ?>
                     </div>
                   </div>
