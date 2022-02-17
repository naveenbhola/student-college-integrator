<div class="profile-col">
            <div class="profile-col-heading">WORK EXPERIENCE
               <?php if(!$publicProfile){ ?>
                <div id='editWorkExId'  class="flRt">
                    <!-- <a  href="#pagetwo" data-transition="slideup" class="ui-link"> -->
                      <i class="profile-sprite profile-edit-icon"></i>
                    <!-- </a> -->
                </div>         
               <?php }?> 
            </div>  
            <div id='' class="profile-detail-list">
                <ul>

                  <?php 
                    if( (!empty($personalInfo['Experience']) || $personalInfo['Experience'] =='0' ) || ( (!empty($personalInfo['Experience']) || $personalInfo['Experience'] =='0' )  && !$publicProfile ) ) {
                  ?>
                    <li>Total work experience:
                      

                    <?php if(!$publicProfile){ ?>   

                      <?php $privacyFields = array('0'=>'Experience'); 
                             $privacyFields = serialize($privacyFields);
                      
                        $publicFlag = false;  
                          if($privacyDetails['Experience'] == 'public'){
                              $publicFlag = true;
                            }?>

                   <div class="mrMins">  
                     <?php $this->load->view('userTogglePrivacy',array('privacyFields' =>$privacyFields,'publicFlag'=>$publicFlag)); ?>
                   </div>

                   <?php }?>

                    <?php if(!$publicProfile){ ?>    
                     <select class="dfl-drp-dwn totalWorkExDrop" name="workExperience" id="totalWorkEx" >  
                        <?php 
                          foreach($WorkExRange as $value=>$description){ ?>
                                <option <?php  if ($value == $personalInfo['Experience'] ) { echo "selected='selected'";} ?> value="<?php echo $value; ?>" > <?php echo $description; ?> </option>
                         <?php } ?>
                      </select>    
                    <?php }else{

                        if($personalInfo['Experience'] == -1){?>
                          <span class="bold-text">No experience</span>  
                        <?php } else{?>


                           <span class="bold-text"><?php echo $personalInfo['Experience'].' Year';

                           if($personalInfo['Experience']  > 1){
                            echo 's';
                           }

                           ?></span>
                       <?php }
                    }?>      

                    </li>
                    <?php }?>

                    <?php $count =0;
                          $dataForNextPageWorkEx =  array();

                          for ($i=1; $i <=10 ; $i++) {
                            $workExLevel = 'workExp'.$i;
                            $workExLevelData = $$workExLevel;

                            if(!empty($workExLevelData) && !empty($workExLevelData['Employer'])){
                              $dataForNextPageWorkEx[] = $workExLevelData;
                              $count++;
                              $workExLevelData['counter'] = $i;

                              if($workExLevelData['CurrentJob'] == 'YES'){
                                      $currentValueFlag = true;
                              }

                            $this->load->view('userProfileWorkExSection',$workExLevelData);
                              
                            }
                          }   
                   
                 
                    if($count < 10){?>
                      <a class="ui-link" >
                        <li id='workExAddDetails' class="borderNone">
                          <div>
                             <?php if(!$publicProfile){  ?>   
                            <!-- <a href="#pagetwo" data-transition="slideup" class="ui-link"> -->
                              <i id='addNewWrkEx' class="profile-sprite plus-icon flRt "></i>
                            <!-- </a> -->
                            <?php }?>
                          </div>
                        </li>
                      </a>  
                   <?php }?> 
                    
                </ul>
            </div>
        </div>

<input type="hidden" id="currentValueFlag" value='<?php if($currentValueFlag){echo '1';} else{echo '0';} ?>' />

<script> 
  var globalWorkExData = '<?php echo serialize($dataForNextPageWorkEx); ?>';
  var workExCountGlobal = <?php echo $count;?> ;       
</script>