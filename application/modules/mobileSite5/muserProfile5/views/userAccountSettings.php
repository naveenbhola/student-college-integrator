
  <div class="userpage-container">
    <div class="page-heading header-fix">
      <a href="#" class="p-title" id="p-title-id">Change Account Settings</a>
      <a href="#" class="cls-head flRt" data-rel="back">&times;</a>
    </div>
    <div class="workexp-dtls">
      <form action="/user/UserProfileController/changePassword" id="changePasswordForm" >
        <section class="workexp-cont clearfix">
          <article class="workexp-box">
            <p class="main-heading">CHANGE PASSWORD</p>
            <div class="dtls">                  
              
              <ul class="wrkexp-ul">
                <li>
                  <div class="text-show">
                    <label class="form-label">Current Password</label>
                    <input type="password" id="currentPassword" name="currentPassword" class="user-txt-flds" maxlength="20" minlength="5" caption="Current password" />
                  </div>
                  <div>
                    <div class="regErrorMsg" id="currentPassword_error"></div>
                  </div>
                </li>      
                <li>
                  <div class="text-show">
                    <label class="form-label">New Password</label>
                    <input type="password" id="newPassword" name="newPassword"  class="user-txt-flds" maxlength="20" minlength="5" caption="New password" />
                  </div>
                  <div>
                    <div class="regErrorMsg" id="newPassword_error"></div>
                  </div>
                </li>  
                <li>
                  <div class="text-show">
                    <label class="form-label">Re Type New Password</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" class="user-txt-flds" maxlength="20" minlength="5" caption="Retype new password" />
                  </div>
                  <div>
                    <div class="regErrorMsg" id="confirmPassword_error"></div>
                  </div>
                </li> 
                <li>
                  <div class="text-show">
                    <div class="btn-group">
                      <!-- <a href="javascript:void(0);" class="btn gray-btn">Cancel</a>   -->
                      <a href="javascript:void(0);" class="btn orng-btn" id="savePassword">Save</a>
                    </div>    
                    <p class="clr"></p>
                  </div>    
                </li>
              </ul>
            </div>
          </article>
        </section>
      </form>
      <form action="#" id="changeCommPrefForm" >
        <section class="workexp-cont clearfix">
           <article class="workexp-box">
             <p class="main-heading">COMMUNICATION PREFERENCES</p>

          <div class="new_tabs">
     <div class="email-wrapper">
        <div class="email_title">Email Preferences <span>(Choose only the emails you'd like to receive on your registered email id)</span></div>
        <div class="email_row">
        
        <?php foreach ($mailerUnsubscribeCategory as $id => $val) { 
            if($val['showFT']){
                $checked = "";
                if(!$unsubscribeData[$id]){
                    $checked = "checked='checked'";
                }

                ?>
                <div class="email_cols">
                  <div class="col_title">
                    <div class="descrpt_block">
                      <h3><?php echo $val['category'];?></h3>
                      <p><?php echo $val['description'];?></p>
                    </div>
                  </div>
                  <div class="toggle_bar">
                    <input type="checkbox" name="unsubscribeBtn" id="unsubscribeBtn<?php echo $id?>" class="ios-toggle unsubscribeBtn" data-val="<?php echo $id?>" <?=$checked;?>  >
                    <label for="unsubscribeBtn<?php echo $id?>" class="checkbox-label" data-off="off" data-on="on"></label>
                  </div>
                </div>        
            <?php }?>
        <?php }?>
        </div>
     </div>
   </div>
 </article>
        </section>
      </form>
    </div>
  </div>