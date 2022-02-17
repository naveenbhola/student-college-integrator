<!--Right_Form-->
<?php
/*echo "<pre>";
print_r($validateuser);
echo "</pre>";
*/

error_log_shiksha("reqinfo_already.php loading");
error_log_shiksha("$cmsData");
error_log_shiksha("$reqInfo");
error_log_shiksha("reqinfo array::".print_r($reqInfo,true));
//$univname = $details['title'];
?>
<div id="">
		<div style="margin-right:10px">
			<div class="float_L row">
            <?php 
                if($registerText['paid'] == "yes" ){
			?>
            <div class="raised_pink" id="reqInfoContainersContainer"> 
						 <b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
						 <div id="reqInfoContainer" class="boxcontent_pink">
						 <?php
						 if(!isset($cmsData)){
							 if(isset($reqInfo) && count($reqInfo) > 0){
								 $this->load->view("listing/requestInfo_after"); 
							 }
							 else{
                                 if($registerText['paid'] == "yes" ){
                                     $this->load->view("listing/requestInfo_before"); 
                                 }
							 }
						 }
							?>

						 </div>
						 <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
					 </div>
					 <div class="lineSpace_10">&nbsp;</div>
                     <?php } ?>


					 <?php
						$boardId = $details['categoryArr'][0]['category_id'];
						$discussionUrl = "/messageBoard/MsgBoard/topicDetails/".$boardId."/".$details['threadId'];
						$mbArray = array('threadId'=>$details['threadId'], 'boardId'=>$boardId,'listing_type'=> $listing_type,'type_id'=> $type_id, 'discussionUrl'=>$discussionUrl);
									 if(!isset($cmsData)){
										 if(isset($details['threadId']) && $details['threadId'] > 0){
									//		 $this->load->view("listing/askQuery.php",$mbArray); 
										 }
									 }
					 ?>

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
	    if(!isset($validateuser[0]) && $registerText['paid'] != "yes" ){
			?>
            <div class="raised_pink" id="reqInfoContainersContainer"> 
						 <b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
						 <div id="reqInfoContainer" class="boxcontent_pink">
						 <?php
						 if(!isset($cmsData)){
							 if(isset($reqInfo) && count($reqInfo) > 0){
								 $this->load->view("listing/requestInfo_after"); 
							 }
							 else{
                                 if(!isset($validateuser[0]) && $registerText['paid'] != "yes" ){
                                     $this->load->view("listing/requestInfo_before"); 
                                 }
							 }
						 }
							?>

						 </div>
						 <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
					 </div>
					 <div class="lineSpace_10">&nbsp;</div>
                     <?php } ?>

<?php                     if($univname != ''){?>
					 <div class="raised_greenGradient">
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
										<!--<button value="" type="button" onclick="window.location ='<?php echo $networkUrl?>'" title="Join this group for free">
											<div class="shikshaEnabledBtn_L">
													<span class="shikshaEnabledBtn_R">Join&nbsp;this&nbsp;group&nbsp;for&nbsp;free</span>
											</div>
										</button>-->
										<div class="shikshaEnabledBtn_L" style="padding:0 0 0 3px;width:141px;cursor:pointer" onclick="window.location ='<?php echo $networkUrl?>'" >
											<span class="shikshaEnabledBtn_R" style="padding:0 5px">Join&nbsp;this&nbsp;group&nbsp;for&nbsp;free</span>
										</div>
								</div>
								<div class="lineSpace_10">&nbsp;</div>
						  </div>
						  <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
					 </div>
<?php } ?>
                     <div class="lineSpace_10">&nbsp;</div>

                     <!-- START_ALERT_SUBSCRIBE_WIDGET -->
			<?php if((is_array($validateuser) && $validateuser != "false")) { ?>
					 <div class="raised_greenGradient">
						<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
						<div class="boxcontent_viewAllMember">
							<div class="mar_full_10p">
								<div style="line-height:4px">&nbsp;</div>
							   <div class="normaltxt_12p_blk bld OrgangeFont"><h3><span class="cssSprite_Icons" style="background-position:-215px -72px; font-size:13px;padding-left:15px;">&nbsp;</span><!--<img src="/public/images/shikshaBell.jpg" align="absmiddle" />--><span class="myHeadingControl" style = "font-Size:14px">Shiksha Alert</span></h3></div>
							   <div style="line-height:4px">&nbsp;</div>
							   <div class="normaltxt_12p_blk fontSize_12p" id="alertSubscribeString" style="line-height:17px"><?php echo $subscribeString; ?></div>
							   <div class="lineSpace_10">&nbsp;</div>
							   <div class="row">
                               
                               <?php error_log_shiksha("subscribe status :".$subscribeStatus);if($subscribeStatus == 1) { ?>
							   <!--<div class="buttr3" style="padding-left:27px" id="alertsubscribebutton" title="Subscribe">
									<button class="btn-submit7 w3" value="" type="button" onclick="javascript:<?php echo $subscribeAction; ?>" >
									<div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Subscribe</p></div>
									</button>
							   </div>-->
							   <div align="center">
								   <div id="alertsubscribebutton" title="Subscribe" class="shikshaEnabledBtn_L" style="padding:0 0 0 3px;width:72px;cursor:pointer" onclick="javascript:<?php echo $subscribeAction; ?>" >
										<span class="shikshaEnabledBtn_R" style="padding:0 5px">Subscribe</span>
									</div>
									<div class="lineSpace_10">&nbsp;</div>
								</div>
							   <?php } ?>
							   </div>

                               <span id="alertSuccessMsg" style="display:none;color:green;"></span>
							</div>
						  </div>						
							<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
						</div>
                              <div class="lineSpace_10">&nbsp;</div>
                             <?php } ?>
                     <!-- END_ALERT_SUBSCRIBE_WIDGET -->

								 <?php 
                                 $tempMetricsData = array();
                                 $tempMetricsData['listing_type'] = $listing_type;
                                 $tempMetricsData['viewCount'] = $details['viewCount'];
                                 $tempMetricsData['summaryCount'] = $details['summaryCount'];
                                 $this->load->view("common/viewMetrics",$tempMetricsData); 
                                 
                                 ?>
			</div>
		</div>
 </div>
 <!--End_Right_From-->
