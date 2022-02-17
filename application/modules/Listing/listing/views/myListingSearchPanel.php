<?php
		$headerComponents = array(
								'css'	=>	array(
											'raised_all',
											'mainStyle',
											'header',
											'footer'
										),
								'js'	=>	array(
											'common',
											'browserDetect',
											'ratings',
											'listing',
											'drawgraph'										
										),
								'title'	=>	'My Listings',
								'tabName'	=>	'Listing',
                          'taburl' => $thisUrl, 
                          'product' => 'home', 
                          'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
                          'callShiksha'=>1,
                          'metaKeywords'  =>'course, institute, scholarship, examination listing'
 
							);
//		$this->load->view('common/header', $headerComponents);

$this->load->view('common/homepage', $headerComponents);
?>
<script>

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
<div class="lineSpace_8">&nbsp;</div>
