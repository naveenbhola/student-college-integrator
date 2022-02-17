<?php
		$headerComponents = array(
								'css'	=>	array('header','raised_all','mainStyle','footer'),
								'js'	=>	array('common','alerts','prototype'),
								'title'	=>	'Education::Alerts',
								'tabName'	=>	'Alerts',
								'taburl' => site_url('alerts/Alerts/alertsHome'),
								'metaKeywords'	=>'Some Meta Keywords'
							);
		$this->load->view('common/header', $headerComponents);
?>
<?php 
	$headerValue = array();
	$headerValue['searchAction'] = 'Blog/shikshaBlog/blogSearch/1/2';		
	$this->load->view('common/global_search',$headerValue);
?>

<script>
var categoryTreeMain = eval(<?php echo $category_tree; ?>);	
var SITE_URL = '';
</script>
<?php 
	$alert_overlay = array();
	$data['countryList'] = $countryList;
	$this->load->view('alerts/alerts_overlays'); 
?>
<?php  $this->load->view('common/overlay'); ?>

<select name="countrySelect" id="countrySelect" style="display:none;">
<option vlaue="1" selected></option>
</select>
<script>
createCategoryCombo(document.getElementById('countrySelect'),'categoryPlace');
</script>
<script language="javascript">
var url = "alerts/Alerts/createAlert";
var categoryHolder = document.getElementById('categoryCombos').innerHTML;
document.getElementById('categoryCombos').innerHTML = '';
function createOverlay(moduleName,overlayDivId,moduleId)
{
overlayWidth = "500px";
overlayHeight = "500px";
overlayTitle = "Create an Alert";

catgoryPlaceId = "categoryPlace"+overlayDivId;
setTitle(moduleName);
setProductId(moduleName,moduleId);

emptyCategoryPlaces();
document.getElementById(catgoryPlaceId).innerHTML = categoryHolder;

var overLayForm = document.getElementById(overlayDivId).innerHTML;
document.getElementById(overlayDivId).innerHTML = '';
overlayContent = overLayForm;
overlayParent = document.getElementById(overlayDivId); // global variable for all the parent overlay contents;
showOverlay(overlayWidth, overlayHeight, overlayTitle, overlayContent); 
	
}

function emptyCategoryPlaces()
{
overlayDivId = 'categoryPlace'+'form1';
document.getElementById(overlayDivId).innerHTML ='';	
overlayDivId = 'categoryPlace'+'form2';
document.getElementById(overlayDivId).innerHTML ='';
overlayDivId = 'categoryPlace'+'form3';
document.getElementById(overlayDivId).innerHTML ='';
}
</script>

<div class="lineSpace_8">&nbsp;</div>

<!--Start_Center-->
<div>
	<!--End_Right_Panel-->
	<div id="right_Panel">
		<div><img src="/public/images/widget.gif" width="154" height="253" /></div>	
		<div class="lineSpace_10">&nbsp;</div>
		<div><img src="/public/images/widget.gif" width="154" height="253" /></div>	
		<div class="lineSpace_10">&nbsp;</div>
		<div><img src="/public/images/widget.gif" width="154" height="253" /></div>	
		<div class="lineSpace_10">&nbsp;</div>	
	</div>
	<!--End_Right_Panel-->
	
	
	<!--Start_Left_Panel-->
	<div>
	</div>
	<!--End_Left_Panel-->
	
	<!--Start_Mid_Panel-->
	<div id="mid_Panel_noLpanel">
		<div>
			<div class="raised_lgraynoBG"> 
				<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
				<div class="boxcontent_lgraynoBG" style="height:150px;">
					<img src="/public/images/alertBell.jpg" align="left" class="mar_right_10p" />
					<span class="normaltxt_11p_blk fontSize_16p bld OrgangeFont">Alerts</span><br />
					<span class="normaltxt_11p_blk lineSpace_20">Alerts is a free, personalized notification service that instantly informs you of what you consider important and relevant via email, instant message, pager, or cell phone. To take advantage of this service, you can sign in (or sign up to get a Yahoo! account) and customize your Yahoo! Alerts content and how it is delivered to you</span><br />
					<span class="normaltxt_11p_blk bld"><a href="#">How do I create an alert?</a></span>					
				</div>
				<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>				
			</div>	
		</div>
		<div class="lineSpace_10">&nbsp;</div>
		<div style="display:inline; float:left; width:100%">
			<div class="raised_lgraynoBG"> 
				<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
				<div class="boxcontent_lgraynoBG">
					<div class="normaltxt_11p_blk fontSize_13p bld" style="margin-bottom:10px"><img src="/public/images/bellIcon.jpg" width="32" height="20" align="absmiddle" /> Alerts</div>
					<!--Start_Header_Title-->
					<div class="row">					
						<div class="bgcolor_lightgreen h20 normaltxt_11p_blk bld">
							<div class="float_L mar_top_4p w25_per"><span class="mar_left_10p">Type</span></div>
							<div class="float_L mar_top_4p w20_per"><span class="mar_left_20p">Alert Name</span></div>
							<div class="float_L mar_top_4p w10_per"><span>Status</span></div>
							<div class="float_L mar_top_4p w20_per"><span class="mar_left_10p">Deliver to:</span></div>
							<div class="float_L mar_top_4p w10_per"><span class="mar_left_10p">Edit</span></div>
							<div class="float_L mar_top_4p w10_per"><span class="mar_left_10p">Delete</span></div>						
						</div>
					</div>
					<div class="row">					
						<div class="bgcolor_lightgreen h20 normaltxt_11p_blk bld">
							<div class="float_L w25_per"><span class="mar_left_10p">&nbsp;</span></div>
							<div class="float_L w20_per"><span class="mar_left_20p">&nbsp;</span></div>
							<div class="float_L w10_per"><span>&nbsp;</span></div>
							<div class="float_L w20_per"><span><img src="/public/images/mailicon.jpg" width="31" height="18" /><img src="/public/images/faceIcon.jpg" width="31" height="18" /><img src="/public/images/mobileicon.jpg" width="31" height="18" /></span></div>
							<div class="float_L w10_per"><span class="mar_left_10p">&nbsp;</span></div>
							<div class="float_L w10_per"><span class="mar_left_10p">&nbsp;</span></div>						
						</div>
						<br clear="left" style="height:1px" />
					</div>
					<!--End_Header_Title-->
<?php 
        $grayCheck = "grayCheck.gif"; 
	$greenCheck = "greenCheck.gif";
	
?>					
					<!--Data_Container_First-->
						<div>
							<!--Start_Details_row1-->
							<?php if(isset($userAlerts['courseAndCollege']) && (count($userAlerts['courseAndCollege']) > 0)): $i = 0;
								foreach($userAlerts['courseAndCollege'] as $temp): $i++; ?>
							<div class="row">					
								<div class="normaltxt_11p_blk">
									<div class="float_L mar_top_4p w25_per lineSpace_25">
										<span class="mar_left_10p">
										<?php if($i == 1): ?>
										<img src="/public/images/grayBullet.gif" width="5" height="5" align="absmiddle" />
										&nbsp;Course &amp; college
										<?php endif; ?>
										</span>
									</div>
									<div class="float_L mar_top_4p w20_per lineSpace_25">
									  <div class="mar_left_20p"><a href="#"><?php if($noOfAlerts >= $i) echo $temp['alert_name']; ?></a></div>
									</div>
									<div class="float_L mar_top_4p w10_per">
										<span>
											<div class="buttr3">
								<?php if(isset($temp['STATE']) && (strcmp($temp['STATE'],'on') != 0)): ?>		
									<button class="btn-submit17 w1" value="" type="button">
										<div class="btn-submit17"><p class="btn-submit18 btnTxtBlog">OFF</p></div>
									</button>
								<?php else: ?>
									<button class="btn-submit15 w101">
										<div class="btn-submit15"><p class="btn-submit16 btnTxtBlog">ON</p></div>
									</button>	
								<?php endif; ?>
											</div>	
											<div class="clear_L"></div>
										</span>		
									</div>
									<div class="float_L mar_top_4p w20_per"><span class="mar_left_5p"><?php isset($temp['MAIL']) && ($temp['MAIL'] == true)?$mail = $greenCheck:$mail = $grayCheck; ?><img src="/public/images/<?php echo $mail; ?>" width="36" height="25" /><?php isset($temp['IM']) && ($temp['IM'] == true)?$im = $greenCheck:$im = $grayCheck; ?><img src="/public/images/<?php echo $im; ?>" width="36" height="25" /><?php isset($temp['SMS']) && ($temp['SMS'] == true)?$sms = $greenCheck:$sms = $grayCheck; ?><img src="/public/images/<?php echo $sms; ?>" width="36" height="25" /></span></div>
									<div class="float_L mar_top_4p w10_per"><span class="mar_left_10p"><img src="/public/images/editPencil.gif" width="27" height="25" /></span></div>
									<div class="float_L mar_top_4p w10_per"><span class="mar_left_10p"><img src="/public/images/deleteIcon.gif" width="27" height="25" /></span></div>						
								</div>
								<div class="clear_L"></div>
							</div>
							<?php endforeach;
							      endif; ?>	
							<!--End_Details_row1-->
							<!--Start_Details_row2-->
							<div class="row">					
								<div class="normaltxt_11p_blk">
									<div class="float_L mar_top_4p w25_per">
										<span>
											<div class="buttr3">
												<button class="btn-submit13 w3" onClick="javascript:createOverlay('courseAndCollege','form1',1)">
													<div class="btn-submit13"><p class="btn-submit14 btnTxtBlog">Create alerts</p></div>
												</button>
											</div>
											<div class="clear_L"></div>
										</span>							
									</div>
									<div class="float_L mar_top_4p w20_per"><span>&nbsp;</span></div>
									<div class="float_L mar_top_4p w10_per"><span>&nbsp;</span></div>
									<div class="float_L mar_top_4p w20_per"><span class="mar_left_5p">&nbsp;</span></div>
									<div class="float_L mar_top_4p w10_per"><span class="mar_left_10p">&nbsp;</span></div>
									<div class="float_L mar_top_4p w10_per"><span class="mar_left_10p">&nbsp;</span></div>						
								</div>
								<div class="clear_L"></div>
							</div>
							<!--End_Details_row2-->
							<div class="lineSpace_5">&nbsp;</div>
							<div class="grayLine"></div>					
							<!--End_Details-->
					</div>
					<!--End_Date_Container_First-->
					
					<!--Data_Container_Second-->
					<div>
							<!--Start_Details-->
							<?php if(isset($userAlerts['scholarship'])): $i = 0;
								foreach($userAlerts['scholarship'] as $temp): $i++; ?>	
							<div class="row">					
								<div class="normaltxt_11p_blk">
									<div class="float_L mar_top_4p w25_per lineSpace_25">
									<span class="mar_left_10p"><?php if($i == 1): ?>
									<img src="/public/images/grayBullet.gif" width="5" height="5" align="absmiddle" />
									&nbsp;Scholarship Alert<?php endif; ?></span>
									</div>
									<div class="float_L mar_top_4p w20_per lineSpace_25"><div class="mar_left_20p"><a href="#"><?php if($noOfAlerts >= $i) echo $temp['alert_name']; ?></a></div></div>
									<div class="float_L mar_top_4p w10_per">
										<span>
											<div class="buttr3">
								<?php if(isset($temp['STATE']) && (strcmp($temp['STATE'],'on') != 0)): ?>		
									<button class="btn-submit17 w1" value="" type="button">
										<div class="btn-submit17"><p class="btn-submit18 btnTxtBlog">OFF</p></div>
									</button>
								<?php else: ?>
									<button class="btn-submit15 w101">
										<div class="btn-submit15"><p class="btn-submit16 btnTxtBlog">ON</p></div>
									</button>	
								<?php endif; ?>	
											</div>
											<div class="clear_L"></div>
										</span>		
									</div>
									<div class="float_L mar_top_4p w20_per"><span class="mar_left_5p"><?php isset($temp['MAIL']) && ($temp['MAIL'] == true)?$mail = $greenCheck:$mail = $grayCheck; ?><img src="/public/images/<?php echo $mail; ?>" width="36" height="25" /><?php isset($temp['IM']) && ($temp['IM'] == true)?$im = $greenCheck:$im = $grayCheck; ?><img src="/public/images/<?php echo $im; ?>" width="36" height="25" /><?php isset($temp['SMS']) && ($temp['SMS'] == true)?$sms = $greenCheck:$sms = $grayCheck; ?><img src="/public/images/<?php echo $sms; ?>" width="36" height="25" /></span></div>
									<div class="float_L mar_top_4p w10_per"><span class="mar_left_10p"><img src="/public/images/editPencil.gif" width="27" height="25" /></span></div>
									<div class="float_L mar_top_4p w10_per"><span class="mar_left_10p"><img src="/public/images/deleteIcon.gif" width="27" height="25" /></span></div>						
								</div>
								<div class="clear_L"></div>
							</div>
							<?php endforeach;
							      endif; ?>	
							<div class="row">					
								<div class="normaltxt_11p_blk">
									<div class="float_L mar_top_4p w25_per">
										<span>
											<div class="buttr3">
												<button class="btn-submit13 w3" onClick="javascript:createOverlay('scholarship','form1',2)">
													<div class="btn-submit13"><p class="btn-submit14 btnTxtBlog">Create alerts</p></div>
												</button>
											</div>
											<div class="clear_L"></div>
										</span>							
									</div>
									<div class="float_L mar_top_4p w20_per"><span>&nbsp;</span></div>
									<div class="float_L mar_top_4p w10_per"><span>&nbsp;</span></div>
									<div class="float_L mar_top_4p w20_per"><span class="mar_left_5p">&nbsp;</span></div>
									<div class="float_L mar_top_4p w10_per"><span class="mar_left_10p">&nbsp;</span></div>
									<div class="float_L mar_top_4p w10_per"><span class="mar_left_10p">&nbsp;</span></div>						
								</div>
								<div class="clear_L"></div>
							</div>
							<div class="lineSpace_5">&nbsp;</div>
							<div class="grayLine"></div>					
							<!--End_Details-->
					</div>
					<!--End_Date_Container_Second-->
					
					<!--Data_Container_Third-->
					<div>
							<!--Start_Details_row1-->
							<?php if(isset($userAlerts['examForm'])): $i = 0;
								foreach($userAlerts['examForm'] as $temp): $i++; ?>	
							<div class="row">					
								<div class="normaltxt_11p_blk">
									<div class="float_L mar_top_4p w25_per lineSpace_25">
								<span class="mar_left_10p"><?php if($i == 1): ?>
							<img src="/public/images/grayBullet.gif" width="5" height="5" align="absmiddle" />
							&nbsp;Examination Form Alert <?php endif; ?></span>
							</div>
									<div class="float_L mar_top_4p w20_per lineSpace_25"><div class="mar_left_20p"><a href="#"><?php if($noOfAlerts >= $i) echo $temp['alert_name']; ?></a></div></div>
									<div class="float_L mar_top_4p w10_per">
										<span>
											<div class="buttr3">
								<?php if(isset($temp['STATE']) && (strcmp($temp['STATE'],'on') != 0)): ?>		
									<button class="btn-submit17 w1" value="" type="button">
										<div class="btn-submit17"><p class="btn-submit18 btnTxtBlog">OFF</p></div>
									</button>
								<?php else: ?>
									<button class="btn-submit15 w101">
										<div class="btn-submit15"><p class="btn-submit16 btnTxtBlog">ON</p></div>
									</button>	
								<?php endif; ?>	
											</div>
											<div class="clear_L"></div>
										</span>		
									</div>
									<div class="float_L mar_top_4p w20_per"><span class="mar_left_5p"><?php isset($temp['MAIL']) && ($temp['MAIL'] == true)?$mail = $greenCheck:$mail = $grayCheck; ?><img src="/public/images/<?php echo $mail; ?>" width="36" height="25" /><?php isset($temp['IM']) && ($temp['IM'] == true)?$im = $greenCheck:$im = $grayCheck; ?><img src="/public/images/<?php echo $im; ?>" width="36" height="25" /><?php isset($temp['SMS']) && ($temp['SMS'] == true)?$sms = $greenCheck:$sms = $grayCheck; ?><img src="/public/images/<?php echo $sms; ?>" width="36" height="25" /></span></div>
									<div class="float_L mar_top_4p w10_per"><span class="mar_left_10p"><img src="/public/images/editPencil.gif" width="27" height="25" /></span></div>
									<div class="float_L mar_top_4p w10_per"><span class="mar_left_10p"><img src="/public/images/deleteIcon.gif" width="27" height="25" /></span></div>						
								</div>
								<div class="clear_L"></div>
							</div>
							<?php endforeach;
							      endif; ?>	
							<!--End_Details_row1-->
							<!--Start_Details_row2-->
							<div class="row">					
								<div class="normaltxt_11p_blk">
									<div class="float_L mar_top_4p w25_per">
										<span>
											<div class="buttr3">
												<button class="btn-submit13 w3" onClick="javascript:createOverlay('examForm','form1',3)">
													<div class="btn-submit13"><p class="btn-submit14 btnTxtBlog">Create alerts</p></div>
												</button>
											</div>
											<div class="clear_L"></div>
										</span>							
									</div>
									<div class="float_L mar_top_4p w20_per"><span>&nbsp;</span></div>
									<div class="float_L mar_top_4p w10_per"><span>&nbsp;</span></div>
									<div class="float_L mar_top_4p w20_per"><span class="mar_left_5p">&nbsp;</span></div>
									<div class="float_L mar_top_4p w10_per"><span class="mar_left_10p">&nbsp;</span></div>
									<div class="float_L mar_top_4p w10_per"><span class="mar_left_10p">&nbsp;</span></div>						
								</div>
								<div class="clear_L"></div>
							</div>
							<!--End_Details_row2-->
							<div class="lineSpace_5">&nbsp;</div>
							<div class="grayLine"></div>					
							<!--End_Details-->
					</div>
					<!--End_Date_Container_Third-->					
					
					<!--Data_Container_Fourth-->
					<div>
							<!--Start_Details_row1-->
							<?php if(isset($userAlerts['blog'])): $i = 0;
							foreach($userAlerts['blog'] as $temp): $i++; ?>
							<div class="row">					
								<div class="normaltxt_11p_blk">
									<div class="float_L mar_top_4p w25_per lineSpace_25">
						<span class="mar_left_10p"><?php if($i == 1): ?>
					<img src="/public/images/grayBullet.gif" width="5" height="5" align="absmiddle" />&nbsp;Blog Post Alert
						<?php endif; ?>	</span>
						</div>
									<div class="float_L mar_top_4p w20_per lineSpace_25"><div class="mar_left_20p"><a href="#"><?php if($noOfAlerts >= $i) echo $temp['alert_name']; ?></a></div></div>
									<div class="float_L mar_top_4p w10_per">
										<span>
											<div class="buttr3">
								<?php if(isset($temp['STATE']) && (strcmp($temp['STATE'],'on') != 0)): ?>		
									<button class="btn-submit17 w1" value="" type="button">
										<div class="btn-submit17"><p class="btn-submit18 btnTxtBlog">OFF</p></div>
									</button>
								<?php else: ?>
									<button class="btn-submit15 w101">
										<div class="btn-submit15"><p class="btn-submit16 btnTxtBlog">ON</p></div>
									</button>	
								<?php endif; ?>	
											</div>
											<div class="clear_L"></div>
										</span>		
									</div>
									<div class="float_L mar_top_4p w20_per"><span class="mar_left_5p"><?php isset($temp['MAIL']) && ($temp['MAIL'] == true)?$mail = $greenCheck:$mail = $grayCheck; ?><img src="/public/images/<?php echo $mail; ?>" width="36" height="25" /><?php isset($temp['IM']) && ($temp['IM'] == true)?$im = $greenCheck:$im = $grayCheck; ?><img src="/public/images/<?php echo $im; ?>" width="36" height="25" /><?php isset($temp['SMS']) && ($temp['SMS'] == true)?$sms = $greenCheck:$sms = $grayCheck; ?><img src="/public/images/<?php echo $sms; ?>" width="36" height="25" /></span></div>
									<div class="float_L mar_top_4p w10_per"><span class="mar_left_10p"><img src="/public/images/editPencil.gif" width="27" height="25" /></span></div>
									<div class="float_L mar_top_4p w10_per"><span class="mar_left_10p"><img src="/public/images/deleteIcon.gif" width="27" height="25" /></span></div>						
								</div>
								<div class="clear_L"></div>
							</div>
							<?php endforeach;
							      endif; ?>	
							<!--End_Details_row1-->
							<!--Start_Details_row2-->
							<div class="row">					
								<div class="normaltxt_11p_blk">
									<div class="float_L mar_top_4p w25_per">
										<span>
											<div class="buttr3">
												<button class="btn-submit13 w3" onClick="javascript:createOverlay('blog','form1',4)">
													<div class="btn-submit13"><p class="btn-submit14 btnTxtBlog">Create alerts</p></div>
												</button>
											</div>
											<div class="clear_L"></div>
										</span>							
									</div>
									<div class="float_L mar_top_4p w20_per"><span>&nbsp;</span></div>
									<div class="float_L mar_top_4p w10_per"><span>&nbsp;</span></div>
									<div class="float_L mar_top_4p w20_per"><span class="mar_left_5p">&nbsp;</span></div>
									<div class="float_L mar_top_4p w10_per"><span class="mar_left_10p">&nbsp;</span></div>
									<div class="float_L mar_top_4p w10_per"><span class="mar_left_10p">&nbsp;</span></div>						
								</div>
								<div class="clear_L"></div>
							</div>
							<!--End_Details_row2-->
							<div class="lineSpace_5">&nbsp;</div>
							<div class="grayLine"></div>					
							<!--End_Details-->
					</div>
					<!--End_Date_Container_Fourth-->					
					
					<!--Data_Container_Fifth-->
					<div>
							<!--Start_Details_row1-->
							<?php  if(isset($userAlerts['messageBoard'])): $i = 0;
								foreach($userAlerts['messageBoard'] as $temp): $i++; ?>
							<div class="row">					
								<div class="normaltxt_11p_blk">
									<div class="float_L mar_top_4p w25_per lineSpace_25">
			<span class="mar_left_10p"><?php if($i == 1): ?>
			 <img src="/public/images/grayBullet.gif" width="5" height="5" align="absmiddle" />
			&nbsp;Discussion Topic Alert<?php endif;?></span></div>
									<div class="float_L mar_top_4p w20_per lineSpace_25"><div class="mar_left_20p"><a href="#"><?php if($noOfAlerts >= $i) echo $temp['alert_name']; ?></a></div></div>
									<div class="float_L mar_top_4p w10_per">
										<span>
											<div class="buttr3">
								<?php if(isset($temp['STATE']) && (strcmp($temp['STATE'],'on') != 0)): ?>		
									<button class="btn-submit17 w1" value="" type="button">
										<div class="btn-submit17"><p class="btn-submit18 btnTxtBlog">OFF</p></div>
									</button>
								<?php else: ?>
									<button class="btn-submit15 w101">
										<div class="btn-submit15"><p class="btn-submit16 btnTxtBlog">ON</p></div>
									</button>	
								<?php endif; ?>
											</div>
											<div class="clear_L"></div>
										</span>		
									</div>
									<div class="float_L mar_top_4p w20_per"><span class="mar_left_5p"><?php isset($temp['MAIL']) && (strcmp($temp['MAIL'],'on')==0)?$mail = $greenCheck:$mail = $grayCheck; ?><img src="/public/images/<?php echo $mail; ?>" width="36" height="25" /><?php isset($temp['IM']) && (strcmp($temp['IM'],'on')==0)?$im = $greenCheck:$im = $grayCheck; ?><img src="/public/images/<?php echo $im; ?>" width="36" height="25" /><?php isset($temp['SMS']) && (strcmp($temp['SMS'],'on')==0)?$sms = $greenCheck:$sms = $grayCheck; ?><img src="/public/images/<?php echo $sms; ?>" width="36" height="25" /></span></div>
									<div class="float_L mar_top_4p w10_per"><span class="mar_left_10p"><img src="/public/images/editPencil.gif" width="27" height="25" /></span></div>
									<div class="float_L mar_top_4p w10_per"><span class="mar_left_10p"><img src="/public/images/deleteIcon.gif" width="27" height="25" /></span></div>						
								</div>
								<div class="clear_L"></div>
							</div>
							<?php endforeach;
							      endif; ?>	
							<!--End_Details_row1-->
							<!--Start_Details_row2-->
							<div class="row">					
								<div class="normaltxt_11p_blk">
									<div class="float_L mar_top_4p w25_per">
										<span>
											<div class="buttr3">
												<button class="btn-submit13 w3" onClick="javascript:createOverlay('messageBoard','form1',5)">
													<div class="btn-submit13"><p class="btn-submit14 btnTxtBlog">Create alerts</p></div>
												</button>
											</div>
											<div class="clear_L"></div>
										</span>							
									</div>
									<div class="float_L mar_top_4p w20_per"><span>&nbsp;</span></div>
									<div class="float_L mar_top_4p w10_per"><span>&nbsp;</span></div>
									<div class="float_L mar_top_4p w20_per"><span class="mar_left_5p">&nbsp;</span></div>
									<div class="float_L mar_top_4p w10_per"><span class="mar_left_10p">&nbsp;</span></div>
									<div class="float_L mar_top_4p w10_per"><span class="mar_left_10p">&nbsp;</span></div>						
								</div>
								<div class="clear_L"></div>
							</div>
							<!--End_Details_row2-->
							<div class="lineSpace_5">&nbsp;</div>
							<div class="grayLine"></div>					
							<!--End_Details-->
					</div>
					<!--End_Date_Container_Fifth-->					

					<!--Data_Container_Sixth-->
					<div>
							<!--Start_Details_row1-->	
							<?php if(isset($userAlerts['event'])): $i = 0;
								foreach($userAlerts['event'] as $temp): $i++; ?>
							<div class="row">					
								<div class="normaltxt_11p_blk">
								 <div class="float_L mar_top_4p w25_per lineSpace_25">
									<span class="mar_left_10p"><?php if($i == 1): ?>
									<img src="/public/images/grayBullet.gif" width="5" height="5" align="absmiddle" />
									&nbsp;Event Alert <?php endif; ?> </span>
								 </div>
									<div class="float_L mar_top_4p w20_per lineSpace_25"><div class="mar_left_20p"><a href="#"><?php if($noOfAlerts >= $i) echo $temp['alert_name']; ?></a></div></div>
									<div class="float_L mar_top_4p w10_per">
										<span>
											<div class="buttr3">
								<?php if(isset($temp['STATE']) && (strcmp($temp['STATE'],'on') != 0)): ?>		
									<button class="btn-submit17 w1" value="" type="button">
										<div class="btn-submit17"><p class="btn-submit18 btnTxtBlog">OFF</p></div>
									</button>
								<?php else: ?>
									<button class="btn-submit15 w101">
										<div class="btn-submit15"><p class="btn-submit16 btnTxtBlog">ON</p></div>
									</button>	
								<?php endif; ?>	
											</div>
											<div class="clear_L"></div>
										</span>		
									</div>
									<div class="float_L mar_top_4p w20_per"><span class="mar_left_5p"><?php isset($temp['MAIL']) && ($temp['MAIL'] == true)?$mail = $greenCheck:$mail = $grayCheck; ?><img src="/public/images/<?php echo $mail; ?>" width="36" height="25" /><?php isset($temp['IM']) && ($temp['IM'] == true)?$im = $greenCheck:$im = $grayCheck; ?><img src="/public/images/<?php echo $im; ?>" width="36" height="25" /><?php isset($temp['SMS']) && ($temp['SMS'] == true)?$sms = $greenCheck:$sms = $grayCheck; ?><img src="/public/images/<?php echo $sms; ?>" width="36" height="25" /></span></div>
									<div class="float_L mar_top_4p w10_per"><span class="mar_left_10p"><img src="/public/images/editPencil.gif" width="27" height="25" /></span></div>
									<div class="float_L mar_top_4p w10_per"><span class="mar_left_10p"><img src="/public/images/deleteIcon.gif" width="27" height="25" /></span></div>						
								</div>
								<div class="clear_L"></div>
							</div>
							<?php endforeach;
							      endif; ?>
							<!--End_Details_row1-->
							<!--Start_Details_row2-->
							<div class="row">					
								<div class="normaltxt_11p_blk">
									<div class="float_L mar_top_4p w25_per">
										<span>
											<div class="buttr3">
												<button class="btn-submit13 w3" onClick="javascript:createOverlay('event','form2',6)">
													<div class="btn-submit13"><p class="btn-submit14 btnTxtBlog">Create alerts</p></div>
												</button>
											</div>
											<div class="clear_L"></div>
										</span>							
									</div>
									<div class="float_L mar_top_4p w20_per"><span>&nbsp;</span></div>
									<div class="float_L mar_top_4p w10_per"><span>&nbsp;</span></div>
									<div class="float_L mar_top_4p w20_per"><span class="mar_left_5p">&nbsp;</span></div>
									<div class="float_L mar_top_4p w10_per"><span class="mar_left_10p">&nbsp;</span></div>
									<div class="float_L mar_top_4p w10_per"><span class="mar_left_10p">&nbsp;</span></div>						
								</div>
								<div class="clear_L"></div>
							</div>
							<!--End_Details_row2-->
							<div class="lineSpace_5">&nbsp;</div>
							<div class="grayLine"></div>					
							<!--End_Details-->
					</div>
					<!--End_Date_Container_Sixth-->					

					<!--Data_Container_Seventh-->
					<div>
							<!--Start_Details_row1-->
							<?php if(isset($userAlerts['collegeRating'])): $i = 0;
								foreach($userAlerts['collegeRating'] as $temp): $i++; ?>
							<div class="row">					
								<div class="normaltxt_11p_blk">
								<div class="float_L mar_top_4p w25_per lineSpace_25">
									<span class="mar_left_10p"><?php if($i == 1): ?>
									<img src="/public/images/grayBullet.gif" width="5" height="5" align="absmiddle" />
									&nbsp;College Rating Alert<?php endif; ?></span>
								</div>
									<div class="float_L mar_top_4p w20_per lineSpace_25"><div class="mar_left_20p"><a href="#"><?php if($noOfAlerts >= $i) echo $temp['alert_name']; ?></a></div></div>
									<div class="float_L mar_top_4p w10_per">
										<span>
											<div class="buttr3">
								<?php if(isset($temp['STATE']) && (strcmp($temp['STATE'],'on') != 0)): ?>		
									<button class="btn-submit17 w1" value="" type="button">
										<div class="btn-submit17"><p class="btn-submit18 btnTxtBlog">OFF</p></div>
									</button>
								<?php else: ?>
									<button class="btn-submit15 w101">
										<div class="btn-submit15"><p class="btn-submit16 btnTxtBlog">ON</p></div>
									</button>	
								<?php endif; ?>	
											</div>
											<div class="clear_L"></div>
										</span>		
									</div>
									<div class="float_L mar_top_4p w20_per"><span class="mar_left_5p"><?php isset($temp['MAIL']) && ($temp['MAIL'] == true)?$mail = $greenCheck:$mail = $grayCheck; ?><img src="/public/images/<?php echo $mail; ?>" width="36" height="25" /><?php isset($temp['IM']) && ($temp['IM'] == true)?$im = $greenCheck:$im = $grayCheck; ?><img src="/public/images/<?php echo $im; ?>" width="36" height="25" /><?php isset($temp['SMS']) && ($temp['SMS'] == true)?$sms = $greenCheck:$sms = $grayCheck; ?><img src="/public/images/<?php echo $sms; ?>" width="36" height="25" /></span></div>
									<div class="float_L mar_top_4p w10_per"><span class="mar_left_10p"><img src="/public/images/editPencil.gif" width="27" height="25" /></span></div>
									<div class="float_L mar_top_4p w10_per"><span class="mar_left_10p"><img src="/public/images/deleteIcon.gif" width="27" height="25" /></span></div>						
								</div>
								<div class="clear_L"></div>
							</div>
							<?php endforeach;
							      endif; ?>
							<!--End_Details_row1-->
							<!--Start_Details_row2-->
							<div class="row">					
								<div class="normaltxt_11p_blk">
									<div class="float_L mar_top_4p w25_per">
										<span>
											<div class="buttr3">
												<button class="btn-submit13 w3" onClick="javascript:createOverlay('collegeRating','form3',7)">
													<div class="btn-submit13"><p class="btn-submit14 btnTxtBlog">Create alerts</p></div>
												</button>
											</div>
											<div class="clear_L"></div>
										</span>							
									</div>
									<div class="float_L mar_top_4p w20_per"><span>&nbsp;</span></div>
									<div class="float_L mar_top_4p w10_per"><span>&nbsp;</span></div>
									<div class="float_L mar_top_4p w20_per"><span class="mar_left_5p">&nbsp;</span></div>
									<div class="float_L mar_top_4p w10_per"><span class="mar_left_10p">&nbsp;</span></div>
									<div class="float_L mar_top_4p w10_per"><span class="mar_left_10p">&nbsp;</span></div>						
								</div>
								<div class="clear_L"></div>
							</div>
							<!--End_Details_row2-->
							<div class="lineSpace_5">&nbsp;</div>
							<div class="grayLine"></div>					
							<!--End_Details-->
					</div>
					<!--End_Date_Container_Seventh-->					

					<!--Data_Container_Eight-->
					<div>
							<!--Start_Details_row1-->
							<?php if(isset($userAlerts['commentAlert'])): $i = 0;
								foreach($userAlerts['commentAlert'] as $temp): $i++; ?>	
							<div class="row">					
								<div class="normaltxt_11p_blk">
								<div class="float_L mar_top_4p w25_per lineSpace_25">
									<span class="mar_left_10p"><?php if($i == 1): ?>
									<img src="/public/images/grayBullet.gif" width="5" height="5" align="absmiddle" />
									&nbsp;Comment Alert<?php endif; ?></span>
								</div>
									<div class="float_L mar_top_4p w20_per lineSpace_25"><div class="mar_left_20p"><a href="#"><?php if($noOfAlerts >= $i) echo $temp['alert_name']; ?></a></div></div>
									<div class="float_L mar_top_4p w10_per">
										<span>
											<div class="buttr3">
								<?php if(isset($temp['STATE']) && (strcmp($temp['STATE'],'on') != 0)): ?>		
									<button class="btn-submit17 w1" value="" type="button">
										<div class="btn-submit17"><p class="btn-submit18 btnTxtBlog">OFF</p></div>
									</button>
								<?php else: ?>
									<button class="btn-submit15 w101">
										<div class="btn-submit15"><p class="btn-submit16 btnTxtBlog">ON</p></div>
									</button>	
								<?php endif; ?>	
											</div>
											<div class="clear_L"></div>
										</span>		
									</div>
									<div class="float_L mar_top_4p w20_per"><span class="mar_left_5p"><?php isset($temp['MAIL']) && ($temp['MAIL'] == true)?$mail = $greenCheck:$mail = $grayCheck; ?><img src="/public/images/<?php echo $mail; ?>" width="36" height="25" /><?php isset($temp['IM']) && ($temp['IM'] == true)?$im = $greenCheck:$im = $grayCheck; ?><img src="/public/images/<?php echo $im; ?>" width="36" height="25" /><?php isset($temp['SMS']) && ($temp['SMS'] == true)?$sms = $greenCheck:$sms = $grayCheck; ?><img src="/public/images/<?php echo $sms; ?>" width="36" height="25" /></span></div>
									<div class="float_L mar_top_4p w10_per"><span class="mar_left_10p"><img src="/public/images/editPencil.gif" width="27" height="25" /></span></div>
									<div class="float_L mar_top_4p w10_per"><span class="mar_left_10p"><img src="/public/images/deleteIcon.gif" width="27" height="25" /></span></div>						
								</div>
								<div class="clear_L"></div>
							</div>
							<?php endforeach;
							      endif; ?>	
							<!--End_Details_row1-->
							<!--Start_Details_row2-->
							<div class="row">					
								<div class="normaltxt_11p_blk">
									<div class="float_L mar_top_4p w25_per">
										<span>
											<div class="buttr3">
												<button class="btn-submit13 w3" value="" type="button">
													<div class="btn-submit13"><p class="btn-submit14 btnTxtBlog">Create alerts</p></div>
												</button>
											</div>
											<div class="clear_L"></div>
										</span>							
									</div>
									<div class="float_L mar_top_4p w20_per"><span>&nbsp;</span></div>
									<div class="float_L mar_top_4p w10_per"><span>&nbsp;</span></div>
									<div class="float_L mar_top_4p w20_per"><span class="mar_left_5p">&nbsp;</span></div>
									<div class="float_L mar_top_4p w10_per"><span class="mar_left_10p">&nbsp;</span></div>
									<div class="float_L mar_top_4p w10_per"><span class="mar_left_10p">&nbsp;</span></div>						
								</div>
								<div class="clear_L"></div>
							</div>
							<!--End_Details_row2-->
							<div class="lineSpace_5">&nbsp;</div>					
							<!--End_Details-->
					</div>
					<!--End_Date_Container_Eight-->					

					
					<div class="lineSpace_20">&nbsp;</div>
				</div>
				<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>				
			</div>	
		</div>
  	</div>
	<!--End_Mid_Panel-->

<?php $this->load->view('common/footer');  ?>

