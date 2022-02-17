<?php if($slotData['targetURL']) {
        $checked = 'checked=checked';
} ?>
<ul>
                             <li>
                                 <label>Header: </label>
                                 <div class="left-div">
                                   <input name="Header" value="<?php echo $slotData['header'];?>" maxLength="50" class="title-txt-fld" />
                                   <div class="erro-instrct"></div>
                                 </div>
                             </li>
                             <li>
                                 <label>Sub-header: *</label>
                                 <div class="left-div">
                                   <input maxLength="105"  minLength="1" name="Subheader" value="<?php echo $slotData['subHeader'];?>" class="title-txt-fld  required" value="<?php echo $slotData['name']; ?>" />
                                   <div id="Subheader_error" class="erro-instrct errorMsg"></div>
                                 </div>
                             </li>
                             <li>
                                 <label>Description: *</label>
                                 <div class="left-div">
                                  <textarea class="testmonials required" maxLength="175"  minLength="1" name="Description"><?php echo $slotData['description'];?></textarea>
                                  <input type="checkbox" id="readMore" name="readMore" <?=$checked?> /> Add read more link
                                   <div id="Description_error" class="erro-instrct errorMsg"></div>
                                 </div>
                             </li>
                              <li>
                                 <label>Target URL: </label>
                                  <div class="left-div">
                                       <input type="text" maxLength="200" value="<?php echo $slotData['targetURL'];?>" class="target-txt-fld" id="url" name="TargetUrl">
                                      <div id="url_error" class="erro-instrct errorMsg"></div>
                                    </div>
                             </li>
                             <li>
                                 <label>Image: *</label>
                                  <div class="left-div">
                                       <?php if(!empty($slotData['imgURL'])) {
                                        $display = 'display:none;'; ?>
                                        <img id="bannerImage" width="250" height="288" border="0" src="<?php echo MEDIA_SERVER.$slotData['imgURL']; ?>"/>
                                        <a id="removeBannerImage" onclick="removeBannerImage();" href="javascript:void(0);" >Remove</a>
                                      <?php } ?>
                                      <input style="<?php echo $display; ?>" type="file" id="inputBannerImage" name="bannerImage[]" value="" />
                                      <p style="<?php echo $display; ?>" id="bannerImageHelpText" class="erro-instrct">Accepted Size: 250px X 288px</p>
                                      <div id="bannerImage_error" class="erro-instrct errorMsg"></div>
                                    </div>
                             </li>
                            
                          </ul>

                          


