<script>var noOfLocations ='<?php echo count($locations); ?>';</script>
<div id="location_main_div">

   <?php for($i=1; $i<=10;$i++):?>
   <?php
      $j = $i-1;
      $icity_id=isset($locations[$j]['city_id']) ? $locations[$j]['city_id'] : "";
      $icountry_id=isset($locations[$j]['country_id']) ? $locations[$j]['country_id'] : "";
      $icity_name=isset($locations[$j]['city_name']) ? $locations[$j]['city_name'] : "";
      $icountry_name=isset($locations[$j]['country_name']) ? $locations[$j]['country_name'] : "";
      $iaddress=isset($locations[$j]['address']) ? $locations[$j]['address'] : "";
   ?>
   <div id="location_indi<?php echo $i;?>" <?php  ($icity_id != "")?$str="":$str="style=\"display:none\""; echo $str; ?> >
      <div class="lineSpace_13">&nbsp;</div>
      <div class="row">
         <div>
            <div class="r1 bld">Country:<span class="redcolor">*</span></div>
            <div class="r2">
                <select <?php $icountry_id!=''?$str="name=\"i_country_id[]\"":$str=""; echo $str; ?> id="c_country<?php echo $i;?>" onChange="getCitiesForCountryListAnother(<?php echo $i;?>);" style="width:100px" >
                  <?php
                     foreach($country_list as $country) :
                     $countryIdSelect = $country['countryID'];
                     $countryNameSelect = $country['countryName'];
                     if($countryIdSelect == 1) { continue; }
                     /*$selected = "";
                     if($countryId == 2) { $selected = "selected='selected'"; } */
                     if ($icountry_id == $countryIdSelect)
                     {
                        $selected = "selected='selected'";
                     }
                     else
                     {
                        $selected = "";
                     }

                  ?>
                  <option value="<?php echo $countryIdSelect; ?>" <?php echo $selected; ?>><?php echo $countryNameSelect; ?></option>
                  <?php endforeach; ?>
               </select>
            </div>
            <div class="clear_L"></div>
         </div>		
      </div>
      <div class="lineSpace_13">&nbsp;</div>
      <div class="row">
         <div>
            <div>
               <div class="r1 bld">City:<span class="redcolor">*</span></div>
               <div class="r2">
                  <select <?php $icity_id!=''?$str="name=\"i_city_id[]\"":$str=""; echo $str; ?> id="c_cities<?php echo $i;?>" onChange="showOtherOptions(this);" style="width:125px" >
                     <?php
                        if($icity_id != "")
                        {
                           echo '<option value="'.$icity_id.'">'.$icity_name.'</option>';
                        }
                     ?>
                  </select>
                  <?php
                     if($icity_id != "")
                     {
                        echo '<a href="javascript:void(0);" onClick="getCitiesForCountryListAnother('.$i.');showOtherOptions(document.getElementById(\'c_cities'.$i.'\'));" style="cursor:pointer;">Change This City</a>';
                     }
                  ?>

               </select>
               <input type="text" validate="validateStr" maxlength="25" name="cities<?php echo $i;?>_other" id="c_cities<?php echo $i;?>_other" value="" style="display:none" />
            </div>
            <div class="clear_L"></div>
         </div>
         <div class="row errorPlace">
             <div class="r1">&nbsp;</div>
             <div class="r2 errorMsg"  id="c_cities<?php echo $i;?>_other_error"></div> 
             <div class="clear_L"></div>
         </div>
         <div class="row errorPlace">
             <div class="r1">&nbsp;</div>
             <div class="r2 errorMsg"  id="c_cities<?php echo $i;?>_error"></div>
             <div class="clear_L"></div>
         </div>

      </div>
   </div>
   <div class="lineSpace_13">&nbsp;</div>

   <div class="row">
      <div>
         <div>
            <div class="r1 bld">Address:</div>
            <div class="r2">
                <textarea <?php echo $icity_id!=''?$str="name=\"address[]\"":$str=""; echo $str; ?> id="address<?php echo $i;?>" style="height:30px;" class="w62_per" ><?php echo $iaddress; ?></textarea>
               <?php if ($i!=1): ?>
               <a onclick="removeLocation(<?php echo $i; ?>);" href="javascript:void(0);" > Remove</a>
               <?php endif;?>
            </div>
            <div class="clear_L"></div>
         </div>
         <div class="row errorPlace">
            <div class="r1">&nbsp;</div>
            <div class="r2 errorMsg"  id="address<?php echo $i;?>_error"></div> 
            <div class="clear_L"></div>
         </div>
      </div>
   </div>
   <div class="lineSpace_13">&nbsp;</div>
</div>
<?php endfor; ?>
        </div>

        <div class="row" id="another_location">
           <div>
              <div>
                 <div class="r1 bld">&nbsp;</div>
                 <div class="r2">
                    <a onclick="addLocation();" href="javascript:void(0);" >Add Another</a>
                 </div>
                 <div class="clear_L"></div>
              </div>
              <div class="row errorPlace">
                 <div class="r1">&nbsp;</div>
                 <div class="r2 errorMsg"  id="another_location_error"></div> 
                 <div class="clear_L"></div>
              </div>
           </div>
        </div>
        <div class="lineSpace_20">&nbsp;</div>	
