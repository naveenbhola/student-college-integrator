<?php
		$headerComponents = array(
								'css'	=>	array(
											'raised_all',
											'header',
											'mainStyle',
											'events'
										),
								'js'	=>	array(
											'common',
											'prototype',
											'home',
										),
								'title'	=>	'Shiksha Home',
                                'taburl' =>  site_url(),
								'tabName'	=>	'Event Calendar',
								'product'	=>	'home',
							    'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
								'metaKeywords'	=>'Some Meta Keywords'
							);
		$this->load->view('common/homepage', $headerComponents);
?>

<script>
var SITE_URL = '<?php echo base_url(); ?>';
var collegeList = eval(<?php echo $collegeList; ?>);
</script>
<!--Start_Center-->
<div class="mar_full_10p">
	<input type="hidden" id="categoryId" value="<?php echo $categoryId?>"/>
	<input type="hidden" id="countryId" value="<?php echo $countryId?>"/>
	<?php $this->load->view('home/category/HomeRightPanel');?>
	<?php $this->load->view('home/category/HomeLeftPanel');?>		
	<!--Start_Mid_Panel-->
	<div id="mid_Panel_noLpanelhome">
			<!--Start_courses_category_Box-->
			<?php $this->load->view('home/category/HomeCategoryHeader');?>
			<div class="lineSpace_10">&nbsp;</div>
			<?php  $this->load->view('home/category/HomeCategoryPanel');?>
			<!--End_courses_category_Box-->		
			<div class="lineSpace_10">&nbsp;</div>		
			<!--Start_Ask_Discussion_Articles-->		
			<div style="display:inline; float:left; width:100%">
				<?php  $this->load->view('home/shiksha/HomeMsgBoardPanel');?>
				<?php  $this->load->view('home/shiksha/HomeEventsPanel');?>
			</div>
			<!--End_Ask_Discussion_Articles-->
			<div class="clear_L"></div>
			<div class="lineSpace_10">&nbsp;</div>
  	</div>
	<!--End_Mid_Panel-->
<br clear="all" />
</div>
<!--End_Center-->
<?php
	$this->load->view('common/footer');
?>
