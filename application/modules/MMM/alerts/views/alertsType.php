<?php 	
	$alertsHomeUrl = site_url('alerts/Alerts/alertsHome').'/12';
        $grayCheck = "grayCheck.gif"; 
	$greenCheck = "greenCheck.gif";
	
	$this->courseAndCollegeCount = count($userAlerts['courseAndCollege']);
	$this->scholarshipCount = count($userAlerts['scholarship']);
	$this->examFormCount = count($userAlerts['examForm']);
	$this->blogCount = count($userAlerts['blog']);
	$this->messageBoardCount = count($userAlerts['messageBoard']);
	$this->eventCount = count($userAlerts['event']);	
	$this->collegeRatingCount = count($userAlerts['collegeRating']);
	
	if($userId != 0)
		$loggedIn = 1;
	else
		$loggedIn = 0;
?>					
<script>	
var loggedIn = '<?php echo $loggedIn;  ?>';
</script>
	
					<!--Data_Container_First-->
					<div classs="row">
						<!--Alert_Type-->
						<div class="float_L mar_top_4p w25_per lineSpace_25">
							<div class="normaltxt_11p_blk mar_left_10p"><img src="/public/images/grayBullet.gif"  align="absmiddle" hspace="4"/>&nbsp;Course &amp; Institute</div>
							<div>
								<div class="buttr3">
						<button class="btn-submit13 w3" onClick="javascript:createOverlay('courseAndCollege',1,1,this)">
							<div class="btn-submit13"><p class="btn-submit14 btnTxtBlog">Create alerts</p></div>
						</button> <a href="#" name="courseAndCollege"></a>
								</div>
								<div class="clear_L"></div>
							</div>	
						</div>
						<!--Alert_Type-->
						<!--Alert_details-->
						
						<div class="float_L mar_top_4p" id="courseAndCollegeDiv" style="width:70%">
								<!--FirstRow-->
					<?php  	if(isset($userAlerts['courseAndCollege']) && (count($userAlerts['courseAndCollege']) > 0)): $i = 0;
						foreach($userAlerts['courseAndCollege'] as $courseAndCollegeAlert): $i++; if($noOfAlerts < $i) break; 
						$alertName = escapeString($courseAndCollegeAlert['alert_name']);  
						$alertName = str_replace('courseAndCollege','courseAndInstitute',$alertName);
					?>
								<div class="lineSpace_20">
									<span class="float_L lineSpace_28 disBlock W68_per">
		<a href="javascript:void(0);" onClick="javascript:updateOverlay('courseAndCollege',1,'<?php echo $courseAndCollegeAlert['alert_id'];  ?>','<?php echo $alertName;  ?>',1)" title="<?php echo $alertName; ?>" style="text-transform:capitalize;"><?php if(strlen($alertName)>32) { echo substr($alertName,0,32)."...";} else { echo $alertName; } ?></a>&nbsp;<img src="/public/images/editPencil.gif" border="0" /> 
		<?php if(isset($courseAndCollegeAlert['SMS']) && (strcmp($courseAndCollegeAlert['SMS'],'on')==0)): ?><img src="/public/images/mobileicon.jpg" /> <?php endif; ?>
									</span>
									<span class="float_L disBlock w20_per">
										<div class="buttr3" id="status<?php echo $courseAndCollegeAlert['alert_id']; ?>" style="padding-left:20px">
							<?php if(isset($courseAndCollegeAlert['STATE']) && (strcmp($courseAndCollegeAlert['STATE'],'on') != 0)): ?>		
								<button class="btn-submit17 w1" onClick="updateStatus(<?php echo $appId ?>,<?php echo $courseAndCollegeAlert['alert_id']; ?>,'on')">
									<div class="btn-submit17"><p class="btn-submit18 btnTxtBlog">OFF</p></div>
								</button>
							<?php else: ?>
								<button class="btn-submit15 w101" style="padding-left:-5px" onClick="updateStatus(<?php echo $appId ?>,<?php echo $courseAndCollegeAlert['alert_id']; ?>,'off')">
									<div class="btn-submit15"><p class="btn-submit16 btnTxtBlog">ON</p></div>
								</button>	
								<?php endif; ?>
										</div>
										<div class="clear_L"></div>
									</span>
									<span class="float_L disBlock w10_per"><?php $urlForDelete = site_url('alerts/Alerts/deleteAlert').'/'.$appId.'/'.$courseAndCollegeAlert['alert_id'].'/1/courseAndCollege/'.$noOfAlerts; echo $this->ajax->link_to_remote("<img src=\"/public/images/deleteIcon.gif\" width=\"27\" height=\"25\" border=\"0\"/>",array('url' => $urlForDelete, 'update' => 'courseAndCollegeDiv','success' => 'javascript:updateAlertCount(\'courseAndCollege\');','confirm' => 'Do you really want to delete this alert?')) ?></span>
							<div class="clear_L"></div>								
								</div>
					<?php endforeach; 
					      if(($noOfAlerts == 1) && ($i > 1)):	?>
						<div class="float_L mar_right_10p">
						<img src="/public/images/eventArrow.gif" width="6" height="6" /> 
						<a href="<?php echo $alertsHomeUrl; ?>#courseAndCollege">View all</a></div>
					<?php endif;
				  	      else: ?>
					<div class="float_L mar_top_4p" style="width:70%">
					<span class="grayFont" style="font-size:13px;">No alerts</span>
					</div>
					<?php endif; ?>	
								<!--EndFirstRow-->
						</div>
						<div class="clear_L"></div>
						<div class="lineSpace_10">&nbsp;</div>
						<div class="grayLine"></div>
						<!--Alert_details-->						
					</div>	
					<!--End_Data_Container_First-->

					<!-- This is the place for admission notification and scholarship and blog -->	
						
					<!--Data_Container_Fifth-->
						<div class="row">
						<!--Alert_Type-->
						<div class="float_L mar_top_4p w25_per lineSpace_25">
							<div class="normaltxt_11p_blk mar_left_10p"><img src="/public/images/grayBullet.gif"  align="absmiddle" hspace="4" />&nbsp;Ask &amp; Answer Alert</div>
							<div>
								<div class="buttr3">
						<button class="btn-submit13 w3" onClick="javascript:createOverlay('messageBoard',2,5,this)">
								<div class="btn-submit13"><p class="btn-submit14 btnTxtBlog">Create alerts</p></div>
						</button> <a href="#" name="messageBoard"></a>
								</div>
								<div class="clear_L"></div>
							</div>	
						</div>
						<!--Alert_Type-->
						<!--Alert_details-->
					
						<div class="float_L mar_top_4p" id="messageBoardDiv" style="width:70%">
								<!--FirstRow-->
						<?php 	if(isset($userAlerts['messageBoard']) && (count($userAlerts['messageBoard']) > 0)): $i = 0;
								foreach($userAlerts['messageBoard'] as $messageBoardAlert): $i++; if($noOfAlerts < $i) 								break;  $alertName = addcslashes(trim($messageBoardAlert['alert_name']),"'"); ?>	
								<div class="lineSpace_20">
			<span class="float_L lineSpace_28 disBlock W68_per">
			<a href="javascript:void(0);" onClick="javascript:updateOverlay('messageBoard',2,'<?php echo $messageBoardAlert['alert_id'];  ?>','<?php echo $alertName;  ?>',5)" title="<?php echo $alertName; ?>" style="text-transform:capitalize;"><?php if(strlen($alertName)>32) { echo substr($alertName,0,32)."...";} else { echo $alertName; } ?></a>&nbsp;<img src="/public/images/editPencil.gif" border="0" />
			</span>
									<span class="float_L disBlock w20_per">
										<div class="buttr3" style="padding-left:20px" id="status<?php echo $messageBoardAlert['alert_id']; ?>">
							<?php if(isset($messageBoardAlert['STATE']) && (strcmp($messageBoardAlert['STATE'],'on') != 0)): ?>		
								<button class="btn-submit17 w1" onClick="updateStatus(<?php echo $appId ?>,<?php echo $messageBoardAlert['alert_id']; ?>,'on')">
									<div class="btn-submit17"><p class="btn-submit18 btnTxtBlog">OFF</p></div>
								</button>
							<?php else: ?>
								<button class="btn-submit15 w101" style="padding-left:-5px" onClick="updateStatus(<?php echo $appId ?>,<?php echo $messageBoardAlert['alert_id']; ?>,'off')">
									<div class="btn-submit15"><p class="btn-submit16 btnTxtBlog">ON</p></div>
								</button>	
								<?php endif; ?>
										</div>
										<div class="clear_L"></div>
									</span>
									<span class="float_L disBlock w10_per"><?php $urlForDelete = site_url('alerts/Alerts/deleteAlert').'/'.$appId.'/'.$messageBoardAlert['alert_id'].'/5/messageBoard/'.$noOfAlerts; echo $this->ajax->link_to_remote("<img src=\"/public/images/deleteIcon.gif\" width=\"27\" height=\"25\" border=\"0\"/>",array('url' => $urlForDelete, 'update' => 'messageBoardDiv','success' => 'javascript:updateAlertCount(\'messageBoard\');','confirm' => 'Do you really want to delete this alert?')) ?></span>
									<div class="clear_L"></div>								
								</div>
					<?php endforeach; 
					      if(($noOfAlerts == 1) && ($i > 1)):	?>
						<div class="float_L mar_right_10p">
						<img src="/public/images/eventArrow.gif" width="6" height="6" /> 
						<a href="<?php echo $alertsHomeUrl; ?>#messageBoard">View all</a></div>
					<?php endif;
				  	      else: ?>
					<div class="float_L mar_top_4p" style="width:70%">
					<span class="grayFont" style="font-size:13px;">No alerts</span>
					</div>
					<?php endif; ?>	
								<!--EndFirstRow-->
						</div>
						<div class="clear_L"></div>
						<div class="lineSpace_10">&nbsp;</div>
						<div class="grayLine"></div>
						<!--Alert_details-->						
					</div>
					<!--End_Date_Container_Fifth-->					

					<!--Data_Container_Sixth-->
					<div class="row">
						<!--Alert_Type-->
						<div class="float_L mar_top_4p w25_per lineSpace_25">
							<div class="normaltxt_11p_blk mar_left_10p"><img src="/public/images/grayBullet.gif"  align="absmiddle" hspace="4" />&nbsp;Event Alert</div>
							<div>
								<div class="buttr3">
						<button class="btn-submit13 w3" onClick="javascript:createOverlay('event',3,6,this)">
								<div class="btn-submit13"><p class="btn-submit14 btnTxtBlog">Create alerts</p></div>
						</button> <a href="#" name="event"></a>
								</div>
								<div class="clear_L"></div>
							</div>	
						</div>
						<!--Alert_Type-->
						<!--Alert_details-->
					
						<div class="float_L mar_top_4p" id="eventDiv" style="width:70%">
								<!--FirstRow-->
						<?php 	if(isset($userAlerts['event']) && (count($userAlerts['event']) > 0)): $i = 0;
							foreach($userAlerts['event'] as $eventAlert): $i++; if($noOfAlerts < $i) break; 
							$alertName = addcslashes(trim($eventAlert['alert_name']),"'");  ?>	
								<div class="lineSpace_20">
				<span class="float_L lineSpace_28 disBlock W68_per">
				<a href="javascript:void(0);" onClick="javascript:updateOverlay('event',3,'<?php echo $eventAlert['alert_id'];  ?>','<?php echo $alertName;  ?>',6)" title="<?php echo $alertName; ?>" style="text-transform:capitalize;"><?php if(strlen($alertName)>32) { echo substr($alertName,0,32)."...";} else { echo $alertName; } ?></a>&nbsp;<img src="/public/images/editPencil.gif" border="0" />
				</span>
									<span class="float_L disBlock w20_per">
										<div class="buttr3" style="padding-left:20px" id="status<?php echo $eventAlert['alert_id']; ?>">
							<?php if(isset($eventAlert['STATE']) && (strcmp($eventAlert['STATE'],'on') != 0)): ?>		
								<button class="btn-submit17 w1" onClick="updateStatus(<?php echo $appId ?>,<?php echo $eventAlert['alert_id']; ?>,'on')">
									<div class="btn-submit17"><p class="btn-submit18 btnTxtBlog">OFF</p></div>
								</button>
							<?php else: ?>
								<button class="btn-submit15 w101" style="padding-left:-5px" onClick="updateStatus(<?php echo $appId ?>,<?php echo $eventAlert['alert_id']; ?>,'off')">
									<div class="btn-submit15"><p class="btn-submit16 btnTxtBlog">ON</p></div>
								</button>	
								<?php endif; ?>
										</div>
										<div class="clear_L"></div>
									</span>
									<span class="float_L disBlock w10_per"><?php $urlForDelete = site_url('alerts/Alerts/deleteAlert').'/'.$appId.'/'.$eventAlert['alert_id'].'/6/event/'.$noOfAlerts; echo $this->ajax->link_to_remote("<img src=\"/public/images/deleteIcon.gif\" width=\"27\" height=\"25\" border=\"0\"/>",array('url' => $urlForDelete, 'update' => 'eventDiv','success' => 'javascript:updateAlertCount(\'event\');','confirm' => 'Do you really want to delete this alert?')) ?></span>
									<div class="clear_L"></div>								
								</div>
					<?php endforeach; 
					      if(($noOfAlerts == 1) && ($i > 1)):	?>
						<div class="float_L mar_right_10p">
						<img src="/public/images/eventArrow.gif" width="6" height="6" /> 
						<a href="<?php echo $alertsHomeUrl; ?>#event">View all</a></div>
					<?php endif;
				  	      else: ?>
					<div class="float_L mar_top_4p" style="width:70%">
					<span class="grayFont" style="font-size:13px;">No alerts</span>
					</div>
					<?php endif; ?>	
								<!--EndFirstRow-->
						</div>
						<div class="clear_L"></div>
						<div class="lineSpace_10">&nbsp;</div>
						<div class="grayLine"></div>
						<!--Alert_details-->						
					</div>
					<!--End_Date_Container_Sixth-->					

					<!--Data_Container_Seventh-->
					<!--	<div class="row">
						Alert_Type
						<div class="float_L mar_top_4p w25_per lineSpace_25">
							<div class="normaltxt_11p_blk mar_left_10p"><img src="/public/images/grayBullet.gif"  align="absmiddle" hspace="4" />&nbsp;College Rating Alert</div>
							<div>
								<div class="buttr3">
						<button class="btn-submit13 w3" onClick="javascript:createOverlay('collegeRating',4,7)">
								<div class="btn-submit13"><p class="btn-submit14 btnTxtBlog">Create alerts</p></div>
						</button> <a href="#" name="collegeRating"></a>
								</div>
								<div class="clear_L"></div>
							</div>	
						</div>
						Alert_Type
						Alert_details
					
						<div class="float_L mar_top_4p" id="collegeRatingDiv" style="width:70%">
								FirstRow
						<?php 	if(isset($userAlerts['collegeRating']) && (count($userAlerts['collegeRating']) > 0)): $i = 0;
							foreach($userAlerts['collegeRating'] as $collegeRatingAlert): $i++; if($noOfAlerts < $i) break; 							$alertName = addcslashes(trim($collegeRatingAlert['alert_name']),"'");  ?>	
								<div class="lineSpace_20">
			<span class="float_L lineSpace_28 disBlock W68_per">
			<a href="javascript:void(0);" onClick="javascript:updateOverlay('collegeRating',3,'<?php echo $collegeRatingAlert['alert_id'];  ?>','<?php echo $alertName;  ?>',7)" title="<?php echo $alertName; ?>" style="text-transform:capitalize;"><?php if(strlen($alertName)>32) { echo substr($alertName,0,32)."...";} else { echo $alertName; } ?></a>&nbsp;<img src="/public/images/editPencil.gif" border="0" />
			</span>
									<span class="float_L disBlock w20_per">
										<div class="buttr3" style="padding-left:20px" id="status<?php echo $collegeRatingAlert['alert_id']; ?>">
							<?php if(isset($collegeRatingAlert['STATE']) && (strcmp($collegeRatingAlert['STATE'],'on') != 0)): ?>		
								<button class="btn-submit17 w1" onClick="updateStatus(<?php echo $appId ?>,<?php echo $collegeRatingAlert['alert_id']; ?>,'on')">
									<div class="btn-submit17"><p class="btn-submit18 btnTxtBlog">OFF</p></div>
								</button>
							<?php else: ?>
								<button class="btn-submit15 w101" style="padding-left:-5px" onClick="updateStatus(<?php echo $appId ?>,<?php echo $collegeRatingAlert['alert_id']; ?>,'off')">
									<div class="btn-submit15"><p class="btn-submit16 btnTxtBlog">ON</p></div>
								</button>	
								<?php endif; ?>
										</div>
										<div class="clear_L"></div>
									</span>
									<span class="float_L disBlock w10_per"><?php $urlForDelete = site_url('alerts/Alerts/deleteAlert').'/'.$appId.'/'.$collegeRatingAlert['alert_id'].'/7/collegeRating/'.$noOfAlerts; echo $this->ajax->link_to_remote("<img src=\"/public/images/deleteIcon.gif\" width=\"27\" height=\"25\" border=\"0\"/>",array('url' => $urlForDelete, 'update' => 'collegeRatingDiv','confirm' => 'Do you really want to delete this alert?')) ?></span>
									<div class="clear_L"></div>								
								</div>
					<?php endforeach;
					      if(($noOfAlerts == 1) && ($i > 1)):	?>
						<div class="float_L mar_right_10p">
						<img src="/public/images/eventArrow.gif" width="6" height="6" /> 
						<a href="<?php echo $alertsHomeUrl; ?>#collegeRating">View all</a></div>
					<?php endif;
				  	      else: ?>
					<div class="float_L mar_top_4p" style="width:70%">
					<span class="grayFont" style="font-size:13px;">No alerts</span>
					</div>
					<?php endif; ?>	
								EndFirstRow
						</div>
						<div class="clear_L"></div>
						<div class="lineSpace_10">&nbsp;</div>
						<div class="grayLine"></div>
						Alert_details
					</div> -->
					<!--End_Date_Container_Seventh-->
					
					<!--Data_Container_Eighth-->
						<div class="row">
						<!--Alert_Type-->
						<div class="float_L mar_top_4p w25_per lineSpace_25">
							<div class="normaltxt_11p_blk mar_left_10p"><img src="/public/images/grayBullet.gif"  align="absmiddle" hspace="4" />&nbsp;Comment Alert</div>
							<div>
								
								<div class="clear_L"></div>
							</div>	
						</div>
						<!--Alert_Type-->
						<!--Alert_details-->
					
						<div class="float_L mar_top_4p" id="commentAlertDiv" style="width:70%">
								<!--FirstRow-->
						<?php  	if(isset($userAlerts['comment']) && (count($userAlerts['comment']) > 0)): $i = 0;
							foreach($userAlerts['comment'] as $commentAlert): $i++; if($noOfAlerts < $i) break;
						$alertName = escapeString($commentAlert['alert_name']);
						?>	
								<div class="lineSpace_20">
									<span class="float_L lineSpace_28 disBlock W68_per"><a href="javascript:void(0);" onClick="javascript:updateOverlay('commentAlert',2,'<?php echo $commentAlert['alert_id'];  ?>','<?php echo $alertName;  ?>',8)" title="<?php echo $alertName; ?>" style="text-transform:capitalize;"><?php if(strlen($alertName)>32) { echo substr($alertName,0,32)."...";} else { echo $alertName; } ?></a>&nbsp;<img src="/public/images/editPencil.gif" border="0" /></span>
									<span class="float_L disBlock w20_per">
										<div class="buttr3" style="padding-left:20px" id="status<?php echo $commentAlert['alert_id']; ?>">
							<?php if(isset($commentAlert['STATE']) && (strcmp($commentAlert['STATE'],'on') != 0)): ?>		
								<button class="btn-submit17 w1" onClick="updateStatus(<?php echo $appId ?>,<?php echo $commentAlert['alert_id']; ?>,'on')">
									<div class="btn-submit17"><p class="btn-submit18 btnTxtBlog">OFF</p></div>
								</button>
							<?php else: ?>
								<button class="btn-submit15 w101" style="padding-left:-5px" onClick="updateStatus(<?php echo $appId ?>,<?php echo $commentAlert['alert_id']; ?>,'off')">
									<div class="btn-submit15"><p class="btn-submit16 btnTxtBlog">ON</p></div>
								</button>	
								<?php endif; ?>
										</div>
										<div class="clear_L"></div>
									</span>
									<span class="float_L disBlock w10_per"><?php $urlForDelete = site_url('alerts/Alerts/deleteAlert').'/'.$appId.'/'.$commentAlert['alert_id'].'/8/commentAlert/'.$noOfAlerts; echo $this->ajax->link_to_remote("<img src=\"/public/images/deleteIcon.gif\" width=\"27\" height=\"25\" border=\"0\"/>",array('url' => $urlForDelete, 'update' => 'commentAlertDiv','confirm' => 'Do you really want to delete this alert?')) ?></span>
									<div class="clear_L"></div>								
								</div>
					<?php endforeach;
					      if(($noOfAlerts == 1) && ($i > 1)):	?>
						<div class="float_L mar_right_10p">
						<img src="/public/images/eventArrow.gif" width="6" height="6" /> 
						<a href="<?php echo $alertsHomeUrl; ?>#comment">View all</a></div>
					<?php endif;
				  	      else: ?>
					<div class="float_L mar_top_4p" style="width:70%">
					<span class="grayFont" style="font-size:13px;">No alerts</span>
					</div>
					<?php endif; ?>	
								<!--EndFirstRow-->
						</div>
						<div class="clear_L"></div>
						<div class="lineSpace_10">&nbsp;</div>
						<div class="grayLine"></div>
						<!--Alert_details-->						
					</div>
					<!--End_Date_Container_Eighth-->
				
					<!--Data_Container_Nine-->
					<div class="row">
						<!--Alert_Type-->
						<div class="float_L mar_top_4p w25_per lineSpace_25">
							<div class="normaltxt_11p_blk mar_left_10p"><img src="/public/images/grayBullet.gif"  align="absmiddle" hspace="4" />&nbsp;Search Alert</div>
							<div>
								<div class="clear_L"></div>
							</div>	
						</div>
						<!--Alert_Type-->
						<!--Alert_details-->
					
						<div class="float_L mar_top_4p" id="saveSearchDiv" style="width:70%">

						</div>
						<div class="clear_L"></div>
						<div class="lineSpace_10">&nbsp;</div>
						<div class="grayLine"></div>
						<!--Alert_details-->						
					</div>
					<!--End_Date_Container_Nine-->
					<!--Data_Container_Ten-->
					<!--	<div class="row">
						Alert_Type
						<div class="float_L mar_top_4p w25_per lineSpace_25">
							<div class="normaltxt_11p_blk mar_left_10p"><img src="/public/images/grayBullet.gif"  align="absmiddle" hspace="4" />&nbsp;Author Alert</div>
							<div>
								
								<div class="clear_L"></div>
							</div>	
						</div>
						Alert_Type
						Alert_details
					
						<div class="float_L mar_top_4p" id="authorDiv" style="width:70%">
								FirstRow
						<?php if(isset($userAlerts['author']) && (count($userAlerts['author']) > 0)): $i = 0;
							foreach($userAlerts['author'] as $authorAlert): $i++; if($noOfAlerts < $i) break; 								$alertName = addcslashes(trim($authorAlert['alert_name']),"'"); ?>	
								<div class="lineSpace_20">
									<span class="float_L lineSpace_28 disBlock W68_per"><a href="javascript:void(0);" title="<?php echo $alertName; ?>" style="text-transform:capitalize;"><?php if(strlen($alertName)>32) { echo substr($alertName,0,32)."...";} else { echo $alertName; } ?></a></span>
									<span class="float_L disBlock w20_per">
										<div class="buttr3" style="padding-left:20px" id="status<?php echo $authorAlert['alert_id']; ?>">
							<?php if(isset($authorAlert['STATE']) && (strcmp($authorAlert['STATE'],'on') != 0)): ?>		
								<button class="btn-submit17 w1" onClick="updateStatus(<?php echo $appId ?>,<?php echo $authorAlert['alert_id']; ?>,'on')">
									<div class="btn-submit17"><p class="btn-submit18 btnTxtBlog">OFF</p></div>
								</button>
							<?php else: ?>
								<button class="btn-submit15 w101" style="padding-left:-5px" onClick="updateStatus(<?php echo $appId ?>,<?php echo $authorAlert['alert_id']; ?>,'off')">
									<div class="btn-submit15"><p class="btn-submit16 btnTxtBlog">ON</p></div>
								</button>	
								<?php endif; ?>
										</div>
										<div class="clear_L"></div>
									</span>
									<span class="float_L disBlock w10_per"><?php $urlForDelete = site_url('alerts/Alerts/deleteAlert').'/'.$appId.'/'.$authorAlert['alert_id'].'/9/comment/'.$noOfAlerts; echo $this->ajax->link_to_remote("<img src=\"/public/images/deleteIcon.gif\" width=\"27\" height=\"25\" border=\"0\"/>",array('url' => $urlForDelete, 'update' => 'authorDiv','confirm' => 'Do you really want to delete this alert?')) ?></span>
								<div class="clear_L"></div>								
								</div>
					<?php endforeach;
					      if(($noOfAlerts == 1) && ($i > 1)):	?>
						<div class="float_L mar_right_10p">
						<img src="/public/images/eventArrow.gif" width="6" height="6" /> 
						<a href="<?php echo $alertsHomeUrl; ?>#author">View all</a></div>
					<?php endif;
				  	      else: ?>
					<div class="float_L mar_top_4p" style="width:70%">
					<span class="grayFont" style="font-size:13px;">No alerts</span>
					</div>
					<?php endif; ?>	
								EndFirstRow
						</div>
						<div class="clear_L"></div>
						<div class="lineSpace_10">&nbsp;</div>
						Alert_details						
					</div> -->
					<!--End_Date_Container_Ten-->		
<script>
if(loggedIn != 0)
{
	showSaveSearch(<?php echo $noOfAlerts ?>);
}
</script>			
