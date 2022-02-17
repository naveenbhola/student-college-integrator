<?php
/*echo "<pre>";
print_r($details);
echo "</pre>";*/
$tests_required = json_decode(htmlspecialchars_decode($details['tests_required']),true);
$tests_required_other = json_decode(htmlspecialchars_decode($details['tests_required_other']),true);

$tests_preparation = json_decode(htmlspecialchars_decode($details['tests_preparation']),true);
$tests_preparation_other = json_decode(htmlspecialchars_decode($details['tests_preparation_other']),true);

if(stripos(trim($details['url']),'http://') === FALSE && strlen($details['url'])>5)
{
    $details['url'] = "http://".trim($details['url']);
}

$discussionUrl = "/messageBoard/MsgBoard/topicDetails/".$details['categoryArr'][0]['category_id']."/".$details['threadId'];
function getCriteriaString($value)
{
	switch ($value) {
		case "minqual" :
			return "Min. Qualification";
		case "marks" :
			return "Marks";
		case "minexp" :
			return "Min. Experience";
		case "maxexp" :
			return "Max. Experience";
	}
}

if(isset($details['start_date']) && ($details['start_date'] != "0000-00-00 00:00:00") && ($details['start_date'] != null )){
    $course_start_date =  date('j F, Y',strtotime($details['start_date']));
}
if(isset($details['end_date']) && ($details['end_date'] != "0000-00-00 00:00:00") && ($details['end_date'] != null)){
    $course_end_date = date('j F, Y',strtotime($details['end_date']));
}

$locations = array();
$optionalArgs = array();
for($i = 0; $i < count($details['locations']); $i++){
//    $locations[$i]  = $details['locations'][$i]['address'];
    if(isset($locations[$i]) && (strlen($locations[$i]) >0)) {
        $locations[$i] .= ', '.$details['locations'][$i]['city_name'].', '.$details['locations'][$i]['country_name'];
        }
        else {
            $locations[$i] = $details['locations'][$i]['city_name'].', '.$details['locations'][$i]['country_name'];
        }
        $optionalArgs['location'][$i] = $details['locations'][$i]['city_name']."-".$details['locations'][$i]['country_name'];
}

$instituteUrl = getSeoUrl($details['institute_id'],"institute",$details['institute_name'],$optionalArgs);
$scholarships = array();
$notifications = array();
?>
<?php
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
<div id="mid_Panel_noRpanel" style="width:98%;margin-left:10px;">
   <?php if(!isset($cmsData)){ ?>
   <div class="float_R" style="width:120px;" >
    <div id="rightpanelads">
      <?php
    if(!isset($cmsData)){
	 $bannerProperties = array('pageId'=>'LISTINGS', 'pageZone'=>'SIDE');
//	 $this->load->view('common/banner',$bannerProperties);
     }
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
                     if($details['city_id'] == '')
                     $city = 0;
                     else
                     $city = $details['city_id'];
                     $arr = array('univname' => $details['institute_name'],'city_id'=>$city,'institute_id'=>$details['institute_id'],'source'=>'LISTING_COURSEDETAIL');
					    $this->load->view("listing/requestInfo_already",$arr);
					 } ?>
				    </div>

                                                            <?php if(!isset($cmsData)){ ?>
						                     <div class="normaltxt_11p_blk lineSpace_17" style="margin-right:230px;font-size:12px;">
                                                            <?php }else{ ?>
                                                                     <div class="normaltxt_11p_blk lineSpace_17" style="font-size:12px;">
                                                            <?php } ?>
							<div class="closeFloat">
									<div class="fontSize_16p">
                                    <?php $appendCollege = "";
                                        if (stripos($details['title'],"institute")===false && stripos($details['title'],"university")===false && stripos($details['title'],"college")===false){
                                        $appendCollege = " <span style='color:#000; font-size:14px;'> - ".$details['institute_name']."</span>";
                                        }
                                        $courseTitle =  $details['title'];
                                        if(strlen($details['url'])>5){
                                            ?>
											<a href="<?php echo $details['url']; ?>" target="_blank"><div class="OrgangeFont mar_left_10p bld"><h1 style="font-size:16px"><?php echo $courseTitle.$appendCollege;?></h1></div></a>
                                        <?php }
                                        else{?>

											<div class="OrgangeFont mar_left_10p bld"><h1 style="font-size:16px"><?php echo $courseTitle.$appendCollege;?></h1></div>
                                        <?php 
                                        }
                                        ?>

									</div>
									<div class="lineSpace_5">&nbsp;</div>
									<div class="mar_full_10p normaltxt_11p_blk" style="font-size:12px;">
		                                <?php $srcarr = array('source'=>'LISTING_COURSEDETAIL');
										 $this->load->view('listing/categoryCrumb',$srcarr); ?>
									</div>
									<?php if(isset($details['overview']) && strlen($details['overview']) > 0) { ?>
										<div class="lineSpace_10">&nbsp;</div>
										<div class="h22 bld bgcolor_div_sky">
											<div class="mar_left_10p lineSpace_23 fontSize_12p">Course Details</div>
										</div>
										<div class="mar_full_10p" style="text-align:justify">
											<?php echo htmlspecialchars_decode($details['overview']); ?>
										</div>
										<div class="lineSpace_5">&nbsp;</div>
									<?php   } ?>
									<div class="lineSpace_5">&nbsp;</div>
                                    <?php 
                                    if((strlen($details['fees']) > 0) ||
                                        (strlen($details['course_type']) > 0) ||
                                        (strlen($details['course_level']) > 0) ||
                                        (count($tests_required) > 0) ||
                                        (count($tests_required_other) > 0) ||
                                        (count($tests_preparation) > 0) ||
                                        (count($tests_preparation_other) > 0) ||
                                        (strlen($course_start_date) > 0) ||
                                        (strlen($course_end_date) > 0) ||
                                       (strlen($details['scholarshipText']) > 0) ||
                                        (strlen($details['hostel']) > 0) ||
                                        (strlen($details['placements']) > 0)) {
                                    ?>
									<div class="brddarkGreen bgcolor_green mar_left_10p">
									    <div class="lineSpace_5">&nbsp;</div>
											<?php if(isset($details['fees']) && (strlen($details['fees']) > 0)) { ?>
												<div class="mar_full_10p">
													<div class="bld float_L" style="width:150px">Fees</div>
													<div style="margin-left:160px">: <?php echo $details['fees']; ?></div>
													<div class="clear_L"></div>
												</div>
												<div class="lineSpace_5">&nbsp;</div>
											<?php } ?>
											<?php if(isset($details['course_type']) && (strlen($details['course_type']) > 0)) { ?>
												<div class="mar_full_10p">
													<div class="bld float_L" style="width:150px">Type of Course</div>
													<div style="margin-left:160px">: <?php echo $details['course_type']; ?></div>
													<div class="clear_L"></div>
												</div>
												<div class="lineSpace_5">&nbsp;</div>
											<?php } ?>
											<?php if(isset($details['course_level']) && (strlen($details['course_level']) > 0)) { ?>
												<div class="mar_full_10p">
													<div class="bld float_L" style="width:150px">Level of Course</div>
													<div style="margin-left:160px">: <?php echo $details['course_level']; ?></div>
													<div class="clear_L"></div>
												</div>
												<div class="lineSpace_5">&nbsp;</div>
											<?php } ?>

											<?php if(count($tests_preparation) > 0 || count($tests_preparation_other) > 0) { ?>
												<div class="mar_full_10p">
													<div class="bld float_L" style="width:150px">Preparation For </div>
													<div style="margin-left:160px">:&nbsp;
                                                    <?php 
                                                        if(count($tests_preparation) > 0){
                                                            echo "<a href='".$tests_preparation[0]['url']."'>".$tests_preparation[0]['blogTitle']."</a>"; 
                                                            for($i = 1;$i< count($tests_preparation); $i++){
                                                                echo ",<a href='".$tests_preparation[$i]['url']."'>".$tests_preparation[$i]['blogTitle']."</a>"; 
                                                            }
                                                        }
                                                        if(count($tests_preparation_other) > 0){
                                                            echo "&nbsp; &nbsp;&nbsp;";
                                                            echo $tests_preparation_other[0]['exam_name'];
                                                            for($i = 1;$i< count($tests_preparation_other); $i++){
                                                                echo $tests_preparation_other[$i]['exam_name']; 
                                                            }
                                                        }

                                                    ?>
                                                    </div>
													<div class="clear_L"></div>
												</div>
												<div class="lineSpace_5">&nbsp;</div>
											<?php } ?>

											<?php if(count($tests_required) > 0 || count($tests_required_other) > 0) { ?>
												<div class="mar_full_10p">
													<div class="bld float_L" style="width:150px">Tests Required</div>
													<div style="margin-left:160px">:&nbsp;
                                                    <?php 
                                                    if(count($tests_required) > 0){
                                                        echo "<a href='".$tests_required[0]['url']."'>".$tests_required[0]['blogTitle']."</a>"; 
                                                        for($i = 1;$i< count($tests_required); $i++){
                                                            echo ",<a href='".$tests_required[$i]['url']."'>".$tests_required[$i]['blogTitle']."</a>"; 
                                                        }
                                                    }
                                                    if(count($tests_required) > 0){
                                                        for($i = 0;$i< count($tests_required); $i++){
                                                            if(strlen($tests_required[$i]['valueIfAny']) > 0 ){
                                                                echo $tests_required[$i]['valueIfAny']."<br/>"; 
                                                            }
                                                        }
                                                    }
                                                    if(count($tests_required_other) > 0){
                                                        echo "&nbsp; &nbsp;&nbsp; ";
                                                        echo $tests_required_other[0]['exam_name'];
                                                        for($i = 1;$i< count($tests_required_other); $i++){
                                                            echo $tests_required_other[$i]['exam_name']; 
                                                        }
                                                    }

                                                    ?>
                                                    </div>
													<div class="clear_L"></div>
												</div>
												<div class="lineSpace_5">&nbsp;</div>
											<?php } ?>
											<?php if(isset($course_start_date) && (strlen($course_start_date) > 0)) { ?>
												<div class="mar_full_10p">
													<div class="bld float_L" style="width:150px">Start Date</div>
													<div style="margin-left:160px">: <?php echo $course_start_date; ?></div>
													<div class="clear_L"></div>
												</div>
												<div class="lineSpace_5">&nbsp;</div>
											<?php } ?>
											<?php if(isset($course_end_date) && (strlen($course_end_date) > 0)) { ?>
												<div class="mar_full_10p">
													<div class="bld float_L" style="width:150px">End Date</div>
													<div style="margin-left:160px">: <?php echo $course_end_date; ?></div>
													<div class="clear_L"></div>
												</div>
												<div class="lineSpace_5">&nbsp;</div>
											<?php } ?>
											<?php if(isset($details['scholarshipText']) && (strlen($details['scholarshipText']) > 0)) { ?>
												<div class="mar_full_10p">
													<div class="bld float_L" style="width:150px">Scholarship</div>
													<div style="margin-left:160px">: <?php echo $details['scholarshipText']; ?></div>
													<div class="clear_L"></div>
												</div>
												<div class="lineSpace_5">&nbsp;</div>
											<?php } ?>
											<?php if(isset($details['hostel']) && (strlen($details['hostel']) > 0)) { ?>
												<div class="mar_full_10p">
													<div class="bld float_L" style="width:150px">Hostel</div>
													<div style="margin-left:160px">: <?php echo $details['hostel']; ?></div>
													<div class="clear_L"></div>
												</div>
												<div class="lineSpace_5">&nbsp;</div>
											<?php } ?>
											<?php if(isset($details['placements']) && (strlen($details['placements'])) > 0) { ?>
												<div class="mar_full_10p">
													<div class="bld float_L" style="width:150px">Placements</div>
													<div style="margin-left:160px">: <?php echo $details['placements']; ?></div>
													<div class="clear_L"></div>
												</div>
												<div class="lineSpace_5">&nbsp;</div>
											<?php } ?>
									</div>
									<div class="lineSpace_10">&nbsp;</div>
                                    <?php } ?>
									<?php if(isset($details['duration']) && (strlen($details['duration']) > 0)) { ?>
										<div class="h22 bld bgcolor_div_sky">
												<div class="mar_left_10p lineSpace_23 fontSize_12p">Course Duration:</div>
										</div>
										<div class="lineSpace_10">&nbsp;</div>
										<div class="mar_full_10p" style="text-align:justify">
											<?php echo $details['duration'];?>
										</div>
										<div class="lineSpace_10">&nbsp;</div>
									<?php } ?>
									<?php if(isset($details['contents']) && (strlen($details['contents']) > 0)) { ?>
										<div class="h22 bld bgcolor_div_sky">
												<div class="mar_left_10p lineSpace_23 fontSize_12p">Course Syllabus:</div>
										</div>
										<div class="lineSpace_10">&nbsp;</div>
										<div class="mar_full_10p" style="text-align:justify">
											<?php echo htmlspecialchars_decode($details['contents']);?>
										</div>
										<div class="lineSpace_10">&nbsp;</div>
									<?php } ?>
									<?php if (count($details['eligibilityArr']) > 0) : ?>
										<div class="h22 bld bgcolor_div_sky">
												<div class="mar_left_10p lineSpace_23 fontSize_12p">Course Eligibility:</div>
										</div>
										<div class="lineSpace_10">&nbsp;</div>
										<?php foreach ($details['eligibilityArr'] as $key=>$value): ?>
											<div class="mar_full_10p">
												<div class="float_L bld" style="width:100px"><?php echo getCriteriaString($value['criteria']);?></div>
												<div style="margin-left:102px">: <?php echo htmlspecialchars_decode($value['value']);?></div>
												<div class="clear_L"></div>
											</div>
										<?php endforeach;?>
										<div class="lineSpace_10">&nbsp;</div>
									<?php endif; ?>
									<?php if(isset($details['selection_criteria']) && (strlen($details['selection_criteria']) > 0)) { ?>
										<div class="h22 bld bgcolor_div_sky">
												<div class="mar_left_10p lineSpace_23 fontSize_12p">Course Selection Criteria:</div>
										</div>
										<div class="mar_full_10p" style="text-align:justify">
											<?php echo htmlspecialchars_decode($details['selection_criteria']);?>
										</div>
										<div class="lineSpace_10">&nbsp;</div>
									<?php } ?>
									<?php if(isset($scholarships) && (count($scholarships) > 0)) { ?>
										<div class="h22 bld bgcolor_div_sky">
												<div class="mar_left_10p lineSpace_23 fontSize_12p">Scholarships:</div>
										</div>
										<div class="lineSpace_10">&nbsp;</div>
										<?php for($i = 0; $i < count($scholarships); $i++) {?>
											<div class="mar_full_10p">
												<a href="#" title="<?php  echo $scholarships[$i]['title']; ?>"><?php  echo $scholarships[$i]['title']; ?></a>
											</div>
										<?php } ?>
										<div class="lineSpace_20">&nbsp;</div>
									<?php } ?>
									<?php if(isset($notifications) && (count($notifications) > 0)) { ?>
										<div class="h22 bld bgcolor_div_sky">
												<div class="mar_left_10p lineSpace_23 fontSize_12p">Admission Notification:</div>
										</div>
										<div class="lineSpace_10">&nbsp;</div>
										<?php for($i = 0; $i < count($notifications); $i++) {?>
											<div class="mar_full_10p">
												<a href="#" title="<?php  echo $notifications[$i]['title']; ?>"><?php  echo $notifications[$i]['title']; ?></a>
											</div>
										<?php } ?>
										<div class="lineSpace_20">&nbsp;</div>
									<?php } ?>
									<div class="h22 bld bgcolor_div_sky">
											<div class="mar_left_10p lineSpace_23 fontSize_12p">Details:</div>
									</div>
									<div class="lineSpace_10">&nbsp;</div>
									<div class="mar_full_10p">
										<?php if(!isset($cmsData)){ ?>
											<div class="OrgangeFont bld">

												<a href="<?php echo $instituteUrl; ?>" title="<?php echo $details['institute_name']; ?>"><?php echo $details['institute_name']; ?></a>
											</div>
										<?php }else{ ?>
											<div class="OrgangeFont bld">
                                                                                            <a href="/enterprise/Enterprise/getDetailsForListingCMS/<?php echo $details['institute_id']; ?>/institute" title="<?php echo $details['institute_name']; ?>"><?php echo $details['institute_name']; ?></a>
											</div>
										<?php } ?>
										<?php echo '<span>'.implode('<br>',$locations).'</span>'; ?>
									</div>
									<div class="lineSpace_5">&nbsp;</div>
									<div style="text-align:justify" class="mar_full_10p">
                                    <?php if(strlen($details['institute_logo']) > 0) { ?>
									   <img src="<?php echo str_replace('_s','_m',$details['institute_logo']); ?>" class="mar_right_10p" align="left" alt="<?php echo $details['institute_name']; ?>"/>
                                    <?php  } ?>
										<?php echo htmlspecialchars_decode($details['institute_desc']); ?>
                                        <div class="clear_L"></div>
									</div>

									<div class="lineSpace_10">&nbsp;</div>
									<?php if(isset($details['photos']) && (count($details['photos']) > 0 )) { ?>
										<a name="images"></a>
										<div class="h22 bld bgcolor_div_sky">
											<div class="mar_left_10p lineSpace_23">Images</div>
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
                                    <?php
                                    if(isset($details['videos']) && (count($details['videos']) > 0 )) { ?>
										<a name="videos"></a>
										<div class="h22 bld bgcolor_div_sky">
											<div class="mar_left_10p lineSpace_23">Video</div>
										</div>
										<div class="lineSpace_10">&nbsp;</div>
                                        <div class="mar_left_10p">
											<?php for($i = 0; $i < count($details['videos']); $i++) { ?>
											<div class="float_L" style="width:225px">
                                              <object width="200" height="150" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"> <br/> <param value="lt" name="salign"/>  <param value="high" name="quality"/>  <param value="noscale" name="scale"/>  <param value="transparent" name="wmode"/>  <param value="http://geekfile.googlepages.com/flvplay.swf" name="movie"/>  <param value="&streamName=<?php echo $details['videos'][$i]['url']?>&skinName=http://geekfile.googlepages.com/flvskin&autoPlay=false&autoRewind=true" name="FlashVars"/>  <embed width="200" height="150" wmode="transparent" src="http://geekfile.googlepages.com/flvplay.swf" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" salign="LT" scale="noscale" quality="high" flashvars="&streamName=<?php echo $details['videos'][$i]['url']?>&autoPlay=false&autoRewind=true&skinName=http://geekfile.googlepages.com/flvskin"/></object>
                                              <br/>
                                              <div class="bld txt_align_c" style="width:110px;"><?php echo $details['videos'][$i]['name']; ?></div>
											  </div>
											<?php  } ?>
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
                                     //   }
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

                                    <div class="mar_left_10p">
                                    <?php  
									if(!isset($cmsData)){
		                                $sourcearr = array('source'=>'LISTING_COURSEDETAIL_MIDDLEPANEL_ASKQUESTION');
                                        $this->load->view("listing/relatedQns",$sourcearr);
                                    }
                                    ?>
                                    </div>

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
