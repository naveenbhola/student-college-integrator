<input type="hidden" name="updateCategories" value="<?php echo $editCategFormOpen; ?>" />
<div class="row">
   <div class="mar_left_5p"><span class="normaltxt_11p_blk fontSize_13p OrgangeFont bld">Edit University/ Institute Details</span></div>
   <div class="lineSpace_5">&nbsp;</div>
   <div class="grayLine"></div>							
</div>
<div class="lineSpace_25">&nbsp;</div>
<div class="row">	
   <div style="display: inline; float:left; width:100%">
      <div class="r1 bld">&nbsp;</div>
      <div class="r2">All field marked with <span class="redcolor">*</span> are compulsory to fill in</div>
   </div>
</div>
<div class="lineSpace_25">&nbsp;</div>
<div class="row">
   <div class="row">	
      <div>
         <div>
            <div class="r1 bld">University/ Institute Name:&nbsp;</div>
            <div class="r2">
                <input type="text" name="c_institute_name" id="c_institute_name" validate="validateStr" required="true" maxlength="100" minlength="10" class="w62_per" tip="college_name" caption="University/College Name" value="<?php echo $title; ?>"/>
            </div>
            <div class="clear_L"></div>
         </div>
         <div class="row errorPlace pd_top_1">
            <div class="r1">&nbsp;</div>
            <div class="r2 errorMsg" id="c_institute_name_error"/></div>
            <div class="clear_L"></div>
         </div>
      </div>			
   </div>
   <div class="lineSpace_11">&nbsp;</div>
   
   <?php $this->load->view('enterprise/collegeLogoAndPanel'); ?>

   <div class="row">	
      <div>
         <div>
            <div class="r1 bld">University Description:&nbsp;</div>
            <div class="r2">
               <!-- <textarea name="c_institute_desc" id="c_institute_desc" validate="validateStr" maxlength="5000" class="w62_per mceEditor" style="height:130px;" caption="Description" ><?php echo $short_desc ?></textarea> -->
               <textarea name="c_institute_desc" id="c_institute_desc" maxlength="5000" class="w62_per mceEditor" style="height:130px;" validate="validateStr" caption="Description" ><?php echo $short_desc ?></textarea>
            </div>
            <div class="clear_L"></div>
         </div>
         <div class="row errorPlace pd_top_1">
            <div class="r1">&nbsp;</div>
            <div class="r2 errorMsg" id="c_institute_desc_error"/></div>
            <div class="clear_L"></div>
         </div>
      </div>			
   </div>
   <div class="lineSpace_11">&nbsp;</div>

<?php if($editCategFormOpen == 'Yes'){ ?>
<div class="row">
   <div>
      <div>
         <div class="r1 bld">Category:<span class="redcolor">*</span>&nbsp;</div>
         <div class="r2">
            <div id="c_categories_combo"></div>
            <script>
               var selectCatArr = new Array();
               <?php
                  $i=0;
                  foreach($categoryArr as $existingCateg)
                  {?>
                  selectCatArr[<?php echo $i;?>]="<?php echo $existingCateg["category_id"];?>";
                  <?php
                     $i++;
                  } 
               ?>
            </script>
         </div>
         <div class="clear_L"></div>
      </div>
      <div class="row errorPlace">
         <div class="r1">&nbsp;</div>
         <div class="r2 errorMsg" id="c_categories_error" ></div>
         <div class="clear_L"></div>
      </div>
   </div>
</div>
<div class="lineSpace_11">&nbsp;</div>
<?php } ?>

   <div id="location_main">
      <div id="location" >	
         <?php $this->load->view('enterprise/countryCityCourseCMS'); ?>
      </div>
   </div>


   <div class="row">	
      <div>
         <div>
            <div class="r1 bld">Affiliated to:&nbsp;</div>
            <div class="r2">
               <input type="text" name="affiliated_to" id="affiliated_to" style="width:200px" tip="college_affil" validate="validateStr" maxlength="200" minlength="5" caption="Affilated to" value="<?php echo $certification ?>"/>
            </div>
            <div class="clear_L"></div>
         </div>
         <div class="row errorPlace pd_top_1">
            <div class="r1">&nbsp;</div>
            <div class="r2 errorMsg" id="affiliated_to_error"/></div>
            <div class="clear_L"></div>
         </div>				
      </div>			
   </div>
   <div class="lineSpace_11">&nbsp;</div>

   <div class="row">	
      <div>
         <div>
            <div class="r1 bld">Year of Establishment:&nbsp;</div>
            <div class="r2">
               <input type="text" name="i_establish_year" id="i_establish_year" style="width:200px" validate="validateInteger" minlength="4" maxlength="4" tip="college_year" caption="Year Of Establishment" value="<?php echo $establish_year ?>"/>
               <!--<select id="i_establish_year" name="i_establish_year">
                  <option>Decade</option>
                  <option></option>
                  <option></option>
               </select>
               <select>
                  <option>Year</option>
                  <option></option>
                  <option></option>
               </select>-->
            </div>
            <div class="clear_L"></div>
         </div>
         <div class="row errorPlace pd_top_1">
            <div class="r1">&nbsp;</div>
            <div class="r2 errorMsg" id="i_establish_year_error"/></div>
            <div class="clear_L"></div>
         </div>
      </div>
   </div>
   <div class="lineSpace_11">&nbsp;</div>

   <div class="row">	
      <div>
         <div>
            <div class="r1 bld">No. of Students:&nbsp;</div>
            <div class="r2">
               <input type="text" name="i_no_of_students" id="i_no_of_students" style="width:200px" validate="validateInteger" minlength="0" maxlength="6" tip="college_no_stu" value="<?php echo $no_of_students ?>"/>
            </div>
            <div class="clear_L"></div>
         </div>
         <div class="row errorPlace pd_top_1">
            <div class="r1">&nbsp;</div>
            <div class="r2 errorMsg" id="i_no_of_students_error"/></div>
            <div class="clear_L"></div>
         </div>
      </div>			
   </div>
   <div class="lineSpace_11">&nbsp;</div>

   <div class="row">	
      <div>
         <div>
            <div class="r1 bld">No. of International Sutdents:&nbsp;</div>
            <div class="r2">
               <input type="text" name="i_no_of_i_students" id="i_no_of_i_students" style="width:200px" validate="validateInteger" minlength="0" maxlength="6" tip="college_no_i_stu" value="<?php echo $no_of_int_students ?>"/>
            </div>
            <div class="clear_L"></div>
         </div>
         <div class="row errorPlace pd_top_1">
            <div class="r1">&nbsp;</div>
            <div class="r2 errorMsg" id="i_no_of_i_students_error"/></div>
            <div class="clear_L"></div>
         </div>
      </div>			
   </div>
   <div class="lineSpace_20">&nbsp;</div>

</div>
   
<div id="admin_details_main">
   <div id="admin_details" style="<?php echo $style;?>">			
      <div class="row">
          <div class="mar_left_5p"><span class="normaltxt_11p_blk fontSize_13p OrgangeFont bld">Institute Contact Details (Update the institute contact details to receive emails and phone enquiries from the interested candidates.)</span></div>
         <div class="grayLine mar_top_5p"></div>							
      </div>
      <div class="lineSpace_13">&nbsp;</div>

      <div class="row">
         <div>
            <div>
               <div class="r1 bld">Name:&nbsp;</div>
               <div class="r2">
                  <input type="text" name="c_cordinator_name" id="c_cordinator_name"  validate="validateStr" maxlength="100" minlength="0" style="width:200px" caption="Name" value="<?php echo $contact_name;?>"/>
               </div>
               <div class="clear_L"></div>
            </div>
            <div class="row errorPlace">
               <div class="r1">&nbsp;</div>
               <div class="r2 errorMsg" id="c_cordinator_name_error"></div>
               <div class="clear_L"></div>
            </div>
         </div>
      </div>
      <div class="lineSpace_13">&nbsp;</div>

      <div class="row">	
         <div>
            <div>
               <div class="r1 bld">Contact No:&nbsp;</div>
               <div class="r2">
                  <input type="text" name="c_cordinator_no" id="c_cordinator_no" maxlength="100" minlength="5" style="width:200px" caption="Contact Number" value="<?php echo $contact_cell;?>"/>
               </div>
               <div class="clear_L"></div>
            </div>
            <div class="row errorPlace">
               <div class="r1">&nbsp;</div>
               <div class="r2 errorMsg" id="c_cordinator_no_error"></div>
               <div class="clear_L"></div>
            </div>
         </div>
      </div>
      <div class="lineSpace_13">&nbsp;</div>

      <div class="row">
         <div>
            <div>
               <div class="r1 bld">Email:&nbsp;</div>
               <div class="r2">
                  <input type="text" name="c_cordinator_email" id="c_cordinator_email" validate="validateEmail" maxlength="125" minlength="0" style="width:200px" caption="Email" value="<?php echo $contact_email;?>"/>
               </div>
               <div class="clear_L"></div>
            </div>
            <div class="row errorPlace">
               <div class="r1">&nbsp;</div>
               <div class="r2 errorMsg" id="c_cordinator_email_error" ></div>
               <div class="clear_L"></div>
            </div>
         </div>		
      </div>
   <div class="lineSpace_13">&nbsp;</div>

   <div class="row">
      <div>
         <div>
            <div class="r1 bld">Website Address:&nbsp;</div>
            <div class="r2">
                <input type="text" name="c_website" id="c_website" maxlength="1000" minlength="0" style="width:200px" caption="College Website Address" validate="validateUrl" value="<?php echo $url; ?>"/>
            </div>
            <div class="clear_L"></div>
         </div>
         <div class="row errorPlace">
            <div class="r1">&nbsp;</div>
            <div class="r2 errorMsg" id="c_website_error" ></div>
            <div class="clear_L"></div>
         </div>
      </div>
   </div>
   <div class="lineSpace_20">&nbsp;</div>
<?php if ($usergroup == "cms"){ ?>
        <div class="row">
           <div>
              <div>
        	 <div class="r1 bld">Tags For Institute:&nbsp;</div>
        	 <div class="r2">
        	    <input type="text" name="i_tags" id="i_tags" validate="validateStr" maxlength="200" minlength="2" tip="institute_tags" class="w62_per" caption="Institute Tags" value="<?php echo $hiddenTags; ?>"/>
        	 </div>
        	 <div class="clear_L"></div>
              </div>
              <div class="row errorPlace">
        	 <div class="r1">&nbsp;</div>
        	 <div class="r2 errorMsg" id="i_tags_error" ></div>
        	 <div class="clear_L"></div>
              </div>
           </div>
        </div>
        <div class="lineSpace_13">&nbsp;</div>
<?php } ?>
   </div>
</div>
<script>
<?php if($editCategFormOpen == 'Yes'){ ?>
getCategories();
selectMultiComboBox(document.getElementById('c_categories'),selectCatArr);
<?php } ?>
</script>
