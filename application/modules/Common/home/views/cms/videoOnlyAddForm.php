 <ul>
                             <li>
                                 <label>Header *</label>
                                 <div class="left-div">
                                   <input name="Header" value="<?php echo $slotData['header'];?>" class="title-txt-fld required" maxLength="50" minLength="1" />
                                   <div id="Header_error" class="erro-instrct errorMsg"></div>
                                 </div>
                             </li>
                            
                            
                              <li>
                                 <label>Target URL *</label>
                                  <div class="left-div">
                                       <input type="text" value="<?php echo $slotData['targetURL'];?>" class="target-txt-fld required" maxLength="200" minLength="1" name="TargetUrl" id="url">
                                      <div id="TargetUrl_error" class="erro-instrct errorMsg"></div>
                                    </div>
                             </li>
                             
                          </ul>