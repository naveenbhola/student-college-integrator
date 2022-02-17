<div class="clg-blok dflt__card" id="<?php echo $section;?>">
       <div>
        <div>
          <table border="0" cellpadding="0" cellspacing="0">
            <?php 

             $wikiObj = $examContent['samplepapers']['wiki'][0];
              if(!empty($wikiObj)){
              $samplepaperswiki = $wikiObj->getEntityValue();
              $wikiLabel = new tidy ();
              $wikiLabel->parseString (htmlspecialchars_decode($samplepaperswiki) , array ('show-body-only' => true ), 'utf8' );
              $wikiLabel->cleanRepair();
              }

            if(!empty($samplePaperData) && count($samplePaperData) > 0){?>
            <tr><td class="hide_border"><h2>Question Papers</h2></td></tr>  
            <?php foreach ($samplePaperData as $key => $val) { ?>
              <tr>
              <td width="100%">
                <span><?=$val['fileName']?></span>
                <a href="<?=$examPageUrl.'/sample-papers';?>?course=<?=$groupId?>&actionType=exam_download_sample_paper&fromwhere=exampage&fileNo=<?=$key+1?>" class="flRt dl-nw" target="_blank">Download Now</a>
                <span class="clear"></span>
              </td>
            </tr>
            <?php }} 

            if(!empty($guidePaperData) && count($guidePaperData) > 0){ ?>
              <tr class="last_tr"><td class="hide_border"><h2>Prep Guides</h2> </td></tr>           
              <?php foreach ($guidePaperData as $key1 => $val1) {?>
              <tr>
                  <td width="100%"><span><?=$val1['fileName']?></span>
                  <a href="<?=$examPageUrl.'/sample-papers';?>?course=<?=$groupId?>&actionType=exam_download_prep_guide&fromwhere=exampage&fileNo=<?=$key1+1?>" class="flRt dl-nw" target="_blank">Download Now</a>
                  <span class="clear"></span>
                </td>
              </tr>
              <?php }} ?>
          </table>
          <?php
            if(!empty($wikiLabel)){ ?>
              <p class="f14__clr3"><?php echo $wikiLabel; ?></p>
            <?php } ?>
        </div>
       </div>
</div>