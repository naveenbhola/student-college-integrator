<?php
$examFilters 	= $filters['exam'];

$totalExamFilters = count($examFilters);
$defaultExamSelected = false;
foreach($examFilters as $filter){
	if($filter->isSelected() == true){
		$defaultExamSelected = true;
	}
}

$showExamFilters = true;
$filterBlockStyle = "width:97%;";
global $widthPercent;
if($widthPercent['course']) {
	$widthStyle = $widthPercent['course']/2 + 24;
	$widthPercent['course'] = $widthPercent['course']/2;
}
if($totalExamFilters <= 1 && $defaultExamSelected){
	$showExamFilters = false;
	$filterBlockStyle = "width:62%;";
	$widthPercent['exam'] = 24;
}
else if($widthPercent['course']) {
	$widthStyle = $widthPercent['course']/2 + 24;
	$widthPercent['course'] = $widthPercent['course']/2;
}

    $mobile_details = json_decode($_COOKIE['ci_mobile_capbilities'],true);
    $screenWidth = $mobile_details['resolution_width'];
    $width = round(($screenWidth - 30)/3) - 20;
?>

		<?php
		$examBlockShown = false;
		if(!empty($examFilters) && count($examFilters) > 1 && !($rankingPageRequest->isUGCategory())){
			$examBlockShown = true;
			$totalExamFilters = count($examFilters);
			$examFiltersLeft 	= $examFilters;
			$examFiltersRight 	= array();
			if($totalExamFilters > 6){
				$examFiltersLeft 		= array_slice($examFilters, 0, (int)$totalExamFilters / 2);
				$examFilterLeftCount 	= count($examFiltersLeft);
				$examFiltersRight 		= array_slice($examFilters, $examFilterLeftCount, $totalExamFilters);
			}
		?>

		<select class="exam-filter" id="examSelection<?=$number?>" onchange="redirectRanking('exam','<?=$number?>',event);" style="width:<?=$widthStyle?>%;">
			<option value=''>Exam</option>
				<?php
				foreach($examFiltersLeft as $filter){
					$title 		= $filter->getName();
					$url   		= $filter->getURL();
					?>
					<option <?=($rankingPageRequest->getExamName() == $title) ? "selected" : "";?> value="<?php echo $url;?>"><?php echo $title;?></option>
					<?php
				}
				?>
		
		<?php
		if(!empty($examFiltersRight)){
			?>
				<?php
				foreach($examFiltersRight as $filter){
					$title 		= $filter->getName();
					$url   		= $filter->getURL();
					?>
					<option value="<?php echo $url;?>"><?php echo $title;?></option>
					<?php
				}
				?>
			<?php
		}
		?>
		</select>


		<?php
		}
		?>
			
