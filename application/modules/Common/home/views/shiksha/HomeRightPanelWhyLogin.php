                    <?php 
			
                         $base64url = base64_encode(SHIKSHA_GROUPS_HOME_URL.'/network/Network/showAllGroups');
			if(!(is_array($validateuser) && $validateuser != "false")) {
                        $onClick = "showuserLoginOverLay(this,'GROUPS_GROUPSHOME_RIGHTPANEL_INVITEFRIEND','jsfunction','showInviteFriends');";
                    }else{
                        if($validateuser[0]['quicksignuser'] == 1)
                        {
                            $onClick = "window.location = '/user/Userregistration/index/$base64url/1'";
                        }
                        else
                        {
                            $onClick = "showInviteFriends();";
                        }} ?>
        <!-- Invite Friends -->
<!--        <div class="raised_greenGradient">
			<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
			<div class="boxcontent_greenGradient">
			  <div class="mar_full_5p">
			  		<div class="normaltxt_11p_blk arial"><span class="fontSize_13p bld float_L">Invite Friends From</span><br clear="left" /></div>
					<div class="lineSpace_12">&nbsp;</div>
					<div class="normaltxt_11p_blk_arial">
                      <div>
                        <div>
                            <a href="#" onClick = "<?php echo $onClick?>">
								<img hspace="5" border="0" src="/public/images/invite.jpg"/>
                            </a>
                        </div>
						<div class="lineSpace_10">&nbsp;</div>
						<div class="fontSize_12p bld">&amp; Earn Shiksha Points</div>
                      </div>
					  <div class = "clear_L"></div>
                    </div>
						<div class="lineSpace_10">&nbsp;</div>
					<div class="row txt_align_c">						 						
							<button class="btn-submit19 w20" value="" type="button" onClick="<?php echo $onClick; ?>">
								<div class="btn-submit19"><p class="btn-submit20 btnTxtBlog" style="padding: 12px 8px 14px 5px;">Invite Friends</p></div>
							</button>						
					</div>
			  </div>
			</div>
			<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
		</div>
		<div class="lineSpace_10">&nbsp;</div>-->
        <!-- Invite Friends Ends -->


		<div class="raised_green_h">
			<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
			<div class="boxcontent_green_h" style="height:90px">
			  <div class="mar_full_5p">
					<div class="lineSpace_5">&nbsp;</div>
					<div class="normaltxt_11p_blk_arial mar_full_10p fontSize_12p">
						<!--	<div style="background:url(/public/images/eventArrow1.gif) no-repeat; padding:0px 0px 0px 15px;background-position:0px 0px;">Invite your batchmates and create your institutes alumni network at <span class="OrgangeFont">Shiksha Groups</span></div>-->
							<div class="lineSpace_10">&nbsp;</div>
							<div style="background:url(/public/images/eventArrow1.gif) no-repeat; padding:0px 0px 0px 15px;background-position:0px 0px;">Invite your friends &amp; earn Shiksha points</div>

					</div>
					<div class="lineSpace_10">&nbsp;</div>
                    <?php $this->load->view('network/commonOverlay');?>
					<?php  $this->load->view('common/inviteMail'); ?>
					<?php
						if((is_array($validateuser) && $validateuser != "false")) {
							if($validateuser[0]['quicksignuser'] == "1") {
						        $base64url = base64_encode($_SERVER['REQUEST_URI']);
						        $onClick = 'javascript:location.replace(\'/user/Userregistration/index/'. $base64url.'/1\');return false;';
							} else {  
								$onClick = 'showInviteFriends();';
							}
						}
					?>
					<div class="row txt_align_c">						 						
							<button class="btn-submit19 w20" value="" type="button" onClick="<?php echo $onClick; ?>">
								<div class="btn-submit19"><p class="btn-submit20 btnTxtBlog" style="padding: 12px 8px 14px 5px;">Invite Friends</p></div>
							</button>						
					</div>
			  </div>
			</div>
			<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
		</div>
		<div class="lineSpace_10">&nbsp;</div>
