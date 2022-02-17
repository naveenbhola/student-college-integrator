                          <ul>
                             <li>
                                 <label>Header *</label>
                                 <div class="left-div">
                                   <input name="Header" value="<?php echo $slotData['header'];?>" class="title-txt-fld required" maxLength="80" minLength="1"/>
                                   <div class="erro-instrct errorMsg" id="Header_error"></div>
                                 </div>
                             </li>
                            
                            
                             <li>
                                 <label>Image *</label>
                                  <div class="left-div">
                                      <?php if(!empty($slotData['imgURL'])) {
                                        $display = 'display:none;'; ?>
                                        <img id="bannerImage" width="512" height="288" border="0" src="<?php echo MEDIA_SERVER.$slotData['imgURL']; ?>"/>
                                        <a id="removeBannerImage" onclick="removeBannerImage();" href="javascript:void(0);" >Remove</a>
                                      <?php } ?>
                                      <input style="<?php echo $display; ?>" type="file" id="inputBannerImage" name="bannerImage[]" value="" />
                                      <p style="<?php echo $display; ?>" id="bannerImageHelpText" class="erro-instrct">Accepted Size: 512px X 288px</p>
                                      <div id="bannerImage_error" class="erro-instrct errorMsg"></div>
                                    </div>
                             </li>
                              <li>
                                 <label>Target URL </label>
                                  <div class="left-div">
                                       <input type="text" maxLength="200" value="<?php echo $slotData['targetURL'];?>" class="target-txt-fld" id="url" name="TargetUrl">
                                    </div>
                             </li>
                             
                          </ul>