<div class="filter-section">
	<table cellpadding="0" cellspacing="0">
		<thead>
		<tr>
			<td width="10%"><strong>Filter By :</strong></td>
			<td>

			<?php
				if($rankingPageRequest->getBaseCourseId() == 10){ 
					if(count($filters['specialization']['childrenUrl']) > 0 ||
						 count($filters['specialization']['parentUrl']) > 0 ) { ?>
					<div class="filter-sel-box" style="width:188px" >
						<div class="sel-overlay" type='specialization'></div>
						<select class="exam-select">
								<?php 	
								if(!empty($filters['specialization']['selectedName'])){
	                                	echo "<option>".$filters['specialization']['selectedName']."</option>";
								}else{
										echo "<option>Specialisation</option>";
								}
								?>
						</select>
						<?php $this->load->view(RANKING_PAGE_MODULE.'/ranking-revamp/widgets/rankingPageSpecialisationAndExamOverLay',array('divId'=>'specialisationDropdownLayer','filterResult'=>$filters['specialization'],'showFilters'=>true)); ?>

					</div>
			<?php 
					} 
				}
				?>
				<div class="filter-sel-box">
					<div class="sel-overlay" type='location'></div>
					<select class="exam-select">
						<?php 	
					
							$selectedLocation = $filtersChecks['selectedLocation']; 
							if(!empty($selectedLocation)){
								$title      = $selectedLocation->getName();
                             	$url        = $selectedLocation->getURL();
                             	$isSelected = $selectedLocation->isSelected();
                             
	                            if($isSelected == true){
	                            	if($title == 'All')
	                        			echo "<option>All (Locations)</option>";
	                        		else
	                        			echo "<option>$title</option>";
	                            }else{
	                                echo "<option>Location</option>";
	                            }
							}else{
									echo "<option>Location</option>";
							}?>
					</select>
					<?php $this->load->view(RANKING_PAGE_MODULE.'/ranking-revamp/widgets/rankingPageLocationOverLay',array('cityFilters'=>$filters['city'],'useCityFilter'=>$filtersChecks['useCityFilter'],'stateFilters'=>$filters['state'])); ?>

					
				</div>
			<?php if(count($filters['exam']) > 1 && $rankingPage->isUgTemplate() == 'No' && (in_array($rankingPageRequest->getBaseCourseId(), array(10,101)))) { ?>
				<div class="filter-sel-box">
					<div class="sel-overlay" type='exam'></div>
					<select class="exam-select">
						<?php 	
							$selectedExam = $filtersChecks['selectedExam']; 
							if(!empty($selectedExam)) {
								$title      = $selectedExam->getName();
	                            $url        = $selectedExam->getURL();
	                            $isSelected = $selectedExam->isSelected();
                                    
	                            if($isSelected == true){
	                            	if($title == 'All')
	                        			echo "<option>All (Exams)</option>";
	                        		else
	                        			echo "<option>$title</option>";
	                            }else{
	                            	echo "<option>Exam</option>";
	                            }
							}else{
									echo "<option>Exam</option>";
							}?>
					</select>
					<?php $this->load->view(RANKING_PAGE_MODULE.'/ranking-revamp/widgets/rankingPageSpecialisationAndExamOverLay',array('divId'=>'examDropdownLayer','filterResult'=>$filters['exam'],'showFilters'=>$filtersChecks['showExamFilters'])); ?>

				</div>
				<?php } ?>
			</td>
		</tr>
		</thead>
	</table>
</div>