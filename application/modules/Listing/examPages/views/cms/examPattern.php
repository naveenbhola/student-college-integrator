<div class="clear-width dragable-block" id="exam_pattern_section">
    <select id="pattern_sort" class="cms-dropdown sort" name="position" onchange="changePosition(<?=$sectionOrder['pattern'];?>,'pattern_sort','section')">
        <?php for($i =2 ; $i <= $numberOfSections ; $i++) {
                $value = '';
            if($sectionOrder['pattern'] == $i) {
                $value = 'selected';
                }
            ?>
            <option value="<?=$i;?>" <?=$value;?>><?=$i;?></option>
        <?php } ?>
    </select>
    <h3 class="section-title" style="cursor:pointer;"><i class="abroad-cms-sprite <?=$headingImageClass?>"></i>Pattern</h3>
    <div id="pattern_<?=$formName?>" class="cms-form-wrap cms-accordion-div" style="<?=$sectionDisplayStyle?>">
        <ul>
            <li>
                <label>Pattern : <p class="collapse-state"></p></label>
                <div class="cms-fields pattern-div">
                    <textarea id="pattern" name="patternpagedata" class="cms-textarea tinymce-textarea" caption="Pattern" validationtype="html"><?=$pattern['pattern']['wikiData'];?></textarea>
                    <div id="pattern_error" class="errorMsg" style="display:none;"></div>
                    <input type="hidden" id="prevUpdOn_pattern" value="<?php echo $pattern['pattern']['updatedOn'];?>">
                    <?php if($actionType == 'edit'){ ?>
                    <div class="belowEdit-btnWrapper">
                        <button onclick="saveWikiData('pattern','0','pattern','');return false;" class="button button--secondary">Save</button>
                        <?php if($actionType == 'edit' && $status = 'live' && count($groupsLiveList) > 0) {
                                $this->load->view('cms/addToMultipleGroupsLayer',array('secName' => 'pattern'));
                        } ?>                        
                    </div>
                    <?php } ?>
                </div>

                <div style="width:120px;margin-left: 160px;top:13px;float:left;position: relative;"><input type="checkbox" value="on" id="updatedOn_pattern"> Update Date</div>
            </li>
        </ul>
    </div>
</div>


