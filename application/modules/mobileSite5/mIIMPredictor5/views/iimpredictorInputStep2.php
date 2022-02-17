<div data-role="content" data-enhance="false" id="iimpredictorInputStep2" name="AcademicDetails" class="initial_hide" >

<div class="txt-layer initial_hide" id="helpText">
  <div class="txt-col" style="max-height: 448px;overflow-y: scroll;overflow-x: hidden;width: 90%;">
  <a href="javascript:void(0);">UG Marks Guidelines<span id="txt-crs" data-rel='back'><i class="txt-crs"></i></span></a>
    <ol  class="a">
      <li>Percentage of marks of an applicant who is yet to complete bachelor’s degree will be computed based on his/her latest available marks (for example – till 3rd year or 6th/7th semester)</li>
      <li>The percentage obtained by the candidate in the bachelor’s degree would be based on the practice followed by the university/institution from where the candidate has obtained the degree. In case of the candidates being awarded grades/CGPA instead of marks, the equivalence would be based on the equivalence certified by applicant’s university/institution. In case the university/institution does not have any scheme for converting CGPA into equivalent marks, applicants can get equivalent percentage by dividing obtained CGPA with the maximum possible CGPA and multiplying the resultant with 100.</li>
    </ol>
  </div>
</div>

<div class="drp-dwn-layer" id="xthboard">
  <div class="drp-cont">
   <ul class="drpdwn-layer-ul">
    
	<?php foreach($board as $key=>$val):?>   
    <li><a class="xthboard" href="javascript:void(0);" id="<?php echo 'predictor_xthboard_'.$key;?>"><?php echo $val;?></a></li>    
    <?php endforeach;?>
   </ul>
   </div>
</div>

<div class="drp-dwn-layer" id="xiithboard">
  <div class="drp-cont">
   <ul class="drpdwn-layer-ul">
	<?php foreach($board as $key=>$val):?>   
    <li><a class="xiithboard" href="javascript:void(0);" id="<?php echo 'predictor_xiithboard_'.$key;?>"><?php echo $val;?></a></li>    
    <?php endforeach;?>
   </ul>
   </div>
</div>

<div class="drp-dwn-layer" id="gradstream">
  <div class="drp-cont">
   <ul class="drpdwn-layer-ul">
  <?php foreach($gradstream as $key=>$val):?>   
    <li><a class="graduationStream" href="javascript:void(0);" id="<?php echo 'predictor_gradstream_'.$key;?>"><?php echo $val;?></a></li>    
    <?php endforeach;?>
   </ul>
   </div>
</div>


<div class="drp-dwn-layer" id="gradYear">
  <div class="drp-cont">
   <ul class="drpdwn-layer-ul">
  <?php foreach($gradYear as $key=>$val):?>   
    <li><a class="gradYear" href="javascript:void(0);" id="<?php echo 'predictor_gradYear_'.$val;?>"><?php echo $val;?></a></li>    
    <?php endforeach;?>
   </ul>
   </div>
</div>
<?php
  $experienceYears = range('0', '20');
  $experienceMonths = range('0', '11');
?>
<div class="drp-dwn-layer" id="expYear">
  <div class="drp-cont">
   <ul class="drpdwn-layer-ul">
  <?php foreach($experienceYears as $key=>$val):?>   
    <li><a class="expYear" href="javascript:void(0);" id="<?php echo 'predictor_expYear_'.$val;?>"><?php echo $val;?> <?php if($val == 1){ echo 'Year'; }else{ echo 'Years'; } ?></a></li>    
    <?php endforeach;?>
   </ul>
   </div>
</div>

<div class="drp-dwn-layer" id="expMonth">
  <div class="drp-cont">
   <ul class="drpdwn-layer-ul">
  <?php foreach($experienceMonths as $key=>$val):?>   
    <li><a class="expMonth" href="javascript:void(0);" id="<?php echo 'predictor_expMonth_'.$val;?>"><?php echo $val;?> <?php if($val == 1){ echo 'Month'; }else{ echo 'Months'; } ?> </a></li>    
    <?php endforeach;?>
   </ul>
   </div>
</div>

<div class="drp-dwn-layer" id="xiithstream">
  <div class="drp-cont">
   <ul class="drpdwn-layer-ul">
      <li><a class="xiithstream" href="javascript:void(0);" id="predictor_xiithstream_Arts"  >Arts</a></li>    
      <li><a class="xiithstream" href="javascript:void(0);" id="predictor_xiithstream_Commerce" >Commerce</a></li>    
      <li><a class="xiithstream" href="javascript:void(0);" id="predictor_xiithstream_Science" >Science</a></li>    
   </ul>
 </div>
</div>
  
  <div class="prsnl-dtls">
   <section class="prsnl-cont clearfix">
       <article class="prsnl-box">
       <div class="error_col">
                    <h3 class="fnt14 clr1 f600">Why do your academics & work- ex matter?</h3>
                    <p class="fnt12 clr3">- For WAT/PI call, most IIMs give high weightage to your 10th 12th and graduation marks</p>
<p class="fnt12 clr3">- Some of the IIMs give up to 5% extra marks to applicants with work-ex</p>
<p class="fnt12 clr3">- We will normalize your marks based on your board</p>
                    <!-- <p class="fnt12 clr3">We will normalize your marks based on your board</p> -->
                </div>
           <div class="dtls ac">
                <!-- <span class="ph">
                 <h3>Academic Details</h3>	
                 <p>Did you know: IIM Indore gives 76% importance to X and XII marks, only 20% to CAT and 4% to gender diversity.</p>
              </span> -->
              
              <div class="academic-dtls">
                <!--form class="academic-form" name="form" href="#" /-->
                  <ul class="aa">
                      <li>
                           <div class="custom-drp">
                                <p class="custm-srch" id="xthboardholder"><?php if(!empty($userData['xthBoard'])){ echo $board[$userData['xthBoard']]; }else{ echo 'Select Class X Board'; } ?><i class="pointerDown"></i></p>
                                <div class="errorMessage" id="predictor_xthboard_error">
                                </div>
                            </div> <!-- harshit -->
                            <div  class="<?php if(empty($userData['xthPercentage']) && empty($userData['xthBoard'])){ echo 'initial_hide"';}?>" id="xthboard_action" >
                               <input type="number" step="any" name="xthPercentage" maxlimit="100" mandatory="1" default="X percentage" fieldtype="enter" id="predictor_xthPercentage" class="txt-fld" value="<?php if(!empty($userData['xthPercentage'])){ echo $userData['xthPercentage']; } ?>" placeholder="Class X Marks in %" maxlength="5"/>
                               <div class="errorMessage" id="predictor_xthPercentage_error" >
                                </div>
                            </div>
                           
                            <span class="emptySpaceArea <?php if(!empty($userData['xthPercentage'])){ echo 'empty-space'; } ?>" </span>
                      </li>
                      
                      <li>
                              <div class="custom-drp">
                                   <p class="custm-srch" id="xiithboardholder" ><?php if(!empty($userData['xiithBoard'])){ echo $board[$userData['xiithBoard']]; }else{ echo 'Select Class XII Board'; } ?> <i class="pointerDown"></i></p>   
                                   <div class="errorMessage" id="predictor_xiithboard_error">
                                </div>
                              </div>
                           <span>   
                          <div class="custom-drp1 <?php if(empty($userData['xiithStream'])){ echo 'initial_hide"';}?>" id="xiithboard_action">
                              <p class="custm-srch" id="xiithstreamholder" ><?php if(!empty($userData['xiithStream'])){ echo $userData['xiithStream']; }else{ echo 'Select Class XII Stream'; } ?><i class="pointerDown"></i></p>
                              <div class="errorMessage" id="predictor_xiithstream_error">
                                </div>
                          </div>
                          
                           <div class="<?php if(empty($userData['xiithPercentage']) && empty($userData['xiithStream'])){ echo 'initial_hide"';}?>" id="xiithstream_action">
                             <input type="number" step="any" name="xiithPercentage" maxlimit="100" mandatory="1" id="predictor_xiithPercentage" default="XII Percentage" fieldtype="enter" value="<?php if(!empty($userData['xiithPercentage'])){ echo $userData['xiithPercentage']; } ?>" class="txt-fld" placeholder="Class XII Marks in %" maxlength="5"/>
                             <div class="errorMessage" id="predictor_xiithPercentage_error" >
                              </div>
                            </div>
                          </span>
                          <span class="emptySpaceArea <?php if(!empty($userData['xiithPercentage'])){ echo 'empty-space'; }?>" > </span>
                      </li>
                      
                      <li>
                           <div class="custom-drp">
                                <p class="custm-srch" id="gradstreamholder"><?php if(!empty($userData['graduationStream'])){ echo $gradstream[$userData['graduationStream']]; }else{ echo 'Select Under Graduate Discipline'; } ?><i class="pointerDown"></i></p>
                                <div class="errorMessage" id="predictor_gradstream_error" >
                              </div>
                          </div>
                          <div class="custom-drp1 <?php if(empty($userData['xiithPercentage'])){ echo 'initial_hide"';}?>" id="gradstream_action" >
                              <p class="custm-srch" id="gradYearholder" ><?php if(!empty($userData['graduationYear'])){ echo $userData['graduationYear']; }else{ echo 'Select Year of Graduation'; } ?><i class="pointerDown"></i></p>
                              <div class="errorMessage" id="predictor_gradYear_error">
                              </div>
                          </div>
                          <div class="gpa-div <?php if(empty($userData['graduationPercentage']) && empty($userData['graduationYear'])){ echo 'initial_hide"';}?>" id="gradYear_action"> 
                           <input type="number" step="any" name="graduationPercentage" id="predictor_gradPercentage" maxlimit="100" mandatory="1" value="<?php if(!empty($userData['graduationPercentage'])){ echo $userData['graduationPercentage']; } ?>" default="graduation percentage" fieldtype="enter" class="txt-fld" placeholder="Marks in %" maxlength="5" />
                            <div class="errorMessage" id="predictor_gradPercentage_error">
                              </div>
                           <p class="c">Confused about what to enter?<a data-inline="true" data-rel="dialog" data-transition="slide" href="javascript:void(0);" class="ui-link clck-gpa"  id="openHelpText" >Tap here </a></p>
                           <em id="gpaConversion" >Does your college provide CGPA/Grades? Tap here to <a href="javascript:void(0);" class="clck-gpa" id="markCoverter">Convert your CGPA/Grades to percentage</a></em>
                           
                           <div class="errorMessage" style="display:none" id="predictor_markCGPA_error">Looks like you have entered your CGPA/grades instead of percentage.</div>
                            <div class="gpa-col" id="marksConversion">
                              <a href="javascript:void(0);" class="gpa-cls">
                                 <i id="convertorcross" class="gpa-col-cros"></i>
                                  <p class="clr"></p>
                              </a>
                              <h3>Enter your CGPA/Grades</h3>
                               <input type="number" step="any" id="yourGPA" placeholder="Your CGPA/Grade" mandatory="0" class="gpa-txt-fld" maxlength="5">
                               <b>/</b>
                               <input type="number" step="any" id="MaxGPA" default="values" fieldtype="enter correct" mandatory="0"  placeholder="Max CGPA/Grade" class="gpa-txt-fld" maxlength="5">
                               <div class="errorMessage" id="predictor_yourGPA_error">
                              </div>
                              <div class="gpa-nxt">
                               <input type="submit" name="next" value="Convert To Percentage" class="button button--orange" id="convertorSubmit">
                             </div>
                            </div>
                            
                            
                            <div class="work-exp-col" id="workExpDiv">
                              <h3>Work experience details</h3>
                                  <div class="custom-drp">
                                    <p class="custm-srch" id="expYearholder"><?php if(!empty($userData['yearEx'])){ echo $userData['yearEx']; }else{ echo '0'; } ?> Years<i class="pointerDown"></i></p>
                                  </div>
                              
                                  <div class="custom-drp">
                                      <p class="custm-srch" id="expMonthholder"><?php if(!empty($userData['monthEx'])){ echo $userData['monthEx']; }else{ echo '0'; } ?> Months<i class="pointerDown"></i></p>
                                  </div>
                              
                             </div> 
                             	
                          </div>
                      </li>
                  </ul>
                <!--/form-->
                  <input type="hidden" id="predictor_xthboard" default="X board" mandatory="1" fieldtype="select" value="<?php if(!empty($userData['xthBoard'])){ echo $userData['xthBoard']; } ?>" name="xthBoard" />
                  <input type="hidden" id="predictor_xiithboard" default="XII board" mandatory="1" fieldtype="select" value="<?php if(!empty($userData['xiithBoard'])){ echo $userData['xiithBoard']; } ?>" name="xiithBoard" />
                  <input type="hidden" id="predictor_xiithstream" default="class XII stream" fieldtype="select" mandatory="1" value="<?php if(!empty($userData['xiithStream'])){ echo $userData['xiithStream']; } ?>" name="xiithStream" />
                  <input type="hidden" id="predictor_gradstream" default="graduation discipline" value="<?php if(!empty($userData['graduationStream'])){ echo $userData['graduationStream']; } ?>" fieldtype="select" mandatory="1" name="graduationStream" />
                  <input type="hidden" id="predictor_gradYear" default="graduation year" value="<?php if(!empty($userData['graduationYear'])){ echo $userData['graduationYear']; } ?>" fieldtype="select" name="graduationYear" mandatory="1" />
                  <input type="hidden" id="predictor_expYear" default="total years" value="<?php if(!empty($userData['yearEx'])){ echo $userData['yearEx']; } else { echo '0'; } ?>" fieldtype="select" mandatory="1" name="expYear" />
                  <input type="hidden" id="predictor_expMonth" default="months" value="<?php if(!empty($userData['monthEx'])){ echo $userData['monthEx']; } else { echo '0'; } ?>" fieldtype="select" mandatory="1" name="expMonth" />
              </div>
              <!-- <section class="prsn-nxt">
                   <input type="submit" name="next" ga-attr="ACADEMIC_DETAILS_CONTINUE" value="Continue" class="nxt-btn" id="step2Button" >
                 </section> -->
                 <div class="clear_s">
                    <a class="button button--secondary" ga-attr="ACADEMIC_DETAILS_PREVIOUS" id="step2PrevButton" href="javascript:void(0);">Previous</a>
                    <a class="predict button button--orange" ga-attr="ACADEMIC_DETAILS_CONTINUE" id="step2Button" href="javascript:void(0);">Next</a>
                 </div>
           </div>
       </article>
   </section>
  </div>  
</div>
