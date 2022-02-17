<?php
    if(count($filters['course_parent']['category'])>0)
    {
        $dataList = $filters['course_parent']['category'];
    }else{
        $dataList = $filters['course_parent']['subcategory'];
    }
    if($hideSpecFilter !== true && count($dataList)>0){
        $appliedSubs  = $pageData['appliedFilter']['subCategoryIds'];
        $appliedSpecs = $pageData['appliedFilter']['specializationIds'];
    ?>
<div class="newAcrdn active">
    <h2>Specialization <i class="custm__ico"></i></h2>
    <div class="contentBlock scrollBlock">
        <ul class="multiList">
        <?php 
            foreach($specFilter as $specItem){
                $disabledParentClass = ($specItem['count'] == 0?' disabled':'');
                $disabledParent = ($specItem['count'] == 0?'disabled="disabled"':'');
                // keep selected filter checked
                if($specItem['type'] == 'sc')
                {
                    $checkedStr = (in_array($specItem['id'],$appliedSubs)?'checked="checked"':'');
                    $fType = 'subCategoryIds';
                }else if($specItem['type'] == 'si'){
                    $checkedStr = (in_array($specItem['id'],$appliedSpecs)?'checked="checked"':'');
                    $fType = 'specializationIds';
                }
        ?>
        <li class="multiLi insideList <?php echo $disabledParentClass; ?>">
            <input type="checkbox" fValue="<?php echo $specItem['heading']; ?>" class="toggleFilter" fType="<?php echo $fType?>" alias="<?php echo $specItem['type']; ?>" name="<?php echo $specItem['type']; ?>" value="<?php echo $specItem['id']; ?>" id="<?php echo $specItem['type']; ?><?php echo $specItem['id']; ?>" <?php echo $checkedStr; ?>  <?php echo $disabledParent; ?>>
            <label for="<?php echo $specItem['type']; ?><?php echo $specItem['id']; ?>"><?php echo $specItem['heading']; ?> <span>(<?php echo $specItem['count']; ?>)</span></label>
            <?php if(count($specItem['children']) >1){ ?>
                <i class="srpSprite iPlus"></i>
                <ul class="inheritList" style="display: none;">
                <?php foreach($specItem['children'] as $spec){ 
                    if($spec['disabled'] ==1)
                    {
                        $labelCount = '0';
                        $liClassStr ='class="disabled"';
                        $chboxDisabled ='disabled="disabled"';
                    }else{
                        $labelCount = $spec['count'];
                        $liClassStr ='';
                        $chboxDisabled ='';
                    }
                    $val = $spec['id'];
                    // keep selected filter checked
                    if($checkedStr != '' && $liClassStr ==''){
                        $childCheckedStr = $checkedStr;
                    }else{
                        $childCheckedStr = (in_array($spec['id'],$appliedSpecs) && $liClassStr == ''?'checked="checked"':'');
                    }
                    ?>
                    <li <?php echo $liClassStr; ?> >
                        <input type="checkbox" fValue="<?php echo $spec['heading']; ?>" class="toggleFilter" fType="specializationIds" alias="<?php echo $spec['type']; ?>" name="<?php echo $spec['type']; ?>" id="<?php echo $spec['type']; ?><?php echo $val; ?>" value="<?php echo $val; ?>" <?php echo $childCheckedStr; ?> <?php echo $chboxDisabled; ?> >
                        <label for="<?php echo $spec['type']; ?><?php echo $val; ?>"><?php echo $spec['heading']; ?> <span>(<?php echo $labelCount; ?>)</span></label>
                    </li>
                <?php } ?>
                </ul>
            <?php } ?>
        </li>
        <?php } ?>
        </ul>
    </div>
</div>
<?php } ?>
