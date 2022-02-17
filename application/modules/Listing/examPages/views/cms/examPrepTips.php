<?php $preptipsUpdatedOn = '';
    if(!empty($preptips['preptipswikidata1']['updatedOn']))
        $preptipsUpdatedOn = $preptips['preptipswikidata1']['updatedOn'];   
    if(!empty($preptips['preptipswikidata2']['updatedOn']))
    {
        $preptipsUpdatedOn = $preptips['preptipswikidata2']['updatedOn'];
    }
    if(!empty($preptips['files'][0]['updatedOn']))
    {
        $preptipsUpdatedOn = $preptips['files'][0]['updatedOn'];
    }
?>
<div class="clear-width dragable-block" id="exam_preptips_section">
    <select id="preptips_sort" class="cms-dropdown sort" name="position" onchange="changePosition(<?=$sectionOrder['preptips'];?>,'preptips_sort','section')">
        <?php for($i =2 ; $i <= $numberOfSections ; $i++) {
                $value = '';
            if($sectionOrder['preptips'] == $i) {
                $value = 'selected';
                }
            ?>
            <option value="<?=$i;?>" <?=$value;?>><?=$i;?></option>
        <?php } ?>
    </select>
    <h3 class="section-title" style="cursor:pointer;"><i class="abroad-cms-sprite <?=$headingImageClass?>"></i>Prep  Tips</h3>
    <div id="preptips_<?=$formName?>" class="cms-form-wrap cms-accordion-div" style="<?=$sectionDisplayStyle?>">
        <ul>
             <li>
                <label id="preptipswikidata1">Prep Tips : <p class="collapse-state"></p></label>
                <div class="cms-fields preptips-div">
                    <textarea id="preptipswiki1" name="preptipspagedata[]" class="cms-textarea tinymce-textarea" caption="Sample Papers" validationtype="html"><?=$preptips['preptipswikidata1']['wikiData'];?></textarea>
                    <div id="samplepapers_error" class="errorMsg" style="display:none;"></div>
                    <?php if($actionType == 'edit'){ ?>
                    <div class="belowEdit-btnWrapper">
                        <button onclick="saveWikiData('preptipswiki1','0','preptips','preptipswikidata1');return false;" class="button button--secondary">Save</button>
                    </div>
                    <?php } ?>
                </div>
            </li>
            <li>
                <label class="cmsFont"> Upload </label> 
                 <div class="cms-fields application-div"> 
                     <input name="preptips" type="file" id="preptips" onchange="uploadForm(preptips,'preptipslist')"> 
                     <div aria-valuenow="100" class="progress-bar progress-bar-danger" data-transitiongoal="25" style="display: none;">100</div>
                     <div class="col-md-3" class="viewform" style="display: none" id="viewform">
                             <a class="btn cmsButton cmsFont" target="_blank" href="">View uploaded file</a>
                    </div>
                    <div class="col-md-2 hide" id="cross">
                             <a class="btn btn-default" target="_blank" onclick="clearBrochureData(preptips)">X</a>
                    </div>
                    <input type="hidden" id="appformid" value="">
                 </div>
            </li>
            <li id="preptipslist">
            </li>
            <li>
                <label id="preptipswikidata2">Prep Tips : <p class="collapse-state"></p></label>
                <div class="cms-fields preptips-div">
                    <textarea id="preptipswiki2" name="preptipspagedata[]" class="cms-textarea tinymce-textarea" caption="Prep Tips" validationtype="html"><?=$preptips['preptipswikidata2']['wikiData'];?></textarea>
                    <div id="preptips_error" class="errorMsg" style="display:none;"></div>
                    <input type="hidden" id="prevUpdOn_preptips" value="<?=$preptipsUpdatedOn;?>">
                    <?php if($actionType == 'edit'){ ?>
                    <div class="belowEdit-btnWrapper">
                        <button onclick="saveWikiData('preptipswikidata2','1','preptips','preptipswikidata2');return false;" class="button button--secondary">Save</button>
                    <?php if($actionType == 'edit' && $status = 'live' && count($groupsLiveList) > 0) {
                            $this->load->view('cms/addToMultipleGroupsLayer',array('secName' => 'preptips'));
                        } ?>
                    </div>
                    <?php } ?>
                </div>

                 <div style="width:120px;margin-left: 160px;top:13px;float:left;position: relative;"><input type="checkbox" value="on" id="updatedOn_preptips"> Update Date</div>
            </li>
        </ul>
    </div>
</div>
<script type="text/javascript">
    uploadedPrepTipFiles = [];
</script>
<?php foreach ($preptips['files'] as $sKey => $sValue) { ?>
        <script>uploadedPrepTipFiles[<?=$sKey?>] = {'file_url' : '<?php echo $sValue['file_url'];?>','thumbnail_url' : '<?php echo $sValue['thumbnail_url'];?>','file_name' : '<?php echo $sValue['file_name'];?>','file_relative_url' : '<?php echo $sValue['file_relative_url'];?>','position':<?=$sValue['position']?>};
        //updateSortOptions('preptipslist');
        </script>
<?php } ?>

<script>
if(uploadedPrepTipFiles.length>0){
    updateSortOptions('preptipslist');
}
</script>
