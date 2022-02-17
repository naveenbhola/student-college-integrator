<div class="clear-width dragable-block" id="exam_callletter_section">
    <select id="callletter_sort" class="cms-dropdown sort" name="position" onchange="changePosition(<?=$sectionOrder['callletter'];?>,'callletter_sort','section')">
        <?php for($i =2 ; $i <= $numberOfSections ; $i++) {
                $value = '';
            if($sectionOrder['callletter'] == $i) {
                $value = 'selected';
                }
            ?>
            <option value="<?=$i;?>" <?=$value;?>><?=$i;?></option>
        <?php } ?>
    </select>
    <h3 class="section-title" style="cursor:pointer;"><i class="abroad-cms-sprite <?=$headingImageClass?>"></i>Call Letter</h3>
    <div id="callletter_<?=$formName?>" class="cms-form-wrap cms-accordion-div" style="<?=$sectionDisplayStyle?>">
        <ul>
            <li>
                <label>Call Letter : <p class="collapse-state"></p></label>
                <div class="cms-fields callletter-div">
                    <textarea id="callletter" name="callletterpagedata" class="cms-textarea tinymce-textarea" caption="callletter" validationtype="html"><?=$callletter['callletter']['wikiData'];?></textarea>
                    <div id="callletter_error" class="errorMsg" style="display:none;"></div>
                    <input type="hidden" id="prevUpdOn_callletter" value="<?php echo $callletter['callletter']['updatedOn'];?>">
                    <?php if($actionType == 'edit'){ ?>
                    <div class="belowEdit-btnWrapper">
                        <button onclick="saveWikiData('callletter','0','callletter','');return false;" class="button button--secondary">Save</button>
                    <?php if($actionType == 'edit' && $status = 'live' && count($groupsLiveList) > 0) {
                        $this->load->view('cms/addToMultipleGroupsLayer',array('secName' => 'callletter'));
                        } ?>                        
                    </div>
                    <?php } ?>                    
                </div>

                <div style="width:120px;margin-left: 160px;top:13px;float:left;position: relative;"><input type="checkbox" value="on" id="updatedOn_callletter"> Update Date</div>
            </li>
        </ul>
    </div>
</div>


