<?php
	$keyword 		= "";
	$title 			= "";
	$metaTitle 		= "";
	$metDescription = "";
	$metaKeywords 	= "";
	$headerComponents = array(
		'js'              =>	array('common'),
		'jsFooter'        =>	array('lazyload', 'searchV2'),
		'product'         => "SearchV2",
		'taburl'          =>  site_url(),
		'title'           =>	$title,
		'canonicalURL'    => "",
		'metaDescription' => $metDescription,
		'metaKeywords'    => $metaKeywords,
	);
	$this->load->view('common/header', $headerComponents);
?>
	<div id="search-background-search-page">
	    <div id="srch-content">
	        <?php $this->load->view("home/search/searchMenu"); ?>
	    </div>
	</div>
	<div id="content-child-wrap">
			<h1 class="h1">
				<?php if($totalInstituteCount > 0){
					?>
					<?php 
					if($totalInstituteCount==1)
						echo "$totalInstituteCount college ";
					else
						echo "$totalInstituteCount colleges ";
					?>
					and 
					<?php 
					if($totalCourseCount==1)
						echo "$totalCourseCount course ";
					else
						echo "$totalCourseCount courses ";
					 ?>
					found for "<?=$request->getSearchKeyword()?>"
					<?php
				}
				else{
					echo 'No colleges and courses found for "'.$request->getSearchKeyword().' "';
				} 
				?>
			</h1>
			
				<!-- search box will come here -->
				<div class="container_12">
				<?php $this->load->view('nationalV2/search_content_top'); ?>
				<!-- load filters based on context -->
				<?php
				// If subCat filters are present then its a noncontext search.
				if(!empty($filters['subCat']) && !empty($filters['locations'])) {
					$this->load->view('nationalV2/nocoursecontext_filters');
				}else{
					$this->load->view('nationalV2/coursecontext_filters');
				}
				?>
				<?php $this->load->view('nationalV2/results'); ?>
				</div>
		</div>
	</div>
<?php
	$this->load->view('nationalV2/search_footer');
	$this->load->view('common/footerNew', array('loadJQUERY' => 'YES',
												'jsFilePlugins' => array('chosen.jquery.min','jquery.sumoselect.min')));
?>
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("jquery.tinyscrollbar.min.v3"); ?>"></script>
<script type="text/javascript">
var mobileSearch = 'true';
var inputBoxPlaceholderText = "Enter One or More Locations";
	$j(document).ready(function(){
		$j("#chosenMultiSelectBox").hide();
		$j(".chosen-select").chosen({max_selected_options:5,215
									width:"400px",
									placeholder_text_multiple:'Enter One or More Locations',
									group_search: false,
									display_selected_options: false,
									tab_pressed_callback: handleTabPressedChosen,
									input_box_placeholder_text: inputBoxPlaceholderText
									});
		$j('.custom-select-normal').SumoSelect();

		$j('#chosenMultiSelectBox').prev().find(':input').focus(showMultiSelectContainer);
		$j('.chosen-select').chosen().change(handleLocationChangeChosen);
		// body click event
		$j("body").on("click", function(e){
			if($j(e.target).closest("div.chosen-container").length == 0){
				hideMultiSelectContainer();
			}
			if($j(e.target).closest("div.search-refine-colleges").length == 0) {
				for(var key in autoSuggestorInstanceArray) {
					if(autoSuggestorInstanceArray.hasOwnProperty(key)) {
						autoSuggestorInstanceArray[key].hideSuggestionContainer();
					}
				}
			}
		});
		initializeSearchTabs();
		updateSearchFormData('<?php echo json_encode($searchFilterData);?>');
	});

<?php if(!empty($filters['subCat'])) {?>
initializeOpenSearch();
<?php } else { ?>
initializeClosedSearch();
<?php } ?>
</script>
	
	
