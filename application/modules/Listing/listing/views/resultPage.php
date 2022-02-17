<?php
$headerComponents = array(
                          'css'   => array(
		                 	  'header',
                                          'mainStyle',
                                          'raised_all',
                                          'footer'
                                          ),
                          'js'    => array(
                                          'common',
					  'listing',
					  'prototype'
                                           ),
                          'title' => 'Listing Addition Result Page',
                          'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
                          'callShiksha'=>1,
                          'tabName' => 'Listing',
                          'taburl' => site_url('listing/Listing/addCourse'),
                          'product' => 'home',
                          'metaKeywords'  =>'course, institute, scholarship, examination listing'
                         );
//$this->load->view('common/header', $headerComponents);
$this->load->view('common/homepage', $headerComponents);
$this->load->view('common/overlay');
?>

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
	<!--<div id="left_Panel">
			<div class="raised_blue_nL"> <b class="b2"></b>
                <div class="boxcontent_nblue">
                  <div class="tpBrd_nblue normaltxt_11p_blk bld"><span class="mar_left_10p">Benifits of Listing</span></div>
                  <div class="lineSpace_11 tpBrd_nblue">&nbsp;</div>
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
                  <div class="lineSpace_11 deactiveselectCategory tpBrd_nblue">&nbsp;</div>
                </div>
			  <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
			</div>
			<div class="lineSpace_10">&nbsp;</div>
			<div></div>
  	</div>-->
	<!--End_Left_Panel-->
	<!--Start Javascript for opening particular DIVs-->


	<!--End Javascript for opening particular DIVs-->
	<!--Start_Mid_Panel-->
<?php
   $this->load->view('common/overlay');
   $url = "";
   switch($response['listing_type'])
   {
      case "course":
      $url = "addCourse";break;
      case "notification":
      	$url = "addAddmission";break;
      case "scholarship":
      	$url = "addScholarship";break;
}
?>
	<div class="mar_full_10p">
		<div>
			<div class="normaltxt_11p_blk fontSize_16p OrgangeFont"><strong>Create Listing</strong></div>
			<div class="lineSpace_5">&nbsp;</div>
		</div>
		<div style="float:left; width:100%;">
			<div class="raised_lgraynoBG">
				<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
					<div class="boxcontent_lgraynoBG">
						<div class="lineSpace_5">&nbsp;</div>
						<div style="float:left; display:inline; width:100%">
                                                    <!--	<div class="normaltxt_11p_blk bld pd_left_5p">You have <?php echo $productname; ?> listing Product and you have <?php echo $remaining; ?> Listing(s) left in your A/c</div> -->
						</div>
						<div class="lineSpace_5">&nbsp;</div>
						<div class="grayLine"></div>
						<div class="lineSpace_5">&nbsp;</div>
						<!--Start_Add_Listing-->
						<div class="row">
							<div class="mar_left_5p OrgangeFont"><img src="/public/images/view_icon.gif" width="15" height="13" align="absmiddle" /> <span class="normaltxt_11p_blk fontSize_13p bld OrgangeFont">Recently Added Listing</span></div>
							<div class="lineSpace_10">&nbsp;</div>
							<div style="display: inline; float:left; width:100%">

								<div class="normaltxt_11p_blk bld txt_align_r float_L">You have recently added <?php echo '<a href="getDetailsForListing/'.$response['type_id'].'/'.$response['listing_type'].'/'.seo_url($response['title']).'" >'.$response['title'].'</a>'; ?> listing. &nbsp;</div>
							</div>
						</div>
						<div class="lineSpace_5">&nbsp;</div>

						<div class="row" width="100px;">
							<div class="w99 h23 float_R bgImg_create" align="center">
								<div class="pos_t5">
									<a class="blackFont bld pd_full_2_10" style="text-decoration: none;" href="<?php echo $url;?>">Add New</a>
								</div>
							</div>
						</div>
						<div class="lineSpace_10">&nbsp;</div>
					</div>
				<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
			</div>
			<div class="lineSpace_11">&nbsp;</div>
		</div>
  </div>
	<!--End_Mid_Panel-->
<?php
	                $this->load->view('common/footer');
?>
