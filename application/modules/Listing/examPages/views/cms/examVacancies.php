<div class="clear-width dragable-block" id="exam_vacancies_section">
    <select id="vacancies_sort" class="cms-dropdown sort" name="position" onchange="changePosition(<?=$sectionOrder['vacancies'];?>,'vacancies_sort','section')">
        <?php for($i =2 ; $i <= $numberOfSections ; $i++) {
                $value = '';
            if($sectionOrder['vacancies'] == $i) {
                $value = 'selected';
                }
            ?>
            <option value="<?=$i;?>" <?=$value;?>><?=$i;?></option>
        <?php } ?>
    </select>
    <h3 class="section-title" style="cursor:pointer;"><i class="abroad-cms-sprite <?=$headingImageClass?>"></i>Vacancies</h3>
    <div id="vacancies_<?=$formName?>" class="cms-form-wrap cms-accordion-div" style="<?=$sectionDisplayStyle?>">
        <ul>
            <li>
                <label>Vacancies : <p class="collapse-state"></p></label>
                <div class="cms-fields vacancies-div">
                    <textarea id="vacancies" name="vacanciespagedata" class="cms-textarea tinymce-textarea" caption="vacancies" validationtype="html"><?=$vacancies['vacancies']['wikiData'];?></textarea>
                    <div id="vacancies_error" class="errorMsg" style="display:none;"></div>
                    <input type="hidden" id="prevUpdOn_vacancies" value="<?php echo $vacancies['vacancies']['updatedOn'];?>">
                    <?php if($actionType == 'edit'){ ?>
                    <div class="belowEdit-btnWrapper">
                        <button onclick="saveWikiData('vacancies','0','vacancies','');return false;" class="button button--secondary">Save</button>
                    
                <?php if($actionType == 'edit' && $status = 'live' && count($groupsLiveList) > 0) {
                        $this->load->view('cms/addToMultipleGroupsLayer',array('secName' => 'vacancies'));
                } ?>
                    </div>
                    <?php } ?>
                </div>

                <div style="width:120px;margin-left: 160px;top:13px;float:left;position: relative;"><input type="checkbox" value="on" id="updatedOn_vacancies"> Update Date</div>
            </li>
        </ul>
    </div>
</div>


