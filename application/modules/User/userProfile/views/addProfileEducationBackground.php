
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("jquery.tinyscrollbar.min.v3"); ?>"></script>
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("studyAbroadCommon"); ?>"></script>

<?php $regFormId = random_string('alnum', 6); 
$educationLevel = array('xth','xiith','UG','PG','PHD');

$nameInstitute = array('xth'=>'xthSchool','xiith'=>'xiithSchool','UG'=>'bachelorsCollege','PG'=>'mastersCollege','PHD'=>'phdCollege');
$nameDegree = array('UG'=>'bachelorsDegree','PG' =>'mastersDegree', 'PHD'=>'phdDegree');
?>

                       <section id="section_<?php echo $fieldId?>" class="prf-box-grey" >

                          <div class="prft-titl">
                               <div class="caption">
                                  <p>EDUCATION BACKGROUND</p>
                               </div>
                          </div>

                       <!--profile-tab content-->
                       <div  class="frm-bdy">
                           <form  class="prf-form" id="registrationForm_<?=$regFormId?>" >

                        <div id="eduSection">    
                        <?php $count=0;

                        $field =array();

                        foreach ($educationLevel as $level) {
                          if(!empty($$level)){
                            $count++;
                            $field[$nameInstitute[$level]] = 'TextField';

                            if($level != 'xth' && $level != 'xiith'){
                              $field[$nameDegree[$level]] = 'TextField';
                            }
                            
                            $data = $$level;
                            if($level == 'xth'){
                              $data['level'] = 10;
                              $data['levelKey'] = 'xth';
                            }else if($level == 'xiith'){
                              $data['level'] = 12;
                              $data['levelKey'] = 'xiith';
                            } else{
                              $data['level'] = $level;
                            }
                            
                            $data['regFormId'] = $regFormId;

                            if($level == 'xth'){
                              $this->load->view('userProfile/addProfileEducationBackgroundSection',$data);   
                            } else if($level == 'xiith'){
                               $this->load->view('userProfile/addProfileEducationXII',$data);   
                            } else{
                              $this->load->view('userProfile/addProfileEducationCollege',$data); 
                            }
                            
                          }
                        }

                          if($count == 0){
                            $data['regFormId'] = $regFormId;
                            $data['level'] = 'undefined';
                            $count++;
                            $this->load->view('userProfile/addEducationBackgroundDefault',$data);
                           }
                        ?>
                         <p class="clr"></p>
                      </div>
                         <div class="prf-btns">
                         
                                <div class="lft-sid">
                                   <a id="addMoreEdu" href="javascript:void(0);"  <?php if($count >=5){?>  style="display:none" <?php }?> onclick="addMoreEducationForm('<?=$regFormId?>');"><i class="icons1 ic_addwrk"></i>Add Education Background</a>
                                 </div>
                            
                                <section class="rght-sid">
                                   <a href="javascript:void(0);" onclick="shikshaUserProfileForm['<?=$regFormId?>'].submitForm('cancel');" class="btn-grey">Cancel</a>
                                   <a href="javascript:void(0);" onclick="shikshaUserProfileForm['<?=$regFormId?>'].submitForm('save'); trackEventByGA('UserProfileClick','LINK_SAVE_EDUCATIONAL_BACKGROUND');" class="btn_orngT1">Save</a>
                                  </section>
                              </div>
                               <p class="clr"></p>
                               <input type="hidden" name="sectionType" id="sectionType_<?=$regFormId?>" value="educationalBackgroundSection" />
                               <input type="hidden" name="isStudyAbroad" value="<?php echo $isStudyAbroadFlag; ?>" />
                               <input type="hidden" name="abroadSpecialization" value="<?php echo $abroadSpecializationFlag; ?>" />
                           </form>
                        </div>
                       </section>

<script>
  var EduLevelCount = <?php echo $count;?>;
  var globalEduLevel = <?php echo json_encode(array_keys($field)); ?>;
  var regFormIdEdu = '<?php echo $regFormId; ?>';

    shikshaUserProfileForm['<?php echo $regFormId; ?>'] = new ShikshaUserProfileForm('<?php echo $regFormId; ?>');
   //shikshaUserRegistrationForm['<?php echo $regFormId; ?>'] = new ShikshaUserRegistrationForm('<?php echo $regFormId; ?>');
    shikshaUserProfileForm['<?php echo $regFormId; ?>'].setCustomValidations(<?php echo json_encode($field); ?>);
    shikshaUserProfileForm['<?php echo $regFormId; ?>'].setFormFieldList(<?php echo json_encode(array_keys($field)); ?>); 
 </script>   