<?php
if($groupYear)
  $groupYear = ' '.$groupYear;
?>
<div class="dflt__card mt__15 examTuple ps__rl no__pad" id="<?=$section?>">
  <h2 class="mt__10 f20__clr3"><?php echo str_replace('Summary','Overview',$examName.$groupYear.' '.ucwords($sectionName));?></h2>
  <div class=" f14__clr3 pad__16 setHeight" id="<?=preg_replace('/\\s/','',$sectionName).'WikiId'?>">
    <?php if($sectionName == 'Summary'){ ?>
      <div class="data_change">
          <?php if(count($groupList)>1 && $sectionName == 'Summary'){?>
            <p class="f14__clr3 chngCrs"> Showing details for <strong><?php echo $groupName;?> </strong>            
                <a class="fnt__n" style="cursor:pointer" id="changeCourse">Change Course<i class="chnge-brnchico"></i></a>
            </p>
          <?php } ?>
          </div>
    <?php } 
    

    $wikiLabel = new tidy ();
    $data = addTargetBlankInWikiData($data);
    $wikiLabel->parseString (htmlspecialchars_decode($data) , array ('show-body-only' => true ), 'utf8' );
    $wikiLabel->cleanRepair();?>
    <div class="f14__clr3 mbtm_sp">
      <?php echo $wikiLabel;?>
    </div>

    <?php if($sectionName!='Summary'){ ?>
    <br/>
    <?php } ?>

    <?php if($sectionName == 'Contact Information'){
      $this->load->view('examPages/newExam/contactInfo'); 
    }?>


    <?php if($sectionName!='Summary'){ ?>
    <div class = "gradient-col-exam " style="display:none;" id="<?=preg_replace('/\\s/','',$sectionName).'WikiIdRm'?>">
      <a href="javascript:void(0);" class="viewMoreHP" divToShow="<?=preg_replace('/\\s/','',$sectionName).'WikiId'?>" class="f14__clrb fnt__sb mtop__5 i__block" ga-attr="<?php echo strtoupper($sectionName);?>_READ_MORE">Read More</a>
    </div>
    <?php } ?>

  </div>
  
</div> 

    <?php if($sectionName == 'Summary'){ //After 1st section - LAA and LAA1
      $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'C3','bannerType'=>"content")); 
      $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'C4','bannerType'=>"content")); 
    }
    ?>
