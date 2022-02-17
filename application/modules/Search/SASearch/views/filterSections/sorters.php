<?php
if (is_array($pageData['appliedFilter']['exams']) && !empty($pageData['appliedFilter']['exams'])){
    $exam = $pageData['appliedFilter']['exams'][0];
}elseif (is_array($filters['exam']) && !empty($filters['exam'])){
    $exam = reset($filters['exam'])['name'];
}
$examNameForSorting = strtolower($exam);
$examNameToDisplay  = strtoupper($exam);
$ascendingExamScoreValue = "exam_asc_".$examNameForSorting;
$descendingExamScoreValue = "exam_desc_".$examNameForSorting;
?>
<div class="filtersDropdown">
    Sort by  :
    <i class="filter_arw"></i>
    <select class="toggleFilter" alias="sr" fType="sorting">
        <option <?=(($sortParam == "populairity_desc" /*&& $sortParamText == "applied"*/)?"selected":"")?> value="populairity_desc">Popularity</option>
        <option <?=(($sortParam == "fees_asc" /*&& $sortParamText == "applied"*/)?"selected":"")?> value="fees_asc">Low to high fees</option>
        <option <?=(($sortParam == "fees_desc" /*&& $sortParamText == "applied"*/)?"selected":"")?> value="fees_desc">High to low fees</option>
        <?php
                if ($examNameToDisplay){
        ?>
                    <option <?=(($sortParam == $ascendingExamScoreValue /*&& $sortParamText == "applied"*/)?"selected":"")?> value="<?=$ascendingExamScoreValue?>">Low to high <?=$examNameToDisplay?> score</option>
                    <option <?=(($sortParam == $descendingExamScoreValue /*&& $sortParamText == "applied"*/)?"selected":"")?> value="<?=$descendingExamScoreValue?>">High to low <?=$examNameToDisplay?> score</option>
        <?php   }
        ?>
    </select>
</div>
