<div> 
      <?php if($publicFlag == true) { ?>
           <i class="profile-sprite eye-icon-active flRt privacyUpdate"></i>
      <?php }else{?>     
        <i class="profile-sprite eye-icon flRt privacyUpdate"></i>         
      <?php }?>

        <input  type="hidden" class='privacyFields' value='<?php echo $privacyFields;?>' />
  </div>