<div class="newAcrdn active">
    <h2>Scholarship<i class="custm__ico"></i></h2>
    <div class="contentBlock">
        <ul class="multiList">
        <?php 
            $schYesCheckedStr = (in_array('Yes',$pageData['appliedFilter']['scholarship'])?'checked="checked"':'');
            $schNoCheckedStr = (in_array('No',$pageData['appliedFilter']['scholarship'])?'checked="checked"':'');
            $disabled = '';
            // check if sop is available at all (in parent)
            if(!is_null($filters['scholarship_parent']['Yes'])){
                if(is_null($filters['scholarship']['Yes']))
                {
                    $disabled='disabled="disabled"';
                    $countLabel = '0';
                    $disabledClassStr = 'disabled';
                }else{
                    $disabled='';
                    $countLabel = $filters['scholarship']['Yes'];
                    $disabledClassStr = '';
                }
        ?>
        <li class="multiLi <?php echo $disabledClassStr; ?>">
            <input type="checkbox" class="toggleFilter" alias="scp" fType="scholarships" id="scholarshipYes" value="<?php echo 'Yes'; ?>" <?php echo $disabled; ?> fValue="Scholarship (Available)" <?php echo $schYesCheckedStr; ?> >
            <label for="scholarshipYes">Available <span>(<?php echo $countLabel; ?>)</span></label>
        </li>
        <?php } 
            $disabled = '';
            // check if lor is available at all (in parent)
            if(!is_null($filters['scholarship_parent']['No'])){
                if(is_null($filters['scholarship']['No']))
                {
                    $disabled='disabled="disabled"';
                    $countLabel = '0';
                    $disabledClassStr = 'disabled';
                }else{
                    $disabled='';
                    $countLabel = $filters['scholarship']['No'];
                    $disabledClassStr = '';
                }
        ?>
        <li class="multiLi <?php echo $disabledClassStr; ?>">
            <input type="checkbox" class="toggleFilter" alias="scp" fType="scholarships" id="scholarshipNo" value="<?php echo 'No'; ?>" <?php echo $disabled; ?> fValue="Scholarship (Not Available)" <?php echo $schNoCheckedStr; ?> >
            <label for="scholarshipNo">Not Available <span>(<?php echo $countLabel; ?>)</span></label>
        </li>
        <?php } ?>
        </ul>
    </div>
</div>