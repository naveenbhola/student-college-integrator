<?php
 $sampleTrackingKeyId = $trackingKeys['download_sample_paper_page'];
 $prepGuideTrackingId = $trackingKeys['download_prep_guide'];
 $wikiObj = $examContent['samplepapers']['wiki'][0];
 if($isHomePage)
 {

    $sampleTrackingKyId = $trackingKeys['download_sample_paper'];
 }
?>
 <section>
            <div class="data-card wdg-card m15">
                <?php
                if($groupYear)
                    $groupYear = ' '.$groupYear;
                if($isHomePage)
                { ?>
                <h2 class="color-3 f16 heading-gap font-w6"><a href ="<?php echo $snippetUrl[$section]; ?>" class="color-3" ga-attr="SAMPLE_PAPER_VIEW_ALL"><?php echo $examName.$groupYear.' '.$samplePaperHeading?></a></h2>
                <?php }else{
                    ?>
                    <!--<h1 class="color-3 f16 heading-gap font-w6"><?//=$h1?></h1>-->
                <?php } ?>
                <div class="lcard color-w f14 color-3">
                    <div class="<?=$className;?>" id="<?php echo $section.'det';?>">
                <?php if(count($groupList)>1 && !$isHomePage){?>
                  <p class="f14 color-3 change-brdr">Showing details for <strong class="font-w6"><?php echo $groupName;?> </strong>   
                  <a href="javascript:void(0);" class="font-w4" id="changeCourse" ga-attr="CHANGE_COURSE">Change Course<i class="chnge-brnchico"></i></a>
                  </p>
                <?php }?> 
                         <p class="sample-txt">Download previous year <?php echo $examName?> question papers and sample papers. Use them for practice and improve your speed and accuracy. You can download <?php echo $examName?> previous year question papers and the preparation guides for free.</p>

<?php if($examId == '6244'){ ?>
<table style="margin-bottom:10px;">
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

                    <table class="cmn-lst">
                        <?php
                        if(!empty($wikiObj)){
                        $samplepaperswiki = $wikiObj->getEntityValue();
                        }
                        if(!empty($samplePaperData) && count($samplePaperData) > 0){ ?>
                        <tr>
                          <th>
                            <h2 class="f14 inner-h2">Download <?php echo $examName.' '.'Question Papers';?></h2>
                          </th>

                        </tr>
                        
                         <?php
                            foreach ($samplePaperData as $key => $val) {?>
                            <tr>
                            <td data-link="<?=$val['url'];?>" class="sampledownload" data-tracking="<?=$sampleTrackingKeyId;?>" ga-attr="DOWNLOAD_SAMPLE_PAPER" data-no = "<?='sampledownload_'.($key+1)?>">
                              <div class="table_cl">
                                <p class="pos-rl <?php echo getFileType($val['url']);?>"><?=$val['fileName']?>
                                </p>
                                <i class="dwn-bg"><span>DOWNLOAD</span></i>
                              </div>
                           </td>
                         </tr>
                        <?php }}
                        if(!empty($guidePaperData) && count($guidePaperData) > 0){ ?>
                          <tr>
                            <th>
                              <h2 class="f14 inner-h2">Download <?php echo $examName.' '.'Prep Guides';?></h2>
                            </th>
                          </tr>

                          <?php
                            foreach ($guidePaperData as $key1 => $val1) {?>
                           <tr>
                            <td data-link="<?=$val1['url'];?>" class="prepguidedownload" data-tracking="<?=$prepGuideTrackingId;?>" ga-attr="DOWNLOAD_PREP_GUIDE" data-no = "<?='guidedownload_'.($key1+1)?>">
                                <div class="table_cl">
                                  <p class="pos-rl <?php echo getFileType($val1['url']);?>"><?=$val1['fileName']?>
                                  </p>
                                  <i class="dwn-bg"><span>DOWNLOAD</span></i>
                                </div>

                            </td>
                          </tr>
                        <?php }} ?>

                    </table>
                    <?php
                        if(!empty($samplepaperswiki) && !$isHomePage){ ?>
                        <div class="f14__clr3 mbtm_sp"><?php echo html_entity_decode($samplepaperswiki); ?></div>
                    <?php } ?>
                </div>
                <?php if($isHomePage) {
                    ?>
                    <div class="btn-sec" id="<?php echo $section.'detRm';?>">
                        <a href ="<?php echo $snippetUrl[$section]; ?>" class="btn btn-secondary color-w color-b f14 font-w6 m-15top m-5btm" ga-attr="SAMPLE_PAPER_VIEW_ALL">View All</a>
                    </div>
                <?php } ?>
               </div>
              </div>
          </section>
<?php if(!$isHomePage){
    $this->load->view("mcommon5/socialShareThis",array('className'=>'shadow')); 
}?>

