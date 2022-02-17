<div class="clear-width dragable-block" id="exam_syllabus_section">
    <select id="syllabus_sort" class="cms-dropdown sort" name="position" onchange="changePosition(<?=$sectionOrder['syllabus'];?>,'syllabus_sort','section')">
        <?php for($i =2 ; $i <= $numberOfSections ; $i++) {
                $value = '';
            if($sectionOrder['syllabus'] == $i) {
                $value = 'selected';
                }
            ?>
            <option value="<?=$i;?>" <?=$value;?>><?=$i;?></option>
        <?php } ?>
    </select>
    <h3 class="section-title" style="cursor:pointer;"><i class="abroad-cms-sprite <?=$headingImageClass?>"></i>Syllabus</h3>
    <div id="syllabus_<?=$formName?>" class="cms-form-wrap cms-accordion-div" style="<?=$sectionDisplayStyle?>">
        <ul>
            <li>
                <label>Syllabus : </label>
                <div class="cms-fields syllabus-div">
                    <textarea id="syllabus" name="syllabuspagedata" class="cms-textarea tinymce-textarea" caption="Syllabus" validationtype="html"><?=$syllabus['syllabus']['wikiData'];?></textarea>
                    <div id="syllabus_error" class="errorMsg" style="display:none;"></div>
                    <input type="hidden" id="prevUpdOn_syllabus" value="<?php echo $syllabus['syllabus']['updatedOn'];?>">
                    <?php if($actionType == 'edit'){ ?>
                    <div class="belowEdit-btnWrapper">
                        <button onclick="saveWikiData('syllabus','0','syllabus','');return false;" class="button button--secondary">Save</button>
                    <?php if($actionType == 'edit' && $status = 'live' && count($groupsLiveList) > 0) {
                        $this->load->view('cms/addToMultipleGroupsLayer',array('secName' => 'syllabus'));
                } ?>
                    </div>
                    <?php } ?>
                </div>

                <div style="width:120px;margin-left: 160px;top:13px;float:left;position: relative;"><input type="checkbox" value="on" id="updatedOn_syllabus"> Update Date</div>
            </li>
        </ul>
    </div>
</div>
