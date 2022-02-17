<?php
/*echo "<pre>";
	print_r($details);
echo "</pre>";*/
if(stripos(trim($details['url']),'http://') === FALSE && strlen($details['url'])>5)
{
    $details['url'] = "http://".trim($details['url']);
}

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
		case "faminc";
			return "Income Limit";
		case "gender" :
			return "Gender";
		case "other":
			return "Other";
		case "workex" :
			return "Work Experience";
	}
}
    if(!isset($cmsData)){
$this->load->view('common/overlay');
$this->load->view("listing/alertOverlay");
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
   //      $this->load->view('common/banner',$bannerProperties);
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
                                $arr = array('univname'=>'','source'=>'LISTING_SCHOLARDETAIL');
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
										<div class="OrgangeFont mar_left_10p bld"><h1 style="font-size:16px"><?php  echo $details['title'];?></h1></div>
									</div>
									<div class="lineSpace_5">&nbsp;</div>
									<div class="mar_full_10p normaltxt_11p_blk" style="font-size:12px;">
		                                <?php $srcarr = array('source'=>'LISTING_SCHOLARDETAIL');
										 $this->load->view('listing/categoryCrumb',$srcarr); ?>
									</div>
									<?php if ($details['desc']!="") :?>
										<div class="h22 bld bgcolor_div_sky">
											<div class="mar_left_10p lineSpace_23 fontSize_12p">Scholarship Details:</div>
										</div>
										<div class="lineSpace_10">&nbsp;</div>
										<div class="mar_full_10p" style="text-align:justify">
											<?php  echo htmlspecialchars_decode($details['desc']);?>
										</div>
										<div class="lineSpace_10">&nbsp;</div>
									<?php endif; ?>
									<?php if ($details['levels']!="") :?>
										<div class="h22 bld bgcolor_div_sky">
											<div class="mar_left_10p lineSpace_23 fontSize_12p">Purpose of Scholarship:</div>
										</div>
										<div class="lineSpace_10">&nbsp;</div>
										<div class="mar_full_10p" style="text-align:justify">
												<?php  echo $details['levels'];?>
										</div>
										<div class="lineSpace_10">&nbsp;</div>
									<?php endif; ?>
									<?php if ($details['num_of_schols']!="") : ?>
										<div class="h22 bld bgcolor_div_sky">
											<div class="mar_left_10p lineSpace_23 fontSize_12p">Number of Scholarship available:</div>
										</div>
										<div class="lineSpace_10">&nbsp;</div>
										<div class="mar_full_10p" style="text-align:justify">
											<?php echo $details['num_of_schols']; ?>
										</div>
										<div class="lineSpace_10">&nbsp;</div>
									<?php endif; ?>
									<?php if (count($details['eligibilityArr']) > 0) : ?>
										<div class="h22 bld bgcolor_div_sky">
												<div class="mar_left_10p lineSpace_23 fontSize_12p">Eligibility:</div>
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
                                    <?php if((strlen(htmlspecialchars_decode($details['application_procedure'])) > 0) || (count($details['docs']) > 0 )) { ?>
									<div class="h22 bld bgcolor_div_sky">
											<div class="mar_left_10p lineSpace_23 fontSize_12p">Application Process:</div>
									</div>
									<div class="lineSpace_10">&nbsp;</div>
									<div class="mar_full_10p" style="text-align:justify">
										<?php  echo htmlspecialchars_decode($details['application_procedure']);?>
									</div>
									<div class="lineSpace_10">&nbsp;</div>
                                    <?php if(count($details['docs']) > 0) {  ?>
									<div class="h22 bld">
										<img src="/public/images/attachment.gif" width="20" height="16" align="absmiddle" style="margin-left:10px" /> Upload Files:
									</div>
									<div class="lineSpace_10">&nbsp;</div>
									<?php for($i = 0 ; $i < count($details['docs']) ; $i++) { ?>
										<div class="mar_full_10p">
											<a href="<?php echo $details['docs'][$i]['url'] ; ?>"><?php echo $details['docs'][$i]['name'] ; ?></a>
										</div>
										<div class="lineSpace_10">&nbsp;</div>
									<?php } }  ?>
                                    <?php } ?>
									<?php if ($details['last_date_submission']!="0000-00-00 00:00:00") : ?>
										<div class="mar_full_10p">
											<span class="bld">Last date of submission</span>: <?php echo date('j F',strtotime($details['last_date_submission'])); ?>
										</div>
										<div class="lineSpace_10">&nbsp;</div>
									<?php endif; ?>
									<?php if ($details['selection_process']!="") : ?>
										<div class="h22 bld bgcolor_div_sky">
												<div class="mar_left_10p lineSpace_23 fontSize_12p">Selection Process:</div>
										</div>
										<div class="lineSpace_10">&nbsp;</div>
										<div class="mar_full_10p" style="text-align:justify">
											<?php  echo htmlspecialchars_decode($details['selection_process']);?>
										</div>
										<div class="lineSpace_10">&nbsp;</div>
									<?php endif; ?>
									<?php if($details['value']!="") : ?>
										<div class="h22 bld bgcolor_div_sky">
												<div class="mar_left_10p lineSpace_23 fontSize_12p">Amount of Scholarship:</div>
										</div>
										<div class="lineSpace_10">&nbsp;</div>
										<div class="mar_full_10p" style="text-align:justify">
												<?php  echo $details['value'];?>
										</div>
										<div class="lineSpace_10">&nbsp;</div>
									<?php endif; ?>
									<?php if (count($details['instituteArr']) >0) : ?>
										<div class="h22 bld bgcolor_div_sky">
												<div class="mar_left_10p lineSpace_23 fontSize_12p">University Details:</div>
										</div>
										<div class="lineSpace_10">&nbsp;</div>
										<div class="mar_full_10p">
											<?php foreach($details['instituteArr'] as $insti) : ?>
												<div class="OrgangeFont bld">
													<a href="<?php echo $insti['url'];?>" title="<?php echo $insti['institute_name']; ?>"><?php echo $insti['institute_name']; ?></a>
												</div>
												<div class="lineSpace_5">&nbsp;</div>
											<?php endforeach; ?>
										</div>
										<div class="lineSpace_10">&nbsp;</div>
									<?php endif; ?>
										<div class="lineSpace_10">&nbsp;</div>
                                            <?php  
                                         if(isset($validateuser[0]) && isset($validateuser[0]['displayname']) && $registerText['paid'] == "yes"){ 
										$this->load->view('listing/queryBottom'); 
                                    }
                                    ?>


									<?php 
                                    // if($registerText['paid'] == "no") {
                                        $this->load->view("listing/contactInfo");
                                    //    }
                                     ?>

										<?php if((isset($details['url']) && strlen($details['url']) > 5)) { ?>

                                        <?php if($details['crawled'] == "crawled") { ?>
											This information was retrieved from - <a href="<?php echo $details['url'];?>" target="_blank"> <span style="font-weight:bold;font-size:11px;"> <?php echo $details['url'];?> </span></a>- on <span style="color:#888;"><?php echo date('M j, Y',strtotime($details['timestamp'])); ?> </span>
                                            <?php }else { ?>
											This information was compiled from - <a href="<?php echo $details['url'];?>" target="_blank"> <span style="font-weight:bold;font-size:11px;"> <?php echo $details['url'];?> </span></a>- on <span style="color:#888;"><?php echo date('M j, Y',strtotime($details['timestamp'])); ?> </span>
                                            <?php } ?>
											<?php }?>


                                    <?php  
									if(!isset($cmsData)){
		                                $sourcearr = array('source'=>'LISTING_SCHOLARDETAIL_MIDDLEPANEL_ASKQUESTION');
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

									<?php if(!isset($cmsData)){ ?>
										<!--<div class="txt_align_r">
											<?php if((is_array($validateuser) && $validateuser != "false")) { ?>
												<a href="/listing/Listing/addCourse" >Add Your Institute Information</a>
												<?php }else { ?>
												<a href="javascript:void(0);" onClick = "showuserOverlay(this,'ask','<?php echo site_url('listing/Listing/addCourse');?>',1);return false;">Add Your Institute Information</a>
												<?php }?> |
												<?php if((is_array($validateuser) && $validateuser != "false")) { ?>
												<a href="/listing/Listing/addScholarship" >Add Scholarship Listing</a>
												<?php }else { ?>
												<a href="javascript:void(0);" onClick = "showuserOverlay(this,'ask','<?php echo site_url('listing/Listing/addScholarship');?>',1);return false;">Add Scholarship Listing</a>
												<?php }?> |
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
