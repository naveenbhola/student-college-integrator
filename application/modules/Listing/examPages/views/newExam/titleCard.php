<div class="dflt__card global-box-shadow">
    <div class="title__card clear__space">
      <?php 
      $sectionHeading = '';
      $headingTag = 'h1';
/*      if(!$isHomePage) {
          $headingTag = 'p';
        ?>
       <<?=$headingTag;?> class="Lft f20__clr3__sb">

	<?php if($groupYear){
        	$groupYear = ' '.$groupYear;
	       }
	      if($examFullName){ 
			echo "$examName - $examFullName$groupYear";
		}
		else{
		        echo $examName.$groupYear;
        }?>

	</<?=$headingTag;?>>
	<?php } else { 

if($sectionName == 'Summary'){
     // echo '<div class="updatedOn">'.$updatedOn[$section].'</div>';
    }
  */
    if(empty($activeSectionName)){
          $activeSectionName = 'homepage';
    }
    $section = $activeSectionName;
    if(empty($activeSectionName)){
      $section = 'homepage';
    }
    if($anaWidget['totalNumber'] == 1){
      $displayString = "1 Anwered Question";
    }
    else if($anaWidget['totalNumber'] >= 2){
      $displayNumber = formatNumber($anaWidget['totalNumber']);
      $displayString = $displayNumber." Answered Questions";
    }
    ?>


	<h1 class="Lft f20__clr3__sb"><?=$h1?></h1>
	<?php //}
        if (!empty($upcomingDateInformation['displayLabel'])){
    ?>
            <div class="Rft f12__clr9 dot__div no-dot" id="eApplybx">
                <div class="admit-sctn">
                    <p>
                        <i class="admit-ico"></i>
                        <strong><?=$upcomingDateInformation['displayLabel']?>:</strong> <?=$upcomingDateInformation['displayDate']?>
                    </p>
                </div>
            </div>
    <?php   }?>
   <?php if(!empty($updatedOn[$section])){ ?>
       <div>
          <p class="rcnt_updte"><?php echo $updatedOn[$section];?></p>
      </div>
    <?php } ?>
    </div>
    <div class="below__card mtop__16 clear__space">
    <?php if($anaWidget['totalNumber'] > 0){ ?>
      <div class="multi__div">
         <div class="discuss-block">
            <a id="discusn"><i class="discusn-ico"></i><?php echo $displayString;?></a>
        </div>
      </div> 
      <?php } ?>
          <!--<div class="multi__div">-->
          <?php /*if(count($groupList)>1){?>
            <p class="f11__clr6">Conducted for <strong class="fnt__sb"><?php echo count($groupList)?> courses</strong>. Showing details for </p>
          <?php }?>  
          <?php if(count($groupList)>1){?>
            <p class="f14__clr3 chngCrs"><?php echo $groupName;?>             
                <a class="fnt__n" style="cursor:pointer" id="changeCourse">Change Course</a>
            </p>
          <?php } */?>
          <!--</div>-->
          <?php
           /*if($conductedBy['name'] || $conductedBy){?>
          <div class="multi__div">
            <p class="f11__clr6">Conducted by <span class="f14__clr3 con-ex">
            <?php if(is_array($conductedBy)){?>
              <a href="<?php echo $conductedBy['url'];?>" ga-attr="CONDUCTED_BY"><?php echo $conductedBy['name'];?></a>
             <?php }else{ echo htmlentities($conductedBy);}?></span> </p>
         </div>
         <?php } */?>
         <div class="btns__col Rft" id="CTASection">
          <?php if(array_key_exists('samplepapers', $snippetUrl)){?>
              <a class="blue__brdr__btn dwn-esmpr dwn-smp-top" data-trackingKey="<?php echo $trackingKeyList['download_sample_paper'];?>" title="Download previous question papers to read offline" ga-attr="DOWNLOAD_SAMPLE_PAPERS_BUTTON">Get Question Papers</a>
          <?php }?>
           <a class="prime__btn mlt__5 dwn-guide-top dwn-eguide <?php if(isset($guideDownloaded) && $guideDownloaded){?> disable-btn <?php }?>" data-trackingKey="<?php echo $trackingKeyList['download_guide'];?>" title="Download exam information to read offline" ga-attr="DOWNLOAD_GUIDE">Get Updates</a>
         </div>
    </div>
</div>
