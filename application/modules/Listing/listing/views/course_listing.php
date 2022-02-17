<?php
$headerComponents = array(
                          'css'   => array('header','mainStyle','raised_all','footer','cal_style'),
			  'js'    => array('common','newcommon','listing','prototype','utils','tooltip','CalendarPopup'),
                          'title' => 'Add Institute & Course',
                          'tabName' => 'Listing',
                          'taburl' => site_url('listing/Listing/addCourse'),
                          'product' => 'home',
                          'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
                          'callShiksha'=>1,
                          'metaKeywords'  =>'course, institute, scholarship, examination listing',
                          'search'=> false
                         );
//$this->load->view('common/header', $headerComponents);
$this->load->view('common/homepage', $headerComponents);
$this->load->view('common/overlay');
$this->load->view('common/calendardiv');
?>
<script language="javascript" src="/public/js/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script>
		var SITE_URL = '<?php echo base_url() ."/";?>';
</script>
<!--Start_GreenGradient-->
<!--<div id="greenGradient">
	<div id="innerContainer">
		<div class="lineSpace_16">&nbsp;</div>
		<div class="txt_white_bold"><span style="position:relative; top:-4px">All | Courses | Universities | Scholarships | Exams |</span> <a href="#" class="bgBox whiteFont fontSize_12p pd_full_7_9" style="text-decoration:none"><span style="position:relative; top:-4px">Listing</span></a><span style="position:relative; top:-4px">| more <img src="/public/images/searchArrow.gif" align="absmiddle" /></span></div>
		<div class="lineSpace_3">&nbsp;</div>
		<div class="normaltxt_11p_blk bld whiteFont fontSize_18p float_L"><img src="/public/images/searchImg.gif" align="absmiddle" />Search&nbsp;</div>
		<div class="float_L w10"><input type="text" value="" class="boxstyle" /></div>
		<div class="buttr3">
			<button class="btn-submit3 w5" value="" type="button">
				<div class="btn-submit3"><p class="btn-submit4 btnTxt">GO</p></div>
			</button>
		</div>
		<div class="clear_L"></div>
	</div>
</div>-->
<!--End_GreenGradient-->
<!--<div class="lineSpace_8">&nbsp;</div>-->
<!--Start_Center-->
<div>
	<!--End_Right_Panel-->
	<div>
	</div>
	<!--End_Right_Panel-->


	<!--Start_Left_Panel-->
	<!--left_panel_IdName-->
	<div>
			<!--Course_TYPE-->
			<!--
			<div class="raised_blue_nL">
				<b class="b2"></b>
                <div class="boxcontent_nblue">
                  <div class="tpBrd_nblue normaltxt_11p_blk bld"><span class="mar_left_10p">Benifits of Listing</span></div>
                  <div class="row">
						  <div class="normaltxt_11p_blk lineSpace_20 w9 mar_left_11px">
						  <div class="deactiveselectCategory"><a href="#" class="OrgangeFont">&nbsp;All Categories</a></div>
						  <div class="deactiveselectCategory"><a href="#">Foreign Education</a></div>
						  <div class="deactiveselectCategory"><a href="#">Management</a></div>
						  <div class="deactiveselectCategory"><a href="#">Distance/Online Edu...</a></div>
						  <div class="deactiveselectCategory"><a href="#">Banking/Finance/Insu..</a></div>
						  <div class="deactiveselectCategory"><a href="#">Vocational Courses</a></div>
						  <div class="deactiveselectCategory"><a href="#">Language Courses</a></div>
						  <div class="deactiveselectCategory"><a href="#">Media & Mass Comm...</a></div>
						  <div class="deactiveselectCategory"><a href="#">IT</a></div>
						  <div class="deactiveselectCategory"><a href="#">Hospitality</a></div>
						  <div class="deactiveselectCategory"><a href="#">Health & Beauty</a></div>
						  <div class="deactiveselectCategory"><a href="#">Professional Courses</a></div>
                 	</div>
                </div>
                </div>
			<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
			</div>
			<div class="lineSpace_10">&nbsp;</div>-->
			<!--End_Course_TYPE-->
   </div>
   <style>
   		SELECT {font-size:11px; font-family:Verdana, Arial, Helvetica, sans-serif;}
		INPUT {font-size:11px; font-family:Verdana, Arial, Helvetica, sans-serif;}
		TEXTAREA {font-size:11px; font-family:Verdana, Arial, Helvetica, sans-serif;}
	</style>
	<!--End_Left_Panel-->
	<!--Start Javascript for opening particular DIVs-->
	<div id="mid_Panel_noLRpanel">
		<div class="">
			<div class="normaltxt_11p_blk fontSize_16p OrgangeFont"><strong>Add Institute & Course</strong></div>
            <?php if ($productname=="Basic") :?>
            <!--<div class="txt_align_r darkgray"><a href="updateProduct" >Upgrade your Listing </a> </div>-->
            <?php endif; ?>
			<div class="lineSpace_10">&nbsp;</div>
			<div class="float_R txt_align_r darkgray"><a href="addScholarship">Add Scholarship Listing</a> | <a href="addAddmission">Add Admission Notification</a>| <a href="/payment/payment/index">Product Catalog</a></div>
			<div class="lineSpace_5">&nbsp;</div>
		</div>
		<div style="float:left; width:100%;">
			<div class="raised_lgraynoBG">
				<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
					<div class="boxcontent_lgraynoBG">
						<div style="float:left; display:inline; width:100%; margin-top:5px">
<!--							<div style="float:right">
								<div class="normaltxt_11p_blk pd_Right_6p" align="left"><img src="/public/images/view_icon.gif" width="15" height="13" align="absmiddle" /> <a href="#">View Your Listing</a></div>
								<div class="lineSpace_8">&nbsp;</div>
								<div class="normaltxt_11p_blk pd_Right_6p" align="left"><img src="/public/images/view_icon.gif" width="15" height="13" align="absmiddle" /> <a href="#">View payment history</a></div>
							</div>-->
						</div>
						<div class="clear_L"></div>
<?php $this->load->view('listing/course_sub_listing'); ?>

						<div class="lineSpace_10">&nbsp;</div>
					</div>
				<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
			</div>
		</div>
  </div>
	<!--End_Mid_Panel-->
<?php $this->load->view('common/footer');?>
