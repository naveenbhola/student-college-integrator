<?php 
if($isAjax) { ?>
    <?php $this->load->view('msearch5/msearchV3/msearchPageBody'); ?>
<?php } else {
    $this->load->view('msearch5/msearchV3/msearchPageHeader'); ?>
    
	<?php if ($totalInstituteCount > 0) { ?>
	    <div class="location-container srp-container" id="searchTuples">
	        <?php $this->load->view('msearch5/msearchV3/msearchPageBody'); ?>
	    </div>
	    <!-- Loading Image -->
	    <div id="loader" class="srp-loader"><img class="small-loader" id="loadingImage1" src="//<?php echo IMGURL; ?>/public/mobile5/images/ShikshaMobileLoader.gif" border=0 alt="" /></div>
	    <?php  $this->load->view('msearch5/msearchV3/msearchPagination'); ?>
	    <?php 
	    	if($product == 'McategoryList' || $product == 'MAllCoursesPage') {
	    		$this->load->view('msearch5/msearchV3/paginationForBots'); 
	    	}
	    	if($product == 'McategoryList' && !empty($categoryPageInterlinkgUrls)) {
		    	$this->load->view('msearch5/msearchV3/interlinkingWidget');
	    	}
	    ?>
	<?php }
	else if($totalCourseCount > 0){
		?>
			<div class="location-container srp-container" id="searchTuples">
		        <?php $this->load->view('msearch5/msearchV3/mAllCoursePageBody'); ?>
		    </div>
		    <!-- Loading Image -->
		    <div id="loader" class="srp-loader"><img class="small-loader" id="loadingImage1" src="//<?php echo IMGURL; ?>/public/mobile5/images/ShikshaMobileLoader.gif" border=0 alt="" /></div>
		    <?php $this->load->view('msearch5/msearchV3/msearchPagination'); ?>
		    <?php 
		    	if($product == 'McategoryList' || $product == 'MAllCoursesPage')
		    	$this->load->view('msearch5/msearchV3/paginationForBots'); 
		    ?>

		    <?php if($product == 'MAllCoursesPage'){
		    	echo $reviewWidget['html'];
		    	} ?>
		    <?php $this->load->view('msearch5/msearchV3/relatedLinks'); ?>
		<?php
	}
	 else {
	    $this->load->view('msearch5/msearchV3/mserpNoResultPage.php');
	} 
	$this->load->view('msearch5/msearchV3/msearchPageFooter');
} ?>
	
<div class='select-class'>
<select name="allIndiaCitySelect" show-search="1" id="allIndiaCitySelect" style="display:none;" append-selected-value="1" multiple="multiple" showLoaderOnDone="false" layer-version="fullScreen" div-heading='Select your location' first-line='To see relevant colleges, select preferred location(s):' gaAttrOnCross='MS_Location_Close'>

<optgroup>
<?php if(!empty($filters['location']['city'])) {
foreach($filters['location']['city'] as $city){
	if((!isset($city['enabled']) || $city['enabled']==1))
	{
 ?>
<option value="<?php echo 'ct_'.$city['id'];?>"><?php echo $city['name'];?> </option>
<?php } } }?>
<?php if(!empty($filters['location']['state']))
 {
 	GLOBAL $statesToIgnore;
 	foreach($filters['location']['state'] as $state){
 	 if((!isset($state['enabled']) || $state['enabled']==1) && !(in_array($state['id'],$statesToIgnore)))
 	 {	
 	 ?>
<option value="<?php echo 'st_'.$state['id'];?>"><?php echo $state['name'];?> </option>
<?php } } } ?>
</optgroup>
</select>

</div>
<div>
	<input type="hidden" id="allIndiaCitySelect_input">
	</input>
</div>