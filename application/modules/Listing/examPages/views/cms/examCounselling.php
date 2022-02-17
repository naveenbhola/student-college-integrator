<div class="clear-width dragable-block" id="exam_counselling_section">
    <select id="counselling_sort" class="cms-dropdown sort" name="position" onchange="changePosition(<?=$sectionOrder['counselling'];?>,'counselling_sort','section')">
        <?php for($i =2 ; $i <= $numberOfSections ; $i++) {
                $value = '';
            if($sectionOrder['counselling'] == $i) {
                $value = 'selected';
                }
            ?>
            <option value="<?=$i;?>" <?=$value;?>><?=$i;?></option>
        <?php } ?>
    </select>
    <h3 class="section-title" style="cursor:pointer;"><i class="abroad-cms-sprite <?=$headingImageClass?>"></i>Counselling</h3>
    <div id="counselling_<?=$formName?>" class="cms-form-wrap cms-accordion-div" style="<?=$sectionDisplayStyle?>">
        <ul>
            <li>
                <label>Counselling : <p class="collapse-state"></p></label>
                <div class="cms-fields counselling-div">
                    <textarea id="counselling" name="counsellingpagedata" class="cms-textarea tinymce-textarea" caption="Counselling" validationtype="html"><?=$counselling['counselling']['wikiData'];?></textarea>
                    <div id="counselling_error" class="errorMsg" style="display:none;"></div>
                    <input type="hidden" id="prevUpdOn_counselling" value="<?php echo $counselling['counselling']['updatedOn'];?>">
                    <?php if($actionType == 'edit'){ ?>
                    <div class="belowEdit-btnWrapper">
                        <button onclick="saveWikiData('counselling','0','counselling','');return false;" class="button button--secondary">Save</button>
                    <?php if($actionType == 'edit' && $status = 'live' && count($groupsLiveList) > 0) {
                        $this->load->view('cms/addToMultipleGroupsLayer',array('secName' => 'counselling'));
                    } ?>                        
                    </div>
                    <?php } ?>
                </div>
            <div style="width:120px;margin-left: 245px;top: 30px;position: relative;"><input type="checkbox" value="on" id="updatedOn_counselling"> Update Date</div>                
            </li>
        </ul>
    </div>
</div>
