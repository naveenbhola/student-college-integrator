<section id="section_<?php echo $fieldId?>" class="workexp-cont clearfix">
           <article class="workexp-box">
               <div class="dtls">
                  
                  <a href="javascript:void(0);" class="cross-sec clearfix"><i class="up-cross deleteWorkEx">&times;</i>
                    <input value ='<?php echo $fieldId;?>' style='display:none' class = 'fieldId'>
                  </a>
                  
                   <ul class="wrkexp-ul">
                     <li>
                        <div class="text-show <?php if(!empty($Employer)){ echo 'filled1'; }?> ">
                          <label class="form-label">Company/Employer Name</label>
                          <input id="employer<?php echo '_'.$fieldId.'_'.$regFormId?>" regFieldId="employer_<?php echo $fieldId;?>"  type="text" maxlength =150 mandatory="1" profanity="1" label="Employer Name"  default="Employer Name" caption="your Employer Name" value ="<?php echo $Employer;?>" name="employer_<?php echo $fieldId;?>" class="user-txt-flds" />
                         </div>

                         <span><div class="regErrorMsg" id="employer<?php  echo '_'.$fieldId.'_error_'.$regFormId;  ?>"></div></span> 
                     </li> 
                     
                     <li>
                        <div class="text-show <?php if(!empty($Designation)){ echo 'filled1'; }?> ">
                          <label class="form-label">Designation</label>
                          <input type="text" regFieldId="designation_<?php echo $fieldId;?>" maxlength =150  mandatory="1" profanity= "1" label="Designation" default="Designation" caption="your Designation" id= "designation<?php echo '_'.$fieldId.'_'.$regFormId?>" label="Designation" value="<?php echo $Designation;?>" name="designation_<?php echo $fieldId;?>"  value ="<?php echo $Designation;?>" name="designation_<?php echo $fieldId;?>" class="user-txt-flds" />
                         </div>

                         <span><div class="regErrorMsg" id="designation<?php echo '_'.$fieldId.'_error_'.$regFormId; ?>"></div></span>
                     </li>  
                     
                      <li>
                         <div class="text-show <?php if(!empty($Department)){ echo 'filled1'; }?> ">
                          <label class="form-label">Function/Department</label>
                          <input type="text" regFieldId=name="department_<?php echo $fieldId;?>"  maxlength =150 profanity= "1" label="Department" default="Department" caption="your Department"  id= "department<?php echo '_'.$fieldId.'_'.$regFormId?>" value="<?php echo $Department;?>" name="department_<?php echo $fieldId;?>" value="<?php echo $Department;?>" name="department_<?php echo $fieldId;?>" class="user-txt-flds" />
                         </div>

                         <span><div class="regErrorMsg" id="department<?php echo '_'.$fieldId.'_error_'.$regFormId; ?>"></div></span>
                     </li> 
                     <li>

                     <div class="text-show">
                          <p class="currently-wrkng">Is this your Current Job?</p>
                              <label id="currentJobLabel_<?php echo $fieldId;?>" class="switch switch-green">
                              <input type="checkbox" class="switch-input" <?php if ($CurrentJob == 'YES'){ echo 'checked="checked"';}?>>
                              <span class="switch-label" data-on="" data-off="" ></span>
                              <span class="switch-handle"></span>
                            </label>
                              <input id="currentJob_<?php echo $fieldId;?>" type="hidden" class="currentJob" value="<?php if ($CurrentJob == 'YES'){ echo 'YES';} else { echo 'NO';}?>" name='currentJob[]' />
                        </div>    
                     </li>
                    
                    <input type="hidden" value="1" name='workExp[]' />

                   </ul>
               </div>
            </article>
       </section>