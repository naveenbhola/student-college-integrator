<div class="clear-width dragable-block" id="exam_applicationform_section">
    <select id="applicationform_sort" class="cms-dropdown sort" name="position" onchange="changePosition(<?=$sectionOrder['applicationform'];?>,'applicationform_sort','section')">
        <?php for($i =2 ; $i <= $numberOfSections ; $i++) {
                $value = '';
            if($sectionOrder['applicationform'] == $i) {
                $value = 'selected';
                }
            ?>
            <option value="<?=$i;?>" <?=$value;?>><?=$i;?></option>
        <?php } ?>
    </select>
    <h3 class="section-title" style="cursor:pointer;"><i class="abroad-cms-sprite <?=$headingImageClass?>"></i>Application Form</h3>
    <div id="applicationform_<?=$formName?>" class="cms-form-wrap cms-accordion-div" style="<?=$sectionDisplayStyle?>">
        <ul>
            <li>
                <label class="cmsFont">Upload </label> 
                 <div class="cms-fields application-div"> 
                     <input name="applicationFile" type="file" id="applicationFile" onchange="uploadForm(applicationFile)"> 
                     <div aria-valuenow="100" class="progress-bar progress-bar-danger" data-transitiongoal="25" style="display: none;">100</div>
                     <?php 
                     $style = 'none';
                     if(count($applicationform['files']) > 0)
                     {
                        $style = 'block';
                     }
                     ?>
                     <div class="cms-div" style="display: <?=$style;?>;">
                         <input id="application_name" type= "text" value="<?=!empty($applicationform['files']['file_name']) ? $applicationform['files']['file_name'] : '';?>" disabled class="cms-input">
                         <span class="cms-inline" class="viewform" id="viewform">
                                 <a class="btn cmsButton cmsFont" target="_blank" href="<?=!empty($applicationform['files']['file_url']) ? $applicationform['files']['file_url'] : ''?>">View uploaded form</a>
                        </span>
                        <span class="cms-inline" id="cross">
                                 <a class="btn btn-default" target="_blank" onclick="clearFileData(applicationFile)">X</a>
                        </span>
                    </div>
                 </div>
            </li>
            <li>    
                <label>URL of Form</label>
                <div class="cms-fields application-div">
                    <input name="name" id="appformurl" type="text" class="form-control ng-pristine ng-invalid ng-touched"  onkeyup="showErrorMessage(this, 'addExamPage');" validationType="reglink" caption="link" value="<?=$applicationform['applicationformurl']['wikiData'];?>">
                    <div id="appformurl_error" class="errorMsg" style="display:none;"></div>
                </div>
            </li>
            <li>
                <label>Application Form :</label>
                <div class="cms-fields application-div">
                    <textarea id="application" name="applicationformpagedata" class="cms-textarea tinymce-textarea" caption="Application Form" validationtype="html"><?=$applicationform['applicationform']['wikiData'];?></textarea>
                    <div id="application_error" class="errorMsg" style="display:none;"></div>
                    <input type="hidden" id="prevUpdOn_applicationform" value="<?php echo ($applicationform['applicationform']['updatedOn']) ? $applicationform['applicationform']['updatedOn'] : $applicationform['applicationformurl']['updatedOn'];?>">
                    <?php if($actionType == 'edit'){ ?>
                    <div class="belowEdit-btnWrapper">
                        <button onclick="saveWikiData('application','0','applicationform','');return false;" class="button button--secondary">Save</button>
                    <?php if($actionType == 'edit' && $status = 'live' && count($groupsLiveList) > 0) {
                        $this->load->view('cms/addToMultipleGroupsLayer',array('secName' => 'applicationform'));
                        } ?>                        
                    </div>
                    <?php } ?>
                </div>

             <div style="width:120px;margin-left: 160px;top:13px;float:left;position: relative;"><input type="checkbox" value="on" id="updatedOn_applicationform"> Update Date</div>                
            </li>
        </ul>
    </div>
</div>
<script type="text/javascript">
    uploadedAppFiles = {};
</script>
<?php 
    if(count($applicationform['files'] > 0))
    {?>
<script>uploadedAppFiles = {'file_url' : '<?php echo $applicationform['files']['file_url'];?>','file_name' : '<?php echo $applicationform['files']['file_name'];?>','file_relative_url' : '<?php echo $applicationform['files']['file_relative_url'];?>'};</script>
       
<?php 
    }
 ?>
