<?php global $queryStringFieldNameToAliasMapping; 
            $appliedFilters = $request->getAppliedFilters();
            $appliedIntakeYears = $appliedFilters['saScholarshipIntakeYear'];
?>
<li>
    <input type="checkbox" class="main__check">
    <i class="custm__ico"></i>
    <h3 class="main__titl f12-clr3 fnt-sbold">Intake Year</h3>
    <div id="intkScroll" class="intake__sc">
        
                   <ul class="sub__menu">
                    <?php foreach($scholarshipData['filterData']['saScholarshipIntakeYear'] as $intakeYear => $rowCount){ ?>
                        <li>
                            <a class="chek__box">
                            <?php unset($scholarshipData['filterData']['saScholarshipIntakeYear_parent'][$intakeYear]);?>
                                <div class="">
                                <input type="checkbox" id="intake_<?php echo $intakeYear; ?>" class="inputChk" name="intakeYear[]" value="<?php echo $intakeYear; ?>" alias="<?php echo $queryStringFieldNameToAliasMapping['intakeYear'];?>" <?php echo (in_array($intakeYear,$appliedIntakeYears)?'checked':''); ?>/>
                                <label class="f12-clr3 check__opt" for="intake_<?php echo $intakeYear; ?>" alias="<?php echo $queryStringFieldNameToAliasMapping['intakeYear'];?>">
                                    <i></i>
                                    <?php echo $intakeYear." (".$rowCount.")"; ?>
                                </label>
                                </div>
                            </a>
                        </li>
                    <?php } ?>
                    <?php foreach($scholarshipData['filterData']['saScholarshipIntakeYear_parent'] as $intakeYear => $rowCount){ ?>
                        <li>
                            <a class="chek__box">
                                <div class="">
                                <input type="checkbox" disabled id="intake_<?php echo $intakeYear; ?>" class="inputChk" name="intakeYear[]" value="<?php echo $intakeYear; ?>" alias="<?php echo $queryStringFieldNameToAliasMapping['intakeYear'];?>" <?php echo (in_array($intakeYear,$appliedIntakeYears)?'checked':''); ?>/>
                                <label class="f12-clr3 check__opt" for="intake_<?php echo $intakeYear; ?>" alias="<?php echo $queryStringFieldNameToAliasMapping['intakeYear'];?>">
                                    <i></i>
                                    <?php echo $intakeYear." (0)"; ?>
                                </label>
                                </div>
                            </a>
                        </li>
                    <?php } ?>
                   </ul>
               
    </div>
</li>