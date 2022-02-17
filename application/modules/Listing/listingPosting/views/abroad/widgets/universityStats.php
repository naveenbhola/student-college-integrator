<div class="clear-width">
    <h3 class="section-title" style="cursor:pointer;"><i class="abroad-cms-sprite <?php echo $otherSectionHeadingImageClass; ?>"></i>University stats</h3>
    <div class="cms-form-wrap cms-accordion-div" style = "<?php echo $otherSectionDisplayStyle; ?>">
        <ul>
        <li>
            <label>% International Students :</label>
            <div class="cms-fields">
                <input tytpe="text" class="universal-txt-field cms-text-field last-in-section" name="percentIntlStud" id="percentIntlStud_<?php echo $formName; ?>" maxlength = "9" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" caption = "% of international students" validationType = "float" value="<?php echo $univStatsData['percentIntlStud']['attrVal']; ?>">
                <div style="display: none" class="errorMsg" id="percentIntlStud_<?php echo $formName; ?>_error"></div>
            </div>
        </li>
        <li>
            <label>Total International Students :</label>
            <div class="cms-fields">
                <input tytpe="text" class="universal-txt-field cms-text-field last-in-section" name="totalIntlStud" id="totalIntlStud_<?php echo $formName; ?>" maxlength = "9" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" caption = "total number of international students" validationType = "numeric" value="<?php echo $univStatsData['totalIntlStud']['attrVal']; ?>">
                <div style="display: none" class="errorMsg" id="totalIntlStud_<?php echo $formName; ?>_error"></div>
            </div>
        </li>
        <li>
            <label>Size of Campus in acres :</label>
            <div class="cms-fields">
                <input tytpe="text" class="universal-txt-field cms-text-field last-in-section" name="campusSize" id="campusSize_<?php echo $formName; ?>" maxlength = "9" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" caption = "size of campus (in acres)" validationType = "numeric" value="<?php echo $univStatsData['campusSize']['attrVal']; ?>">
                <div style="display: none" class="errorMsg" id="campusSize_<?php echo $formName; ?>_error"></div>
            </div>
        </li>
        <li>
            <label>Male/Female Ratio :</label>
            <div class="cms-fields">
                <input tytpe="text" class="universal-txt-field cms-text-field last-in-section" name="genderRatio" id="genderRatio_<?php echo $formName; ?>" maxlength = "9" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" caption = "male/female ratio" validationType = "float" value="<?php echo $univStatsData['genderRatio']['attrVal']; ?>">
                <div style="display: none" class="errorMsg" id="genderRatio_<?php echo $formName; ?>_error"></div>
            </div>
        </li>
        <li>
            <label>UG/PG Course Ratio :</label>
            <div class="cms-fields">
                <input tytpe="text" class="universal-txt-field cms-text-field last-in-section" name="ugPgRatio" id="ugPgRatio_<?php echo $formName; ?>" maxlength = "9" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" caption = "UG/PG ratio" validationType = "float" value="<?php echo $univStatsData['ugPgRatio']['attrVal']; ?>">
                <div style="display: none" class="errorMsg" id="ugPgRatio_<?php echo $formName; ?>_error"></div>
            </div>
        </li>
        <li>
            <label>Faculty/Student Ratio :</label>
            <div class="cms-fields">
                <input tytpe="text" class="universal-txt-field cms-text-field last-in-section" name="studentFacultyRatio" id="studentFacultyRatio_<?php echo $formName; ?>" maxlength = "9" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" caption = "Student/Faculty ratio" validationType = "float" value="<?php echo $univStatsData['studentFacultyRatio']['attrVal']; ?>">
                <div style="display: none" class="errorMsg" id="studentFacultyRatio_<?php echo $formName; ?>_error"></div>
            </div>
        </li>
        <li>
            <label>Endowments Currency:</label>
            <div class="cms-fields">
                <select class="universal-select cms-field" name = "endowmentsCurr" id="endowmentsCurr_<?php echo $formName; ?>" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" caption = "endowments currency"  validationType = "select">
                <option value = "">Select a Currency </option>
                <?php
                    foreach($currencies as $currency)
                    {
                        if($univStatsData['endowmentsCurr']['attrVal'] == $currency['id']){
                            $selected = 'selected="selected"';
                        }
                        else{
                            $selected = '';
                        }
                        echo '<option '.$selected.' value="'.$currency['id'].'">'.$currency['currency_name'].'</option>';
                    }
                ?>
                </select>
                <div style="display: none" class="errorMsg" id="endowmentsCurr_<?php echo $formName; ?>_error"></div>
            </div>
        </li>
        <li>
            <label>Endowments Value:</label>
            <div class="cms-fields">
                <input type="text" class="universal-txt-field cms-text-field last-in-section" name="endowmentsVal" id="endowmentsVal_<?php echo $formName; ?>" maxlength = "11" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" caption = "endowments value" validationType = "numeric" value="<?php echo $univStatsData['endowmentsVal']['attrVal']; ?>">
                <div style="display: none" class="errorMsg" id="endowmentsVal_<?php echo $formName; ?>_error"></div>
            </div>
        </li>


        <!--  new code goes here  -->
            <li>
                <div class="add-more-sec2 clear-width">
                    <ul class = "customKbInputBlock">
                        <?php if(count($univCustomAttributes) > 0){ ?>
                            <?php foreach($univCustomAttributes as $k=>$customUnivMapping){ ?>
                                <li class = "CustomKnowledgeBoxInputRow">
                                    <ul>
                                        <li>
                                            <label>Custom field label : </label>
                                            <div class="cms-fields">
                                                <textarea name='kb_custom_field_name[]' id="customKbFieldName_<?=$k?>_<?=$formName?>" class="cms-textarea" type="text" caption="custom field label" validationtype = "str" maxlength="50" minlength="1"> <?=$customUnivMapping['custom_label']?> </textarea>
                                                <div style="display: none" class="errorMsg" id="customKbFieldName_<?=$k?>_<?=$formName?>_error"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <label>Custom field value : </label>
                                            <div class="cms-fields">
                                                <textarea name='kb_custom_field_value[]' id="customKbFieldValue_<?=$k?>_<?=$formName?>" class="cms-textarea" maxlength="50" minlength="1" validationType="str" caption="Custom Field Value"><?=$customUnivMapping['custom_value']?></textarea>
                                                <div style="display: none" class="errorMsg" id="customKbFieldValue_<?=$k?>_<?=$formName?>_error"></div>
                                            </div>

                                        </li>
                                    </ul>
                                    <a class="remove-link flRt" href="Javascript:void(0);" onclick = "removeCustomKnowledgeBoxField(this);" style="margin:5px 30px 0 0;<?=($k==0?'display:none;':'')?>"><i class="abroad-cms-sprite remove-icon"></i>Remove custom field</a>
                                </li>
                            <?php } ?>
                        <?php } else { ?>
                            <li class = "CustomKnowledgeBoxInputRow">
                                <ul>
                                    <li>
                                        <label>Custom field label : </label>
                                        <div class="cms-fields">
                                            <textarea name='kb_custom_field_name[]' id="customKbFieldName_0_<?=$formName?>" class="cms-textarea" type="text" validationtype = "str" caption="custom field label" minlength="1" maxlength="50"><?=$customUnivMapping['custom_label']?></textarea>
                                            <div style="display: none" class="errorMsg" id="customKbFieldName_0_<?=$formName?>_error"></div>
                                        </div>
                                    </li>
                                    <li>
                                        <label>Custom field value : </label>
                                        <div class="cms-fields">
                                            <textarea name='kb_custom_field_value[]' id="customKbFieldValue_0_<?=$formName?>" class="cms-textarea" maxlength="50"  validationType="str" caption="Custom Field Value"><?=($customUnivMapping['custom_label'])?></textarea>
                                            <div style="display: none" class="errorMsg" id="customKbFieldValue_0_<?=$formName?>_error"></div>
                                        </div>

                                    </li>
                                </ul>
                                <a class="remove-link flRt" href="Javascript:void(0);" onclick = "removeCustomKnowledgeBoxField(this);" style="margin:5px 30px 0 0;display:none;"><i class="abroad-cms-sprite remove-icon"></i>Remove custom field</a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
                <a class="add-more-link last-in-section" href="Javascript:void(0);" onclick = "addMoreKnowledgeBoxCustomField(this);">[+] Add more</a>
            </li>


<!--till here-->


        <li>
            <label>No.of Campuses in original country including main campus:</label>
            <div class="cms-fields">
                <select class="universal-select cms-field" name = "campusCount" id="campusCount_<?php echo $formName; ?>" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" caption = "number of campuses" validationType = "select">
                    <option value="">Select</option>
                    <?php for($i=1;$i<=50;$i++){ ?>
                    <option value="<?php echo $i; ?>" <?php echo ($univStatsData['campusCount']['attrVal'] == $i?'selected="selected"':''); ?>><?php echo $i; ?></option>
                    <?php } ?>
                </select>
                <div style="display: none" class="errorMsg" id="campusCount_<?php echo $formName; ?>_error"></div>
            </div>
        </li>
        <li>
            <label>Ranking :</label>
            <div class="cms-fields">
                <textarea class="cms-textarea tinymce-textarea" name = "univRanking" id="univRanking_<?php echo $formName; ?>" maxlength = "5000" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" caption = "ranking" validationType = "html"><?php echo $univStatsData['univRanking']['attrVal']; ?></textarea>
                <div style="display: none" class="errorMsg" id="univRanking_<?php echo $formName; ?>_error"></div>
            </div>
        </li>
        </ul>
    </div>
</div>
