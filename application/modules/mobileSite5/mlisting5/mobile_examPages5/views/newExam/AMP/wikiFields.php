<?php 
  if($isHomePage)
  {
    $className = 'data-height-col';
  }
?>
<section>
            <div class="data-card ">
            <?php 
              if($groupYear)
                $groupYear = ' '.$groupYear;
            if($isHomePage)
            { ?>
                <h2 class="color-3 f16 heading-gap font-w6"><a href ="<?php echo $snippetUrl[$section]; ?>" data-vars-event-name="<?php echo str_replace(' ', '_', strtoupper($sectionName));?>_VIEW_ALL" class="color-3 f16 font-w6 ga-analytic"><?php echo $examName.$groupYear.' '.ucwords($sectionName);?></a></h2>
            <?php }else{
            ?>
              <!--<h1 class="color-3 f16 heading-gap font-w6"><?//=$h1?></h1>-->
            <?php } ?>
                <div class="card-cmn color-w f14 color-3 l-12 ">
                  <?php if(count($groupList)>1 && !$isHomePage){?>
              <p class="f14 color-3 font-w6 change-brdr"><?php echo "Showing details for ". $groupName;?>     
                <a class="font-w4 ga-analytic" data-vars-event-name="CHANGE_COURSE" id="changeCourse" on="tap:change-group" role="button" tabindex="0">Change Course<i class="chnge-brnchico"></i></a>
              </p>
            <?php } ?>
                  <div class="<?=$className;?>">
            <?php foreach ($sectionData as $key => $curObj) { ?>
        <?php 
            $data = $curObj->getEntityValue();
            if($isHomePage)
            {
              $this->htmlSummarizeLogicLib = $this->load->library('examPages/HtmlSummarizeLogicLib');
              $data = $this->htmlSummarizeLogicLib->summarizeData($data);
            }
            ?>
            <div class="m-btm mbtm_sp"><?php echo html_entity_decode($data); ?>    
                      <?php } ?></div>        
                </div>
              <?php if($isHomePage) {?>
                <div class="btn-sec">
                  <a href ="<?php echo $snippetUrl[$section]; ?>" data-vars-event-name="<?php echo str_replace(' ', '_', strtoupper($sectionName));?>_VIEW_ALL" class="btn btn-secondary color-w color-b f14 font-w6 m-15top  ga-analytic">View Details</a>
                </div>
              <?php } ?>
              </div>
            </div>
</section>
<?php if(!$isHomePage){ $this->load->view("mcommon5/socialShareThis",array('pageType'=>'ampPage','className'=>'shadow'));}?>
