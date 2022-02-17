<div id="predictor-layer" style="display:none">
   <div class="custom-dropdown mrg-btm-10">
      <select class="clist">
         <option value="">Select Exam</option>
         <?php 
            foreach ($predictorData as $predictor) {
               if($pageName == 'Admission')
               {
                  $gaAttr=" ga-attr = 'VIEW_".htmlentities($predictor['name'])."_PREDICTOR'";
               }
         ?>
            <option value='<?php echo $predictor['url']?>' <?php echo $gaAttr;?>><?php echo htmlentities($predictor['name']);?></option>
         <?php
            }
         ?>
      </select>
   </div>
   <div class="predictorLayerError" style="display:none;">
      <div class="regErrorMsg">Please select exam</div>
   </div>
   <div class="algn-rt">
      <input type="button" value="Submit" class="button button--orange submitPredictor">
   </div>
</div>
  
