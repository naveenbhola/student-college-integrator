<?php
$result_type = "main";
if(!empty($resultType)){
	$result_type = $resultType;
}

$examFilters = $filters['exam'];
$specializationFilters = $filters['specialization'];
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

$rankingPageData = $ranking_page->getRankingPageData();
$showFeesColoumn = false;
$showExamFilters = false;
foreach($rankingPageData as $pageRow){
	if(count($pageRow->getExams()) > 0){
		$showExamFilters = true;
	}
	if($pageRow->getFeesValue() > 0){
		$showFeesColoumn = true;
	}
	if($showExamFilters && $showFeesColoumn){
		break;
	}
}
$showExamColoumn = $showExamFilters;
$enableSortFunctionality = false;

$sortKey 		=  "rank";
$sortKeyValue 	=  "";
$sortOrder 		=  "desc";
if(!empty($sorter) && count($rankingPageData) > 1){
	$enableSortFunctionality = true;
	$sortKey 		=  $sorter->getKey();
	$sortKeyValue 	=  $sorter->getKeyValue();
	$sortOrder 		=  $sorter->getOrder();
}

$examId = "";
if($result_type == "main"){
	$examId = $request_object->getExamId();
} else if($result_type == "exam"){
	if(!empty($main_request_object)){
		$examId = $main_request_object->getExamId();	
	} else {
		$examId = $request_object->getExamId();
	}
} 

if(empty($rankingPageData) && $result_type == "main"){
	?>
	<div class="confirmation-msg">
		Sorry, no results were found matching your selection. Please alter your refinement options above.
	</div>
	<?php
} else {
?>
	<div class="ranking-details-cont">
		<span class="top-flag flLt" style="margin-top: 5px;">&nbsp;</span>
		<?php
		if($result_type == "main"){
		?>
		<div class="flRt" style="display:none;color:#757575;">Last updated in <?php echo date('F Y', strtotime($ranking_page->getLastUpdatedTime())); ?></div>
		<?php
		}
		?>
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<tr>
				<?php
				if($enableSortFunctionality){
				?>
					<th class="rank-num" width="85">
						<div style="position:relative;">
							<span class="flLt">Rank</span>
							<div class="sorting-arrows">
								<?php
								if($sortKey == "rank" && $sortOrder == "desc"){
									?>
									<span class="sort-by-high pointer-cursor"  onmouseout="hideToolTip('rank');" onmouseover="showSorterTooltip('rank', 'up');"></span>
									<span class="sort-dwn-arr pointer-cursor" onmouseout="hideToolTip('rank');" onmouseover="showSorterTooltip('rank', 'down');" onclick="sortResults('<?php echo $result_type;?>', 'rank', 'asc');" ></span>
								<?php
								} else if($sortKey == "rank" && $sortOrder == "asc"){
									?>
									<span class="sort-up-arr pointer-cursor" onmouseout="hideToolTip('rank');" onmouseover="showSorterTooltip('rank', 'up');" onclick="sortResults('<?php echo $result_type;?>', 'rank', 'desc');"></span>
									<span class="sort-by-low pointer-cursor" onmouseover="showSorterTooltip('rank', 'down');" onmouseout="hideToolTip('rank');"></span>
								<?php
								} else {
									?>
									<span class="sort-up-arr pointer-cursor" onmouseout="hideToolTip('rank');" onmouseover="showSorterTooltip('rank', 'up');" onclick="sortResults('<?php echo $result_type;?>', 'rank', 'desc');" ></span>
									<span class="sort-dwn-arr pointer-cursor" onmouseout="hideToolTip('rank');" onmouseover="showSorterTooltip('rank', 'down');" onclick="sortResults('<?php echo $result_type;?>', 'rank', 'asc');" ></span>
									<?php
								}
								?>
                            </div>
							<div id="sort_loader_rank" style="float:left;margin-top:5px;margin-left:5px;"></div>
							<div class="help-tool" id="rank_help_tooltip" style="display:none;">
								<div class="pointer"></div>
								<div class="content" id="rank_help_tooltip_content"></div>
							</div>
						</div>
					</th>
				<?php
				} else {
					?>
					<th class="rank-num" width="55">
						<span class="flLt">Rank</span>
					</th>
					<?php
				}	
				?>
				<th width="357">Name of Institute</th>
				<th width="150">
					<?php
					if($totalExamFilters <= 1 && $showSpecializationFilters == false){
					?>
					<div style="position:relative;"><span class="pointer-cursor" onclick="toggleInlineLocationLayer();">City <span uniqueattr="RankingPage/cityinlinelocationlayer" class="dwn-arrow pointer-cursor"></span></span>
						<?php
						$inlineLocationLayerData = array();
						$inlineLocationLayerData['city_filters'] = $filters['city'];
						$inlineLocationLayerData['result_type'] = $result_type;
						?>
						<?php $this->load->view('ranking/ranking_page_inline_locationlayer', $inlineLocationLayerData); ?>
					</div>
					<?php
					} else {
						?>
						<div style="position:relative;">City</div>
						<?php
					}
					?>
				</th>
				<th width="100">Course</th>
				<?php
				if($result_type  == "main"){
					if($showFeesColoumn){
						if($enableSortFunctionality){
							?>
							<th width="100">
								<div style="position:relative;">
									<span class="flLt">Fees</span>
									<div class="sorting-arrows">
										<?php
										if($sortKey == "fees" && $sortOrder == "asc"){
											?>
											<span class="sort-by-high pointer-cursor" onmouseout="hideToolTip('fees');" onmouseover="showSorterTooltip('fees', 'up');"></span>
											<span class="sort-dwn-arr pointer-cursor" onmouseout="hideToolTip('fees');" onmouseover="showSorterTooltip('fees', 'down');" onclick="sortResults('<?php echo $result_type;?>', 'fees', 'desc');" ></span>
										<?php
										} else if($sortKey == "fees" && $sortOrder == "desc"){
											?>
											<span class="sort-up-arr pointer-cursor" onmouseout="hideToolTip('fees');" onmouseover="showSorterTooltip('fees', 'up');" onclick="sortResults('<?php echo $result_type;?>', 'fees', 'asc');"></span>
											<span class="sort-by-low pointer-cursor" onmouseout="hideToolTip('fees');" onmouseover="showSorterTooltip('fees', 'down');" ></span>
										<?php
										} else {
											?>
											<span class="sort-up-arr pointer-cursor" onmouseout="hideToolTip('fees');" onmouseover="showSorterTooltip('fees', 'up');" onclick="sortResults('<?php echo $result_type;?>', 'fees', 'asc');" ></span>
											<span class="sort-dwn-arr pointer-cursor" onmouseout="hideToolTip('fees');" onmouseover="showSorterTooltip('fees', 'down');" onclick="sortResults('<?php echo $result_type;?>', 'fees', 'desc');" ></span>
											<?php
										}
										?>
									</div>
									<div id="sort_loader_fees" style="float:left;margin-top:5px;margin-left:5px;"></div>
									<div class="help-tool" id="fees_help_tooltip" style="display:none;">
										<div class="pointer"></div>
										<div class="content" id="fees_help_tooltip_content"></div>
									</div>
								</div>
							</th>
						<?php
						} else { ?>
							<th width="100">Fees</th>
						<?php
						}
					}	
				} else {
					?>
					<th width="100">Fees</th>
					<?php
				}
				
				if($result_type  == "main"){
					if($showExamColoumn){
						if($enableSortFunctionality && !empty($examId)){
							?>
							<th width="150">
								<div style="position:relative;">
									<span class="flLt">Cutoff</span>
									<div class="sorting-arrows">
										<?php
										if($sortKey == "marks" && $sortOrder == "asc"){
											?>
											<span class="sort-by-high pointer-cursor" onmouseout="hideToolTip('marks');" onmouseover="showSorterTooltip('marks', 'up');"></span>
											<span class="sort-dwn-arr pointer-cursor" onmouseout="hideToolTip('marks');" onmouseover="showSorterTooltip('marks', 'down');" uniqueattr="RankingPage/markssorter" onclick="sortResults('<?php echo $result_type;?>', 'marks', 'desc');" ></span>
										<?php
										} else if($sortKey == "marks" && $sortOrder == "desc"){
											?>
											<span class="sort-up-arr pointer-cursor" uniqueattr="RankingPage/markssorter" onmouseout="hideToolTip('marks');" onmouseover="showSorterTooltip('marks', 'up');" onclick="sortResults('<?php echo $result_type;?>', 'marks', 'asc');"></span>
											<span class="sort-by-low pointer-cursor" onmouseout="hideToolTip('marks');" onmouseover="showSorterTooltip('marks', 'down');" ></span>
										<?php
										} else {
											?>
											<span class="sort-up-arr pointer-cursor" uniqueattr="RankingPage/markssorter" onmouseout="hideToolTip('marks');" onmouseover="showSorterTooltip('marks', 'up');" onclick="sortResults('<?php echo $result_type;?>', 'marks', 'asc');" ></span>
											<span class="sort-dwn-arr pointer-cursor" uniqueattr="RankingPage/markssorter" onmouseout="hideToolTip('marks');" onmouseover="showSorterTooltip('marks', 'down');" onclick="sortResults('<?php echo $result_type;?>', 'marks', 'desc');" ></span>
											<?php
										}
										?>
									</div>
									<div id="sort_loader_marks" style="float:left;margin-top:5px;margin-left:5px;"></div>
									<div class="help-tool" id="marks_help_tooltip" style="display:none;">
										<div class="pointer"></div>
										<div class="content" id="marks_help_tooltip_content"></div>
									</div>
								</div>
							</th>
						<?php
						} else {
							?>
							<th width="150">Cutoff</th>	
							<?php
						}	
					}	
				} else {
					?>
					<th width="150">Cutoff</th>	
					<?php
				}
				?>
			</tr>
			<?php
			$count = 1;
			foreach($rankingPageData as $pageData){
				$trClass = "";
				if($count % 2 == 0){
					$trClass = "alt-rows";
				}
				?>
				<tr class="<?php echo $trClass;?>">
					<td class="rank-num"><?php echo $pageData->getRank();?></td>
					<td>
						<div class="inst-name" style="margin-bottom:6px !important;">
							<a target="_blank" href="<?php echo $pageData->getInstituteURL();?>"><?php echo $pageData->getInstituteName();?></a>
							<?php
								if($validateuser != 'false') {
									if($validateuser[0]['usergroup'] == 'cms' || $validateuser[0]['usergroup'] == 'enterprise' || $validateuser[0]['usergroup'] == 'sums' || $validateuser[0]['usergroup'] == 'saAdmin' || $validateuser[0]['usergroup'] == 'saCMS'){
									    if(is_object($pageData)){
										if($pageData->isCoursePaid()){
										    echo '<br/><label style="color:white; font-weight:normal; font-size:13px; background:#b00002; text-align:center; padding:2px 6px;">Paid</label>';
										}else{
										    echo '<br/><label style="color:white; font-weight:normal; font-size:13px; background:#1c7501; text-align:center; padding:2px 6px;">Free</label>';	
										}
									    }
									}
								}
							?>
						</div>
						<?php
						//if($pageData->isCoursePaid()){
						if(true && ($pageData->isCoursePaid() || $brochureURL->getCourseBrochure($pageData->getCourseId()))){
							$getInstituteImage = $instituteRepository->find($pageData->getInstituteId());?>
						<div>
						<div class=flLt>
						
							<!--------------------compare tool--------------------------->               
							<p>
							    <input onclick="updateCompareText('compare<?php echo $pageData->getInstituteId();?>-<?=$pageData->getCourseId();?>');
							    updateAddCompareList('compare<?php echo $pageData->getInstituteId();?>-<?=$pageData->getCourseId();?>');trackEventByGA('LinkClick','ADD_TO_COMPARE_ON_RANKING_PAGE');checkactiveStatusOnclick();"
							    type="checkbox" name="compare" style="margin-left:0;" id="compare<?php echo $pageData->getInstituteId();?>-<?=$pageData->getCourseId();?>" class="compare<?php echo $pageData->getInstituteId();?>-<?=$pageData->getCourseId();?>" value="<?php echo $pageData->getInstituteId().'::'.' '.'::'.($getInstituteImage->getMainHeaderImage()?$getInstituteImage->getMainHeaderImage()->getThumbURL():'').'::'.htmlspecialchars(html_escape($pageData->getInstituteName())).', '.$pageData->getCityName().'::'.$pageData->getCourseId().'::'.$pageData->getCourseURL();?>"/>
							    <a  href="javascript:void(0);" onclick="checkactiveStatusOnclick();trackEventByGA('LinkClick','ADD_TO_COMPARE_ON_RANKING_PAGE');toggleCompareCheckbox('compare<?php echo $pageData->getInstituteId();
							    ?>-<?=$pageData->getCourseId();?>');updateAddCompareList('compare<?php echo $pageData->getInstituteId();
							    ?>-<?=$pageData->getCourseId();?>');return false;" id="compare<?php echo $pageData->getInstituteId();?>-<?=$pageData->getCourseId();?>lable" class="compare<?php echo $pageData->getInstituteId();?>-<?=$pageData->getCourseId();?>lable">Add to Compare</a>
							</p>
							<div style="display:none">
								
							<input type="hidden" name="compare<?php echo $pageData->getInstituteId();?>-<?=$pageData->getCourseId();?>list[]"  value="<?=$pageData->getCourseId();?>"/>
								
							</div>
							<!--------------------compare tool--end--------------------------->
						</div>
						<div class="flRt">
							<a href="javascript:void(0)" onclick="changeDropDownSelectedIndex('applynow<?php echo $pageData->getInstituteId();?>', <?php echo $pageData->getCourseId();?>); return multipleCourseApplyForCategoryPage(<?php echo $pageData->getInstituteId();?>,'RANKING_PAGE',this, <?php echo $pageData->getCourseId();?>);" value="Request E-brochure" title="Request E-brochure" class="request-button">Request E-brochure</a>
						</div>
						</div>
						
						
						
						
						<?php
						}
						?>
					</td>
					<td><?php echo $pageData->getCityName();?></td>
					<td><a target="_blank" href="<?php echo $pageData->getCourseURL();?>"><?php echo $pageData->getCourseAltText();?></a></td>
					<?php
					if($result_type  == "main" && $showFeesColoumn){
					?>
						<td>
							<?php
							$feesUnit = $pageData->getFeesUnit();
							$feesValue = $pageData->getFeesValue();
							if(!empty($feesUnit) && !empty($feesValue)){
								echo $feesUnit;
							}
							?>
							<?php echo $pageData->getFeesValue();?>
						</td>
					<?php
					} else if($result_type == "exam" || $result_type == "location") {
						?>
						<td>
							<?php
							$feesValue = $pageData->getFeesValue();
							$feesUnit = $pageData->getFeesUnit();
							if(!empty($feesUnit) && !empty($feesValue)){
								echo $feesUnit;
							}
							?>
							<?php echo $pageData->getFeesValue();?>
						</td>
						<?php
					}
					
					$id 			= $pageData->getId();
					$exams 			= $pageData->getExams();
					usort($exams, 'sortExamsInUI');
					$validExams = array();
					if(!empty($examId)){
						foreach($exams as $exam){
							if($exam['id'] == $examId){
								$validExams[] = $exam;
								break;
							}
						}
					}
					if(!empty($validExams)){
						$exams = $validExams;
					}
					
					$examString 	= "";
					$examMoreString = "";
					if(count($exams) > 0){
						$mainExams = array_slice($exams, 0, 2);
						if(count($mainExams) > 1){
							$examString .= $mainExams[0]['name'];
							if(!empty($mainExams[0]['marks'])){
								$examString .= ": ". $mainExams[0]['marks'] . ", ";
							} else {
								$examString .= ", ";
							}
							$examString .= $mainExams[1]['name'];
							if(!empty($mainExams[1]['marks'])){
								$examString .=  ": ". $mainExams[1]['marks'];
							}
						} else {
							$examString .= $mainExams[0]['name'];
							if(!empty($mainExams[0]['marks'])){
								$examString .= ": ". $mainExams[0]['marks'] . ", ";
							}
						}
						
						$extraExams = array();
						if(count($exams) > 2){
							$extraExams = array_slice($exams, 2);
						}
						$counter = 0;
						foreach($extraExams as $exam){
							$examMoreString .= $exam['name'];
							if(!empty($exam['marks'])){
								$examMoreString .= ": ". $exam['marks'];
							}
							$counter++;
							if($counter % 2 == 0 && $counter != 0){
								$examMoreString .= "<br/>";
							} else {
								$examMoreString .= ", ";
							}
						}
						$examString = trim($examString);
						$examString = trim($examString, ",");
						$examMoreString = trim($examMoreString);
						$examMoreString = trim($examMoreString, ",");
					}
					$flag = false;
					if($result_type == "main" && $showExamColoumn){
						$flag = true;
					} else if($result_type == "location" || $result_type == "exam"){
						$flag = true;
					}
					
					if($flag){
					?>
						<td class="cutoff-details">
							<?php echo $examString;?>
							<?php
							if(!empty($examMoreString)){
								?>
								<br/>
								<span style="display:none;" id="more_exams_<?php echo $id;?>"><?php echo $examMoreString;?></span>
								<a href="javascript:void(0);" id="more_exams_handle_<?php echo $id;?>" onclick="toggleMoreExams('<?php echo $id;?>')">+ view more</a>
								<?php
							}
							?>
						</td>
					<?php
					}
					?>
				</tr>
				<?php
				$count++;
			}
			?>
		</table>
		<span class="bot-flag">&nbsp;</span>
		<p style="margin:15px 0 10px;font-size:11px;">Disclaimer: This ranking is based on the aggregation and analysis of college rankings published by various credible sources.</p>
		<div class="clearFix"></div>
		<?php
			if(!empty($result_type) && $result_type != "main"){
				if((int)$offset_reach + (int)$max_rows <= (int)$total_results){
					$offset_reach = (int)$offset_reach + (int)$max_rows;
					?>
					<div class="tar">
						<a href="javascript:void(0);" uniqueattr="RankingPage/viewextradatablock" class="view-more" onclick="loadMoreResults('<?php echo $result_type;?>', <?php echo $offset_reach;?>);">View More</a>
					</div>
					<?php
				}
			}
		?>
	</div>
	<?php
}
?>

<script>
compareDiv = 1;
</script>
