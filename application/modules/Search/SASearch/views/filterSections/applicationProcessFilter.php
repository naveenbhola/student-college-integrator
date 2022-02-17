<?php if($filters['sop_parent'] != 0 && $filters['lor_parent']!=0) { ?>
<div class="newAcrdn active">
    <h2>Application Process<i class="custm__ico"></i></h2>
    <div class="contentBlock">
        <ul class="multiList">
        <?php
            $disabled = $disabledClassStr = '';
            // check if sop is available at all (in parent)
            if(!is_null($filters['sop_parent'])){
                if(is_null($filters['sop']) || $filters['sop'] == 0){
                    $disabled= 'disabled="disabled"';
                    $countLabel = '0';
                    $disabledClassStr = 'disabled';
                }else{
                    $disabled= '';
                    $countLabel = $filters['sop'];
                    $disabledClassStr = '';
                }
                $checkedStr = (reset($pageData['appliedFilter']['sop'])==1?'checked="checked"':'');
        ?>
        <li class="multiLi <?php echo $disabledClassStr; ?>">
            <input type="checkbox"value="1" class="toggleFilter" fType="applicationProcess" alias="sop" id="sop" <?php echo $disabled; ?> fValue="SOP" <?php echo $checkedStr; ?> >
            <label for="sop">SOP <span>(<?php echo $countLabel; ?>)</span></label>
        </li>
        <?php } 
            $disabled = $disabledClassStr = '';
            // check if sop is available at all (in parent)
            if(!is_null($filters['lor_parent'])){
                if(is_null($filters['lor']) || $filters['lor'] == 0){
                    $disabled= 'disabled="disabled"';
                    $countLabel = '0';
                    $disabledClassStr = 'disabled';
                }else{
                    $disabled= '';
                    $countLabel = $filters['lor'];
                    $disabledClassStr = '';
                }
                $checkedStr = (reset($pageData['appliedFilter']['lor'])==1?'checked="checked"':'');
        ?>
        <li class="multiLi <?php echo $disabledClassStr; ?>">
            <input type="checkbox"value="1" class="toggleFilter" fType="applicationProcess" alias="lor" id="lor" <?php echo $disabled; ?> fValue="LOR" <?php echo $checkedStr; ?> >
            <label for="lor">LOR <span>(<?php echo $countLabel; ?>)</span></label>
        </li>
        <?php } ?>
        </ul>
    </div>
</div>
<?php } ?>