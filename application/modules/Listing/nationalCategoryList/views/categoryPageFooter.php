<input type='hidden' id='streamId' value = '<?php echo $pageStream; ?>'>
<div class='All_india_city_layer' style="display: none;">
	<?php 
    $this->benchmark->mark('Footer_All_India_Layer_start');
    $this->load->view('nationalCategoryList/cityLayerAllIndia'); 
    $this->benchmark->mark('Footer_All_India_Layer_end');
    ?>
</div>
<?php 
    $this->benchmark->mark('Footer_New_start');
	$this->load->view('common/footerNew');
    $this->benchmark->mark('Footer_New_end');

    $this->benchmark->mark('Footer_Website_Tour_JS_start');
	echo includeJSFiles('shikshaDesktopWebsiteTour');
    $this->benchmark->mark('Footer_Website_Tour_JS_end');

    $this->benchmark->mark('Footer_Script_start');
?>

<script type="text/javascript">

	window.srpRecoCallbackAction = "ND_SRP_Reco_popupLayer";
	
    $j(document).ready(function(){
    	
    	initializeClosedSearch();
    	window.contentMapping = <?php echo json_encode($websiteTourContentMapping); ?>;
    	if(shikshaProduct != 'Category') {
    		initializeWebsiteTour('cta',window.shikshaProduct,contentMapping);
    	}
    	// initializeMouseHoversOnPage();
    	seoMMPRegLayer();
    	<?php
    	if($product == "SearchV2"){
    		?>
    			$j(window).load(function() {
					updateSearchFormData('<?php echo json_encode($searchFilterData);?>');
				});
    		<?php
    	}
    	?>
		<?php if(!$isAjax) { ?>
			$j('.zeroResultDropDown').SumoSelect();
		<?php } ?>

		<?php if($product == "Category" && !empty($topInstitute)){
        ?>
	        loadShoskeleFromAjax();
	        <?php
	    }
	    ?>
    });
var destination_url = '<?php echo $mmpData['mmp_details']['destination_url']; ?>';
var mmp_page_type = '<?php echo $mmpData['mmp_details']['display_on_page'];?>';

function seoMMPRegLayer(){
	
	var mmp_form_id_on_popup = '<?php echo $mmpData['mmp_details']['page_id'];?>';
	var forceOpenSeo = '<?php echo $forceOpenSeo;?>';
	if(mmp_form_id_on_popup != '') {

		var regFormPrefillValues = JSON.parse('<?php echo json_encode($regFormPrefillValue); ?>');
		var customFields = regFormPrefillValues.customFields;
	    customFields['mmpFormId'] = mmp_form_id_on_popup;
	    var customFieldValueSource = regFormPrefillValues.customFieldValueSource;

	        var formData = {
	            'trackingKeyId' : '<?php echo addslashes(htmlentities($mmpData['trackingKeyId']));?>',
	            'customFields':customFields,
	             'customFieldValueSource':customFieldValueSource,
	            'callbackFunction':'registrationFromMMPCallback',
	            'submitButtonText':'<?php echo addslashes(htmlentities($mmpData['submitButtonText']));?>',
	            'httpReferer':'',
	            'formHelpText':'<?php echo addslashes(htmlentities($mmpData['customHelpText']));?>'
	        };

	        //registrationForm.showRegistrationForm(formData);
	}
}
function registrationFromMMPCallback() {
    if(destination_url != '') {
		window.location = destination_url;
	}  else {
		//window.location = JSURL;
                window.location.reload();
	}
}

searchCompareCTAEventAttach();
</script>

<?php 
    $this->benchmark->mark('Footer_Script_end');

    $this->benchmark->mark('Footer_SRP_Tracking_start');

	$finalUpdatedCount = $totalInstituteCount;
	if(empty($finalUpdatedCount)){
		$finalUpdatedCount = $totalCourseCount;
	}
	if(empty($finalUpdatedCount)){
		$finalUpdatedCount = 0;
	}
	if(DO_SEARCHPAGE_TRACKING) {
		if(!empty($trackingSearchId)){
			echo "<input type='hidden' id='trackingSearchId' value={$trackingSearchId}>";
			if(!empty($updateResultCountForTracking)){
				?>
				<script type="text/javascript">
					// var img = new Image();
					var url = SEARCH_PAGE_URL_PREFIX+"/trackSearchQuery?ts=<?php echo urlencode(base64_encode($trackingSearchId)); ?> &count=<?php echo urlencode(base64_encode($finalUpdatedCount)) ?>";
					<?php 
						if(!empty($singleStreamClosedSearch)){
							?>
							url += "&pageType=<?php echo urlencode(base64_encode('close')); ?>";
							url += "&entityType=<?php echo urlencode(base64_encode('stream')); ?>";
							url += "&entityId=<?php echo urlencode(base64_encode($singleStreamClosedSearchStream)); ?>";
							<?php
						}
						if(!empty($relevantResults)){
							if($relevantResults != 'relax'){
								?>
								url += "&newKeyword=<?php echo urlencode(base64_encode($searchKeyword)); ?>";
								<?php
							}
							?>
							url += "&criteriaApplied=<?php echo urlencode(base64_encode($relevantResults)); ?>";
							<?php
						}
					?>
					makeCustomAjaxCall(url,{});
				</script>
				<?php
			}
		}
	}

    $this->benchmark->mark('Footer_SRP_Tracking_end');
?>
