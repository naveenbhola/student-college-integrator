
<?php

  $this->load->view('Tagging/includes/header', array('title' => 'Add Tags', 'page' => 'add'));
  $isPostRequest = false;
  if($pendingMappingTableId > 0){
    $isPostRequest = true;
  }
?>
          <div class='main-wrapper'>
<form action="/Tagging/TaggingCMS/addTagsToRedis"  method="POST" id="tag_form">

                    <div class="cms-form-wrap cms-accordion-div">
                        <ul>
                        
                        <li>
                                <label>Tag Name* : </label>
                                <div class="cms-fields">
                                    <input id="tagName" name="tagName" class="universal-txt-field cms-text-field" type="text" style="98% !important;" caption="Tag Name" onblur="showTagsVarientsOnBlur();" required="true" maxlength="100" value='<?php echo $tagName;?>'/>
                                    <div id="tagName_error" class="errorMsg" style="display: none;"></div>
                                </div>
                            </li>
                            <li>
                                <label>Tag Entity* : </label>
                                <div class="cms-fields">
                                    <select id="tagEntity" name="tagEntity" class="universal-select cms-field" caption="Exam Name" required="true" onchange="showTagsVarients();">
                                        <option value="">Select an Tag Entity</option>
                                        <?php
                                            foreach ($entities as $data) {
                                                ?>
                                                    <option value="<?=$data['tag_entity'];?>"><?=$data['tag_entity'];?></option>              
                                                <?php
                                            }
                                        ?>
                                    </select>
                                    <div id="tagEntity_error" class="errorMsg" style="display:none;"></div>
                                </div>
                            </li>
                            <li>
                                <label><span>Tag Description</span> : </label>
                                <div class="cms-fields">
                                    <input id="tagDescription" name="tagDescription" class="universal-txt-field cms-text-field" type="text" style="98% !important;" validationtype="str" caption="Tag Description" maxlength="100"/>
                                    <div id="tagDescription" class="errorMsg" style="display: none;"></div>
                                </div>
                            </li>
                            <li><label><span><a href='javascript:void(0)' onclick="checkUncheckAll('varients_tags')">Check / Uncheck all Varients</a></span></label>
                                <div class="cms-fields">
                                  <!--a href='javascript:void(0)' onclick='showTagsVarients()'>Show Auto Generated Varient Tags</a-->
                                  <div id='varients_tags' style='height:auto;max-height:400px;overflow:scroll;display:none;'>

                                  </div>
                                </div>
                              </li>

                             
                            <li>
                                <label><span>Add Tag Parent</span> : </label>
                                <div class="cms-fields tag_parent">
                                    <div>
                                        <input type="text" autocomplete="off" class="tags universal-txt-field cms-text-field"   style="color:#565656;width:275px;" name='tag_parent[]'>
                                        <input type='hidden' name='tag_parent_id[]'>
                                         &nbsp;&nbsp;&nbsp; <a href='javascript:void(0);' onclick="add_more('parent','addPage')">Add More</a>
                                         <div class="empty-message"></div>
                                     </div>

                                </div>    
                                                            
                            </li>

                              <li>
                                <label><span>Add Tag Synonym</span> : </label>
                                <div class="cms-fields tag_synonym">
                                    <input type="text" autocomplete="off" class="synonym universal-txt-field cms-text-field"   style="color:#565656;width:275px;" name='tag_synonym[]' maxlength='100'> &nbsp;&nbsp;&nbsp; <a href='javascript:void(0);' onclick="add_more('synonym','addPage')" >Add More</a>
                                </div> 
                                <div id='synonym_error' class="cms-fields errorMsg"></div>                               
                            </li>

                           <div class='mappingsData' id="mappingsData">
                                
                           </div>
                             <li>
                                
                                <div class="cms-fields" style='display:inline;'>
                                    <a class="orange-btn" id='submitBtn' onclick="findxyz();" href="javaScript:void(0);">Submit</a>
                                </div> 
                                <span id="loadingText"> Working, Please wait..</span>
                                
                            </li>
                        </ul>
                        <input type='hidden' id='pendingMappingTableId' name='pendingMappingTableId' value='<?php echo $pendingMappingTableId;?>'>
                        <input type='hidden' id='shikshaEntityAdding' name='shikshaEntityAdding' value='<?php echo $shikshaEntity;?>'>
                        <input type='hidden' id='shikshaEntityAddingId' name='shikshaEntityAddingId' value='<?php echo $shikshaEntityId;?>'>
            </form>
        </div>
<?php
$this->load->view('Tagging/includes/footer',array('pageNameForSuggestor' => 'addPage'));
?>
<script type="text/javascript">
    initializeAddTagsForm();
    function initializeAddTagsForm(){
        $j("#tagEntity").val('<?php echo $tagEntity;?>').trigger('change');
        $selectedEntity = "<?php echo $selectedData['selectedEntity_1']."";?>";
        $selectedEntityId = "<?php echo $selectedData['selectedEntityId_1']."";?>";
        getMappings(0,$selectedEntity,$selectedEntityId);

    }
</script>
<?php
if(isset($msg) && trim($msg)!=""){

    if($msg == "addedToRedis"){
        echo "<script>alert('Tag Added to Redis.');</script>";    
    }

    
}
?>