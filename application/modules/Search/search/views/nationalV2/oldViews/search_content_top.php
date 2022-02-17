<section class="fltr_rslts">
	<div class="shw_fltrs">
		<?php
		$showSelectedFiltersArray = $selectedFilters;
		unset($showSelectedFiltersArray['subCategory']);
		if(!empty($showSelectedFiltersArray)){
		?>
		<p class="slctd-fltrs"> Selected Filters : </p>
		<div class="fltrs">
		<?php
		
			foreach($showSelectedFiltersArray as $filterName => $filterValues) {
				switch($filterName){
					case 'locations':
						foreach($filterValues as $locationType => $locationValues) {
							foreach($locationValues as $details) { ?>
								<a href="javascript:void(0);" class="show-slctd-fltrs" data-section="<?=$filterName;?>" data-val="<?=$locationType.'_'.$details['id']?>"><?php echo $details['name'];?><i class="fltr-cross">&times;</i></a>
							<?php
							}
						}
					break;
					case 'fees':
						foreach($filterValues as $feesValue => $feesRange) {
						?>
							<a href="javascript:void(0);" class="show-slctd-fltrs"><?php echo $feesRange;?><i class="fltr-cross">&times;</i></a>
						<?php
						}
					break;
					case 'exams':
						foreach($filterValues as $examName) {
							$exam = explode("_", $examName);
						?>
							<a href="javascript:void(0);" class="show-slctd-fltrs" data-section="<?=$filterName;?>" data-val="<?=$exam[0]?>"><?php echo $exam[0];?><i class="fltr-cross">&times;</i></a>
						<?php
						}
					break;
					case 'specialization':
						foreach($filterValues as $specializationDetails) {
							if(strtolower($specializationDetails['name']) == 'all'){
								continue;
							}
						?>
							<a href="javascript:void(0);" class="show-slctd-fltrs" data-section="specialisation" data-val="<?=$specializationDetails['id']?>" ><?php echo $specializationDetails['name'];?><i class="fltr-cross">&times;</i></a>
						<?php
						}
					break;
					case 'mode':
						foreach ($filterValues as $key => $mode) {?>
							<a href="javascript:void(0);" class="show-slctd-fltrs" data-section="<?=$filterName;?>" data-val="<?=$mode?>" ><?php echo $mode;?><i class="fltr-cross">&times;</i></a>
						<?php
						}
					break;
					case 'courseLevel':
						foreach ($filterValues as $key => $courseLevel) {?>
							<a href="javascript:void(0);" class="show-slctd-fltrs" data-section="<?=$filterName;?>" data-val="<?=$courseLevel?>" ><?php echo $courseLevel;?><i class="fltr-cross">&times;</i></a>
						<?php
						}
					break;
				}
			}
		}
		?>
		</div>
		<p class="srt-fltr">Sort By:
			<select class="fltr-srt-drpdwn">
				<option>Popularity</option>
				<option>High</option>
			</select>
		</p>
	</div>
</section>
<div class="slct_fltrs">