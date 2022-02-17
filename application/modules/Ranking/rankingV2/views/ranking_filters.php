<?php
$cityFilters  	= $filters['city'];
$stateFilters  	= $filters['state'];
$examFilters 	= $filters['exam'];
$specializationFilters = $filters['specialization'];
$citySelected = false;

$totalSpecializationFilters = count($specializationFilters);
$defaultSpecializationSelected = false;
foreach($specializationFilters as $filter){
	if($filter->isSelected() == true){
		$defaultSpecializationSelected = true;
	}
}

$totalExamFilters = count($examFilters);
$defaultExamSelected = false;
foreach($examFilters as $filter){
	if($filter->isSelected() == true){
		$defaultExamSelected = true;
	}
}

$showSpecializationFilters = true;
if($totalSpecializationFilters <= 1 && $defaultSpecializationSelected){
	$showSpecializationFilters = false;
}

$showExamFilters = true;
$filterBlockStyle = "width:97%;";
if($totalExamFilters <= 1 && $defaultExamSelected){
	$showExamFilters = false;
	$filterBlockStyle = "width:62%;";
}


if($showExamFilters != false || $showSpecializationFilters != false){
?>
   <div style="margin-top:18px;">
	<h2 class="refine-title flLt">Refine Top <?php echo $page_headline['page_name'];?> Institutes by</h2>
	<?php $this->load->view("ranking/social_network_share_widget"); ?>
	<div class="clearFix spacer5"></div>
	<div class="rank-refine-cont" style="<?php echo $filterBlockStyle;?>">
		<?php
		if(!empty($specializationFilters)){
		?>
			<div class="refine-cols">
				<h3 class="refine-title">Course</h3>
				<div class="refine-child-cols">
					<ul>
						<?php
						foreach($specializationFilters as $filter){
							$title 		= $filter->getName();
							$url   		= $filter->getURL();
							$isSelected = $filter->isSelected();
							$className = "";
							if($isSelected == true){
							?>
								<li class="active"><a class="active"><?php echo $title;?></a></li>
							<?php
							} else {
								?>
								<li><a href="<?php echo $url;?>"><?php echo $title;?></a></li>
								<?php
							}
						}
						?>
					</ul>
				</div>
			</div>
		<?php
		}
		?>
		
		<?php
		$examBlockShown = false;
		if(!empty($examFilters) && count($examFilters) > 1){
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
			<div class="refine-cols ent-col">
				<h3 class="refine-title">Exams Accepted</h3>
				<div class="refine-child-cols">
					<ul class="ent-item-cols">
						<?php
						foreach($examFiltersLeft as $filter){
							$title 		= $filter->getName();
							$url   		= $filter->getURL();
							$isSelected = $filter->isSelected();
							$className = "";
							if($isSelected == true){
							?>
								<li class="active"><a class="active"><?php echo $title;?></a></li>
							<?php
							} else {
								?>
								<li><a href="<?php echo $url;?>"><?php echo $title;?></a></li>
								<?php
							}
						}
						?>
					</ul>
					<?php
					if(!empty($examFiltersRight)){
						?>
						<ul class="ent-item-cols">
						<?php
						foreach($examFiltersRight as $filter){
							$title 		= $filter->getName();
							$url   		= $filter->getURL();
							$isSelected = $filter->isSelected();
							$className = "";
							if($isSelected == true){
							?>
								<li class="active"><a class="active"><?php echo $title;?></a></li>
							<?php
							} else {
								?>
								<li><a href="<?php echo $url;?>"><?php echo $title;?></a></li>
								<?php
							}
						}
						?>
						</ul>
						<?php
					}
					?>
				</div>
			</div>
		<?php
		}
		?>
		
		<?php
		if(!empty($stateFilters) || !empty($cityFilters)){
			$tempCityFilterSelected = NULL;
			$tempStateFilterSelected = NULL;
			foreach($cityFilters as $filter){
				$isSelected = $filter->isSelected();
				if($isSelected){
					$tempCityFilterSelected = $filter;
					break;
				}
			}
			foreach($stateFilters as $filter){
				$isSelected = $filter->isSelected();
				if($isSelected){
					$tempStateFilterSelected = $filter;
					break;
				}
			}
			
			$useCityFilter = true;
			if(!empty($tempCityFilterSelected) && !empty($tempStateFilterSelected)){
				$tempCitySelected = $tempCityFilterSelected->getName();
				$tempStateSelected = $tempStateFilterSelected->getName();
				if(strtolower($tempCitySelected) == "all" && trim($tempCitySelected) != ""){
					$useCityFilter = false;
				}
			}
		
		$className = "locality-col";
		$blockRightBorderStyle = "";
		if(!$examBlockShown){
			$className = "ent-col";
			$blockRightBorderStyle = "border-right:none;";
			
		}
		?>
		<div class="refine-cols <?php echo $className;?>">
			<h3 class="refine-title">Location</h3>
			<div class="refine-child-cols" style="<?php echo $blockRightBorderStyle;?>">
				<?php
				if(!empty($cityFilters)){
				?>
				<ul class="ent-item-cols">
					<?php
						foreach($cityFilters as $filter){
							$title 		= $filter->getName();
							$url   		= $filter->getURL();
							$isSelected = $filter->isSelected();
							$className = "";
							if($isSelected == true && $useCityFilter){
								$citySelected = true;
							?>
								<li class="active"><a class="active"><?php echo $title;?></a></li>
							<?php
							} else {
								?>
								<li><a href="<?php echo $url;?>"><?php echo $title;?></a></li>
								<?php
							}
						}
					?>
				</ul>
				<?php
				}
				?>
				
				<?php
				if(!empty($stateFilters)){
				?>
				<ul class="ent-item-cols">
					<?php
						foreach($stateFilters as $filter){
							$title 		= $filter->getName();
							$url   		= $filter->getURL();
							$isSelected = $filter->isSelected();
							$className = "";
							if($isSelected == true && $citySelected != true){
							?>
								<li class="active"><a class="active"><?php echo $title;?></a></li>
							<?php
							} else {
								?>
								<li><a href="<?php echo $url;?>"><?php echo $title;?></a></li>
								<?php
							}
						}
					?>
				</ul>
				<?php
				}
				?>
			</div>
		</div>
		<?php
		}
		?>
	</div>
   </div>
<?php
} else {
	?>
	<?php $this->load->view("ranking/social_network_share_widget"); ?>
	<?php
}
?>
