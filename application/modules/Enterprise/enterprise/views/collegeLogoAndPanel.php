   <div id="instiLogo" style="display:none;" class="row">				
         <div class="r1 bld">Logo of the Institute:&nbsp;</div>
         <div class="r2">
            <?php 
               if($institute_logo != NULL){
                  echo '<img id="insti_logo_fetched" width="119" height="78" border="0" src="'.$institute_logo.'"/>
                  <div id="logo_anchor"> <a onclick="removeInstiLogo('.$institute_id.');" href="javascript:void(0);" > Remove</a> '.$logo_name.' </div>
                  <input type="text" name="c_insti_logo_caption[]" id="c_insti_logo_caption" size="14" style="display:none;"/>&nbsp;&nbsp;<input type="file" name="i_insti_logo[]" id="c_institute_logo" value="" size="4" style="display:none; onChange="uploadInstiLogo('.$institute_id.',this);" "/> ';
               }else{
                   echo '<input type="text" name="c_insti_logo_caption[]" id="c_insti_logo_caption" size="14" style=""/>&nbsp;&nbsp;<input type="file" name="i_insti_logo[]" id="c_institute_logo" value="" size="4" style=""/>';
               }
            ?>

         </div>
         <div class="clear_L"></div>
   </div>
   <div class="lineSpace_11">&nbsp;</div>

   <div id="instiPanel" style="display:none;" class="row">				
          <div class="r1 bld">Featured panel:&nbsp;</div>
         <div class="r2">
            <?php 
               if($featured_panel != NULL){
                   echo '<img id="insti_panel_fetched" width="119" height="78" border="0" src="'.$featured_panel.'"/>
                   <div id="panel_anchor"><a onclick="removeFeaturedPanelLogo('.$institute_id.');" href="javascript:void(0);" > Remove</a> '.$featured_panel_name.'</div>
                   <input type="text" name="c_feat_panel_caption[]" id="c_feat_panel_caption" size="14" style="display:none;"/>&nbsp;&nbsp;<input type="file" name="i_feat_panel[]" id="i_feat_panel" value="" size="4" style="display:none;"/> ';
               }else{
                   echo '<input type="text" name="c_feat_panel_caption[]" id="c_feat_panel_caption" size="14" style=""/>&nbsp;&nbsp;<input type="file" name="i_feat_panel[]" id="i_feat_panel" value="" size="4" style=""/>';
               }
            ?>

         </div>
         <div class="clear_L"></div>
   </div>
   <div class="lineSpace_11">&nbsp;</div>
