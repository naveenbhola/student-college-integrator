<?php
$quot = ($count / 10);
$remCount = $count%10;
if($remCount!=0){
	$quotTempCount = ($count - $remCount)+10;
	$quotTemp      = ($quotTempCount / 10);
	$rem      	   = $quotTemp%3;
}else{
	$rem = $quot % 3;	
}


$formName         = 'rankingCategorySearch'.$count;

if($rankingPageOf['FullTimeMba']==1){
	$collegeTitleMsg1 = "Find the best college for your score.";
	$collegeTitleMsg2 = "<span>Know your score?</span> <br> Find the top college that's right for you.";
	$collegeTitleMsg3 = "Don't see a top college here for your score? Find them here.";
}else{
	$collegeTitleMsg1 = "Find the best college suited for you:";
	$collegeTitleMsg2 = "Find the top college that's right for you:";
	$collegeTitleMsg3 = "Don't see a top college acceptable for you ? Find them here.";
}



?>
<section class="content-wrap2 clearfix">
	<article class="score-form-box">
		<?php
		if($rem == 0){
		?>
			<strong class="form-title"><?=$collegeTitleMsg3?></strong>
		<?php
		} else if($rem == 1){
		?>
			<strong class="form-title"><?=$collegeTitleMsg1?></strong>
		<?php
		} else if($rem == 2){
		?>
			<strong class="form-title"><?=$collegeTitleMsg2?></strong>
		<?php
		} else {
			?>
			<strong class="form-title"><?=$collegeTitleMsg2?></strong>
			<?php
		}
		?>
		<form id ="form_<?=$formName?>" name="<?=$formName?>" action=""  method="POST" enctype="multipart/form-data">

    	<ul class="form-items">
    		<?php if(count($filters['exam'])>1){ ?>
	        	<li>
	            	<div class="">
	                	<select id="catPageExam_<?=$count?>" class="examList_<?=$formName?>">
							<option value="">Select Exam</option>
							<?php
							$examFilters = $filters['exam'];
							foreach($examFilters as $filter) {
								$title 		= $filter->getName();
								$id 		= $filter->getId();
								if($title !='All'){
							?>
								<option value="<?php echo $id;?>"><?php echo $title;?></option>
							<?php
								}
							}
							?>
						</select>
		           		<div id="rp_cat_widget_examdd_error" class="errorMsg" style="display:none;"></div>

	                </div>
	                
	                
	                <div class="cols flRt" style="display:none;">
						<input id="catPageExamScore_<?=$count?>" type="text" class="cutOffList_<?=$formName?>" placeholder="Enter Rank/Score" >
	                </div>
	                 
	                
	            </li>
            <?php } ?>
            <li style="margin-bottom:0">
            	<a href="JavaScript:void(0);" onclick ="sendToCategoryPage(this);" count="<?=$count?>" class="go-btn">GO</a>
                <div style="margin-right:45px;">
                	<select id="catPageLocation_<?=$count?>" class="locationList_<?=$formName?>">
						<option value="none">Select Location</option>
						<?php
						$cityFilters = $filters['city'];
						if(empty($cityFilters)){
							$cityFilters = array();
						}
						$stateFilters = $filters['state'];
						if(empty($stateFilters)){
							$stateFilters = array();
						}
						foreach($cityFilters as $filter) {
							$title 		= $filter->getName();
							$id = $filter->getId();
						?>
							<option value="<?php echo $id;?>-city"><?php echo $title;?></option>
						<?php
						}
						foreach($stateFilters as $filter) {
							$title 		= $filter->getName();
							$id 		= $filter->getId();
							if( in_array($id, array(128, 129, 130, 131, 134, 135, 345))){
								continue;
							}
						?>
							<option value="<?php echo $id;?>-state"><?php echo $title;?></option>
						<?php
						}
						?>
					</select>
                </div>
            </li>
        </ul>
        <input class="categoryId_<?=$formName?>" name="categoryId" value="<?=$categoryId?>" type="hidden">
			<input class="subCategoryId_<?=$formName?>" name="subCategoryId" value="<?=$subCategoryId?>" type="hidden">
			<input class="LDBCourseId_<?=$formName?>" name="LDBCourseId" value="<?=$specializationId?>" type="hidden">
        </form>
    <div class="clearfix"></div>
    </article>
</section>
