<div class="raised_greenGradient">
    <b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
    <div class="boxcontent_greenGradient">
        <div class="mar_full_5p">
            <div class="lineSpace_5">&nbsp;</div>
            <div class="normaltxt_11p_blk arial">
                <span class="fontSize_13p bld testPreOrangeColor float_L">Invite Friends From</span>
                <br clear="left" />
            </div>
            <div class="lineSpace_12">&nbsp;</div>
            <div class="normaltxt_11p_blk_arial">
                <div style = "height:40px">
                    <?php
                        $base64url =  base64_encode($_SERVER['REQUEST_URI']);
                        if(!(is_array($validateuser) && $validateuser !=  "false")) {
                            $onClick = "showuserLoginOverLay(this,'SHIKSHA_INVITEFRIENDSWIDGET_RIGHTPANEL_INVITEFRIENDS','jsfunction','showInviteFriends');return false;";
                        } else {
                            if($validateuser[0]['quicksignuser'] == 1) {
                                $onClick = "window.location = '/user/Userregistration/index/$base64url/1'";
                            } else {
                                $onClick = "showInviteFriends();";
                            }
                        }
                    ?>
                <div class="float_L">
                    <a href="#" onClick = "<?php echo $onClick?>">
                        <img hspace="5" border="0" width = "212px" align="left" src="/public/images/invite.jpg"/>
                    </a>
                </div>
            </div>
            <div class = "clear_L"></div>
            </div>
			<div class="bld">&amp; Earn Shiksha Points</div>
        </div>
    </div>
    <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
</div>
<div class="lineSpace_10">&nbsp;</div>
<!-- Invite Friends Ends -->

