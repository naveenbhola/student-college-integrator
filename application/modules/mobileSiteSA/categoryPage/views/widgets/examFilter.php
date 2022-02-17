<?php
    $examPreSelectedFilters         = $appliedFilters["exam"];
    $examsScorePreSelectedFilters   = $appliedFilters["examsScore"];
    
    $examsScorePreSelectedDetails = explode('--',$examsScorePreSelectedFilters[0]);
		
	if(array_search($examsScorePreSelectedDetails[1], $userAppliedExamsScoreForFilters[$examsScorePreSelectedDetails[2]]) === false)
	{
		array_push($userAppliedExamsScoreForFilters[$examsScorePreSelectedDetails[2]],$examsScorePreSelectedDetails[1]);
		arsort($userAppliedExamsScoreForFilters[$examsScorePreSelectedDetails[2]]);
	}
?>
<article class="filter-options customInputs clearfix filterTab" id="examTab">
    <ul>
        <?php $k = 0;
        foreach($examsForFilter as $examid=>$exam) {
        if(trim($exam)) {
                $checked = in_array($examid, $examPreSelectedFilters) ? "checked" : "";
                $disabled = in_array($exam, $userAppliedExamsForFilters) ? "" : "disabled";
        ?>    
        <li>
            <input type="checkbox" tabname="examTab" name="exam[]" id="exam-<?=$k?>" value="<?=$examid?>" <?=$checked?> autocomplete="off" <?=$disabled?> data-role="none" onclick="showHideSelectBox(this,'<?=$k?>','<?=$examid?>');">
            <label class="exam-labels" for="exam-<?=$k?>" style="float:none; padding-bottom:4px">
                <span class="sprite flLt"></span><p><?=$exam?></p>
            </label>
            <?php
            if(($index = array_search('N/A', $userAppliedExamsScoreForFilters[$examid])) !== false)
            {
                unset($userAppliedExamsScoreForFilters[$examid][$index]);
            }
            if(count($userAppliedExamsScoreForFilters[$examid]) >0)
            { ?>
            <div class="custom-dropdown" data-enhance="false" id="examScore-<?=$examid?>-<?=$k?>" style="margin:0 12px 0 20px;<?php echo ($checked=="checked")?"":"display:none;"?>">
                <select id="examScoreVal-<?=$examid?>-<?=$k?>" name="examsScore[]" class="universal-select"  style=" font-size:11px;padding:4px;" onchange="checkIfMoreThanOneExamSelected(this);">
                    <option value="">Select Score</option>
                    <?php  foreach($userAppliedExamsScoreForFilters[$examid] as $key=>$val)
                            {
								if($val == -1)
								{
									continue;
								}
                                $scoreSelected = '';
                                $formattedValue =$exam."--".$val."--".$examid;
                                if(array_search($formattedValue,$examsScorePreSelectedFilters)!==false && array_search($formattedValue,$examsScorePreSelectedFilters)!==null)
                                {
                                    $scoreSelected = 'selected="selected"';
                                }   ?>
                      <option value="<?php echo $formattedValue;?>" <?php echo $scoreSelected;?>><?php echo $val;?> & below</option>
                    <?php   } ?>
                </select>
            </div>
        <?php } ?>
        </li>
        <?php }
        $k++; }?>    
        <li class="clear-link"><a href="javascript:void(0);" onclick="resetFilterofTab('examTab');">Clear This Filter</a></li>
    </ul>
</article>
<div id= "examScoreFilterRestrictionDiv" class="hide">
    <a href="#examScoreFilterRestriction" data-transition="slide" data-inline="true" data-rel="dialog"></a>
</div>

