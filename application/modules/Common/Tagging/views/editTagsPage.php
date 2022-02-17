
<?php
    $this->load->view('Tagging/includes/header', array('title' => 'Edit Tags', 'page' => 'edit'));
     $isPostRequest = false;
     $actionPostRequest = "";
      if($pendingMappingTableId > 0){
        $isPostRequest = true;
      }
?>
            <div class='main-wrapper'>
            
                    <form action='/Tagging/TaggingCMS/editTags' method='post' id='editTagsForm'>
                    <div class="cms-form-wrap cms-accordion-div">
                        <ul>
                              <li>
                                <label><span>Select Action </span> : </label>
                                <div class="cms-fields">                                    
                                    <select name='action' id='action' onchange='onEditActionChange(this);' class="universal-select cms-field">
                                        <option value='-1'> -Select Action-</option>
                                        <option value='rename_tag'> Rename Tag </option>
                                        <option value='add_synonym'> Add Synonym </option>
                                        <option value='delete_synonym'> Delete Synonym </option>
                                        <option value='add_parent'> Add Parent </option>
                                        <option value='delete_parent'> Delete Parent </option>
                                        <option value='edit_mapping'> Edit Mappings </option>
                                    </select>
                                </div>

                              </li>
                              <div class='edit_action_option' style='display:none;' id='rename_tag'>
                                <li>
                                    <label><span>Old Tag Name </span> : </label>
                                    <div class="cms-fields">
                                        <div>
                                            <input type="text" autocomplete="off" class="tags universal-txt-field cms-text-field"   style="color:#565656;width:275px;" name='old_tag_name' id='old_tag_name'>
                                            <div class="empty-message"></div>
                                            <input type='hidden' name='old_tag_id' id='old_tag_id'>
                                         </div>
                                    </div>                                                                
                                </li>
                                <li>
                                    <label><span>New Tag Name </span> : </label>
                                    <div class="cms-fields">
                                        <div>
                                            <input type="text" autocomplete="off" class="universal-txt-field cms-text-field"   style="color:#565656;width:275px;" name='tagName' id='tagName' onblur="findTagExistence(this.value,'dummy','tagName')">
                                            <div id="tagName_error" class="errorMsg" style="display: none;"></div>
                                         </div>
                                    </div>                                                                
                                </li>
                                <li>
                                    <label><span>Select Varients To Rename </span> : 
                                        <br />
                                        <!--a href='javascript:void(0)' onclick="checkUncheckAll('rename_varients_list')">Check / Uncheck all</a-->
                                    </label>

                                    <div class="cms-fields">
                                        <div id='rename_varients_list'>
                                            
                                         </div>
                                    </div>                                                                
                                </li>
                              </div>
                              <div class='edit_action_option' style='display:none;' id='add_synonym'>
                                <li>
                                    <label><span>Tag Name </span> : </label>
                                    <div class="cms-fields">
                                        <div>
                                            <input type="text" autocomplete="off" class="tags universal-txt-field cms-text-field"   style="color:#565656;width:275px;" name='tag_name' id='tag_name'>
                                            <div class="empty-message"></div>
                                            <input type='hidden' name='tag_id' id='tag_id'>
                                         </div>
                                    </div>                                                                
                                </li>
                                <li>
                                    <label><span> Synonym to be added </span> : </label>
                                    <div class="cms-fields tag_synonym">
                                        <input type="text" autocomplete="off" class="synonym universal-txt-field cms-text-field"   style="color:#565656;width:275px;" name='tag_synonym[]' onblur="findSynExistence(this.value,this)"> &nbsp;&nbsp;&nbsp; <a href='javascript:void(0);' onclick="add_more('synonym','addPage')" >Add More</a>
                                    </div> 
                                    <div id='synonym_error' class="cms-fields errorMsg"></div>                               
                                </li>                               


                              </div>



                              <div class='edit_action_option' style='display:none;' id='delete_synonym'>

                                <li>
                                    <label><span>Tag Name </span> : </label>
                                    <div class="cms-fields">
                                        <div>
                                            <input type="text" autocomplete="off" class="tags universal-txt-field cms-text-field"   style="color:#565656;width:275px;" name='tag_name_del_syn' id='tag_name_del_syn'>
                                            <div class="empty-message"></div>
                                            <input type='hidden' name='tag_id_del_syn' id='tag_id_del_syn'>
                                         </div>
                                    </div>                                                                
                                </li>

                                <li>
                                    <label><span>Select Synonym </span> : 
                                        <br />
                                        <a href='javascript:void(0)' onclick="checkUncheckAll('syn_list')">Check / Uncheck all</a>
                                    </label>

                                    <div class="cms-fields">
                                        <div id='syn_list'>
                                            
                                         </div>
                                    </div>                                                                
                                </li>

                              </div>


                              <div class='edit_action_option' style='display:none;' id='add_parent'>
                                    <li>
                                        <label><span>Tag Name </span> : </label>
                                        <div class="cms-fields">
                                            <div>
                                                <input type="text" autocomplete="off" class="tags universal-txt-field cms-text-field"   style="color:#565656;width:275px;" name='tag_name_add_parent' id='tag_name_add_parent'>
                                                <div class="empty-message"></div>
                                                <input type='hidden' name='tag_id_add_parent' id='tag_id_add_parent'>
                                             </div>
                                                
                                             <div id='parent_list_view'></div>
                                             
                                        </div>                                                                
                                    </li>
                                    <li>
                                        <label><span>Parent Name </span> : </label>
                                        <div class="cms-fields">
                                            <div>
                                                <input type="text" autocomplete="off" class="tags universal-txt-field cms-text-field"   style="color:#565656;width:275px;" name='parent_name_add_parent' id='parent_name_add_parent'>
                                                <div class="empty-message"></div>
                                                <input type='hidden' name='parent_id_add_parent' id='parent_id_add_parent'>
                                                <div id='parent_error' class="errorMsg"></div>                               
                                             </div>
                                        </div>                                                                
                                    </li>
                              </div>


                              <div class='edit_action_option' style='display:none;' id='delete_parent'>
                                <li>
                                    <label><span>Tag Name </span> : </label>
                                    <div class="cms-fields">
                                        <div>
                                            <input type="text" autocomplete="off" class="tags universal-txt-field cms-text-field"   style="color:#565656;width:275px;" name='tag_name_delete_parent' id='tag_name_delete_parent'>
                                            <div class="empty-message"></div>
                                            <input type='hidden' name='tag_id_delete_parent' id='tag_id_delete_parent'>
                                         </div>
                                    </div>                                                                
                                </li>
                                <li>
                                    <label><span>Select Parent </span> : 
                                         <br />
                                        <a href='javascript:void(0)' onclick="checkUncheckAll('parent_list')">Check / Uncheck all</a>
                                    </label>
                                    <div class="cms-fields">
                                        <div id='parent_list'>
                                            
                                         </div>
                                    </div>                                                                
                                </li>

                              </div>

                              <div class='edit_action_option' style='display:none;' id='edit_mapping'>
                                <li>
                                    <label><span>Tag Name </span> : </label>
                                    <div class="cms-fields">
                                        <div>
                                            <input type="text" autocomplete="off" class="tags universal-txt-field cms-text-field"   style="color:#565656;width:275px;" name='tag_name_edit_mapping' id='tag_name_edit_mapping'>
                                            <span class="errorMsg" id="tag_name_edit_mapping_error"></span>
                                            <input type='hidden' name='tag_id_edit_mapping' id='tag_id_edit_mapping'>
                                         </div>
                                    </div>                                                                
                                </li>
                                  <div style='margin-top:20px' id='mappingsData'>
                                  </div>
                                    
                              </div>

                              <li>                                
                                <div class="cms-fields" style="display:inline;margin-top:20px;">
                                    <a class="orange-btn" onclick="submitEditTagsData();"  id='submitBtn' href="JavaScript:void(0);">Submit</a>
                                </div>      
                                 <span id="loadingText"> Working, Please wait..</span>                           
                            </li>

                        </ul>
                    </div>
                    <input type="hidden" name="pendingMappingTableId" id="pendingMappingTableId" value="<?=$pendingMappingTableId;?>" >
                </form>
            </div>
<?php
$this->load->view('Tagging/includes/footer',array('pageNameForSuggestor' => 'editPage'));
?>
<?php
if(isset($msg) && trim($msg)!=""){

    if($msg == "renamesuccess"){
        echo "<script>alert('Rename Success');</script>";    
    } else if($msg == "addsynsuccess"){
        echo "<script>alert('Synonyms added Successfully');</script>";    
    } else if($msg == "delsynsuccess"){
        echo "<script>alert('Synonyms deleted Successfully');</script>";    
    } else if($msg == "addparentsuccess"){
        echo "<script>alert('Parent added Successfully');</script>";    
    } 
    else if($msg == "deleteparentsuccess"){
        echo "<script>alert('Parent(mapping) deleted Successfully');</script>";    
    } 

    
}
?>
<script type="text/javascript">
    initializeEditPage();
    function initializeEditPage(){
        
        <?php
            if($isPostRequest){
                ?>
                    $j("#action").val("<?php echo $action;?>");
                    <?php
                    if($action == "rename_tag"){
                        ?>
                        $j("#old_tag_name").val("<?php echo $oldTagName;?>");
                        $j("#old_tag_id").val("<?php echo $tagId;?>");
                        $j("#tagName").val("<?php echo $expectedNewTagName;?>").trigger('blur');
                        findAllVarientsTags(<?php echo $tagId?>);
                        <?php
                    }else{
                        ?>
                        $j("#tag_name_edit_mapping").val("<?php echo $oldTagName;?>");
                        $j("#tag_id_edit_mapping").val("<?php echo $tagId;?>");
                        getMappings(<?php echo $tagId;?>,0,0,'<?php echo $newMappingEntity?>','<?php echo $newMappingEntityId?>');
                        <?php
                    }
                    ?>
                    
                <?php
            }
        ?>
        $j("#action").trigger('change');

    }
</script>