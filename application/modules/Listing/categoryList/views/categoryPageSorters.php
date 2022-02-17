<?php
global $sortingCriteria;
global $appliedFilters;
global $MBA_SCORE_RANGE;
global $MBA_SCORE_RANGE_CMAT;
global $MBA_SCORE_RANGE_GMAT;
global $ENGINEERING_EXAMS_REQUIRED_SCORES;
global $MBA_EXAMS_REQUIRED_SCORES;
global $MBA_NO_OPTION_EXAMS;
global $MBA_PERCENTILE_RANGE_MAT;
global $MBA_PERCENTILE_RANGE_XAT;
global $MBA_PERCENTILE_RANGE_NMAT;

$sortType = false;
$sortOrder = false;
$sortExam = false;
if(!empty($sortingCriteria)){
	$sortType   = $sortingCriteria['sortBy'];
	$sortParams = $sortingCriteria['params'];
	if($sortType == "examscore"){
		$sortExam  = $sortParams['exam'];
	}
	$sortOrder = strtolower($sortParams['order']);
}

$emptyScoreExams = array();
$userSelectedExams = array();
$customExamsList = array("CMAT", "GMAT");
if(!empty($appliedFilters)){
	$exams = $appliedFilters['courseexams'];
	if(!empty($exams)){
		$userSelectedExams = array();
		foreach($exams as $exam){
			$explode = explode("_", $exam);
			if(!in_array($explode[0], $MBA_NO_OPTION_EXAMS)){
				$userSelectedExams[] = $explode[0];
				if(in_array($explode[0], $customExamsList)) {
					$splitByHyphen = explode("-", $explode[1]);
					if(count($splitByHyphen) == 1){
						if($splitByHyphen[0] <= 0){
							$emptyScoreExams[] = $explode[0];
						}
					}
				} else {
					if($explode[1] == 0){
						$emptyScoreExams[] = $explode[0];
					}
				}
			}
		}
	}
}

$pageSubCategoryId = $request->getSubCategoryId();
if(count($institutes) > 0){
?>
<div class="sorting-box">
	<strong>Sort by:</strong>
	<div class="sort-details">
		<div class="flLt sort-items">
			<a href="javascript:void(0);" class="flLt" title="Sort By Fees">Fees</a> 
			<div class="sorting-arrows">
				<?php
				if($sortType == "fees" && $sortOrder == "asc"){
					$asc_class = "sort-by-high";
					$desc_class = "sort-dwn-arr";
				} else if($sortType == "fees" && $sortOrder == "desc"){
					$asc_class  = "sort-up-arr";
					$desc_class = "sort-by-low";
				} else {
					$asc_class  = "sort-up-arr";
					$desc_class = "sort-dwn-arr";
				}
				?>
				<span id="sort_fees_asc" class="<?php echo $asc_class;?>" onclick="sortCategoryPageResults('fees', 'asc');" title="Low to High"></span>
				<span id="sort_fees_desc" class="<?php echo $desc_class;?>" onclick="sortCategoryPageResults('fees', 'desc');" title="High to Low"></span>
			</div>
			<span class="sort-pipe flLt">|</span>
		</div>
		
		<div id="sort_exam_head" class="flLt sort-items" onclick="toggleExamSortLayer();" style="<?php echo $examsSorterStyle;?>;cursor: pointer;">
			<?php
			if($pageSubCategoryId  == 23){
			?>
				<a href="javascript:void(0);" class="flLt" style="cursor: pointer !important;" title="Sort by Exam Score">Exam Score</a>
			<?php
			} else {
				?>
				<a href="javascript:void(0);" style="cursor: pointer !important;" class="flLt" title="Sort by Exam Rank">Exam Rank</a>
				<?php
			}
			?>
			<div class="sorting-arrows">
				<span class="sort-up-arr"></span>
				<span class="sort-dwn-arr"></span>
			</div>
		</div>
		
		<div id="sort_exam_layer" class="sorting-layer" style="display:none;" onmouseover="manageExamSortLayer('show');" onmouseout="manageExamSortLayer('hide');">
			<?php
			if(!empty($userSelectedExams)){
				foreach($userSelectedExams as $exam){
					$string_asc = $exam . "_asc";
					$string_desc = $exam . "_desc";
					$asc_class  = "sort-up-arr";
					$desc_class = "sort-dwn-arr";
					if($sortType == "examscore" && $sortExam == $exam){
						if($sortOrder == "asc"){
							$asc_class = "sort-by-high";
							$desc_class = "sort-dwn-arr";
						} else if($sortOrder == "desc"){
							$asc_class  = "sort-up-arr";
							$desc_class = "sort-by-low";	
						} else {
							$asc_class  = "sort-up-arr";
							$desc_class = "sort-dwn-arr";
						}
					}
					?>
					<div class="sort-items" id="sort_<?php echo $exam;?>_cont">
						<a href="javascript:void(0);" class="flLt"><?php echo $exam;?></a>
						<div class="sorting-arrows">
							<span class="<?php echo $asc_class;?>" onclick="sortCategoryPageResults('examscore', '<?php echo $string_asc;?>');" title="Low to High"></span>
							<span class="<?php echo $desc_class;?>" onclick="sortCategoryPageResults('examscore', '<?php echo $string_desc;?>');" title="High to Low"></span>
						</div>
					</div>
					<?php
				}
			} else {
				?>
				<div class="sort-items">
					<span style="color:#707070;" class="flLt">No exam selected</span>
				</div>
				<?php
			}
			?>
			<?php
			if($sortExam != false && $sortOrder != false){
			?>
			<div class="cleartxt">
				<a href="javascript:void(0)" style="cursor: pointer;" onclick="resetSortOptions();">Clear all</a>
			</div>
			<?php
			}
			?>
		</div>
	</div>
	<div class="clearFix"></div>
</div>
<?php
}
?>
<!--CODE for exam score layer -->
<?php
if($pageSubCategoryId == 23 || $pageSubCategoryId == 56) {
	$examScoreLayerDisplayStyle = "display:none;";
	if(count($emptyScoreExams) > 0){
		$examScoreLayerDisplayStyle = "display:none;";
	}
	$header = "Filter results by institute cut-off";
	if($pageSubCategoryId == 56){
		$header = "Filter results by institute cut-off";
	}
?>
	<div class="relevant-res-box" id="relevant-res-box" style="<?php echo $examScoreLayerDisplayStyle;?>">
			<div class="relevant-header" id="relevant-header-close" style="display:none;">
				<a href="javascript:void(0);" onclick="openExamLayer();" class="icon-collapse"></a>
				<p style="padding-top:10px; padding-bottom:10px; margin-right:45px"><?php echo $header;?></p>
			</div>
			<div class="relevant-header" id="relevant-header-open">
				<a href="javascript:void(0);" onclick="collapseExamLayer();" class="close-black">x</a>
				<p><?php echo $header;?></p>
			</div>
			<ul class="relevant-content" id="examscore_layer_cont">
				<?php
				$i = 0;
				while($i < count($emptyScoreExams)){
					$inputExams = array_slice($emptyScoreExams, $i, 2);
					?>
					<li>
					<?php
					$j = 0;
					$examCount = count($inputExams);
					$widthStyle = "";
					if($examCount == 1){
						$widthStyle = "width:70%;";
					}
					foreach($inputExams as $examName){
						$className = "flLt";
						if($j > 0){
							$className = "flRt";
						}
						$j++;
					?>
						<p class="<?php echo $className;?>" style="<?php echo $widthStyle;?>">
							<label>
								<input checked='checked' onclick="onExamSelect('<?php echo trim($examName);?>', this, '<?php echo $pageSubCategoryId;?>', 'rel');" id="rel_exam_cb_<?php echo trim($examName);?>" type="checkbox" name="relcourseexams[]" value = "<?=$examName?>" />&nbsp; <?php echo $examName;?>
							</label>
							<br/>
							<?php
							if($pageSubCategoryId == 23) {
								$defaultDropdownValue = "Any percentile";
								if(in_array($examName, $MBA_EXAMS_REQUIRED_SCORES)){
									$defaultDropdownValue = "Any score";
								}
					
							?>
								<select style="width:120px;" id="rel_courseexamdd_<?php echo trim($examName);?>" onchange="onExamRangeChange('<?php echo trim($examName);?>', 'rel');">
									<option value="none"><?php echo $defaultDropdownValue;?></option>
									<?php
									$scoreRanges = $MBA_SCORE_RANGE;
									if($examName == "CMAT"){
										$scoreRanges = $MBA_SCORE_RANGE_CMAT;
									} else if($examName == "GMAT"){
										$scoreRanges = $MBA_SCORE_RANGE_GMAT;
									} else if($examName == "MAT"){
										$scoreRanges = $MBA_PERCENTILE_RANGE_MAT;
									} else if($examName == "XAT"){
										$scoreRanges = $MBA_PERCENTILE_RANGE_XAT; 
									} else if($examName == "NMAT"){
										$scoreRanges = $MBA_PERCENTILE_RANGE_NMAT; 
									}
									foreach($scoreRanges as $key => $value){
										?>
										<option value="<?php echo $value;?>"><?php echo $key;?></option>
										<?php
									}
									?>
								</select>
							<?php
							} else if($pageSubCategoryId == 56){
								$placeHolderText = "Enter Rank";
								if(in_array($examName, $ENGINEERING_EXAMS_REQUIRED_SCORES)){
									$placeHolderText = "Enter Score";
								}
							?>
								<input style="width:85px;" type="text" onblur="checkTextElementOnTransition(this,'blur');" onfocus="checkTextElementOnTransition(this,'focus');" default="<?php echo $placeHolderText;?>" id="rel_courseexamtextbox_<?php echo trim($examName);?>" value="<?php echo $placeHolderText;?>"></input>
							<?php
							}
							?>
						</p>
					<?php
					}
					$i = $i + 2;
					?>
					</li>
					<?php
				}
				?>
				<li>
					<button onclick="submitExamScoreLayer();" class="orange-button" style="font-size:14px !important">Go<span class="btn-arrow"></span></button>
					<span id="examLayerErrorCont" class="errorMsg"></span>
				</li>
			</ul>
			<div class="clearFix"></div>
		</div>
<?php
}
?>

<script>
	var EXAMS_SELECTED_COUNT = <?php echo count($emptyScoreExams); ?>;
</script>