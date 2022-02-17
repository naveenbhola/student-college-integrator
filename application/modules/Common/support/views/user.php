<?php $this->load->view('header'); ?>
<div id='left_panel'>
    <form method="post" onsubmit="return submitFindUserById();">
    Search by user ID (CID)
    <input type="text" name="suserId" id="suserId" class='inputbox'  />
    <div class='input_error' id='error_suserId'>Please enter user id</div>
    <input type="submit" value="Submit" class='inputbutton' />
    </form>
    <br />
    <form method="post" action='/support/Support/findUserByEmail' onsubmit="return submitFindUserByEmail();">
    Search by email
    <input type="text" name="semail" id="semail" class='inputbox' />
    <div class='input_error' id='error_semail'>Please enter email id</div>
    <input type="submit" value="Submit" class='inputbutton' />
    </form> 
    <input type="hidden" id="logged_in_userid" name="logged_in_userid" value="<?php echo $loggedInUserInfo[0]['userid']; ?>" />
    <input type="hidden" id="logged_in_username" name="logged_in_username" value="<?php echo $loggedInUserInfo[0]['displayname']; ?>" />
    <div>
        <li style="padding: 00px 0px 0px 10px;">
        <a href="#" id="mappingLink" onclick="doGetMappingInterface(); return false;" style="text-decoration:none; ">
            Client Sales Mapping Interface
        </a>
        </li>
        <br>
        <li style="padding: 00px 0px 0px 10px;">
            <a href="http://www.shiksha.com/user/Userregistration/UpdateUserBounce" style="text-decoration:none;" >Update hard bounce</a>
        </li>    
        <br>
        <li style="padding: 00px 0px 0px 10px;">
        <a target="_blank" href="http://www.shiksha.com/LDBLeadTracking/login" style="text-decoration:none;">Lead Tracking Interface</a>
        </li>
    </div>
</div>
<?php
displaySupportMessage();
if($userId) { 
?>
<div id='main_panel'>
    <div id='main_panel_heading'>
        # <?php echo $userId; ?>
    </div>
    
    <div id='user_details_block'>
    <ul>
        <li>
            <div class='user_details_left'>Display Name:</div>
            <div class='user_details_right'><?php echo $displayName; ?></div>
            <div class="clr"></div>
        </li>
        <li>
            <div class='user_details_left'>Email:</div>
            <div class='user_details_right'>
            <?php
            if($blocked) {
                $emailBlockPrefix = 'blocked__'.$userId.'__';
                echo substr($email,strlen($emailBlockPrefix));
            }
            else {
                echo $email;
            }
            ?>
            </div>
            <div class="clr"></div>
        </li>
        <li>
            <div class='user_details_left'>Mobile:</div>
            <div class='user_details_right'><?php echo $mobile; ?></div>
            <div class="clr"></div>
        </li>

<li>
            <div class='user_details_left'>Password:</div>
            <div class='user_details_right'><?php echo $password; ?></div>
            <div class="clr"></div>
        </li>

        <li>
            <div class='user_details_left' style='margin-top:3px;'>Status:</div>
            <div class='user_details_right'>
                <?php
                if($blocked) {
                ?>
                    <div id='user_status_blocked'>Blocked</div>
                <?php
                }
                else if($ownershipChallenged) {
                ?>
                    <div id='user_status_blocked'>Ownership Challenged</div>
                <?php
                }
                else {
                ?>
                    <div id='user_status_active'>Active</div>
                <?php
                }
                ?>
            </div>
            <div class="clr"></div>
        </li>
    </ul>
    </div>
    
    <?php if($ownershipChallenged) { ?>
    <div id="block_button" style="margin-left:145px;">
    <form method='post' action='/support/Support/resolveOwnershipChallenged'>
        <input type="hidden" name="userId" value='<?php echo $userId; ?>' />
        <input type="submit" value="Resolve Challenged Ownership" />
    </form>
    </div>
    <?php } else { ?>
    <div id="block_button">
    <form method='post' action='/support/Support/blockUser'>
        <input type="hidden" name="userId" value='<?php echo $userId; ?>' />
        <input type="submit" value="<?php if($blocked) echo "Unblock"; else echo "Block"; ?> User" />
    </form>
    </div>
    <?php } ?>

    <?php
    if(!$blocked) {
    ?>
    <div id='change_info_heading'>
        or change info
    </div>
    <br />
    <form method='post' action='/support/Support/editUser' onsubmit="return submitEditUser();">
    <div id='user_details_block'>
    <ul>
       <!--  <li>
            <div class='user_details_left mt'>Display Name:</div>
            <div class='user_details_right'>
                <input type="text" name="displayName" id="displayName" class='inputbox' />
                <div class='input_error' id='error_displayName'></div>
            </div>
            <div class="clr"></div>
        </li> -->
        <li>
            <div class='user_details_left mt'>Email:</div>
            <div class='user_details_right'>
                <input type="text" name="email" id="email" class='inputbox' />
                <div class='input_error' id='error_email'></div>
            </div>
            <div class="clr"></div>
        </li>
        <li>
            <div class='user_details_left mt'>Mobile:</div>
            <div class='user_details_right'>
                <input type="text" name="mobile" id="mobile" class='inputbox' maxlength="10" />
                <div class='input_error' id='mobile_email'></div>
            </div>
            <div class="clr"></div>
        </li>
        <li>
            <div class='user_details_left mt'>User Group:</div>
            <div class='user_details_right' style="margin-top:5px;">
                <select name="usergroup" id="usergroup">
                <?php foreach($userGroups as $userGroup) { ?>
                    <option value="<?php echo $userGroup; ?>" <?php if($userGroup == $selectedUserGroup) echo "selected='selected'"; ?>><?php echo $userGroup; ?></option>
                <?php } ?>    
                </select>
                <div class='input_error' id='error_usergroup'></div>
            </div>
            <div class="clr"></div>
        </li>
        <li>
            <div class='user_details_left'>&nbsp;</div>
            <div class='user_details_right'><input type="submit" value="Submit" class='inputbutton' /></div>
            <div class="clr"></div>
        </li>
    </ul>
    </div>
    <input type="hidden" name="userId" value='<?php echo $userId; ?>' />
    </form>
    <?php
    }
    ?>
</div>
<div class="clr"></div>
<?php
}
else {
?>
<div id='user_search_helptext'>Search user by user id or email to block/unblock, change email/display name</div>
<?php
}
?>
<?php $this->load->view('footer'); ?>
