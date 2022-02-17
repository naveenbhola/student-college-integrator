<section class="prf-box-grey" >
                          <div class="prft-titl">
                               <div class="caption">
                                  <p>WORK EXPERIENCE</p>
                               </div>
                               <?php if($publicProfile != true){?>
                               <div class="tools">
                                  <a href="javascript:void(0);" onclick="editUserProfile('workExperienceSection','WORK EXPERIENCE');" class="change">Edit</a>
                               </div>
                               <?php } ?>
                          </div>

                       <!--profile-tab content-->
                        <div class="frm-body">
                        <?php 
                          
                          if( isset($personalInfo['Experience']) && $personalInfo['Experience'] >= 0){
                            $this->workExperienceLib = new \registration\libraries\FieldValueSources\WorkExperience;
                             $expValues = $this->workExperienceLib->getValues();
                            ?>
                            <div class="prf-edu">

                               <span class="edu-bck btm11">
                                  <?php if($publicProfile != true){
                                      if($privacyDetails['Experience'] == 'public') {
                                        $priv = 'icons1 ic_view';
                                        $helptext = 'Visibility: public';
                                      }else { 
                                        $priv = 'icons1 ic_none';
                                        $helptext = 'Visibility: private';
                                      }
                                    ?>
                                    <em><a href="javascript:void(0);"><i class="<?php echo $priv; ?>" title="<?php echo $helptext; ?>" onclick='togglePrivacy(this,"<?php echo $userData['userId']; ?>" , {0:"Experience"});'></i></a></em>
                                  <?php } ?>
                                  <i class="icons1 ic_work"></i><p>Total Work Experience:<span class="lite-txt"><?php echo $expValues[$personalInfo['Experience']];?></span></p>
                                 
                               </span>
                       
                              
                          </div> 

                         <?php }?> 
                        <ul>
                      <li>   
                        <?php $count =0;
                          for ($i=1; $i <=10 ; $i++) { 
                            $workExLevel = 'workExp'.$i;
                            $workExLevelData = $$workExLevel;

                            if(!empty($workExLevelData)){
                              $count++;
							  $workExLevelData['counter'] = $i;
                              $this->load->view('userProfile/profilePageWorkExperienceSection',$workExLevelData);
                            }
					 
                          }                          
                        ?>  
                      </li></ul>
                      <?php if($publicProfile != true){ ?>
                         <?php if ($count != 10){
                          ?>
                          <div class="prf-btns">
                                <div class="lft-sid">
                                   <a href="javascript:void(0);" onclick="editUserProfile('workExperienceSection','WORK EXPERIENCE');"><i class="icons1 ic_addwrk"></i>Add Work Experience</a>
                                 </div>
                              </div>

                            <p class="clr"></p>
                        </div>

                        <?php }?>
                        <?php } ?>
                       </section>
