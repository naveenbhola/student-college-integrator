<?php
$RankingTextMsg = "Find the best college for yourself";
?>
 <div class="college-search-sec clear-width college-search-sticky" id="ranking_category_sticky_widget" style="display: block;">
	<a href="javascript:void(0);" class="show-hide-button" id="hide-category-widget-btn" onclick="toggleCategoryWidgetBox('false')">Hide <i class="ranking-sprite hide-arrw"></i></a>
	<div style="display:none;" id="show-category-widget-btn" class="flRt">
		<p class="show-text flLt"><?=$RankingTextMsg?></p>
		<a href="javascript:void(0);" class="show-hide-button flRt"  onclick="toggleCategoryWidgetBox('true')">Show <i class="ranking-sprite show-arrw"></i></a>
	</div>
	<div class="college-search-box">
		<ul>
			<li class="first-child">
				<label><?=$RankingTextMsg?></label>
			</li>
			<?php if(count($filters['exam'])>1){ ?>
				<li>
					<select  id="catPageExam" class="score-selectfield">
						<option value="">Select Exam</option>
							<?php 
								$examList = $filters['exam'];
								foreach($examList as $exam){
					 				$title      = $exam->getName();
					 				if($title !='All'){
                     					echo "<option value='".$exam->getId()."'>".$title."</option>";
					 				}
								}
							?>
					</select>
				</li>
			<?php } ?>
			<li>
				<select class="score-selectfield" id="catPageLocation">
					<option>Select Location</option>
					<?php 
						$cityList = $filters['city'];
						$stateList = $filters['state'];
						$locationList = array(
							'city'=>array('locationSet' => $cityList,'locationType' => 'city'),
							'state'=>array('locationSet'=>$stateList,'locationType' => 'state')
						);
						foreach($locationList as $location){
							foreach($location['locationSet'] as $locationVal){
								if( in_array($locationVal->getId(), array(128, 129, 130, 131, 134, 135, 345)) && $location['locationType'] == "state"){
									continue;
								}
								$title      = $locationVal->getName();
                        		$id        = $locationVal->getId()."-".$location['locationType'];
                        		echo "<option value='$id'>$title</option>";
							}
						}
			
					?>
				</select>
			</li>
			<input type="hidden" id="stream" value="<?php echo $rankingPageRequest->getStreamId();?>">
			<input type="hidden" id="substream" value="<?php echo $rankingPageRequest->getSubstreamId();?>">
			<input type="hidden" id="specialization" value="<?php echo $rankingPageRequest->getSpecializationId();?>">
			<input type="hidden" id="baseCourse" value="<?php echo $rankingPageRequest->getBaseCourseId();?>">
			<li style="float:right">
				<a  href="javascript:void(0);" onclick ="sendToCategoryPage();" class="search-btn">
					SEARCH
				</a>
			</li>
		</ul>
	</div>
</div>
<div id="ranking_category_append_widget">
</div>
