<?php $this->load->view('common/footerNew'); ?>

<script src="//<?php echo JSURL; ?>/public/js/jquery.lazyload.min.js"></script>
<script type="text/javascript">

	window.srpRecoCallbackAction = "ND_SRP_Reco_popupLayer";
	
    $j(document).ready(function(){
    	
    	if(typeof(initializeClosedSearch) == 'function'){
    		initializeClosedSearch();
    	}
    	
		$j(window).load(function() {
			updateSearchFormData('<?php echo json_encode($searchFilterData);?>');
		});
		<?php if(!$isAjax) { ?>
			$j('.zeroResultDropDown').SumoSelect();
		<?php } ?>

		$j(document).mouseup(function (e) {
			//hide exam container when clicked outside the container
		    var container = $j(".srpHoverCntnt");
		    if (!container.is(e.target) // if the target of the click isn't the container...
		        && container.has(e.target).length === 0) // ... nor a descendant of the container
		    {
		        container.hide();
		    }

		    //disable search input box when clicked outside
		    var container = $j("#searchby-college");
		});
    });

</script>

<?php 
if(DO_SEARCHPAGE_TRACKING && in_array($requestFrom,array('subcatopentoclose','subcatclosetoopen'))){
	?>
	<script>
		if(typeof history != 'undefined'){
			window.history.replaceState(null,null,"<?php echo $request->getUrl(); ?>");
		}
	</script>
	<?php
}
?>