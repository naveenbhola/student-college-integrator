
<?php

  $this->load->view('Tagging/includes/header', array('title' => 'Delete Tags', 'page' => 'delete'));
  $isPostRequest = false;
  if($pendingMappingTableId > 0){
    $isPostRequest = true;
  }
?>
<div class='main-wrapper'>
                    <form action='/Tagging/TaggingCMS/deleteTags' method='post' id='deleteTagsForm'>
                    <div class="cms-form-wrap cms-accordion-div">
                        <ul>
                            <li>
                                <label><span>Tag Name </span> : </label>
                                <div class="cms-fields tag_parent">
                                    <div>
                                        <input type="text" autocomplete="off" class="tags universal-txt-field cms-text-field"   style="color:#565656;width:275px;" name='tag_name' id='tag_name'>
                                        <div class="empty-message"></div>
                                        <input type='hidden' name='tag_id' id='tag_id'>
                                     </div>

                                </div>    
                                                            
                            </li>

                              <li>
                                <label><span>Tag child  </span> : </label>
                                <div class="cms-fields">                                    
                                    <div id='child_tags' style='height:auto;max-height:400px;overflow:scroll;display:none;'>
                                  </div>
                                </div>
                              </li>
                              <div  class='action_on_childs'>
                              <li>
                                <label><span>Action on childs  </span> : </label>
                                <div class="cms-fields">                                    
                                    <select name='action' id='action' onchange='onActionChange(this);' class="universal-select cms-field">
                                        <option value='delete'> Delete </option>
                                        <option value='reassign'> Re-assign Parent </option>
                                    </select>
                                </div>

                              </li>

                              <li id='reassign_parent_field'>
                                <label><span>New Parent Tag Name </span> : </label>
                                <div class="cms-fields tag_parent">
                                    <div>
                                        <input type="text" autocomplete="off" class="tags universal-txt-field cms-text-field"   style="color:#565656;width:275px;" name='tag_new_parent_name' id='tag_new_parent_name'>
                                        <div class="empty-message"></div>
                                        <input type='hidden' name='reassign_tag_id' id='reassign_tag_id'>
                                     </div>

                                </div>    
                                                            
                            </li>
                              </div>

                
                          <li class='impact_delete' id='no_tags_impact' style='display:none;'>
                                <label><span>Total Questions / Discussions with no Tags  </span> : </label>
                                <div class="cms-fields" style='line-height:25px;font-weight:bold;'>                                    
                                    
                                </div>
                          </li>
                          <li class='impact_delete' id='total_impact_count' style='display:none;'>
                                <label><span>Total Questions / Discussions Effected  </span> : </label>
                                <div class="cms-fields" style='line-height:25px;font-weight:bold;'>                                    
                                    
                                </div>
                          </li>
                            
                            <li>
                                <br />
                                <div class="cms-fields">
                                    <span id='impact_loader' class='errorMsg' style='display:none;'>Loading...</span>
                                    <a class="orange-btn" onclick="showImpactOnDelete();" href="JavaScript:void(0);">Show Impact</a>

                                    <a class="orange-btn" onclick="submitTagsData();" href="JavaScript:void(0);">Submit</a>

                                </div> 
                                
                            </li>



            
                        </ul>
                    </div>
                    <input type='hidden' name='pendingMappingTableId' id='pendingMappingTableId' value='<?=$pendingMappingTableId;?>'>
                </form>
                <form action='/Tagging/TaggingCMS/showDetailedImpactOnDelete' method='post' target='_blank' id='impact_form'>
                    <input type='hidden' name='impact_tag_id' id='impact_tag_id'>
                    <input type='hidden' name='impact_childs_id' id='impact_childs_id'>
                    <input type='hidden' name='type' id='type'>

                </form>
  
</div>
<?php
$this->load->view('Tagging/includes/footer',array('pageNameForSuggestor' => 'deletePage'));
?>
<?php
if(isset($successMessage) && $successMessage){
    echo "<script>alert('Deleted Success')</script>";
}
?>
<script type="text/javascript">
    initializeDeletePage();
    function initializeDeletePage(){
        
        <?php
            if($isPostRequest){
                ?>
                    $j("#tag_name").val("<?php echo $tagName;?>");
                    $j("#tag_id").val("<?php echo $tagId;?>");
                    findChildTags(<?php echo $tagId;?>,'deletePage');
                <?php
            }
        ?>

    }
</script>