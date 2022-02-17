<?php

/*echo "<pre>";
  print_r($details);
  echo "</pre>";*/
if(stripos(trim($details['url']),'http://') === FALSE && strlen($details['url'])>5)
{
    $details['url'] = "http://".trim($details['url']);
}

$scholarships = array();
$courses = array();
$notifications = array();
$locations =array();
for($i = 0; $i < count($details['sublisting']); $i++){
    //echo $details['sublisting'][$i]['sublistingType'];
    switch ($details['sublisting'][$i]['sublistingType']) {

        case "notification":

            array_push($notifications,array(
                        'sublistingId'=>$details['sublisting'][$i]['sublistingId'],
                        'title'=>$details['sublisting'][$i]['title'],
                        'catId'=>$details['sublisting'][$i]['categoryIds'],
                        ));
        break;
        case "scholarship":
            array_push($scholarships,array(
                        'sublistingId'=>$details['sublisting'][$i]['sublistingId'],
                        'title'=>$details['sublisting'][$i]['title'],
                        'catId'=>$details['sublisting'][$i]['categoryIds'],
                        ));
        break;
        case "course":

            array_push($courses,array(
                        'sublistingId'=>$details['sublisting'][$i]['sublistingId'],
                        'title'=>$details['sublisting'][$i]['title'],
                        'catId'=>$details['sublisting'][$i]['categoryIds'],
                        ));
        break;

    }
}
//echo "<pre>".print_r($courses,true)."<pre/>";
$courses  = reorderArray($courses,$refCategory,$refCategoryParent,'catId');
$optionalArgs = array();
for($i = 0; $i < count($details['locations']); $i++){
    $locations[$i]  = $details['locations'][$i]['address'];
    if(isset($locations[$i]) && (strlen($locations[$i]) >0)) {
        $locations[$i] .= ', '.$details['locations'][$i]['city_name'].', '.$details['locations'][$i]['country_name'];
        }
        else {
            $locations[$i] = $details['locations'][$i]['city_name'].', '.$details['locations'][$i]['country_name'];
        }
        $optionalArgs['location'][$i] = $details['locations'][$i]['city_name']."-".$details['locations'][$i]['country_name'];
}

?>


	<?php
/*    if(!isset($userId)){
        $subscribeAction = "showuserOverlay(this,'join');";
    }
else{
    $subscribeAction = 'showAlertListingOverlay();';
}*/
    if(!isset($cmsData)){
        $this->load->view("listing/alertOverlay");

        $this->load->view('common/overlay');
        }

    ?>

    <!--Start_Mid_Panel-->
	<style>
		*html .ie6align{float:left;margin-right:10px}
		.mar_right_130{margin-right:130px}
	</style>
    <div id="mid_Panel_noRpanel" style="width:98%;margin-left:10px;" >
   <?php if(!isset($cmsData)){ ?>
    <div class="float_R" style="width:120px;">
    <div id="rightpanelads">
     <?php
    /*if(!isset($cmsData)){
	$bannerProperties = array('pageId'=>'LISTINGS', 'pageZone'=>'SIDE');
	$this->load->view('common/banner',$bannerProperties);
     }*/
     ?>
    </div>
  </div>
   <?php } ?>
   <?php if(!isset($cmsData)){ ?>
       <div class="mar_right_130 ie6align">
   <?php }else{ ?>
       <div>
   <?php } ?>
        <div>
        <?php $this->load->view("listing/breadCrumb"); ?>
        </div>
		<div class="raised_lgraynoBG">
			<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
			<div class="boxcontent_lgraynoBG">
					<div class="lineSpace_10">&nbsp;</div>
					<div>
							<div id="right_Form">
								<?php if(!isset($cmsData)){
                                if($details['locations'][0]['city_id'] == '')
                                $city = 0;
                                else
                                $city = $details['locations'][0]['city_id'];
                               $arr = array('univname'=>$details['title'],'city_id'=>$city,'institute_id'=>$details['institute_id'],'source'=>'LISTING_INSTITUTEDETAIL');                                
										$this->load->view("listing/requestInfo_already",$arr);
								} ?>
							</div>
                                                            <?php if(!isset($cmsData)){ ?>
							             <div class="mar_right_265p normaltxt_11p_blk lineSpace_17" style="margin-right:230px;font-size:12px;">
                                                            <?php }else{ ?>
                                                                     <div class="normaltxt_11p_blk lineSpace_17" style="font-size:12px;">
                                                            <?php } ?>
								<div class="closeFloat">
                                                                    <div class="fontSize_16p">
                                                                        <div class="OrgangeFont mar_left_10p bld">
                                                                            <?php if (isset($details['outLink']) && $details['outLink']!="") { ?>
                                                                            <a target="_blank" href="<?php 
                                                                                    if(strncasecmp(substr(strtolower(trim($details['outLink'])),0,7),"http://",7)==0){
                                                                                        echo trim($details['outLink']);
                                                                                    }else{
                                                                                        echo "http://".trim($details['outLink']);
                                                                                    }
                                                                                ?>" class="OrgangeFont bld" style="font-size:16px" title="<?php echo $details['title']; ?>"><h1 style="font-size:16px">
                                                                                <?php } ?>
                                                                                <?php echo $details['title']; ?>
                                                                                <?php  if (isset($details['outLink']) && $details['outLink']!="") { ?>
                                                                            </h1></a>
                                                                            <?php } ?>
                                                                        </div>
                                                                    </div>
									<div class="lineSpace_5">&nbsp;</div>
									<div class="mar_full_10p normaltxt_11p_blk" style="font-size:12px;">
		                                <?php $srcarr = array('source'=>'LISTING_INSTITUTEDETAIL');
										 $this->load->view('listing/categoryCrumb',$srcarr); ?>
									</div>
									<?php if((isset($details['short_desc']) && (strlen($details['short_desc']) > 0)) || (strlen($details['institute_logo']) > 0)) { ?>
										<div class="lineSpace_10">&nbsp;</div>
										<div class="h22 bld bgcolor_div_sky">
										   <div class="mar_left_10p lineSpace_23 fontSize_12p">Details</div>
										</div>
										<div class="lineSpace_10">&nbsp;</div>
										<div class="mar_full_10p" style="text-align:justify">
												<?php if(isset($details['institute_logo']) && (strlen($details['institute_logo']) > 0)) { ?>
														<img src="<?php echo str_replace('_s','_m',$details['institute_logo']); ?>" class="mar_right_10p" align="left"  alt="<?php echo $details['title']; ?>" title="<?php echo $details['title']; ?>" />
												<?php } ?>
												<?php echo htmlspecialchars_decode($details['short_desc']); ?>
											<div class="clear_L"></div>
										</div>
										<div class="lineSpace_10">&nbsp;</div>
									<?php } ?>
									<div class="h22 bld bgcolor_div_sky">
											<div class="mar_left_10p lineSpace_23 fontSize_12p">Other Details:</div>
									</div>
									<div class="lineSpace_10">&nbsp;</div>
									<?php if(isset($details['certification']) && (strlen($details['certification']) > 0)) { ?>
										<div class="mar_full_10p">
											<div style="width:120px" class="float_L bld txt_align_r">Affiliated to:</div>
											<div style="margin-left:130px"><?php echo $details['certification']; ?></div>
											<div class="clear_L"></div>
										</div>
										<div class="lineSpace_5">&nbsp;</div>
									<?php } ?>
									<?php if(isset($details['establish_year']) && (strlen($details['establish_year']) > 0)) { ?>
										<div class="mar_full_10p">
											<div style="width:120px" class="float_L bld txt_align_r">Established in:</div>
											<div style="margin-left:130px"><?php echo $details['establish_year']; ?></div>
											<div class="clear_L"></div>
										</div>
										<div class="lineSpace_5">&nbsp;</div>
									<?php } ?>
									<?php if(isset($locations) && (count($locations) > 0)) { ?>
										<div class="mar_full_10p">
											<div style="width:120px" class="float_L bld txt_align_r">Location:</div>
											<div style="margin-left:130px"><?php echo '<span>'.implode('<br>',$locations).'</span>'; ?></div>
											<div class="clear_L"></div>
										</div>
										<div class="lineSpace_5">&nbsp;</div>
									<?php } ?>
									<?php if(isset($details['no_of_students']) && (strlen($details['no_of_students']) > 0)) { ?>
										<div class="mar_full_10p">
											<div style="width:120px" class="float_L bld txt_align_r">No. Of Students:</div>
											<div style="margin-left:130px"><?php echo $details['no_of_students']; ?></div>
											<div class="clear_L"></div>
										</div>
										<div class="lineSpace_5">&nbsp;</div>
									<?php } ?>
									<?php if(isset($details['no_of_int_students']) && (strlen($details['no_of_int_students']) > 0)) { ?>
										<div class="mar_full_10p">
											<div style="width:120px" class="float_L bld txt_align_r">No. Of International Students:</div>
											<div style="margin-left:130px"><?php echo $details['no_of_int_students']; ?></div>
											<div class="clear_L"></div>
										</div>
										<div class="lineSpace_5">&nbsp;</div>
									<?php } ?>
									<div class="lineSpace_10">&nbsp;</div>
									<?php if(isset($courses) && (count($courses) > 0)) { ?>
										<div class="h22 bld bgcolor_div_sky">
											<div class="mar_left_10p lineSpace_23 fontSize_12p">Course Details:</div>
										</div>
										<div class="lineSpace_10">&nbsp;</div>
										<?php for($i = 0; $i < count($courses); $i++) {?>
                                        <?php if ($i==5) { ?>
                                            <div id="morecourses" style="display:none">
                                        <?php } ?>
										<?php  if(!isset($cmsData)){ ?>
											<div class="mar_full_10p">
                                            <?php error_log_shiksha("seo ".$courses[$i]['title']); ?>
                                            <?php 
                                            $tempOptionalArgs = $optionalArgs;
                                            $tempOptionalArgs['institute'] = $details['title'];
                                            $courseUrl = getSeoUrl($courses[$i]['sublistingId'],"course",$courses[$i]['title'],$tempOptionalArgs);
                                            ?>
												<a href="<?php echo $courseUrl; ?>" title="<?php  echo $courses[$i]['title']; ?>"><?php  echo $courses[$i]['title']; ?></a>
											</div>
										<?php }else{ ?>
										<div class="mar_full_10p">
                                                                                    <a href="/enterprise/Enterprise/getDetailsForListingCMS/<?php echo $courses[$i]['sublistingId']; ?>/course" title="<?php  echo $courses[$i]['title']; ?>"><?php  echo $courses[$i]['title']; ?></a>
										</div>
										<?php } ?>
										<?php } ?>
                                        <?php if (count($courses)>5) { ?>
                                            </div>
                                                <div id="expandcourses" class="mar_left_10p"><a href="javascript:void(0);" onclick="$('morecourses').style.display='';$('expandcourses').style.display = 'none';$('collapsecourses').style.display='';" title="Show All Courses">Show All Courses</a></div>
                                                <div id="collapsecourses" class="mar_left_10p" style="display:none"><a href="javascript:void(0);" onclick="$('morecourses').style.display='none';$('collapsecourses').style.display = 'none';$('expandcourses').style.display='';" title="Close this list">Close this list</a></div>
                                                <?php } ?>
										<div class="lineSpace_20">&nbsp;</div>
									<?php } ?>
									<?php if(isset($scholarships) && (count($scholarships) > 0)) { ?>
										<div class="h22 bld bgcolor_div_sky">
											<div class="mar_left_10p lineSpace_23 fontSize_12p">Scholarship:</div>
										</div>
										<div class="lineSpace_10">&nbsp;</div>
										<?php for($i = 0; $i < count($scholarships); $i++) {?>
										<?php  if(!isset($cmsData)){ 
                                        
                                            $tempOptionalArgs = $optionalArgs;
                                            $tempOptionalArgs['institute'] = $details['title'];
                                            $scholarshipUrl = getSeoUrl($scholarships[$i]['sublistingId'],"scholarship",$scholarships[$i]['title'],$tempOptionalArgs);

                                        ?>
											<div class="mar_full_10p">
												<a href="<?php echo $scholarshipUrl; ?>" title="<?php  echo $scholarships[$i]['title']; ?>"><?php  echo $scholarships[$i]['title']; ?></a>
											</div>
										<?php }else{ ?>
											<div class="mar_full_10p">
                                                                                            <a href="/enterprise/Enterprise/getDetailsForListingCMS/<?php echo $scholarships[$i]['sublistingId']; ?>/scholarship" title="<?php  echo $scholarships[$i]['title']; ?>"><?php  echo $scholarships[$i]['title']; ?></a>
											</div>
										<?php } ?>
										<?php } ?>
										<div class="lineSpace_20">&nbsp;</div>
									<?php } ?>
									<?php if(isset($notifications) && (count($notifications) > 0)) { ?>
										<div class="h22 bld bgcolor_div_sky">
											<div class="mar_left_10p lineSpace_23 fontSize_12p">Admission Notification:</div>
										</div>
										<div class="lineSpace_10">&nbsp;</div>
										<?php for($i = 0; $i < count($notifications); $i++) {?>
											<?php  if(!isset($cmsData)){ 
                                                $tempOptionalArgs = $optionalArgs;
                                                $tempOptionalArgs['institute'] = $details['title'];
                                                $notificationUrl = getSeoUrl($notifications[$i]['sublistingId'],"notification",$notifications[$i]['title'],$tempOptionalArgs);


                                            
                                            ?>
												<div class="mar_full_10p">
												   <a href="<?php echo $notificationUrl; ?>" title="<?php  echo $notifications[$i]['title']; ?>"><?php  echo $notifications[$i]['title']; ?></a>
												</div>
											<?php }else{ ?>
												<div class="mar_full_10p">
                                                                                                    <a href="/enterprise/Enterprise/getDetailsForListingCMS/<?php echo $notifications[$i]['sublistingId']; ?>/notification" title="<?php  echo $notifications[$i]['title']; ?>"><?php  echo $notifications[$i]['title']; ?></a>
												</div>
											<?php } ?>
										<?php } ?>
										<div class="lineSpace_20">&nbsp;</div>
									<?php } ?>
									<?php if(isset($details['photos']) && (count($details['photos']) > 0)) { ?>
										<div class="lineSpace_10">&nbsp;</div>
										<a name="images"></a>
										<div class="h22 bld bgcolor_div_sky">
											<div class="mar_left_10p lineSpace_23 fontSize_12p">Images</div>
										</div>
										<div class="lineSpace_10">&nbsp;</div>
										<div class="mar_left_10p">
											<table border="0" cellspacing="10" cellpadding="0" width="100%">
												<tr>
													<?php for($i = 0; $i < count($details['photos']); $i++) { ?>
													<td width="30%" valign="top">
														<div>
															<a href="<?php echo $details['photos'][$i]['url']; ?>">
																<img src="<?php echo str_replace('_s','_m',$details['photos'][$i]['thumburl']); ?>" border="0" />
															</a>
														</div>
														<div class="bld"><?php echo $details['photos'][$i]['name']; ?></div>
													</td>													
													<?php } ?>
												</tr>
											</table>
										</div>										
										<div class="lineSpace_10">&nbsp;</div>
									<?php } ?>
									<?php if(isset($details['videos']) && (count($details['videos']) > 0)) { ?>
										<a name="videos"></a>
										<div class="h22 bld bgcolor_div_sky">
											<div class="mar_left_10p lineSpace_23">Videos</div>
										</div>
										<div class="lineSpace_10">&nbsp;</div>

										<div class="mar_left_10p">
											<?php for($i = 0; $i < count($details['videos']); $i++) { ?>
											<div class="float_L" style="width:225px;padding-left:10px;">
                                              <object width="200" height="150" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"> <br/> <param value="lt" name="salign"/>  <param value="high" name="quality"/>  <param value="noscale" name="scale"/>  <param value="transparent" name="wmode"/>  <param value="http://geekfile.googlepages.com/flvplay.swf" name="movie"/>  <param value="&streamName=<?php echo $details['videos'][$i]['url']?>&skinName=http://geekfile.googlepages.com/flvskin&autoPlay=false&autoRewind=true" name="FlashVars"/>  <embed width="200" height="150" wmode="transparent" src="http://geekfile.googlepages.com/flvplay.swf" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" salign="LT" scale="noscale" quality="high" flashvars="&streamName=<?php echo $details['videos'][$i]['url']?>&autoPlay=false&autoRewind=true&skinName=http://geekfile.googlepages.com/flvskin"/></object>
                                              <br/>
                                              <div class="bld txt_align_c" style="width:110px;"><?php echo $details['videos'][$i]['name']; ?></div>
                                              </div>
											<?php } ?>
											<div class="clear_L"></div>
										</div>
										<div class="lineSpace_10">&nbsp;</div>
									<?php } ?>
									<?php if(isset($details['docs']) && (count($details['docs']) > 0 )) { ?>
										<a name="docs"></a>
										<div class="h22 bld bgcolor_div_sky">
											<div class="mar_left_10p lineSpace_23">Documents</div>
										</div>
										<div class="lineSpace_10">&nbsp;</div>
										<div class="mar_left_10p">
											<?php for($i = 0; $i < count($details['docs']); $i++) { ?>
												<div class="float_L" style="width:30%"><a href="<?php  echo $details['docs'][$i]['url']; ?>"><?php ($details['docs'][$i]['name']!="")?$s=$details['docs'][$i]['name']:$s="Document ".($i+1); echo $s; ?></a></div>
											<?php  } ?>
												<div class="clear_L"></div>
										</div>
										<div class="lineSpace_10">&nbsp;</div>
									<?php } ?>

                                 <?php  
                                         if(isset($validateuser[0]) && isset($validateuser[0]['displayname']) && $registerText['paid'] == "yes"){ 
										$this->load->view('listing/queryBottom'); 
                                    }
                                    ?>


									<?php 
                                    //if($registerText['paid'] == "no") {
                                        $this->load->view("listing/contactInfo");
                                    //    }
                                     ?>

									<div class="txt_align_l" style="margin-left:10px;">
										<?php if((isset($details['url']) && strlen($details['url']) > 5)) { ?>
                                        <?php if($details['crawled'] == "crawled") { ?>
											This information was retrieved from - <a href="<?php echo $details['url'];?>" target="_blank"> <span style="font-weight:bold;font-size:11px;"> <?php echo $details['url'];?> </span></a>- on <span style="color:#888;"><?php echo date('M j, Y',strtotime($details['timestamp'])); ?> </span>
                                            <?php }else { ?>
											This information was compiled from - <a href="<?php echo $details['url'];?>" target="_blank"> <span style="font-weight:bold;font-size:11px;"> <?php echo $details['url'];?> </span></a>- on <span style="color:#888;"><?php echo date('M j, Y',strtotime($details['timestamp'])); ?> </span>
                                            <?php } ?>
											<?php }?>
									</div>

									<div class="lineSpace_10">&nbsp;</div>

                                       <?php  
									if(!isset($cmsData)){
		                                $sourcearr = array('source'=>'LISTING_INSTITUTEDETAIL_MIDDLEPANEL_ASKQUESTION');
                                    }
                                    ?>
									<div class="lineSpace_10">&nbsp;</div>
                                    <div class="mar_full_10p">
                                       <?php if(!isset($cmsData)){
                                            $bannerProperties = array('pageId'=>'LISTINGS', 'pageZone'=>'FOOTER');
                                            $this->load->view('common/banner',$bannerProperties);
                                            }
                                        ?>
                                    </div>
									<?php if(!isset($cmsData)){ ?>
										<!--<div class="txt_align_r">
											<?php if((is_array($validateuser) && $validateuser != "false")) { ?>
												<a href="/listing/Listing/addCourse" >Add Your Institute Information</a>
												<?php }else { ?>
												<a href="javascript:void(0);" onClick = "showuserOverlay(this,'ask','<?php echo site_url('listing/Listing/addCourse');?>',1);return false;">Add Your Institute Information</a>
												<?php }?> <span style="color:#ccc">|</span>
												<?php if((is_array($validateuser) && $validateuser != "false")) { ?>
												<a href="/listing/Listing/addScholarship" >Add Scholarship Listing</a>
												<?php }else { ?>
												<a href="javascript:void(0);" onClick = "showuserOverlay(this,'ask','<?php echo site_url('listing/Listing/addScholarship');?>',1);return false;">Add Scholarship Listing</a>
												<?php }?> <span style="color:#ccc">|</span>
												<?php if((is_array($validateuser) && $validateuser != "false")) { ?>
												<a href="/listing/Listing/addAddmission" >Add Admission Notification</a>
												<?php }else { ?>
												<a href="javascript:void(0);" onClick ="showuserOverlay(this,'ask','<?php echo site_url('listing/Listing/addAddmission');?>',1);return false;">Add Admission Notification</a> &nbsp; &nbsp;
											<?php }?>
										  </div>-->
									<?php } ?>



								</div>
								<div class="clear_L"></div>
							</div>
							<div class="clear_R"></div>
					</div>
					<div class="lineSpace_10">&nbsp;</div>
			</div>
			<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
		</div>
	     </div>

</div>
<!--End_Mid_Panel-->
