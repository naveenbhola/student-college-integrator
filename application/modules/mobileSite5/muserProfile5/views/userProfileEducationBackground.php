<?php $index = 0;  ?>
<!-- <div class="profile-col"> -->
    <!-- <div class="profile-col-heading">EDUCATIONAL BACKGROUND</div> -->
    <div class="profile-col-heading">EDUCATION BACKGROUND
       <?php if(!$publicProfile){ ?>
        <div class="flRt editEducationBackground">
            <!-- <a  href="#pagetwo" data-transition="slideup" class="ui-link"> -->
              <i class="profile-sprite profile-edit-icon"></i>
            <!-- </a> -->
        </div>         
      <?php }?>  
    </div>  

    <div class="profile-detail-list">
        <ul>
            
            <?php 
                $board = array('CBSE'=>'CBSE','ICSE'=>'ICSE/State Boards','IGCSE' => 'Cambridge IGCSE','IBMYP'=>'International Baccalaureate','NIOS'=>'NIOS');
                $collegeLevels = array('PHD', 'PG', 'UG');
                $marks = array(
                            'CBSE' => array(''=>'Select Marks',4=>'4 - 4.9', 5=>'5 - 5.9', 6=>'6 - 6.9', 7=>'7 - 7.9', 8=>'8 - 8.9', 9=>'9 - 10.0'),
                            'ICSE' => array(''=>'Select Marks','50'=>'< than 50%','60'=>'50% to 60%','70' => '60% to 70%','80'=>'70% to 80%','90'=>'80% to 90%','100' => '90% or above'),
                            'IGCSE' => array(''=>'Select Marks','1'=>'A*','2'=>'A','3'=>'B', '4'=>'C', '5'=>'D', '6'=>'E','7'=>'F', '8'=>'G'),
                            'NIOS' => array(''=>'Select Marks','50'=>'< than 50%','60'=>'50% to 60%','70' => '60% to 70%','80'=>'70% to 80%','90'=>'80% to 90%','100' => '90% or above'),
                            'IBMYP' => array(''=>'Select Marks','28'=>'28','29'=>'29','30'=>'30','31'=>'31','32'=>'32','33'=>'33','34'=>'34','35'=>'35','36'=>'36','37'=>'37','38'=>'38','39'=>'39','40'=>'40','41'=>'41','42'=>'42','43'=>'43','44'=>'44','45'=>'45','46'=>'46','47'=>'47','48'=>'48','49'=>'49','50'=>'50','51'=>'51','52'=>'52','53'=>'53','54'=>'54','55'=>'55','56'=>'56')
                    );

                $collegeLevelMapping = array('PHD'=>'PHD', 'PG'=>'Masters', 'UG'=>'Graduation');
            foreach($collegeLevels as $key=>$value){ 
                if(!empty(${$value})){ ?>

              <li style="word-wrap:break-word">    
              <?php if(!$publicProfile){ 
              $privacyFields = array('0'=>'InstituteName'.$value,'1'=>'CourseCompletionDate'.$value,'2' =>'Board'.$value,'3' =>'Subjects'.$value,'4'=>'Marks'.$value,'5'=>'Name'.$value,'6'=>'CourseSpecialization'.$value); 
                            $privacyFields = serialize($privacyFields);

                          $publicFlag = false;  
                          if($privacyDetails['InstituteName'.$value] == 'public' || $privacyDetails['CourseCompletionDate'.$value] == 'public'){
                              $publicFlag = true;
                            }

                        $this->load->view('userTogglePrivacy',array('privacyFields' =>$privacyFields,'publicFlag'=>$publicFlag));         
                 
             }?>        

                <?php 
                    $completionLogic = ( empty(${$value}['InstituteName']) && empty(${$value}['Board']) && empty(${$value}['Subjects']) && !empty(${$value}['CourseCompletionDate']) && ${$value}['CourseCompletionDate'] != '-0001' && empty(${$value}['Marks']) ) ? true:false;
                    $secondLine = array();
                    if($completionLogic){
                            echo '<strong>'.$collegeLevelMapping[$value].'</strong>';
                            echo '<br/>';
                            echo ${$value}['CourseCompletionDate']; 
                    }else {

                        if(!empty(${$value}['Name'])){ 
                            echo '<strong>'.${$value}['Name'].'</strong>';
                        }
                        
                        if(!empty(${$value}['Subjects'])){
                           echo ' ('.${$value}['Subjects'].')';
                        }

                        if(!empty(${$value}['InstituteName'])){
                            echo ' at '.${$value}['InstituteName'];
                        }
                        
                        if(!empty(${$value}['Board'])){
                            echo ', '.${$value}['Board'];
                        }

                        if(!empty(${$value}['CourseCompletionDate']) && ${$value}['CourseCompletionDate'] != '-0001'){ 
                            echo "<br/>".${$value}['CourseCompletionDate'];
                        } 

                        if(!empty(${$value}['Marks'])){
                            echo '<br/>Marks: '.$marks['ICSE'][${$value}['Marks']];
                        }
                        
                        echo "</li>";
                        $index++;
                      }   
                   } 
               }?>

               <?php if(!empty($twelfth)){ 
                
                $index++; 
                $displayArray = array();
                ?>
                <li style="word-wrap:break-word">
                    <div>

                       <?php if(!$publicProfile){ ?>
                        <?php $privacyFields = array('0'=>'InstituteName12','1'=>'CourseCompletionDate12','2' =>'Board12','3' =>'Specialization12','4'=>'Marks12','5'=>'Subjects12');
                        
                            $privacyFields = serialize($privacyFields);
                          
                          $publicFlag = false;  
                          if($privacyDetails['InstituteName12'] == 'public' || $privacyDetails['CourseCompletionDate12'] == 'public'){
                              $publicFlag = true;
                            }

                        $this->load->view('userTogglePrivacy',array('privacyFields' =>$privacyFields,'publicFlag'=>$publicFlag));

                    }?>

                        <?php $twelfthCompletionLogic = ( empty($twelfth['InstituteName']) && empty($twelfth['Board']) && !empty($twelfth['CourseCompletionDate']) && $twelfth['CourseCompletionDate'] != '-0001' && empty($twelfth['Marks']) ) ? true:false; ?>
                       <?php 
                       if(!empty($twelfth)){ 
                           if($twelfthCompletionLogic){
                                echo '<strong>XIIth</strong>';
                                echo '<br/>';
                                echo $twelfth['CourseCompletionDate'];
                           }else{  ?>
                                <strong>XIIth</strong> 
                                <?php if(!empty($twelfth['Specialization'])){ echo '('.$twelfth['Specialization'].')'; } 
                                 if(!empty($twelfth['InstituteName'])){ echo ' at '.$twelfth['InstituteName']; } 
                                if(!empty($twelfth['Board'])){echo ', '.$board[$twelfth['Board']]; } ?>
                                <br/>
                                <?php if(!empty($twelfth['CourseCompletionDate']) && $twelfth['CourseCompletionDate'] != '-0001'){ echo $twelfth['CourseCompletionDate']; }  ?>
                                <br/>
                                <?php if(!empty($twelfth['Marks'])){ echo 'Marks: '.$marks['ICSE'][$twelfth['Marks']]; } ?>
                        <?php } 
                        } ?>
                    </div>
                </li>
            <?php } ?>

            <?php if(!empty($tenth)){ 
                $index++; 
                $displayArray = array();

                $tenthCompletionLogic = ( empty($tenth['InstituteName']) && empty($tenth['Board']) && !empty($tenth['CourseCompletionDate']) && empty($tenth['Marks']) ) ? true:false;

                ?>
                <li <?php if($index >= 5){ echo 'style="border-bottom:0px !important;"'; } ?> style="word-wrap:break-word">
                    <div>

                      <?php if(!$publicProfile){ ?>
                        <?php $privacyFields = array('0'=>'InstituteName10','1'=>'CourseCompletionDate10','2' =>'Board10','3' =>'Subjects10','4'=>'Marks10'); 
                            $privacyFields = serialize($privacyFields);

                            $publicFlag = false;  
                          if($privacyDetails['InstituteName10'] == 'public' || $privacyDetails['CourseCompletionDate10'] == 'public'){
                              $publicFlag = true;
                            }

                        $this->load->view('userTogglePrivacy',array('privacyFields' =>$privacyFields,'publicFlag'=>$publicFlag));
                      }?>   
                      
                       <?php if(!empty($tenth)){ 
                            if($tenthCompletionLogic){

                            echo '<strong>Xth</strong>';
                            echo '<br/>';
                            echo $tenth['CourseCompletionDate'];
                            }else{ ?>
                               
                                <strong>Xth</strong> 
                                <?php //if(!empty($twelfth['Specialization'])){ echo '('.$tenth['Specialization'].')'; } 
                                 if(!empty($tenth['InstituteName'])){ echo 'at '.$tenth['InstituteName']; } 
                                if(!empty($tenth['Board'])){echo ', '.$board[$tenth['Board']]; } 
                               
                              if(!empty($tenth['CourseCompletionDate']) && $tenth['CourseCompletionDate'] != '-0001'){ echo ' <br/>'.$tenth['CourseCompletionDate']; }  
                                
                               if(!empty($tenth['Marks'])){ 
                                    if($tenth['Board'] == 'ICSE'){
                                        $marksType = 'Marks: ';
                                    }else{
                                        $marksType = 'Grade: ';
                                    }
                                    echo '<br/>'.$marksType.$marks[$tenth['Board']][$tenth['Marks']]; }
                                }
                       
                         } ?>
                         
                    </div>
                </li>
            <?php } ?>

  
            <?php if($index < 5){ ?>
            <li class="borderNone">
              <?php if(!$publicProfile){ ?>
                <!-- <a class="ui-link addMoreEB" href="#pagetwo" data-transition="slideup" class="ui-link"> -->
                    <div class="addMoreEB"><i class="profile-sprite plus-icon flRt "></i></div>
                <!-- </a>   -->
              <?php }?>  
            </li>
            <?php }?>  
        </ul>
    </div>
<!-- </div> -->