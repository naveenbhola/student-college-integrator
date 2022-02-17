<?php  
if($CourseCompletionDate){
  $date =  $CourseCompletionDate->format('Y-m-d H:i:s');
  $completionDate = substr($date, 0, 4);
}
  $sectionTitle = array('10'=>'Class X','12'=>'Class XII','UG'=>'Under Graduation','PG'=>'Post Graduation','PHD'=>'PHD');
  if(!empty($InstituteName) || (!empty($completionDate) && $completionDate !=' -000')){
  ?>

<div class="prf-edu">
                              <span class="edu-bck">
                                <i class="icons1 ic_edu"></i><p><?php echo $sectionTitle[$level];?></p>
                              </span>
                              <?php
                               if($publicProfile != true){
                                
                                if($level == 10){
                                  $x = "{0:'InstituteName$level',1:'CourseCompletionDate$level',2:'Board$level',3:'Subjects$level',4:'Marks$level'}";
                                } else if($level == 12){
                                   $x = "{0:'InstituteName$level',1:'CourseCompletionDate$level',2:'Board$level',3:'Specialization$level',4:'Marks$level'}";
                                }
                								$x = htmlspecialchars($x);
                								if($privacyDetails['InstituteName'.$level] == 'public' || $privacyDetails['CourseCompletionDate'.$level] == 'public') {
                                    $priv = 'icons1 ic_view';
                                    $helptext = 'Visibility: public';
                                  }else { 
                                    $priv = 'icons1 ic_none';
                                    $helptext = 'Visibility: private';
                                  }
                                ?>
                                  <em><a href="javascript:void(0);"><i class="<?php echo $priv; ?>" title="<?php echo $helptext; ?>" onclick="togglePrivacy(this,'<?php echo $userData['userId']; ?>' , <?php echo $x; ?> );"></i></a></em>
                              <?php } ?>
                               <div class="edu-dtls">
                                  <?php if(!empty($InstituteName)){?><p>Studied @<span><?php echo $InstituteName;?></span></p><?php }?>
                                  <?php if(!empty($completionDate) && $completionDate !=' -000'){?><b>Class of <?php echo $completionDate;?> <?php if(!empty($Board)){ echo ', '.$Board;} ?></b><?php }?>
                                  <?php if(!empty($Subjects) && !empty($Subjects[0]) && 0){ ?><p>Subjects:<span><?php $sub= implode(', ', $Subjects); echo $sub;
                                    ?></span></p><?php }?>

                                    <?php if(!empty($Specialization)){ ?><p>Stream: <span><?php echo $Specialization;
                                    ?></span></p><?php }?>

                                  <?php if($level == '10') {
                                          if($Board == 'ICSE') {?>
                                          <?php if(!empty($Marks)){?><p>Marks obtained:<span><?php echo $Marks.' %';?></span></p><?php }?>
                                     <?php } else { if(!empty($Marks)){?><p>Grades obtained:<span><?php echo $Marks;?></span></p><?php }?>
                                  <?php } 
                                    }
                                    else {?>
                                    <?php if(!empty($Marks)){?><p>Marks obtained:<span><?php echo $Marks.' %';?></span></p><?php }?>
                                   <?php }?> 

                               </div>
                          </div>
                          <?php } ?>
                      
