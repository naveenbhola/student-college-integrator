<div class="lcard clg-panel">
<?php echo $examBreadCrumb; ?>
    <div class="ex-blk">
    <?php $headingTag = 'h1';?>
      <h1 class="color-3 f16 font-w6 l-14"><?=$h1?></h1>
      <?php //} ?>
      <?php /*if(count($groupList)>1){?>
      <span class="f12 color-3">Conducted for <strong><?php echo count($groupList)?> courses</strong>. Showing details for</span>
      <?php }?>
      <?php if(count($groupList)>1){?>
        <p class="f14 color-3"><?php echo $groupName;?>
          <a href="javascript:void(0);" class="font-w4" id="changeCourse" ga-attr="CHANGE_COURSE">Change Course</a>
        </p>
      <?php }*/?>
      <?php
      $section = $activeSectionName;
      if(empty($activeSectionName)){
        $section = 'homepage';
      }

      if($anaWidget['totalNumber'] == 1){
                $displayString = "1 Answered Question";
        }
        else if($anaWidget['totalNumber'] >= 2){
                $displayNumber = formatNumber($anaWidget['totalNumber']);
                $displayString = $displayNumber." Answered Questions";
        }
      ?>
      <?php if(!empty($updatedOn[$section])){ ?>
      <div class="updatedOn"><?php echo $updatedOn[$section];?></div>
      <?php } ?>
     <?php if($anaWidget['totalNumber'] > 0){ ?>
       <div class="discuss-block">
           <a id="discusn"><i class="discusn-ico"></i><?php echo $displayString;?></a>
       </div>
     <?php } ?>
        <?php   if (!empty($upcomingDateInformation['displayLabel'])){?>
                    <div class="admit-sctn">
                        <p>
                            <i class="admit-ico"></i><strong><?=$upcomingDateInformation['displayLabel']?>:</strong> <?=$upcomingDateInformation['displayDate']?>
                        </p>
                    </div>
        <?php   }?>
      <?php /*if($conductedBy['name'] || $conductedBy){?>
        <p class="f12 m-top color-6 m-btm">Conducted by <span class="f14 color-3">
        <?php if(is_array($conductedBy)){?><a class="block f14" href="<?php echo $conductedBy['url'];?>" ga-attr="CONDUCTED_BY"><?php echo $conductedBy['name'];?></a><?php }else{ echo htmlentities($conductedBy);}?></span></p>
      <?php } */?>
     <div class="flex-mob">
      <div class="btn-sec shrt-btn">
        <a href="javascript:void(0);" class="btn btn-primary color-o color-f f14 font-w7 m-15top <?php echo $guideDownloaded ? 'btn-mob-dis' : '';?>" id="download_guide" data-tracking="<?=$trackingKeys['download_guide'];?>" ga-attr="DOWNLOAD_GUIDE"> <?php echo $guideDownloaded ? 'Guide Sent' : 'Get Updates';?></a>
      </div>

      <?php if(array_key_exists('samplepapers', $snippetUrl)) { ?>
        <div class="btn-sec long-btn">
          <a class="btn btn-secondary color-w color-b f14 font-w6 m-15top m-5btm" id="download_papers" data-tracking="<?=$trackingKeys['download_sample_paper'];?>" ga-attr="DOWNLOAD_SAMPLE_PAPERS_BUTTON" href="javascript:void(0);">Get Question Papers</a>
        </div>
      <?php } ?>
     </div>
      <div id="applynow-box" data-tracking="<?=$trackingKeys['apply_online'];?>"></div>
  </div>
</div>
