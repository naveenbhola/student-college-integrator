<script>
var newRNRPage = true;    
</script>
<?php
global $sortingCriteria;
$sortType = false;
$sortOrder = false;
$sortExam = false;
$sortingCriteria = $request->getSortingCriteria();
if(!empty($sortingCriteria)){
	$sortType   = $sortingCriteria['sortBy'];
	$sortParams = $sortingCriteria['params'];
	if($sortType == "examscore"){
		$sortExam  = $sortParams['exam'];
	}
	$sortOrder = strtolower($sortParams['order']);
}
?>
<div class="category-head clear-width" id="rnr-category-header">
		<div class="category-list-table">
				<div class="category-list-row">
							<div class="category-list-col" style="width:412px;">
									Colleges and Courses
							</div>
							<div class="category-list-col">
									<p class="flLt" title="Sort By Fees" style="position:relative;top:7px;">Fees (INR)</p>
									<div class="flLt sorting-drpdwn">
									 <?php $sortingCriteria = $request->getSortingCriteria();?>
										<i id="sort_fees_asc" class="cate-new-sprite <?= $sortingCriteria['sortBy'] == 'fees' && $sortingCriteria['params']['order'] == 'ASC' ? 'sort-up-icon-a' : 'sort-up-icon' ?>" title="Low to High" onclick="sortCategoryPageResults('fees', 'asc');"></i><br/>
										<i id="sort_fees_desc" class="cate-new-sprite  <?= $sortingCriteria['sortBy'] == 'fees' && $sortingCriteria['params']['order'] == 'DESC' ? 'sort-dwn-icon-a' : 'sort-dwn-icon' ?>" title="High to Low" onclick="sortCategoryPageResults('fees', 'desc');"></i>
									</div>
							</div>
							<div class="category-list-col">
							<div class="sorting-box">
							<div class="sort-details">
                                                            <p class="flLt" style='cursor: pointer;position:relative;top:7px;' onclick="toggleExamSortLayer();" title="Sort By Eligibility" >Eligibility</p>
									<div class="flLt sorting-drpdwn">
										<i class="cate-new-sprite sort-up-icon"  onclick="toggleExamSortLayer();" title="Sort By Eligibility"></i><br/>
										<i class="cate-new-sprite sort-dwn-icon" onclick="toggleExamSortLayer();" title="Sort By Eligibility"></i>
									</div>
									
										<div id="sort_exam_layer" class="sorting-layer" style="display:none; border:1px solid #A4C4EB;" onmouseover="manageExamSortLayer('show');" onmouseout="manageExamSortLayer('hide');">
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
		</div></div>
							</div>
	<div class="category-list-col" style="width:110px">
									<?php
                                                                        if(CP_HEADER_NAME == 'photos') {
                                                                            echo "Photos &amp;<br/> Videos";
                                                                        } else {
                                                                            global $sortingCriteria;
                                                                            if($sortingCriteria['sortBy'] == "date_form_submission" && $sortingCriteria['params']['order'] == "ASC"){
                                                                                    $asc_class = "sort-up-icon-a";
                                                                                    $desc_class = "sort-dwn-icon";
                                                                            } else if($sortingCriteria['sortBy'] == "date_form_submission" && $sortingCriteria['params']['order'] == "DESC"){
                                                                                    $asc_class  = "sort-up-icon";
                                                                                    $desc_class = "sort-dwn-icon-a";
                                                                            } else {
                                                                                    $asc_class  = "sort-up-icon";
                                                                                    $desc_class = "sort-dwn-icon";
                                                                            }
                                                                             ?>
                                                                        <span style="width:90px" title="Sort By Form Submission Date">Form<br/>submission</span>
                                                                        <div class="sorting-drpdwn flRt" style="position:relative;top: -8px;">
										<i id="sort_form_submission_date_asc" class="cate-new-sprite <?=$asc_class;?>" title="Earliest to Latest" onclick="sortCategoryPageResults('formSubmissionDate', 'asc');"></i><br/>
										<i id="sort_form_submission_date_asc" class="cate-new-sprite <?=$desc_class;?>" title="Latest to Earliest" onclick="sortCategoryPageResults('formSubmissionDate', 'desc');"></i>
									</div>
                                                                        <?php } ?>
							</div>
					</div>
			 </div>	
</div>