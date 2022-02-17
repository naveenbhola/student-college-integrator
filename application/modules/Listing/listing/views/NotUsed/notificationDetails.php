<?php

if(stripos(trim($details['url']),'http://') === FALSE && strlen($details['url'])>5)
{
    $details['url'] = "http://".trim($details['url']);
}
/*echo "<pre>";
	print_r($details);
echo "</pre>";*/

$tests_required = json_decode(htmlspecialchars_decode($details['tests_required']),true);
$tests_required_other = json_decode(htmlspecialchars_decode($details['tests_required_other']),true);
$exam_info = $tests_required_other;
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
		case "age" :
			return "Age";
		case "res_stat";
			return "Residency Status";
	}
}

if(!isset($userId)){
    $subscribeAction = "showuserOverlay(this,'join');";
}
else{
    $subscribeAction = 'showAlertListingOverlay();';
}
    if(!isset($cmsData)){
        $this->load->view("listing/alertOverlay");

        $this->load->view('common/overlay');
    }
    ?>


<!--Start_Mid_Panel-->
<div id="mid_Panel_noRpanel" style="width:98%;margin-left:10px;">
   <?php if(!isset($cmsData)){ ?>
   <div class="float_R" style="width:120px;" >
    <div id="rightpanelads">
      <?php
    if(!isset($cmsData)){
	 $bannerProperties = array('pageId'=>'LISTINGS', 'pageZone'=>'SIDE');
   //      $this->load->view('common/banner',$bannerProperties);
     }
      ?>
   </div>
   </div>
   <?php } ?>
   <?php if(!isset($cmsData)){ ?>
       <div style="margin-right:130px;">
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
                                $arr = array('univname'=>$details['instituteArr'][0]['institute_name'],'city_id'=>0,'institute_id'=>$details['instituteArr'][0]['institute_id'],'source'=>'LISTING_NOTIFICATIONDETAIL');
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
										<div class="OrgangeFont mar_left_10p bld"><h1 style="font-size:16px"><?php echo $details['title']; ?></h1></div>
									</div>
									<div class="lineSpace_5">&nbsp;</div>
									<div class="mar_full_10p normaltxt_11p_blk" style="font-size:12px;">
		                                <?php $srcarr = array('source'=>'LISTING_NOTIFICATIONDETAIL');
										 $this->load->view('listing/categoryCrumb',$srcarr); ?>
									</div>
									<?php if (isset($details['desc']) && strlen($details['desc'])>0) :?>
										<div class="lineSpace_10">&nbsp;</div>
										<div class="h22 bld bgcolor_div_sky">
											<div class="mar_left_10p lineSpace_23 fontSize_12p">Admission Details</div>
										</div>
										<div class="lineSpace_10">&nbsp;</div>
										<div class="mar_full_10p" style="text-align:justify">
											<?php echo htmlspecialchars_decode($details['desc']); ?>
										</div>
										<div class="lineSpace_10">&nbsp;</div>
									<?php endif; ?>
									<?php
									$eligibility =array();
									for($i = 0 ; $i < count($details['eligibilityArr']); $i++){
										switch($details['eligibilityArr'][$i]['criteria']){
											case "age":
												$eligibility[$i]['criteria'] = "Age";
												$eligibility[$i]['value'] = $details['eligibilityArr'][$i]['value'];
												continue;
												break;
											case "res":
												$eligibility[$i]['criteria'] = "Residency Status";
												$eligibility[$i]['value'] = $details['eligibilityArr'][$i]['value'];
												continue;
												break;
											case "minqual":
												$eligibility[$i]['criteria'] = "Minimum Qualification";
												$eligibility[$i]['value'] = $details['eligibilityArr'][$i]['value'];
												continue;
												break;
											case "marks":
												$eligibility[$i]['criteria'] = "Minimum Marks";
												$eligibility[$i]['value'] = $details['eligibilityArr'][$i]['value'];
												continue;
												break;
										}

									}

									if(isset($details['application_brochure_start_date']) && ($details['application_brochure_start_date'] != "0000-00-00 00:00:00")){
										$app_start_date =  date('j F',strtotime($details['application_brochure_start_date']));
									}
									if(isset($details['application_brochure_end_date']) && ($details['application_brochure_end_date'] != "0000-00-00 00:00:00")){
										$app_end_date = date('j F',strtotime($details['application_brochure_end_date']));
									}
									if(isset($details['application_end_date']) && ($details['application_end_date'] != "0000-00-00 00:00:00")){
										$end_date =  date('j F',strtotime($details['application_end_date']));
									}

									if($details['entrance_exam'] == "yes") {
										if(isset($exam_info[0]['exam_date']) && ($exam_info[0]['exam_date'] != "0000-00-00 00:00:00")){
											$exam_date =  date('j F',strtotime($exam_info[0]['exam_date']));
										}
										$exam_duration = $exam_info[0]['exam_duration'];
										$exam_timings = $exam_info[0]['exam_timings'];
									}
									else{
										$exam_date = "Not specified";
										$exam_duration = "Not Specified";
										$exam_timings = "Not Specified";
									}
									?>
									<?php if (count($details['eligibilityArr']) > 0) : ?>
										<div class="h22 bld bgcolor_div_sky">
										   	<div class="mar_left_10p lineSpace_23 fontSize_12p">Admission Eligibility:</div>
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

									<?php if (isset($app_start_date) || isset($app_end_date) || isset($end_date)) :?>
										<div class="h22 bld bgcolor_div_sky">
											<div class="mar_left_10p lineSpace_23 fontSize_12p">Important Dates</div>
										</div>
										<div class="lineSpace_10">&nbsp;</div>
										<?php if(isset($app_start_date)) { ?>
											<div class="mar_full_10p">Application form and brochures are available from <?php echo $app_start_date;?> to <?php echo $app_end_date;?>.</div>
											<div class="lineSpace_10">&nbsp;</div>
										<?php } ?>
										<?php if(isset($app_end_date)) { ?>
											<div class="mar_full_10p">Last Date for sale of Application forms and brochures is <?php echo $app_end_date;?>.</div>
											<div class="lineSpace_10">&nbsp;</div>
										<?php } ?>
										<?php if(isset($end_date)) { ?>
											<div class="mar_full_10p">Last Date for Receipt of Application is <?php echo $end_date;?>.</div>
											<div class="lineSpace_10">&nbsp;</div>
										<?php }  ?>
                                    <?php endif; ?>
									<?php if(isset($details['application_procedure']) && (strlen($details['application_procedure']) > 0) || count($details['docs'])>0) { ?>
										<div class="h22 bld bgcolor_div_sky">
											<div class="mar_left_10p lineSpace_23 fontSize_12p">Application Procedure:</div>
										</div>
										<div class="lineSpace_10">&nbsp;</div>
                                        <?php  if(isset($details['application_procedure']) && (strlen($details['application_procedure']) > 0)) { ?>
										<div class="mar_full_10p" style="text-align:justify">
											<?php echo $details['application_procedure']?>
										</div>
										<div class="lineSpace_10">&nbsp;</div>
                                        <?php } ?>
										<div class="h22 bld mar_full_10p">
											<img src="/public/images/attachment.gif" width="20" height="16" align="absmiddle" style="margin-left:10px" />Upload Files:
										</div>
											<?php for($i = 0 ; $i < count($details['docs']) ; $i++) { ?>
											<div class="mar_full_10p">
												<a href="<?php echo $details['docs'][$i]['url'] ; ?>"><?php ($details['docs'][$i]['name']!="")?$s=$details['docs'][$i]['name']:$s="Document ".($i+1); echo $s; ?></a>
											</div>
											<?php } ?>
										<div class="lineSpace_10">&nbsp;</div>
									<?php } ?>
									<?php if($details['application_fees']!="" || $details['entrance_exam']=="yes" || $details['admission_year']!=""):?>
										<div class="h22 bld bgcolor_div_sky">
											<div class="mar_left_10p lineSpace_23 fontSize_12p">Application Fee &amp; Other Details:</div>
										</div>
										<div class="lineSpace_10">&nbsp;</div>
										<div class="mar_full_10p">
											<div class="bld float_L" style="width:100px">Admission Year:</div>
											<div style="margin-left:102px"><?php echo $details['admission_year']; ?></div>
											<div class="clear_L"></div>
										</div>
										<?php if ($details['application_fees']!="") : ?>
											<div class="mar_full_10p">
												<div class="bld float_L" style="width:100px">Application Fees:</div>
												<div style="margin-left:102px"><?php echo $details['application_fees']; ?></div>
												<div class="clear_L"></div>
											</div>
										<?php endif; ?>
										<?php if($details['entrance_exam'] == "yes") { ?>
											<?php if (count($tests_required) > 0) { ?>
												<div class="mar_full_10p">
													<div class="bld float_L" style="width:100px">Exam Name:</div>
													<div style="margin-left:102px">
                                                    <?php
                                                    if(count($tests_required) > 0){
                                                        echo "<a href='".$tests_required[0]['url']."'>".$tests_required[0]['blogTitle']."</a>"; 
                                                        for($i = 1;$i< count($tests_required); $i++){
                                                            echo ",<a href='".$tests_required[$i]['url']."'>".$tests_required[$i]['blogTitle']."</a>"; 
                                                        }
                                                    }
                                                    ?>

                                                    </div>
													<div class="clear_L"></div>
												</div>
											<?php } ?>

                                            <?php 
                                            if( count($exam_info) > 0){
											if ($exam_info[0]['exam_name']!="") :?>
												<div class="mar_full_10p">
													<div class="bld float_L" style="width:100px">Other Exam Name:</div>
													<div style="margin-left:102px"><?php echo $exam_info[0]['exam_name']; ?></div>
													<div class="clear_L"></div>
												</div>
											<?php endif; 

											if ($exam_date!="") :?>
												<div class="mar_full_10p">
													<div class="bld float_L" style="width:100px">Exam Date:</div>
													<div style="margin-left:102px"><?php echo $exam_date; ?></div>
													<div class="clear_L"></div>
												</div>
											<?php endif; ?>
											<?php if ($exam_duration!=""):?>
												<div class="mar_full_10p">
													<div class="bld float_L" style="width:100px">Exam Duration:</div>
													<div style="margin-left:102px"><?php echo $exam_duration; ?></div>
													<div class="clear_L"></div>
												</div>
											<?php endif; ?>
											<?php if ($exam_timings!="") :?>
												<div class="mar_full_10p">
													<div class="bld float_L" style="width:100px">Exam Timing</div>
													<div style="margin-left:102px"><?php echo $exam_timings; ?></div>
													<div class="clear_L"></div>
												</div>
											<?php endif; ?>
											<?php if(count($exam_info[0]['examcentres']) > 0 ) { ?>
												<div class="mar_full_10p">
													<div class="bld float_L" style="width:100px">Examination Centers:</div>
													<div style="margin-left:102px">
														<?php foreach($exam_info[0]['examcentres'] as $examCentre) { ?>
														<div>
															<?php $str= "";
																if ($examCentre['address_line1']!="")
																	$str .= " ".$examCentre['address_line1'].",";
																if ($examCentre['address_line2']!="")
																	$str .= " ".$examCentre['address_line2'].",";
																if ($examCentre['city']!="")
																	$str .= " ".$examCentre['city'].",";
																if ($examCentre['country']!="")
																	$str .= " ".$examCentre['country'].",";
																if ($examCentre['zip']!="" && $examCentre['zip']!=0 )
																	$str .= " ".$examCentre['zip'].",";
																$str = substr($str,0,strlen($str)-1);
																echo $str;?>
														</div>
														<?php } ?>
													</div>
													<div class="clear_L"></div>
												</div>
											<?php } } } ?>
											<div class="lineSpace_10">&nbsp;</div>
									<?php endif; ?>
									<div class="h22 bld bgcolor_div_sky">
										<div class="mar_left_10p lineSpace_23 fontSize_12p">University Details:</div>
									</div>
									<div class="lineSpace_10">&nbsp;</div>
									<div class="mar_full_10p" style="text-align:justify">
										<?php if(!isset($cmsData)){ ?>

											<?php foreach($details['instituteArr'] as $insti) : ?>
											<div class="OrgangeFont bld">
													<a href="<?php echo $insti['url'];?>" title="<?php echo $insti['institute_name']; ?>"><?php echo $insti['institute_name']; ?></a>
											</div>
										    <div class="lineSpace_5">&nbsp;</div>
											<?php endforeach; ?>
										<?php }else{ ?>
											<div class="OrgangeFont bld">
                                                                                            <a href="/enterprise/Enterprise/getDetailsForListingCMS/<?php echo $details['institute_id']; ?>/institute" title="<?php echo $details['institute_name']; ?>"><?php echo $details['institute_name']; ?></a>
											</div>
										<?php } ?>
										<div class="lineSpace_5">&nbsp;</div>
										<div>
											<?php if (isset($details['instituteArr'][0]['institute_logo']) && $details['instituteArr'][0]['institute_logo']!="") { ?>
											<img src="<?php echo $details['instituteArr'][0]['institute_logo']; ?>" class="mar_right_10p" align="left"  alt="<?php echo $details['institute_name']; ?>"/>
											<?php } ?>
											<?php echo htmlspecialchars_decode($details['instituteArr'][0]['institute_desc']); ?>
										</div>
									</div>
   <?php  
                                         if(isset($validateuser[0]) && isset($validateuser[0]['displayname']) && $registerText['paid'] == "yes"){ 
										$this->load->view('listing/queryBottom'); 
                                    }
                                    ?>

											<div class="lineSpace_10">&nbsp;</div>
								<?php 
                                //if($registerText['paid'] == "no") {
                                        $this->load->view("listing/contactInfo");
                                //        }
                                     ?>

									<div class="lineSpace_10">&nbsp;</div>										<?php if((isset($details['url']) && strlen($details['url']) > 5)) { ?>
                                        <?php if($details['crawled'] == "crawled") { ?>
											This information was retrieved from - <a href="<?php echo $details['url'];?>" target="_blank"> <span style="font-weight:bold;font-size:11px;"> <?php echo $details['url'];?> </span></a>- on <span style="color:#888;"><?php echo date('M j, Y',strtotime($details['timestamp'])); ?> </span>
                                            <?php }else { ?>
											This information was compiled from - <a href="<?php echo $details['url'];?>" target="_blank"> <span style="font-weight:bold;font-size:11px;"> <?php echo $details['url'];?> </span></a>- on <span style="color:#888;"><?php echo date('M j, Y',strtotime($details['timestamp'])); ?> </span>
                                            <?php } ?>
											<?php }?>


                                                                        <?php 
                                                                            if(!isset($cmsData)){
		                                $sourcearr = array('source'=>'LISTING_NOTIFICATIONDETAIL_MIDDLEPANEL_ASKQUESTION');
                                        $this->load->view("listing/relatedQns",$sourcearr);
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
