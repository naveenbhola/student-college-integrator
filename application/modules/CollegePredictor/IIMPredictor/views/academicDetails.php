<?php
  $experienceYears = range('0', '20');
  $experienceMonths = range('0', '11');
?>
<div class="prsnl-dtls initial_hide" id="iimpredictorInputStep2" name="AcademicDetails" >
     <section class="prsnl-cont clearfix">
         <article class="prsnl-box">
             <div class="dtls">
                <div class="error_col">
                     <h3 class="fnt14 clr1 f600">Why do your academics & work-ex matter ?</h3>
                     <p class="fnt12 clr3">- For  WAT/PI call, most IIMs give high weightage to your 10th 12th and graduation marks</p>
                     <p class="fnt12 clr3">- Some of the IIMs give up to 5% extra marks to applicants with work-ex</p>
                     <p class="fnt12 clr3">- We will normalize your marks based on your board</p>
                  </div>

                <div class="academic-dtls">
                     <div class="academic-col">
                       <form class="academic-form" name="form">
                        <ul class="aa">
                            <li>
                                   <div class="drp-col">   
                                        <div class="custom-drp">
                                           <!-- <p class="custm-srch" id="xthboardholder"><?php if(!empty($userData['xthBoard'])){ echo $board[$userData['xthBoard']]; }else{ echo 'Class X Board'; } ?><i class="pointerDown"></i></p> -->
                                          <select class="custm-srch" id="predictor_xthboard" default="X board" mandatory="1" fieldtype="select" name="xthBoard">
                                            <option value="" >Select Class X Board </option>
                                             <?php foreach($board as $key=>$val):?>   
                                                  <option value="<?php echo $key; ?>" <?php if($key == $userData['xthBoard']) { echo "selected";} ?>><?php echo $val; ?></option>
                                              <?php endforeach;?>
                                          </select>

                                           <div class="errorMessage" id="predictor_xthboard_error"> </div>
                                            <p class="clr"></p>
                                        </div>
                                       
                                         <div class="text-hide  id="xthboard_action" style="display:block";>
                                              <input type="text" name="xthPercentage" maxlimit="100" mandatory="1" default="X percentage" fieldtype="enter" id="predictor_xthPercentage" class="txt-fld" value="<?php if(!empty($userData['xthPercentage'])){ echo $userData['xthPercentage']; } ?>" placeholder="Class X Marks in %" maxlength="5" />
                                               <div class="errorMessage" id="predictor_xthPercentage_error" ></div>
                                         </div>
                                       <p class="clr"></p>
                                         </div>    
                                    <span class="emptySpaceArea empty-space"> </span>     
                            </li>
                            
                            <li>
                                   <div class="drop-col">
                                        <div class="custom-drp">
                                             <!-- <p class="custm-srch" id="xiithboardholder" ><?php if(!empty($userData['xiithBoard'])){ echo $board[$userData['xiithBoard']]; }else{ echo 'Select Class XII Board'; } ?> <i class="pointerDown"></i></p>    -->
                                             <select class="custm-srch" id="predictor_xiithboard" default="XII board" mandatory="1" fieldtype="select" name="xiithBoard" >
                                            <option value="" >Select Class XII Board  </option>
                                              <?php foreach($board as $key=>$val):?>   
                                                  <option value="<?php echo $key; ?>" <?php if($key == $userData['xiithBoard']) { echo "selected";} ?>><?php echo $val; ?></option>
                                                <?php endforeach;?>
                                           </select>
                                             <div class="errorMessage" id="predictor_xiithboard_error"> </div>

                                              <p class="clr"></p>       
                                        </div>
                                
                                    
                                    <p class="clr"></p>
                                 </div>
                                 
                              
                                 <div class="hidden-col" id="xiithboard_action" >
                                   <div class="custom-drp">
                                       <!-- <p class="custom-srch" id="xiithstreamholder" ><?php if(!empty($userData['xiithStream'])){ echo $userData['xiithStream']; }else{ echo 'Select Class XII Stream'; } ?><i class="pointerDown"></i></p> -->
                                       <select class="custm-srch" id="predictor_xiithstream" default="class XII stream" fieldtype="select" mandatory="1" name="xiithStream" >
                                            <option value="" >Select Class XII Stream </option>
                                          <option value="Arts" <?php if($userData['xiithStream'] == 'Arts') { echo "selected";} ?>>Arts </option>
                                          <option value="Commerce" <?php if($userData['xiithStream'] == 'Commerce') { echo "selected";} ?>>Commerce </option>
                                          <option value="Science" <?php if($userData['xiithStream'] == 'Science') { echo "selected";} ?>>Science </option>
                                       </select>
                                       <div class="errorMessage" id="predictor_xiithstream_error"> </div>

                                      <p class="clr"></p>	
                                    </div>
                                     <div class="text-hide" id="xiithstream_action" style="display:block">
                                            <input type="text" name="xiithPercentage" maxlimit="100" mandatory="1" id="predictor_xiithPercentage" default="XII Percentage" fieldtype="enter" value="<?php if(!empty($userData['xiithPercentage'])){ echo $userData['xiithPercentage']; } ?>" class="txt-fld" placeholder="Class XII Marks in %" maxlength="5" />
                                            <div class="errorMessage" id="predictor_xiithPercentage_error" > </div>
                                       </div>
                                        <p class="clr"></p>	
                                 </div>
                              
                                  <span class="emptySpaceArea empty-space" > </span>
                            </li>
                            
                            <li>
                                <div class="drop-col">
                                  <div class="custom-drp">
                                      <select class="custm-srch" id="predictor_gradstream" default="graduation discipline" fieldtype="select" mandatory="1" name="graduationStream">
                                            <option value="" >Select Under Graduate Discipline </option>
                                         <?php foreach($gradstream as $key=>$val):?>   
                                                  <option value="<?php echo $key; ?>" <?php if($key == $userData['graduationStream']) { echo "selected";} ?>><?php echo $val; ?></option>
                                        <?php endforeach;?>
                                      </select>
                                       <div class="errorMessage" id="predictor_gradstream_error" > </div>

                                      <p class="clr"></p>
                                  </div>
                                   <p class="clr"></p>
                               </div>  
                                
                                <div class="hidden-col" id="gradstream_action">
                                   <div class="custom-drp ">
                                    <select class="custm-srch" id="predictor_gradYear" default="graduation year" fieldtype="select" name="graduationYear" mandatory="1">
                                            <option value="" >Select Year of Graduation </option>
                                      <?php foreach($gradYear as $key=>$val):?>   
                                                  <option value="<?php echo $val; ?>" <?php if($val == $userData['graduationYear']) { echo "selected";} ?>><?php echo $val; ?></option>
                                        <?php endforeach;?>

                                    </select>
                                    <div class="errorMessage" id="predictor_gradYear_error"></div>

                                    <p class="clr"></p>	
                                </div>
                                <!--marks %ile-->
                                 <div class="gpa-div" id="gradYear_action" style="display:block"> 
                                             <input type="text" name="graduationPercentage" id="predictor_gradPercentage" maxlimit="100" mandatory="1" value="<?php if(!empty($userData['graduationPercentage'])){ echo $userData['graduationPercentage']; } ?>" default="graduation percentage" fieldtype="enter" class="txt-fld" placeholder="Marks in %" maxlength="5" />
                                              <div class="errorMessage" id="predictor_gradPercentage_error"> </div>
                                             <p class="c">Confused about what to enter?<a href="javascript:void(0);" class="clck-gpa"  id="openHelpText">Click here </a></p>
                                             <span id="gpaConversion"><em>Does your college provide CGPA/Grades? Click here to </em>
                                             <a href="javascript:void(0);" id="markCoverter" class="clck-gpa">Convert your CGPA/Grades to percentege</a></span>
                                 
                                      <!-- <p class="clr"></p> -->
                                </div>
                                <!--entere gpa col-->
                                <div class="errorMessage" style="display:none" id="predictor_markCGPA_error">Looks like you have entered your CGPA/grades instead of percentage.</div>

                                   <div class="gpa-col" id="marksConversion">
                                                <h3>Enter your CGPA/Grades</h3>
                                                 <i id="convertorcross" class="gpa-col-cros"></i>
                                                 <input type="text" id="yourGPA" default="values" fieldtype="enter correct" mandatory="0"  placeholder="Your CGPA/Grade"  maxlength="5"  class="gpa-txt-fld">
                                                 <b class="lft-divider">/</b>
                                                 <input type="text" name="gpa" id="MaxGPA" default="values" fieldtype="enter correct" mandatory="0"  placeholder="Max CGPA/Grade"  maxlength="5" class="gpa-txt-fld">
                                                 
                                                 <input type="button" name="next" value="Convert To Percentage" class="gp-btn nxt-btn button button--orange" id="convertorSubmit">
                                                 <div class="empty-space">
                                                   <div class="errorMessage" id="predictor_yourGPA_error"></div>
                                                 </div>
                                     
                                    </div>
                                <!--work experience-->
                                   <div class="work-exp-col initial_hide" id="workExpDiv" <?php if(!empty($userData['graduationYear'])) { echo 'style="display:block"'; } ?>>
                                       <h3>Work experience details</h3>
                                        <div class="custom-drp">
                                           <select class="custm-srch" id="predictor_expYear" default="total years" fieldtype="select" mandatory="1" name="expYear">
                                            <?php foreach($experienceYears as $key=>$val):?>   
                                                  <option value="<?php echo $key; ?>" <?php if($userData['yearEx'] == $key) { echo 'selected'; } ?>><?php echo $val; ?> <?php if($key == 1){ echo 'Year';}else{ echo 'Years';} ?></option>
                                            <?php endforeach;?>

                                           </select>
                                        </div>
                                    
                                        <div class="custom-drp rlt">
                                              <select class="custm-srch" id="predictor_expMonth" default="months" fieldtype="select" mandatory="1" name="expMonth">
                                            <?php foreach($experienceMonths as $key=>$val):?>   
                                                  <option value="<?php echo $key; ?>" <?php if($userData['monthEx'] == $key) { echo 'selected'; } ?>><?php echo $val; ?> <?php if($key == 1){ echo 'Month';}else{ echo 'Months';} ?></option>
                                            <?php endforeach;?>
                                                  
                                           </select>

                                        </div>
                                    <p class="clr"></p>
                                   </div> 
                                 <p class="clr"></p>
                               </div>
                                
                            </li>
                        </ul>
                      </form>
                      </div>
                      <div class="prsn-nxt">
                         <input type="button" class="btn-bck button button--secondary" value="Previous" id="step2Back" ga-attr="ACADEMIC_DETAILS_PREV"/>
                         <input type="button" class="secondary button button--orange" value="Next" id="step2Button" ga-attr="ACADEMIC_DETAILS_CONTINUE"/>
                          <p class="clr"></p>
                     </div>
              </div>
                
             </div>
         </article>
     </section>
  </div>
