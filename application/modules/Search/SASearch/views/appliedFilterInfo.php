<?php
$topFilterData = array();
$aliasIdArray = array();
$trailingText = array("workExperience" => "year(s)", "ugMarks" => "%", "class12Marks" => "%", "courseFee" => "Lakhs", "courseDuration" => "month(s)");
$aliasText = array("workExperience" => "we", "ugMarks" => "ug", "class12Marks" => "c12", "courseFee" => "cf", "courseDuration" => "crdr");
$filtersArrayValue = array("workExperience" => "WorkExperience", "ugMarks" => "UGCutoff", "courseFee" => "fees", "courseDuration" => "duration", "class12Marks" => "12thCutoff");
foreach($pageData['appliedFilter'] as $filterType => $value){
    if(empty($value)) {
        continue;
    }
    $filterId = null;
    switch($filterType){
        case 'class12Marks':
        case 'workExperience':
        case 'courseFee':
        case 'ugMarks':
        case 'courseDuration':
            sort($pageData['appliedFilter'][$filterType]);
            $min = $pageData['appliedFilter'][$filterType][0];
            $max = $pageData['appliedFilter'][$filterType][1];
            $filterParentInfo = $filtersArrayValue[$filterType].'_parent';
            if($min < $filters[$filterParentInfo]['min'] || $min > $filters[$filterParentInfo]['max']){
                $min = $filters[$filterParentInfo]['min'];
            }
            if($max > $filters[$filterParentInfo]['max'] || $max < $filters[$filterParentInfo]['min']){
                $max = $filters[$filterParentInfo]['max'];
            }
            if($min == $filters[$filterParentInfo]['min'] && $max == $filters[$filterParentInfo]['max']){
                break;
            }
            // if min value is invalid, round off to nearest step
            if(($mod = fmod($min, $filters[$filterParentInfo]['scale'])) !=0)
            {
                if($mod>($filters[$filterParentInfo]['scale']/2))
                {
                    $min = $min + ($filters[$filterParentInfo]['scale']-$mod);
                }else{
                    $min = $min - $mod;
                }
            }
            if(($mod = fmod($max, $filters[$filterParentInfo]['scale'])) !=0)
            {
                if($mod>($filters[$filterParentInfo]['scale']/2))
                {
                    $max = $max + ($filters[$filterParentInfo]['scale']-$mod);
                }else{
                    $max = $max - $mod;
                }
            }
            $filterId = 'slider-'.$aliasText[$filterType];
            $valueToDisplay = $min . ' - '.$max . ' ' . $trailingText[$filterType];
            if($filterType == 'class12Marks' || $filterType == "ugMarks") {
                $valueToDisplay = $min . $trailingText[$filterType] . ' - ' . $max . $trailingText[$filterType];
            }
            if($filterType == "courseFee"){
                $valueToDisplay .= ($max==50?" +":"");
            }
            $alias = $aliasText[$filterType];
            break;
        case 'sop':
        case 'lor':
            if($value == 0){
                continue;
            }
            $filterId = $filterType;
            $alias = $filterType;
            $valueToDisplay = strtoupper($filterType);
            break;

        case 'scholarship':
            if(in_array('Yes',$pageData['appliedFilter']['scholarship'])) {
                $filterId = "scholarshipYes" ;
                $valueToDisplay = "Scholarship (Available)";
            }
            if(!empty($filterId)) {
                $topFilterData[$filterId] = $valueToDisplay;
                $aliasIdArray[$filterId] = "scp";
                $filterId = null;
            }
            if(in_array('No',$pageData['appliedFilter']['scholarship'])) {
                $filterId = "scholarshipNo" ;
                $valueToDisplay = "Scholarship (Not Available)";
            }
            $alias = "scp";
            break;
        case 'subCategoryIds':
        case 'specializationIds':
            if(count($filters['course_parent']['category'])>0)
            {
                $dataList = $filters['course_parent']['category'];
            }else{
                $dataList = $filters['course_parent']['subcategory'];
            }
            $appliedCategories = array();
            if($hideSpecFilter !== true && count($dataList)>0) {
                $appliedCategories = $pageData['appliedFilter'][$filterType];
            }
            foreach($appliedCategories as $appliedCategory){
                if (empty($idSpecData[$appliedCategory])) {
                    continue;
                }
                $filterId = $idSpecData[$appliedCategory]['type'].$appliedCategory;
                $valueToDisplay = $idSpecData[$appliedCategory]['value'];
                $aliasIdArray[$filterId] = $idSpecData[$appliedCategory]['type'];
                $topFilterData[$filterId] = $valueToDisplay;
            }
            $filterId = null;
            break;
        case 'country':
        case 'state':
        case 'city':
            $appliedLocations  = $pageData['appliedFilter'][$filterType];
            foreach($appliedLocations as $appliedLocation) {
                if (empty($idLocationData[$appliedLocation])) {
                    continue;
                }
                $filterId = $idLocationData[$appliedLocation]['type'].$appliedLocation;
                $valueToDisplay = $idLocationData[$appliedLocation]['value'];
                $topFilterData[$filterId] = $valueToDisplay;
                $aliasIdArray[$filterId] = $idLocationData[$appliedLocation]['type'];
            }
            $filterId = null;
            break;
        case 'examScore':
        case 'exams':
            $appliedExam = reset($pageData['appliedFilter']['exams']);
            $appliedExamScore = $pageData['appliedFilter']['examScore'];
            sort( $appliedExamScore);
            $filterId = 'slider-'.'es';
            $minScore = $minLt = $filters['exam'][$appliedExam]['scores']['min'];
            $maxScore = $maxLt = $filters['exam'][$appliedExam]['scores']['max'];
            $scale = $filters['exam'][$appliedExam]['scores']['scale'];
            $minScore = (!is_null($appliedExamScore[0])?$appliedExamScore[0]:$minScore);
            $maxScore = (!is_null($appliedExamScore[1])?$appliedExamScore[1]:$maxScore);
            if($minScore < $minLt || $minScore > $maxLt){
                $minScore = $minLt;
            }
            if($maxScore > $maxLt || $maxScore < $minLt){
                $maxScore = $maxLt;
            }
            // if min value is invalid, round off to nearest step
            if(($mod = fmod($minScore,$scale)) !=0)
            {
                if($mod>($scale/2))
                {
                    $minScore = $minScore + ($scale-$mod);
                }else{
                    $minScore = $minScore - $mod;
                }
            }
            if(($mod = fmod($maxScore,$scale)) !=0)
            {
                if($mod>($scale/2))
                {
                    $maxScore = $maxScore + ($scale-$mod);
                }else{
                    $maxScore = $maxScore - $mod;
                }
            }
            $valueToDisplay = $appliedExam . (empty($appliedExamScore) == false ? ' ('. $minScore . ' - ' . $maxScore .')':'');
            if($minScore == $minLt && $maxScore == $maxLt){
                $valueToDisplay = $appliedExam;
            }
            $alias = 'ex';
            break;
        case 'level':
            $appliedLevels = $pageData['appliedFilter']['level'];
            // if($searchTupleType=='course' && count($pageData['appliedFilter']['universities'])>0 && count($pageData['appliedFilter']['level'])>0)
            // {
            //     // don't show level filter if university selected with a level
            //     break;
            // }
            foreach($appliedLevels as $appliedLevel){
                $filterId = 'cl' . str_replace(' ','',ucfirst(strtolower($appliedLevel)));
                $valueToDisplay = ucfirst($appliedLevel);
                $topFilterData[$filterId] = $valueToDisplay;
                $aliasIdArray[$filterId] = 'cl';
            }
            $filterId = null;
            break;
    }
        if(!empty($filterId)) {
            $topFilterData[$filterId] = $valueToDisplay;
            $aliasIdArray[$filterId] = $alias;
        }
}
?>
<div class="multiTags filterTopMaxHeight" id="topFilters" style="display: none">
    <div id="heightCheck">
    <span class="viewMoreBtn" style="display: none">View More</span>
    <?php foreach($topFilterData as $filterId => $valueToDisplay) { ?>
        <a class="searchTags" id="<?php echo 'fil_'.$filterId ?>" urlalias="<?php echo $aliasIdArray[$filterId]; ?>"><?php echo $valueToDisplay ?><span class="closeTag"><i class="srpSprite ics"></i></span> </a>
    <?php } ?>
    </div>
</div>
