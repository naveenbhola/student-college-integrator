  <section class="prf-box-grey" >
    <div class="prft-titl">
         <div class="caption">
            <p>PERSONAL INFORMATION</p>
         </div>
         
        <?php if($publicProfile != true){?>
          <div class="tools">
            <a href="javascript:void(0);" onclick="editUserProfile('personalInformationSection','PERSONAL INFORMATION');" class="change">Edit</a>
          </div> 
        <?php } ?>
    </div>

 <!--profile-tab content-->
  <div class="frm-body">
     <div class="prf-inf">

      <?php if(!empty($additionalInfo['Bio'])){ ?>
       <span class="prf-in">
           <i class="icons1 ic_inf"></i>
             <p> <?php echo $additionalInfo['Bio']; ?> </p>
         </span>
         <?php } ?>

         <?php if(!empty($personalInfo['Mobile'])){ ?>
       <span class="prf-in">
           <i class="icons1 ic_mob"></i>
             <p> <?php echo $personalInfo['Mobile']; ?> </p>
         </span>
         <?php } ?>

         <?php if(!empty($personalInfo['Email'])){ ?>
          <span class="prf-in">
           <i class="icons1 ic_msg"></i>
             <p> <?php echo $personalInfo['Email']; ?> </p>
         </span>
         <?php } ?>
         
          <?php if(!empty($additionalInfo['StudentEmail'])){ ?>
          <span class="prf-in">
           <i class="icons1 ic_msg"></i>
             <p> <?php echo $additionalInfo['StudentEmail']; ?> </p>
         </span>
         <?php } ?>
         
         
         <?php if(!empty($personalInfo['Country'])){ ?>
         <span class="prf-in">
           <i class="icons1 ic_loc"></i>
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
             <?php if($publicProfile != true){
              if($privacyDetails['City'] == 'public' || $privacyDetails['Locality'] == 'public' || $privacyDetails['Country'] == 'public') {
                  $priv = 'icons1 ic_view';
                  $helptext = 'Visibility: public';
                }else { 
                  $priv = 'icons1 ic_none';
                  $helptext = 'Visibility: private';
                }
              ?>
             <em><big class="visible-txt"><?php echo $helptext; ?></big><a href="javascript:void(0);"><i class="<?php echo $priv; ?>" title="<?php echo $helptext; ?>" onclick='togglePrivacy(this,"<?php echo $userData['userId']; ?>" , {0:"Country",1:"City", 2:"Locality"});' ></i></a></em>
             <?php } ?>

         </span>
         <?php } ?>

         <?php if(!empty($personalInfo['DateOfBirth'])){ 
            $DateOfBirth = get_object_vars($personalInfo['DateOfBirth']);             
            $DateOfBirth = explode(' ',$DateOfBirth['date']);
            $date_of_birth = $DateOfBirth[0];                                 
            $DateOfBirth = explode('-',$DateOfBirth[0] );
            $dobflag = false;
          ?>
         <?php if($date_of_birth !='0000-00-00' && $date_of_birth !='-0001-11-30'){?> 
         <span class="prf-in">
           <i class="icons1 ic_cal" style="left:2px;"></i>
             <p><?php echo date("jS F Y ", mktime(0,0,0,$DateOfBirth[1],$DateOfBirth[2],$DateOfBirth[0])); $dobflag = true;?></p>
             
         </span>
         <?php } ?>
         <?php } ?>

     </div>
     <div class="prf-btns">
             <?php if($publicProfile != true){
              if($dobflag == false || empty($personalInfo['Country']) || empty($additionalInfo['StudentEmail']) || empty($personalInfo['Mobile']) || empty($additionalInfo['Bio']) ) { ?>
          <div class="lft-sid">
             <a href="javascript:void(0);" onclick="editUserProfile('personalInformationSection','PERSONAL INFORMATION');"><i class="icons1 ic_addwrk"></i>Add Personal Information</a>
           </div>
            <?php } }?>

         
     </div>
      <p class="clr"></p>
  </div>
 </section>