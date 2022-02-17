    <section class="prf-box-grey" id="changePasswordSection">
        <div class="prft-titl">
            <div class="caption">
                <p>CHANGE PASSWORD</p>
            </div>
            <div class="tools">
                <a id="changePswdLink" href="javascript:void(0);" style="display: none;" class="change" onclick="$j('#changePasswordDiv').show(); $j('#changePswdLink').hide();">Change</a>
            </div>
        </div>
        
        <!--profile-tab content-->
        <div class="frm-bdy" id="changePasswordDiv">
            <form action="/user/UserProfileController/changePassword" class="prf-form" id="changePasswordForm" >
                <div class="prf-d">
                    <span class="pf-s">
                        <label style="cursor: default;">Current Password</label> 
                        <input type="password" id="currentPassword" name="currentPassword" class="prf-inpt" maxlength="20" minlength="5" caption="Current password" onkeyup="if(event.keyCode == 13){ return controlChangePassword($j('#savePassword')); }" />
                        <div>
                            <div id="currentPassword_error" class="prf-error" style="margin-left: 162px;"></div>
                        </div>
                    </span>
                    <span class="pf-s">
                        <label style="cursor: default;">New Password</label> 
                        <input type="password" id="newPassword" name="newPassword"  class="prf-inpt" maxlength="20" minlength="5" caption="New password" onkeyup="if(event.keyCode == 13){ return controlChangePassword($j('#savePassword')); }" />
                        <div>
                            <div id="newPassword_error" class="prf-error" style="margin-left: 162px;"></div>
                        </div>
                    </span>
                    <span class="pf-s">
                        <label style="cursor: default;">Re Type New Password</label> 
                        <input type="password" id="confirmPassword" name="confirmPassword" class="prf-inpt" maxlength="20" minlength="5" caption="Re-type new password" onkeyup="if(event.keyCode == 13){ return controlChangePassword($j('#savePassword')); }" />
                        <div>
                            <div id="confirmPassword_error" class="prf-error" style="margin-left: 162px;"></div>
                        </div>
                    </span>
                </div>
                <div class="prf-btns">
                    <section class="rght-sid">
                        <a href="javascript:void(0);" class="btn-grey" onclick="$j('#changePasswordDiv').hide(); $j('#changePswdLink').show();clearPasswordFields();">Cancel</a>
                        <a href="javascript:void(0);" class="btn_orngT1" onclick="controlChangePassword(this); trackEventByGA('UserProfileClick','LINK_SAVE_CHANGE_PASSWORD');" id="savePassword">Save</a>
                    </section> 
                </div>
                <p class="clr"></p>
            </form>
        </div>
    </section>
