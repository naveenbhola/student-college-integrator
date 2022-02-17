<!--Right_Form-->
<?php

error_log_shiksha("reqinfo_already.php loading");
error_log_shiksha("$cmsData");
error_log_shiksha("$reqInfo");
error_log_shiksha("reqinfo array::".print_r($reqInfo,true));
//$univname = $details['title'];
?>
<?php 
if(!isset($cmsData)){
	if($registerText['paid']!="yes"){
		$this->load->view("listing_forms/widgetConnectInstitute");
	}
}
?>
<div id="">
    <div>
        <div class="float_L row">
        <?php 
                if($registerText['paid'] == "yes"){
        ?>
            <div id="reqInfoContainersContainer">
                <div id="reqInfoContainer">
                <?php
                if($flagForAB == 'true'){
                    $styleForGetFreeAlert = 'display:none';
                }
                if(!isset($cmsData)) {
                        if($registerText['paid'] == "yes"){
                            $this->load->view("listing_forms/get_free_alerts",array('styleForGetFreeAlert' => $styleForGetFreeAlert));
                        }
                }   
                ?>
                </div>            
            </div>
        <div class="lineSpace_10">&nbsp;</div>
                     <?php } ?>

        <?php 
    switch(strtolower($listing_type)){
    case 'course':
        $this->load->view("listing/relatedCourses",array('resultArr' => $details['relatedListings'])); 
        break;
    case 'institute':
        $this->load->view("listing/relatedInstitutes",array('resultArr' => $details['relatedListings'])); 
        break;
    case 'scholarship':
        $this->load->view("common/relatedScholarships",array('resultArr' => $relatedListings)); 
        break;
    default:
        $this->load->view("common/relatedInstitutes",array('resultArr' => $relatedListings)); 
        break;
}

?>

            <?php 
	    if(!isset($validateuser[0]) && $registerText['paid'] == "yes" ){
			?>
            <div class="raised_pink" id="reqInfoContainersContainer"> 
						 <b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
						 <div id="reqInfoContainer" class="boxcontent_pink">
						 <?php
						 if(!isset($cmsData)){
							 if(isset($reqInfo) && count($reqInfo) > 0){
								 $this->load->view("listing/requestInfo_after"); 
							 }
						 }
							?>

						 </div>
						 <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
					 </div>
					 <div class="lineSpace_10">&nbsp;</div>
                     <?php } ?>

<?php                     if($univname != ''){?>
<!--					 <div class="raised_greenGradient">
						  <b class="b1"></b><b class="b2" style="background:#B9E069"></b><b class="b3"></b><b class="b4"></b>
						  <div class="boxcontent_viewAllMember">
								<div class="mar_full_10p">
									<div style="width:185px">
										<div class="lineSpace_5">&nbsp;</div>
										<div class="normaltxt_12p_blk bld OrgangeFont float_L" style="position:relative">
												<div class="myHeadingControl" style = "font-Size:14px;padding-left:25px">Do you wish to know more about the <?php echo $univname; ?> </div>
												<div class="cssSprite_Icons" style="background-position:0 -108px;width:27px;height:34px;position:absolute;top:0">&nbsp;</div>
										</div>
										<div class="lineSpace_10">&nbsp;</div>
										<div class="normaltxt_11p_blk arial" style="padding-left:25px; width:160px;">
                                            <span class="fontSize_12p bld float_L">Join the institutes group for <span style="color:#C90506">free</span> and interact with:</span>
                                        </div>
                                        <br clear="left" />
										<div class="lineSpace_10">&nbsp;</div>
										<div class="normaltxt_11p_blk_arial" style="padding-left:20px; width:160px;" >
											<ul style="margin-top:0;margin-bottom:0">
												<li class="fontSize_12p cssSprite_Icons" style="background-position:-487px -193px;margin-left:-33px;list-style-image:none;padding-left:22px;list-style:none"><b>Prospective Student</b>
												<div class="lineSpace_10">&nbsp;</div></li>
												<li class="fontSize_12p cssSprite_Icons" style="background-position:-487px -193px;margin-left:-33px;list-style-image:none;padding-left:22px;list-style:none"><b>Students</b>
												<div class="lineSpace_10">&nbsp;</div></li>
												<li class="fontSize_12p cssSprite_Icons" style="background-position:-487px -193px;margin-left:-33px;list-style-image:none;padding-left:22px;list-style:none"><b>Alumni</b>
												<div class="lineSpace_10">&nbsp;</div></li>
												<li class="fontSize_12p cssSprite_Icons" style="background-position:-487px -193px;margin-left:-33px;list-style-image:none;padding-left:22px;list-style:none"><b>Faculty</b></b>
												<div class="lineSpace_10">&nbsp;</div></li>                         
											</ul>
										</div>                                                                                                                          <div class="clear_L"></div>
									</div>
								</div>
								<div style="padding-left:20px">                            
										<?php $networkUrl = getSeoUrl($institute_id,"collegegroup",$univname) .'/1' ;?>
										<div class="shikshaEnabledBtn_L" style="padding:0 0 0 3px;width:141px;cursor:pointer" onclick="window.location ='<?php echo $networkUrl?>'" >
											<span class="shikshaEnabledBtn_R" style="padding:0 5px">Join&nbsp;this&nbsp;group&nbsp;for&nbsp;free</span>
										</div>
								</div>
								<div class="lineSpace_10">&nbsp;</div>
						  </div>
						  <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
					 </div>-->
<?php } ?>
<?php 
            $tempMetricsData = array();
            $tempMetricsData['listing_type'] = $listing_type;
            $tempMetricsData['viewCount'] = $details['viewCount'];
            $tempMetricsData['summaryCount'] = $details['summaryCount'];
            $this->load->view("common/viewMetrics",$tempMetricsData); 
        ?>

        <div class="lineSpace_10">&nbsp;</div>
        <?php
        $eventWidgetData = array();
        $eventWidgetData['listing_type'] = $listing_type;
        $eventWidgetData['details'] = $details;
        $eventWidgetData['eventList'] = $eventList;
        $eventWidgetData['type_id'] = $type_id;
        $this->load->view("events/eventWidget",$eventWidgetData);
        ?>
        <div id="rightpanelads">
        </div>
        </div>
    </div>
</div>
<!--End_Right_From-->
