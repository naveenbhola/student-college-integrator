
<?php

  $this->load->view('Tagging/includes/header', array('title' => 'View Tags Details', 'page' => 'view'));
?>
          <div class='main-wrapper'>
                    <form action='/Tagging/TaggingCMS/editTags' method='post' id='editTagsForm'>
                    <div class="cms-form-wrap cms-accordion-div">
                        <ul>
                                 <li>
                                    <label><span>Is Synonym Seacrh </span> : </label>
                                    <div class="cms-fields">
                                        <div>
                                            <input type="checkbox" autocomplete="off" class="universal-txt-field cms-text-field"  name='is_syn_search' id='is_syn_search' onclick='rebindAutosuggestor();'>
                                          
                                         </div>
                                    </div>                                                                
                                </li>

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
                                <li class='tag_entity' style='display:none;'>
                                    <label><span>Tag Entity </span> : </label>
                                    <div class="cms-fields">
                                        <div id='tag_entity_text' style='padding-top:7px;'>
                                            
                                         </div>
                                    </div>                                                                
                                </li>
                                <li class='tag_parent' style='display:none;'>
                                    <label><span>Tag Parents </span> : </label>
                                    <div class="cms-fields">
                                        <div id='parent_list_view' style='padding-top:7px;'>
                                            
                                         </div>
                                    </div>                                                                
                                </li>
                                <li class='tag_syn' style='display:none;'>
                                    <label><span>Tag Synonyms </span> : </label>
                                    <div class="cms-fields">
                                        <div id='syn_list' style='padding-top:7px;'>
                                            
                                         </div>
                                    </div>                                                                
                                </li>
                                <li class='tag_childs' style='display:none;'>
                                <label><span>Tag child  </span> : </label>
                                <div class="cms-fields">                                    
                                    <div id='child_tags' style='height:auto;max-height:400px;overflow:scroll;display:none;'>
                                  </div>
                                </div>
                              </li>
                              <li class='synonym_of_tag' style='display:none;'>
                                <label><span>Synonym Of Tag  </span> : </label>
                                <div class="cms-fields">                                    
                                    <div id='syn_of_tag' style='padding-top:7px;'>
                                  </div>
                                </div>
                              </li>
                                 
                        </ul>
                    </div>
                </form>
            </div>
                <?php
$this->load->view('Tagging/includes/footer',array('pageNameForSuggestor' => 'viewPage'));
?>