 <?php $educationLevel = array('xth','xiith','UG','PG','PHD'); ?>

 <section class="prf-box-grey" >

                           <div class="prft-titl">
                               <div class="caption">
                                  <p>EDUCATION BACKGROUND</p>
                               </div>
                               <?php if($publicProfile != true){?>
                               <div class="tools">
                                  <a href="javascript:void(0);" onclick="editUserProfile('educationalBackgroundSection','EDUCATIONAL BACKGROUND');" class="change">Edit</a>
                               </div>
                               <?php } ?>
                          </div>


                       <!--profile-tab content-->
                        <div class="frm-body">
                          <ul>
                            <li>
                           <?php 
                           $counter =0;
                            foreach ($educationLevel as $level) {
                               if(!empty($$level)){
                                $data = $$level;
                                if($level == 'xiith')
                                  $data['level'] = 12;
                                else if($level == 'xth')
                                  $data['level'] = 10;
                                else
                                   $data['level'] =  $level;

                                 $counter++;

                                 $completionDate ="";

                                 $CourseCompletionDate = $data['CourseCompletionDate'];

                                 if($CourseCompletionDate){
                                    $date =  $CourseCompletionDate->format('Y-m-d H:i:s');
                                    $completionDate = substr($date, 0, 4);
                                  }

                                 
                                if(empty($data['InstituteName']) && (empty($completionDate) || $completionDate ==' -000') ){
                                      continue;
                                }

                                if($level == 'xth' || $level == 'xiith'){
                                  $this->load->view('userProfile/profilePageEducationSection',$data);
                                } else{
                                  $this->load->view('userProfile/profilePageEducationCollege',$data); 
                                }

                               }
                           }
                           ?>
                           </li>
                         </ul>
                            <?php if($publicProfile != true){?>
                           
                           <?php if($counter <5){?> 
                           <div class="lft-sid">
                                   <a href="javascript:void(0);" onclick="editUserProfile('educationalBackgroundSection','EDUCATIONAL BACKGROUND');"><i class="icons1 ic_addwrk"></i>Add Education Background</a>
                          </div>

                          <?php }?>
                           <?php } ?>
                            <p class="clr"></p>
                        </div>
                       </section>
