<?php
$specializationFilters = $filters['specialization'];
$totalSpecializationFilters = count($specializationFilters['childrenUrl']) + count($specializationFilters['parentUrl']);
$showSpecializationFilters = true;
global $widthPercent;
if($totalSpecializationFilters < 1){
	$showSpecializationFilters = false;
	$widthPercent['course'] = '41';
}

if($showSpecializationFilters) {
    $mobile_details = json_decode($_COOKIE['ci_mobile_capbilities'],true);
    $screenWidth = $mobile_details['resolution_width'];
    $width = round(($screenWidth - 30)/3) + 10;
?>
	<select class="exam-filter" id="courseSelection<?=$number?>" onchange="redirectRanking('course','<?=$number?>',event);" style="width:41%;">
			<?php 	
				if(!empty($filters['specialization']['selectedName'])){
                    	echo "<option>".$filters['specialization']['selectedName']."</option>";
				}else{
						echo "<option>Specialisation</option>";
				}
			?>
			<?php if(!empty($specializationFilters['parentUrl'])){ ?>
				<option value='<?php echo $specializationFilters['parentUrl']['url']; ?>'>
					<?php echo $specializationFilters['parentUrl']['name'];?>
				</option>
			<?php } ?>
			<?php
				foreach($specializationFilters['childrenUrl'] as $filter){
					$title 		= $filter['name'];
					$url   		= $filter['url'];
					?>
						<option  value="<?php echo $url;?>"><?php echo $title;?></option>
			<?php
				}
			?>
	</select>
<?php } ?>