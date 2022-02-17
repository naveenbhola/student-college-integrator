<script>
<?php if($product !='SearchV2' && $product!='anaDesktopV2') {?>
	if(typeof coursePageScript == 'function'){
		coursePageScript();
	}
<?php } ?>
/* function to bind autosuggestor */
if(typeof(bindAutoSuggestorEvents) == 'function'){
	bindAutoSuggestorEvents();
}
/* responsible for showing GNB sub-menu on mouse hover */
if(typeof(activateGnbMouseOver) == 'function'){
	activateGnbMouseOver();
}
var homepageMiddlePanel, instituteDetailPageClass, cookieDomain = '<?php echo COOKIEDOMAIN; ?>';
<?php if (is_array($validateuser) && is_array($validateuser[0]) && (isset($validateuser[0]['userid']))&& !empty($validateuser[0]['userid'])){ ?>
loggedInUId = '<?php echo $validateuser[0]['userid'];?>';
<?php }?>
$j(document).ready(function(){
	shikshaOnreadyEventPC();
	<?php if($product == 'examPage'){ ?>
			if(typeof (isExamHomePage) != 'undefined' && isExamHomePage == 1){
				setEPWidgetsHeight(); 
			}
	<?php } ?>
	getSocialSharingLayer();
});
$j(window).load(function(){
	shikshaOnloadEventPC();
	if(typeof isUserLoggedIn !='undefined' && isUserLoggedIn && typeof getPredictorList != 'undefined' && typeof getPredictorList == 'function'){
		getPredictorList();
	}
});
</script>
