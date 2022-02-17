<?php 
    $pageData['appliedFilter']['level'] = array_map('strtolower', $pageData['appliedFilter']['level']);
    // if this is course srp, there is one or more univs and a level filter was applied, we disable that one
    if($searchTupleType=='course' && count($pageData['appliedFilter']['universities'])>0 && count($pageData['appliedFilter']['level'])>0)
    {
        $origStateLevel = ($filters['originalState']!=''?$filters['originalState']:$pageData['appliedFilter']['originalState']);
    }
?>
<div class="newAcrdn active">
    <h2>Course Level<i class="custm__ico"></i></h2>
    <div class="contentBlock">
        <ul class="multiList">
        <?php $disabled  = '';$i=0; foreach($filters['courseLevel_parent'] as $courseLevel){
            if(is_null($filters['courseLevel'][$courseLevel['level']])) // only in parent
            {
                $disabled = 'disabled="disabled"';
                $labelCount = '0';
                $disabledClassStr = 'disabled';
            }else{
                $disabled = '';  
                $labelCount = $filters['courseLevel'][$courseLevel['level']]['count'];
                $disabledClassStr ='';
            }
            if(!is_null($origStateLevel) && strtolower($origStateLevel) === strtolower($courseLevel['level']))
            {
                $disabledCheckedLevel = ' disabled="disabled"';
            }else{
                $disabledCheckedLevel = '';
            }
            $checkedStr = (in_array(strtolower($courseLevel['level']),$pageData['appliedFilter']['level'])?'checked="checked"'.$disabledCheckedLevel:'');
            if($checkedStr !='' && $disabledCheckedLevel!=''){
                $disabledClassStr ='disabled';
            }
            $htmlId =  str_replace(' ','',ucfirst($courseLevel['level']));
        ?>
        <li class="multiLi <?php echo $disabledClassStr; ?>">
            <input type="checkbox" fType="courseLevels" alias="cl" class="toggleFilter" id="cl<?php echo $htmlId; ?>" <?php echo $disabled; ?> value="<?php echo $courseLevel['level']; ?>" fValue="<?php echo ucfirst($courseLevel['level']); ?>" <?php echo $checkedStr; ?>>
            <label for="cl<?php echo $htmlId; ?>"><?php echo ucfirst($courseLevel['level']); ?><span>(<?php echo $labelCount; ?>)</span></label>
        </li>
        <?php $i++ ;} ?>
        </ul>
    </div>
</div>
<?php //} ?>