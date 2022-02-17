			<!-- Alerts Started -->
			<?php
				foreach($myAlerts as $key => $value) {
					$$key = $value;
				}	
			?>

			<div class="inline-l myProfile" style="width:100%;margin-top:10px;">
				<div class="raised_lgraynoBG"> 
					<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
					<div class="boxcontent_lgraynoBG OrgangeFont">
						<div class="fontSize_13p bld raisedbg_sky h22" style="margin-bottom:3px">
							<img src="/public/images/alertIcon.gif" width="33" height="17" align="absmiddle" /> Alerts
						</div>
					<!--Start_Header_Title-->
                        <div class="row">                   
                                      <div class="bgcolor_lightgreen h20 normaltxt_11p_blk bld">
                                      <div class="float_L mar_top_4p w25_per"><span class="mar_left_10p">Type</span></div>
                                      <div class="float_L mar_top_4p w50_per"><span class="mar_left_20p">Alert Name</span></div>
                                      <div class="float_L mar_top_4p w10_per"><span>Status</span></div>
                                                                                                                                                                <!-- <div class="float_L mar_top_4p w20_per"><span class="mar_left_10p">Deliver to:</span></div> 
                                                                                                                                                                                         <div class="float_L mar_top_4p w10_per"><span class="mar_left_10p">Edit</span></div> -->
                                                                                                                                                                                                    <div class="float_L mar_top_4p w10_per"><span class="mar_left_10p">Delete</span></div>                      
                                        </div>
                                        </div>
                                         <div class="row">                   
                                         <div class="bgcolor_lightgreen h20 normaltxt_11p_blk bld">
                                         <div class="float_L w25_per"><span class="mar_left_10p">&nbsp;</span></div>
                                         <div class="float_L w40_per"><span class="mar_left_20p">&nbsp;</span></div>
                                         <div class="float_L w10_per"><span>&nbsp;</span></div>
                                         <!-- <div class="float_L w20_per"><span><img src="/public/images/mailicon.jpg" width="31" height="18" /><img src="/public/images/faceIcon.jpg" width="31" height="18" /><img src="/public/images/mobileicon.jpg" width="31" height="18" /></span></div> 
                                        <div class="float_L w10_per"><span class="mar_left_10p">&nbsp;</span></div> -->
                                         <div class="float_L w10_per"><span class="mar_left_10p">&nbsp;</span></div>                     
                                         </div>
                                         <br clear="left" style="height:1px" />
                                         </div>
						<!--End_Header_Title-->
						 <!-- Include template from zope 	-->
											
						<?php 
							$alrtTypeData['appId'] = $appId;
							$alrtTypeData['userAlerts'] = $userAlerts;	
							$alrtTypeData['noOfAlerts'] = $noOfAlerts;
							$this->load->view('alerts/alertsType',$alrtTypeData); 
						?>
						
						<div class="lineSpace_20">&nbsp;</div>
					</div>
				<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>				
			</div>	
		</div>
		<!-- Alerts Finished -->
		<?php 
				$alert_overlay = array();
				$alert_overlay['countryList'] = $countryList;
				$alert_overlay['appId'] = $appId;
				$alert_overlay['email'] = $email;
				$alert_overlay['mobile'] = $mobile;	
				$alert_overlay['noOfAlerts'] = $noOfAlerts;
				$alert_overlay['category_tree'] = $category_tree;
				$alert_overlay['categoryForLeftPanel'] = $categoryForLeftPanel;
				$this->load->view('alerts/alerts_overlays',$alert_overlay); 
		?>
