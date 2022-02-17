<div class="clear-width dragable-block" id="exam_admitcard_section">
    <select id="admitcard_sort" class="cms-dropdown sort" name="position" onchange="changePosition(<?=$sectionOrder['admitcard'];?>,'admitcard_sort','section')">
        <?php for($i =2 ; $i <= $numberOfSections ; $i++) {
                $value = '';
            if($sectionOrder['admitcard'] == $i) {
                $value = 'selected';
                }
            ?>
            <option value="<?=$i;?>" <?=$value;?>><?=$i;?></option>
        <?php } ?>
    </select>
    <h3 class="section-title" style="cursor:pointer;"><i class="abroad-cms-sprite <?=$headingImageClass?>"></i>Admit Card</h3>
    <div id="admitcard_<?=$formName?>" class="cms-form-wrap cms-accordion-div" style="<?=$sectionDisplayStyle?>">
        <ul>
            <li>
                <label>Admit Card : <p class="collapse-state"></p></label>
                <div class="cms-fields admitcard-div">
                    <textarea id="admitcard" name="admitcardpagedata" class="cms-textarea tinymce-textarea" caption="Admit Card" validationtype="html"><?=$admitcard['admitcard']['wikiData'];?></textarea>
                    <div id="admitcard_error" class="errorMsg" style="display:none;"></div>
                    <input type="hidden" id="prevUpdOn_admitcard" value="<?php echo $admitcard['admitcard']['updatedOn'];?>">
                    <?php if($actionType == 'edit'){ ?>
                    <div class="belowEdit-btnWrapper">
                        <button onclick="saveWikiData('admitcard','0','admitcard','');return false;" class="button button--secondary">Save</button>
                        <?php if($actionType == 'edit' && $status = 'live' && count($groupsLiveList) > 0) {
                                    $this->load->view('cms/addToMultipleGroupsLayer',array('secName' => 'admitcard'));
                            } ?>                        
                    </div>
                    <?php } ?>
                </div>
                <div style="width:120px;margin-left: 160px;top:13px;float:left;position: relative;"><input type="checkbox" value="on" id="updatedOn_admitcard"> Update Date</div>
            </li>
        </ul>
    </div>
</div>
