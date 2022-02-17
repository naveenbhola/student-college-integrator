<div class="clear-width dragable-block" id="exam_cutoff_section">
    <select id="cutoff_sort" class="cms-dropdown sort" name="position" onchange="changePosition(<?=$sectionOrder['cutoff'];?>,'cutoff_sort','section')">
        <?php for($i =2 ; $i <= $numberOfSections ; $i++) {
                $value = '';
            if($sectionOrder['cutoff'] == $i) {
                $value = 'selected';
                }
            ?>
            <option value="<?=$i;?>" <?=$value;?>><?=$i;?></option>
        <?php } ?>
    </select>
    <h3 class="section-title" style="cursor:pointer;"><i class="abroad-cms-sprite <?=$headingImageClass?>"></i>Cut-Off</h3>
    <div id="cutoff_<?=$formName?>" class="cms-form-wrap cms-accordion-div" style="<?=$sectionDisplayStyle?>">
        <ul>
            <li>
                <label>Cut-Off : <p class="collapse-state"></p></label>
                <div class="cms-fields cutoff-div">
                    <textarea id="cutoff" name="cutoffpagedata" class="cms-textarea tinymce-textarea" caption="Cut-Off" validationtype="html"><?=$cutoff['cutoff']['wikiData'];?></textarea>
                    <div id="cutoff_error" class="errorMsg" style="display:none;"></div>
                    <input type="hidden" id="prevUpdOn_cutoff" value="<?php echo $cutoff['cutoff']['updatedOn'];?>">
                    <?php if($actionType == 'edit'){ ?>
                    <div class="belowEdit-btnWrapper">
                        <button onclick="saveWikiData('cutoff','0','cutoff','');return false;" class="button button--secondary">Save</button>
                        <?php if($actionType == 'edit' && $status = 'live' && count($groupsLiveList) > 0) {
                                    $this->load->view('cms/addToMultipleGroupsLayer',array('secName' => 'cutoff'));
                            } ?>                        
                    </div>
                    <?php } ?>
                </div>
            <div style="width:120px;margin-left: 245px;top: 30px;position: relative;"><input type="checkbox" value="on" id="updatedOn_cutoff"> Update Date</div>                
            </li>
        </ul>
    </div>
</div>
