<?php
		$bannerProperties = array('pageId'=>'ALERT_HOME', 'pageZone'=>'HEADER');
		$headerComponents = array(
								'css'	=>	array('header','raised_all','mainStyle'),
								'js'	=>	array('cityList','common','alerts'),
								'title'	=>	'Shiksha.com- Alerts – Education – College – University – Admission Forms – Scholarships – Education Events – Notifications',
								'tabName'	=>	'Alerts',
								'product'	=>	'Alerts',
								'taburl' => site_url('alerts/Alerts/alertsHome'),
								'bannerProperties' => $bannerProperties,
								'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
								'metaDescription' => 'Create alerts in Shiksha.com for Colleges, University, Institutes, Foreign Education programs and information to study in India. Find course / program details, admissions, scholarships of universities of India and from countries all over the world.',	
								'metaKeywords'	=>'Shiksha, Alerts, Study, study abroad, foreign education, foreign education, GMAT, graphic designing, education, career, career options, career prospects, engineering, mba, medical, mbbs, study abroad, foreign education, college, university, institute, courses, coaching, technical education, higher education, forum, community, education career experts, ask experts, admissions, results, events, scholarships',
								'callShiksha'=>1
							);
		$this->load->view('common/homepage', $headerComponents);
?>
<?php 
	$headerValue = array();
	$headerValue['searchAction'] = 'Blog/shikshaBlog/blogSearch/1/2';		
	//$this->load->view('common/global_search',$headerValue);
?>


<!-- place for home js -->

<!--Start_Center-->
<div>
	<!--End_Right_Panel-->
	<div id="right_Panel">
		<div>
		<?php     
		        $bannerProperties = array('pageId'=>'ALERT_HOME', 'pageZone'=>'GOOGLESIDEBANNER');
		        $this->load->view('common/banner', $bannerProperties);
		?>
		</div>	
		<div class="lineSpace_10">&nbsp;</div>
		<div>
		<?php     
		        $bannerProperties = array('pageId'=>'ALERT_HOME', 'pageZone'=>'SKYSCRAPPER');
		        $this->load->view('common/banner', $bannerProperties);
		?>	
		</div>	
		<div class="lineSpace_10">&nbsp;</div>	
	</div>
	<!--End_Right_Panel-->
	
	
	<!--Start_Left_Panel-->
	<div>
	</div>
	<!--End_Left_Panel-->
        <script>
	var flipFlop = false;
        function toBeHidden()
        {
	    if(!flipFlop){	
	    	document.getElementById('toBeHidden').style.display = '';
		flipFlop = true;	
	    }else{
		document.getElementById('toBeHidden').style.display = 'none';	
		flipFlop = false;
	    }	
        }
        </script>
                                                                               
	<!--Start_Mid_Panel-->
	<div id="mid_Panel_noLpanel">
		<div>
			<div class="raised_lgraynoBG"> 
				<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
				<div class="boxcontent_lgraynoBG" style="height:150px;">
					<img src="/public/images/alertBell.jpg" align="left" class="mar_right_10p" />
					<span class="normaltxt_11p_blk fontSize_16p bld OrgangeFont">Alerts</span><br />
					<span class="normaltxt_11p_blk fontSize_13p lineSpace_20">
Alerts’ is a free personalized notification service. It informs you instantly of what is relevant and important to you via email and cell phone. To take advantage of this service create Shiksha Alerts.
</span><br />
					<span class="normaltxt_11p_blk bld"><a href="javascript:void(0);" onClick="toBeHidden();">How do I create an alert?</a></span>				
                    <div style="margin-left:215px">
						<div id="toBeHidden" name="toBeHidden" style="display:none">
								<div style="background-color:#F2F2F2;margin-right:10px">
								<div class="normaltxt_11p_blk" style="margin-left:10px; padding:10px 0"> 
								To create an alert ,Please click on the "CREATE ALERT" button against different products below. You are allowed to create 5 alerts under one product. 
								</div>
								</div>
						</div>
					</div>
				</div>
				<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>				
			</div>	
		</div>
		<div class="lineSpace_10">&nbsp;</div>
		<div class="">
		<div style="display:inline; float:left; width:100%">
			<div class="raised_lgraynoBG"> 
				<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
				<div class="boxcontent_lgraynoBG">
					<div class="normaltxt_11p_blk fontSize_13p bld" style="margin-bottom:10px"><img src="/public/images/bellIcon.jpg" width="32" height="20" align="absmiddle" /> Alerts</div>
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
	<!-- Start of View Code -->
	<?php $alrtTypeData['userAlerts'] = $userAlerts;	
	      $alrtTypeData['noOfAlerts'] = $noOfAlerts;	
	$this->load->view('alerts/alertsType',$alrtTypeData); ?>
	<!-- Start of View Code -->		
					<div class="lineSpace_20">&nbsp;</div>
				</div>
				<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>				
			</div>
			<div class="lineSpace_20">&nbsp;</div>
			<?php
				$bannerProperties1 = array('pageId'=>'ALERT_HOME', 'pageZone'=>'FOOTER');
				$this->load->view('common/banner',$bannerProperties1);  	
			?>
		</div> 		
		</div>
  	</div>
	<!--End_Mid_Panel-->
<?php 
	$alert_overlay = array();
	$alert_overlay['countryList'] = $countryList;
	$alert_overlay['appId'] = $appId;
	$alert_overlay['email'] = $email;
	$alert_overlay['mobile'] = $mobile;	
	$alert_overlay['noOfAlerts'] = $noOfAlerts;
	$alert_overlay['category_tree'] = $category_tree;
	$this->load->view('alerts/alerts_overlays',$alert_overlay);
		 
?>

<?php  $this->load->view('common/overlay'); ?>
<?php
	$bannerProperties1 = array('pageId'=>'', 'pageZone'=>'');
	$this->load->view('common/footer',$bannerProperties1);  	
?> 

