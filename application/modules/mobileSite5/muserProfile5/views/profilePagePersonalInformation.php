    <div class="profile-col-heading">PERSONAL INFORMATION

      <?php if(!$publicProfile){ ?>
                <div id='personalInfoEdit'  class="flRt personalInfoEdit">
                    <!-- <a  href="#pagetwo" data-transition="slideup" class="ui-link"> -->
                      <i class="profile-sprite profile-edit-icon"></i>
                    <!-- </a> -->
                </div> 
       <?php }?>         

    </div>
    <?php if(!empty($additionalInfo['Bio'])){ ?>
        <div class="profile-info-col">
            <div>
                <i class="profile-sprite profile-user-icon flLt"></i>
            </div>
            <div class="profile-info-detail">
                <div class="flLt" style="width:88%">
                    <p><?php echo $additionalInfo['Bio']; ?></p>
                </div>
                <?php if(!$publicProfile){ ?>
                
                  <?php $privacyFields = array('0'=>'Bio'); 
                         $privacyFields = serialize($privacyFields);

                          $publicFlag = false;  
                          if($privacyDetails['Bio'] == 'public'){
                              $publicFlag = true;
                            }

                        $this->load->view('userTogglePrivacy',array('privacyFields' =>$privacyFields,'publicFlag'=>$publicFlag));
                  
                        ?>

                 <?php }?>
            </div>
        </div>
    <?php } ?>
    <?php if(!empty($personalInfo['Mobile'])){ ?>
        <div class="profile-info-col">
            <div>
                <i class="profile-sprite profile-phone-icon flLt"></i>
            </div>
            <div class="profile-info-detail">
                <div class="flLt" style="width:88%">
                    <p><?php echo '+'.$personalInfo['ISDCode'].'-'.$personalInfo['Mobile']; ?></p>
                </div>
                
            </div>
        </div>
    <?php } ?>
    
    <?php if(!empty($personalInfo['Email'])){ ?>
        <div class="profile-info-col">
            <div>
                <i class="profile-sprite profile-mail-icon flLt"></i>
            </div>
            <div class="profile-info-detail">
                <div class="flLt" style="width:88%">
                    <p><?php echo $personalInfo['Email']; ?></p>
                </div>
                
            </div>
        </div>
    <?php } ?>
    
    <?php if(!empty($additionalInfo['StudentEmail'])){ ?>
        <div class="profile-info-col">
            <div>
                <i class="profile-sprite profile-mail-icon flLt"></i>
            </div>
            <div class="profile-info-detail">
                <div class="flLt" style="width:88%">
                    <p><?php echo $additionalInfo['StudentEmail']; ?></p>
                </div>
                
            </div>
        </div>
    <?php } ?>
    
    <?php if(!empty($personalInfo['Country'])){ ?>
        <div class="profile-info-col">
            <div>
                <i class="profile-sprite profile-loc-icon flLt"></i>
            </div>
            <div class="profile-info-detail">
                <div class="flLt" style="width:88%">
                    <p>
                        <?php 
                          if($personalInfo['Country'] != '2'){
                            echo $userCountry;
                          }else if(!empty($userlocationData['locality'])){
                            echo $userlocationData['locality'].', '.$userlocationData['city'].', '.$userCountry;
                          }else if(!empty($userlocationData['city'])){
                            echo $userlocationData['city'].', '.$userCountry;
                          }else{
                            echo $userCountry;
                          } 
                        ?>
                    </p>
                </div>
                
                <?php if(!$publicProfile){ ?>
                
                  <?php $privacyFields = array('0'=>'Country','1'=>'City','2' =>'Locality'); 
                         $privacyFields = serialize($privacyFields);

                          $publicFlag = false;  
                          if($privacyDetails['City'] == 'public' || $privacyDetails['Locality'] == 'public' || $privacyDetails['Country'] == 'public'){
                              $publicFlag = true;
                            }

                        $this->load->view('userTogglePrivacy',array('privacyFields' =>$privacyFields,'publicFlag'=>$publicFlag));
                  
                        ?>

                 <?php }?>

            </div>
        </div>
    <?php } ?>
    

    <?php 
        if(!empty($personalInfo['DateOfBirth'])){ 
             $DateOfBirth = get_object_vars($personalInfo['DateOfBirth']);             
                  $DateOfBirth = explode(' ',$DateOfBirth['date']);
                  $date_of_birth = $DateOfBirth[0];                                 
                  $DateOfBirth = explode('-',$DateOfBirth[0]);
                  $dobflag = false;
          ?>
         <?php if($date_of_birth !='0000-00-00' && $date_of_birth !='-0001-11-30'){?> 
         <div class="profile-info-col">
            <div>
                <i class="profile-sprite ic_calndr flLt"></i>
            </div>
            <div class="profile-info-detail">
                <div class="flLt" style="width:88%">
             <p><?php echo date("jS  F Y ", mktime(0,0,0,$DateOfBirth[1],$DateOfBirth[2],$DateOfBirth[0])); $dobflag = true; ?></p>
             </div>
                
            </div>
        </div>
         <?php }?> 
         <?php } ?>
    
    <div class="profile-info-col" style="padding-top:0;">
        <?php
            if($dobflag == false || empty($personalInfo['Country']) || empty($additionalInfo['StudentEmail']) || empty($personalInfo['Mobile']) || empty($additionalInfo['Bio']) ) { ?>
     
      <?php if(!$publicProfile){ ?>   
        <div>
           <!-- <a href="#pagetwo" data-transition="slideup" class="ui-link" > -->
            <i class="profile-sprite plus-icon flRt personalInfoEdit"></i>
          <!-- </a> -->
        </div>
        <?php } ?>
        
        <!-- <div class="profile-info-detail borderNone">
            <a href="#pagetwo" data-transition="slideup" id="personalInfoEdit" class="public-profile-link font-12 personalInfoEdit" style="margin:0;"></a>
        </div> -->
        <?php } ?>
    </div>