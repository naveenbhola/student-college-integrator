<!-- Recent Members --> 
<div class="raised_greenGradient"> 
	<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
	<div class="boxcontent_greenGradient">			  
		<div class="mar_full_5p">
			<div class="lineSpace_5">&nbsp;</div>
			<div class="normaltxt_11p_blk arial"><span class="fontSize_13p bld float_L">Recently Joined Members</span><br clear="left" /></div>
			<div class="lineSpace_12">&nbsp;</div>
			<?php for($k = 0;$k<count($recentmembers);$k++){ ?>				
				<div class="normaltxt_11p_blk_arial">
					<div class="row" style="margin-bottom:10px">
						<div class="float_L" style="width:58px">
							<a href="/getUserProfile/<?php echo $recentmembers[$k]['displayname']?>">
							<img border="0" src="<?php echo $recentmembers[$k]['avtarimageurl']?>"/></a>
						</div>
						<div style="margin-left:68px;">
							<div class = "bld fontSize_12p">
								<?php
								   if(strlen($recentmembers[$k]['displayname']) > 10)
								   $username = substr($recentmembers[$k]['displayname'],0,10) . '..';
								   else
								   $username = $recentmembers[$k]['displayname'];
								?>
								<a title = "<?php echo $recentmembers[$k]['displayname']?>" href="/getUserProfile/<?php echo $recentmembers[$k]['displayname']?>"><?php echo $username?></a>
								<span class = "grayFont nbld" style="font-size:11px">, <?php echo $recentmembers[$k]['addtime']?></span>
							</div>							
							<?php 
							   if(strlen($recentmembers[$k]['name']) > 28)
							   $colname = substr($recentmembers[$k]['name'],0,28) . '..';
							   else
							   $colname = $recentmembers[$k]['name'];
							?>
							<div class = "fontSize_10p"><a href = "<?php echo $recentmembers[$k]['collegeurl']?>" title = "<?php echo $recentmembers[$k]['name']?>"> <?php echo $colname?></a></div>
							
							<?php if((isset($validateuser[0]['userid'])) && ($validateuser[0]['userid'] == $recentmembers[$k]['userId'])) {?>
								<img src="/public/images/you_icon.gif"/>
							<?php }
							else {?>					
								<img title="<?php echo $recentmembers[$k]['statusmsg']?>" src="<?php echo $recentmembers[$k]['statusimage']?>"/>
							<?php if(isset($validateuser[0]['userid'])) {?>
								<img hspace="4" onclick="showMailOverlay(<?php echo $validateuser[0]['userid']?>,'',<?php echo $recentmembers[$k]['userId']?>,'<?php echo $recentmembers[$k]['displayname']?>')" title="Click here to send message to <?php echo $recentmembers[$k]['displayname']?>" style="cursor: pointer;" src="/public/images/mail.gif"/>
							<?php } else {
								$jsfunc1 = "showMailOverlay(\'\',\'\',\'".$recentmembers[$k]['userId']."\',\'".$recentmembers[$k]['displayname']."\')";
							?>
								<img hspace="4" onclick="showuserLoginOverLay(this,'<?php echo "GROUPS_".$pageNm."_RIGHTPANEL_MAILRECENTMEMBER"?>','jsfunction','showMailOverlay','','','<?php echo $recentmembers[$k]['userId']?>','<?php echo $recentmembers[$k]['displayname']?>');" title="Click here to send message to <?php echo $recentmembers[$k]['displayname']?>" style="cursor: pointer;" src="/public/images/mail.gif"/>
							<?php }
								$MemberFlag = 0;
								if(count($userNetwork) > 0  && ($userNetwork) != "0"){
									for($j=0;$j< count($userNetwork);$j++)		
									{
										if($recentmembers[$k]['userId'] == $userNetwork[$j]['senderuserid'])
										$MemberFlag = 1;	
									}
								 }
								 if($MemberFlag){
							?>
								<img title="<?php echo $recentmembers[$k]['displayname']?> is already added to your network" src="/public/images/network-alreadin.gif"/>
							<?php } else { ?>
							<?php if(isset($validateuser[0]['userid'])) {?>
								<img title="Click here to add <?php echo $recentmembers[$k]['displayname']?> to your network" src="/public/images/plus.gif" style = "cursor:pointer;"  onClick = "sendRequest(<?php echo $validateuser[0]['userid']?>,<?php echo $recentmembers[$k]['userId']?>,0);"/>
							<?php }else {
								$jsfunc2 = "sendRequest(\'\',\'".$recentmembers[$k]['userId']."\',0)";
							?>
								<img title="Click here to add <?php echo $recentmembers[$k]['displayname']?> to your network" src="/public/images/plus.gif" style = "cursor:pointer;" onClick = "showuserLoginOverLay(this,'<?php echo 'GROUPS_'.$pageNm. "_RIGHTPANEL_ADDRECENTMEMBER"?>','jsfunction','sendRequest','',<?php echo $recentmembers[$k]['userId']?>,0)"/>
							<?php }} } ?>
						</div>
					</div>  
					<div class="clear_L" style="font-size:1px;line-height:1px">&nbsp;</div>
				</div>
			<?php } ?>
		</div>
	</div>
	<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
</div>	
<div class="lineSpace_10">&nbsp;</div>
<!-- Recent Members ENds -->
