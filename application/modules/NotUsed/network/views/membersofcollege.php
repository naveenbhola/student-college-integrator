       <!-- Recent Members --> 
				<div class="raised_lgraynoBG">
					<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
					<div class="boxcontent_lgraynoBG">
						<div class="h22 raisedbg_sky">
							<div class="normaltxt_11p_blk fontSize_13p arial bld mar_left_10p">Group Members</div>										
						</div>																						
						<div class="lineSpace_10">&nbsp;</div>
						<div id = "collegenetwork">
                        <?php
                        $totalCount = $usergroup['totalCount'];
                        if($usergroup['totalCount'] > 0){
                        for($j=0;$j< count($usergroup['results']);++$j) {
                        ?> 
                        <div class="normaltxt_11p_blk_arial"><div style = "height:85px"><div class="float_L"><a href="/getUserProfile/<?php echo $usergroup['results'][$j]['displayname']?>"><img  hspace="5" border="0" align="left" src="<?php echo $usergroup['results'][$j]['avtarimageurl']?>"/></a><br/><br/><div class = "clear_L"></div>
                        <div style = "margin-left:5px">
        <?php 
        $onstatus = $usergroup['results'][$j]['displayname'] . " is " . $usergroup['results'][$j]['onlinestatus'] . ' on Shiksha.com';
        $usergroup['results'][$j]['RelativeTime'] = str_replace('ago','',$usergroup['results'][$j]['RelativeTime']);
        if($usergroup['results'][$j]['onlinestatus'] == "idle")
        $onstatus = $usergroup['results'][$j]['displayname'] . " has not been using shiksha.com for " . $usergroup['results'][$j]['RelativeTime']; 
        if($userId > 0)
        {
        $onMail = "showMailOverlay('".$userId."','','".$usergroup['results'][$j]['userId']."','".$usergroup['results'][$j]['displayname']."')";
        $onAdd = "sendRequest('".$userId."','".$usergroup['results'][$j]['userId']."','".$collegeId."')";
        }
        else
        {
        $onMail = "showuserLoginOverLay(this,'GROUPS_".$pageNm."_RIGHTPANEL_MAILMEMBER','jsfunction','showMailOverlay','".$userId."','',".$usergroup['results'][$j]['userId'].",'".$usergroup['results'][$j]['displayname']."')";
        $onAdd = "showuserLoginOverLay(this,'GROUPS_".$pageNm."_RIGHTPANEL_ADDMEMBER','jsfunction','sendRequest','','".$usergroup['results'][$j]['userId']."','')"; 
        }
        
        ?>
                        <img vspace = "2" title = "<?php echo $onstatus ?>" src="<?php echo $usergroup['results'][$j]['statusimage']?>"/><img vspace = "2" hspace = "7" src="/public/images/mail.gif" title = "Click to send mail to <?php echo $usergroup['results'][$j]['displayname']?>"  style = "cursor:pointer" onClick = "<?php echo $onMail;?>"\/>
<?php
            $Flag = 1;
            if($usergroup['userNetwork'] != "0" && count($usergroup['userNetwork']) != 0)
                        {
                        for($k = 0;$k < count($usergroup['userNetwork']); ++$k) {
				        if($usergroup['results'][$j]['userId'] == $usergroup['userNetwork'][$k]['senderuserid'])
        					$Flag = 0;
		            	}}
            			if($userId == $usergroup['results'][$j]['userId'])
		            	{?>
                    			<img src="/public/images/you_icon.gif" vspace = "4"/>
				           <?php     $Flag = -1;
		            	}
			if($Flag == 1) {
            $addmsg = "Click to send 'Add To Network' request to " . $usergroup['results'][$j]['displayname'];?>
				<img vspace = "2" src="/public/images/plus.gif" title = "<?php echo $addmsg ?>" style = "cursor:pointer" onClick = "<?php echo $onAdd;?>" />
             <?php }   else
                { 
                if($Flag != -1) { 
                $addtitle = $usergroup['results'][$j]['displayname'] . " is already added to your network";
                ?>
				<img vspace = "2" src="/public/images/network-alreadin.gif" title = "<?php echo $addtitle ?>"/>
<?php           }}
                        $userdisplayname = $usergroup['results'][$j]['displayname'];
                if(strlen($usergroup['results'][$j]['displayname']) > 10)
                    {
                        $userdisplayname = substr($usergroup['results'][$j]['displayname'],0,7) . "...";
                    }

?>
                        </div></div><div style="margin-left: 85px;"><span class = "bld fontSize_12p"><a title = "'<?php echo $usergroup['results'][$j]['displayname']?>'" href="/getUserProfile/<?php echo $usergroup['results'][$j]['displayname']?>"><?php echo $userdisplayname?></a></span><span class = "grayFont">, joined <?php echo $usergroup['results'][$j]['addtime']?></span>
                        <div class = "lineSpace_5">&nbsp;</div><span class = "fontSize_10p"><?php echo $usergroup['results'][$j]['userStatus']?> <?php echo $usergroup['results'][$j]['graduationYear']?></span>
                        <div class = "lineSpace_5">&nbsp;</div>
                        <a href="/shikshaHelp/ShikshaHelp/upsInfo" class="OrgangeFont bld pd_full brdGreen" style="text-decoration:none;font-size:11px"><?php echo $usergroup['results'][$j]['level']?></a>
                        <div class = "lineSpace_7">&nbsp;</div>
                        </div>
                        <div class = "clear_L"></div>
                        </div>
                        </div>
                        <?php
                        }}else{?>
                       <div style = "margin-left:5px">Presently there are no members in this network </div> 
                       <?php } ?>						
                        </div>
						<div class="lineSpace_5">&nbsp;</div>
                        <?php if($totalCount > USERS_DETAIL_PAGE) {
                        if($grouptype == "group") {
                        ?>
					    <div class="mar_full_10p txt_align_r"><span class = "arrowBullets"><a href="/network/Network/MembersAll/<?php echo $collegeId?>/0/<?php echo USERS_VIEW_ALL_PAGE ?>/<?php echo $listingDetails[0]['instituteName']?>">View All Members</a></span></div>
                        <?php }else {?>
                        
					    <div class="mar_full_10p txt_align_r"><span class = "arrowBullets"><a href="/network/Network/MembersAll/<?php echo $collegeId?>/0/<?php echo USERS_VIEW_ALL_PAGE ?>/<?php echo $blogDetails['blogTitle']?>/TestPreparation">View All Members</a></span></div>
                        
                       <?php }?>
						<div class="lineSpace_5">&nbsp;</div>
                        <?php } ?>
					</div>
					<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
				</div>	
				<div class="lineSpace_10">&nbsp;</div>
