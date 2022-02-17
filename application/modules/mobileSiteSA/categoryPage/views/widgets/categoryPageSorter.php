<?php
    
    
    //$sorterExamName = '';
	if($sorterExamName == '')
	{
		foreach ($examsForFilter as $examId=>$exam){
			if(in_array($exam, $userAppliedExamsForFilters) && in_array($examId, $appliedFilters["exam"])){
				$sorterExamName = $examsForFilter[$examId];
				break;
			}elseif($sorterExamName == '' && in_array($exam, $userAppliedExamsForFilters)){
				$sorterExamName = $examsForFilter[$examId];
			}
		}
	}
    
    switch ($sortingCriteria['sortBy']){
        case    'viewCount' :   $selected_viewCount = 'checked="true"';
                                break;
        case    'fees'      :   if($sortingCriteria['order'] == 'ASC') {
					$selected_fees_asc = 'checked="true"';
				} else {
					$selected_fees_desc = 'checked="true"';
				}
				break;
        case    'exam'      :   if($sortingCriteria['order'] == 'ASC') {
					$selected_exam_asc = 'checked="true"';
				} else {
					$selected_exam_desc = 'checked="true"';
				}
				break;
        default             :   $selected_featured = 'checked="true"';
                                
    }
    
?>
<div id="sorterContainer" data-role="page" data-enhance="false">
    <div class="wrapper">
        <div class="header-unfixed">
            <div class="layer-header">
                <a data-transition="slide" data-rel="back" href="#sorterContainer" class="back-box"><i class="sprite back-icn"></i></a>
                <p style="text-align:center">Sort by</p>
            </div>
        </div>
        
        
        
        <article id="sorterContent" class="filter-options customInputs clearfix sorter-options">
            <ul style="height: 446px;">
                <li data-enhance="false">
                    <input name="categorySorter" type="radio" data-role="none" id="sorter-value-sponsored" onclick="selectSort(this)" <?=$selected_featured?> value="none"/>
                    <label for="sorter-value-sponsored"><span class="sprite flLt"></span><p>Sponsored</p></label>
                </li>
                <li data-enhance="false">
                    <input name="categorySorter" type="radio" data-role="none" id="sorter-value-popularity" onclick="selectSort(this)" <?=$selected_viewCount?> value="viewCount_DESC"/>
                    <label for="sorter-value-popularity"><span class="sprite flLt"></span><p>Popularity</p></label>
                </li>
                <li data-enhance="false">
                    <input name="categorySorter" type="radio" data-role="none" id="sorter-value-asc-fees" onclick="selectSort(this)" <?=$selected_fees_asc?> value="fees_ASC"/>
                    <label for="sorter-value-asc-fees"><span class="sprite flLt"></span><p>Low to high 1st year total fees</p></label>
                </li>
                <li data-enhance="false">
                    <input name="categorySorter" type="radio" data-role="none" id="sorter-value-desc-fees" onclick="selectSort(this)" <?=$selected_fees_desc?> value="fees_DESC"/>
                    <label for="sorter-value-desc-fees"><span class="sprite flLt"></span><p>High to low 1st year total fees</p></label>
                </li>
                <?php
                    if($sorterExamName != ''){
                ?>
                    <li data-enhance="false">
                        <input name="categorySorter" type="radio" data-role="none" id="sorter-value-asc-exam" onclick="selectSort(this)" <?=$selected_exam_asc?> value="exam_ASC_<?=$sorterExamName?>"/>
                        <label for="sorter-value-asc-exam"><span class="sprite flLt"></span><p>Low to high <?=$sorterExamName?> exam score</p></label>
                    </li>
                    <li data-enhance="false">
                        <input name="categorySorter" type="radio" data-role="none" id="sorter-value-desc-exam" onclick="selectSort(this)" <?=$selected_exam_desc?> value="exam_DESC_<?=$sorterExamName?>"/>
                        <label for="sorter-value-desc-exam"><span class="sprite flLt"></span><p>High to low <?=$sorterExamName?> exam score</p></label>
                    </li>
                <?php }?>
            </ul>
        </article>
    </div>
</div>