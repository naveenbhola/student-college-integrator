
    <div class="prf-edu">
  <?php if($publicProfile != true){
      $x = "{0:'DepartmentworkExp$counter',1:'EmployerworkExp$counter',2:'DesignationworkExp$counter',3:'CurrentJobworkExp$counter'}";
                    $x = htmlspecialchars($x);
                    if($privacyDetails['DepartmentworkExp'.$counter] == 'public' || $privacyDetails['EmployerworkExp'.$counter] == 'public') {
                                    $priv = 'icons1 ic_view';
                                    $helptext = 'Visibility: public';
                                  }else { 
                                    $priv = 'icons1 ic_none';
                                    $helptext = 'Visibility: private';
                                  }
    ?>
                      <em><a href="javascript:void(0);"><i class="<?php echo $priv; ?>" title="<?php echo $helptext; ?>" onclick="togglePrivacy(this,'<?php echo $userData['userId']; ?>' ,<?php echo $x;?>);" style="float: right;"></i></a></em>
                    <?php } 

                    if(!empty($Designation) && !empty($Employer)){
                    ?>
                               <span class="edu-bck">
                                 <i class="icons1 ic_work"></i><p style="word-wrap:break-word; width:540px;"><?php echo $Designation; if(!empty($Department)) echo ' ('.$Department.')'?></p>
                               </span>
                               <div class="edu-dtls">
                                  <p style="word-wrap:break-word; width:540px;"><?php if($CurrentJob == 'YES'){ echo "Currently ";}?> <span><?php echo "@ ".$Employer;?><span></p>   
                               </div>
                      <?php } ?>
						<p class="clr"></p>
                          </div> 

                          
