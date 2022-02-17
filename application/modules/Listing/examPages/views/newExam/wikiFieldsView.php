 <?php 
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
			<h2 class="mt__10 f20__clr3"><a href ="<?php echo $snippetUrl[$section]; ?>" style="color: inherit;" ga-attr="<?php echo str_replace(' ', '_', strtoupper($sectionName));?>_VIEW_ALL"><?php echo $examName.$groupYear.' '.ucwords($sectionName)?></a></h2>
	<?php }else{
			?>
			<!--<h1 class="mt__10 f20__clr3"><?//=$h1?></h1>-->
	<?php } ?>
	
	<div class="<?=$className;?> f14__clr3 pad__16 " id="<?php echo $section.'det';?>">
		<div class="data_change">
    <?php if(count($groupList)>1 && !$isHomePage){?>
      <p class="f14__clr3 chngCrs"> Showing details for <strong><?php echo $groupName;?> </strong>            
          <a class="fnt__n" style="cursor:pointer" id="changeCourse">Change Course<i class="chnge-brnchico"></i></a>
      </p>
    <?php } ?>
    </div>
	<?php foreach ($sectionData as $key => $curObj) {
		    if(is_object($curObj) && !empty($curObj)){
		    	$data = $curObj->getEntityValue();
		    	if($isHomePage)
		    	{
				    $this->htmlSummarizeLogicLib = $this->load->library('examPages/HtmlSummarizeLogicLib');
		          	$data = $this->htmlSummarizeLogicLib->summarizeData($data);
          		}	
		    }
		    $data = addTargetBlankInWikiData($data);
		?>
	<div class="f14__clr3 mbtm_sp"><?php echo html_entity_decode($data); ?></div>
	<?php } ?>	
	</div>
	<?php if($isHomePage) { ?>
		<div class="pd__top__10 txt__cntr mtop__10" id="<?php echo $section.'detRm';?>">
		   <a href ="<?php echo $snippetUrl[$section]; ?>" class="blue__brdr__btn arrow_after" ga-attr="<?php echo str_replace(' ', '_', strtoupper($sectionName));?>_VIEW_ALL">View Details</a>
		</div>
	<?php } ?>
	
</div>
