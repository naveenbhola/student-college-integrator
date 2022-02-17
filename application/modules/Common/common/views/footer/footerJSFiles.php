<?php if(in_array($product, array('ArticlesD', 'home'))) { ?>
	<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('shikshaHeader'); ?>"></script>
<?php }
if(isset($loadUpgradedJQUERY) && $loadUpgradedJQUERY == 'YES'){
	echo includeJSFiles('jQuery-v-1.8');
}else{
	echo includeJSFiles('jQuery-v-1.7');
}?>
<script>$j = $.noConflict();</script>

<?php if($product=='collegeReviewHomepage'){?>
	<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('collegeReviewHomepage'); ?>"></script>
<?php }


echo includeJSFiles('shiksha-com');
$additionalAttributes = array();
if($loadFromGruntAsync) {
	$additionalAttributes = array('async');
}
echo includeJSFiles($product, 'shikshaDesktop', $additionalAttributes);
?>

<?php 

$alreadyAddedJsFooter = array();
if(!isset($jsFooter)){
	$jsFooter       = array();
} else {
	foreach ($jsFooter as $foojsfile) {
		if($foojsfile   != 'ajax-api') {
			$foojsfilearr[] = $foojsfile;
		}
	}
	$jsFooter       = $foojsfilearr;
}
$jsFooter = getJsToInclude(array_unique(array_merge($alreadyAddedJsFooter, $jsFooter)));

if(isset($jsFooter) && is_array($jsFooter)) {
    foreach($jsFooter as $jsFile) { 
    	if(strpos($jsFile,'footer') >= 0){ ?>
		<script language="javascript" src="<?php echo $jsFile;?>" ></script>
<?php
		}else{ ?>
		<script   language="javascript" src="<?php echo $jsFile;?>" ></script>

		<?php }
 	}
}


$lazyLoadJsFilesArray = array();
foreach($lazyLoadJsFiles as $jsFile) {
	    $lazyLoadJsFilesArray[] = "//".JSURL."/public/js/".getJSWithVersion($jsFile);
}
?>
<script>
var lazyLoadJsFiles = <?php echo json_encode($lazyLoadJsFilesArray);?>

function lazyLoadJsFilesInParallel() {    
	if(lazyLoadJsFiles.length > 0) {
     LazyLoad.loadOnce(lazyLoadJsFiles,function(){},null,null,true);
	}
}

function LazyLoadAnAContactUsCallback(){
	$LAB.script('//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("quesDiscPosting");?>').wait(function(){
		LazyLoadAnAContactUs();
	});
} 

function loadJsFilesInParallel(){
	$j(document).trigger('labLoadedJs');
	$LAB
	.script("//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("AutoSuggestor"); ?>")
	.wait(function(){
		if(SHOW_AUTOSUGGESTOR_JS){
		autosuggestorInstanceCheck = setInterval(function(){
			var fileLoaded = false;
			try{
				var aso = new AutoSuggestor();
				fileLoaded = true;
			} catch(e) {
				fileLoaded = false;
			}
			if(fileLoaded){
				clearInterval(autosuggestorInstanceCheck);
			<?php if( $product != 'nationalCompare'){ ?>
				if(typeof(initializeAutoSuggestorInstance) == 'function') {
					initializeAutoSuggestorInstance();
              }
             <?php }   ?>
             if(typeof(initializeAutoSuggestorInstanceSearchV2) == 'function') {
					try {
						<?php 
						 	$autosuggestorConfigArray = Modules::run('common/GlobalShiksha/getHeaderSearchConfig');
							if(!empty($autosuggestorConfigArray)) {
								foreach($autosuggestorConfigArray as $autosuggestorConfig) { 
									?>
									initializeAutoSuggestorInstanceSearchV2('<?php echo json_encode($autosuggestorConfig["options"]);?>');
								<?php }
							}
							if($product == 'coursePagesHeader') {
                                                            $currentPageData = $COURSE_HOME_PAGES_LIST[$courseHomePageId];
								$autosuggestorConfigHomepage = Modules::run('common/GlobalShiksha/getHeaderSearchConfig', array('instituteCampusConnectHomepage', 'instituteReviewsHomepage'));
								foreach($autosuggestorConfigHomepage as $autosuggestorConfig) {
                                                                    //  $autosuggestorConfig["options"]["conditions"]=array();
                                                                    if($currentPageData['streamId']>0){
                                                                        $autosuggestorConfig["options"]["conditions"]['stream'][] = $currentPageData['streamId'];
                                                                    }
                                                                    if($currentPageData['substreamId']>0){
                                                                        $autosuggestorConfig["options"]["conditions"]['substream'][] = $currentPageData['substreamId'];
                                                                    }
                                                                    if($currentPageData['baseCourseId']>0){
                                                                        $autosuggestorConfig["options"]["conditions"]['base_course'][] = $currentPageData['baseCourseId'];
                                                                    }
                                                                    if($currentPageData['educationType']>0){
                                                                        $autosuggestorConfig["options"]["conditions"]['education_type'][] = $currentPageData['educationType'];
                                                                    }
                                                                    if($currentPageData['deliveryMethod']>0){
                                                                        $autosuggestorConfig["options"]["conditions"]['deliveryMethod'][] = $currentPageData['deliveryMethod'];
                                                                    }
                                                                    ?>
									initializeAutoSuggestorInstanceSearchV2('<?php echo json_encode($autosuggestorConfig["options"]);?>');								
								<?php } 
							}
							if($product == 'collegeReviewHomepage'){
								$autosuggestorConfigHomepage = Modules::run('common/GlobalShiksha/getHeaderSearchConfig', array('instituteReviewsHomepage'));
								foreach($autosuggestorConfigHomepage as $autosuggestorConfig) {
                                                                     $autosuggestorConfig["options"]["conditions"]=array();
                                                                    if($stream>0){
                                                                        $autosuggestorConfig["options"]["conditions"]['stream'][] = $stream;
                                                                    }
                                                                    if($substream>0){
                                                                        $autosuggestorConfig["options"]["conditions"]['substream'][] = $substream;
                                                                    }
                                                                    if($baseCourse>0){
                                                                        $autosuggestorConfig["options"]["conditions"]['base_course'][] = $baseCourse;
                                                                    }
                                                                    if($educationType>0){
                                                                        $autosuggestorConfig["options"]["conditions"]['education_type'][] = $educationType;
                                                                    }
                                                                    $autosuggestorConfig["options"]["conditions"]['min_course_review'] = 3;
                                                                    ?>
									initializeAutoSuggestorInstanceSearchV2('<?php echo json_encode($autosuggestorConfig["options"]);?>');								
								<?php } 
							}
							if($product == 'campusAmbassador' || $product == 'CollegeReviewForm') {
								$autosuggestorConfigHomepage = Modules::run('common/GlobalShiksha/getHeaderSearchConfig', array('instituteSearch'));
								foreach($autosuggestorConfigHomepage as $autosuggestorConfig) {?>
									initializeAutoSuggestorInstanceSearchV2('<?php echo json_encode($autosuggestorConfig["options"]);?>');								
								<?php } 
							 }

							 if($product == 'nationalCompare') {
								$autosuggestorConfigHomepage = Modules::run('common/GlobalShiksha/getHeaderSearchConfig', array('instituteSearchCompare', 'instituteSearchCompare1'));
								foreach($autosuggestorConfigHomepage as $autosuggestorConfig) {?>
									initializeAutoSuggestorInstanceSearchV2('<?php echo json_encode($autosuggestorConfig["options"]);?>');								
								<?php } 
							 }
							 if($product == 'myShortlist') {
								$autosuggestorConfigHomepage = Modules::run('common/GlobalShiksha/getHeaderSearchConfig', array('myShortlistInstituteSearch'));
								foreach($autosuggestorConfigHomepage as $autosuggestorConfig) {?>
									initializeAutoSuggestorInstanceSearchV2('<?php echo json_encode($autosuggestorConfig["options"]);?>');								
								<?php } 
							 }
							 if($product == 'campusConnect') {
								$autosuggestorConfigHomepage = Modules::run('common/GlobalShiksha/getHeaderSearchConfig', array('campusConnectHomepage')); 
								foreach($autosuggestorConfigHomepage as $autosuggestorConfig) {
											if($setAutoSuggestorOption['streamId']>0){
                                                $autosuggestorConfig["options"]["conditions"]['stream'][] = $setAutoSuggestorOption['streamId'];
                                            }
                                            if($setAutoSuggestorOption['substreamId']>0){
                                                $autosuggestorConfig["options"]["conditions"]['substream'][] = $setAutoSuggestorOption['substreamId'];
                                            }
                                            if($setAutoSuggestorOption['baseCourseId']>0){
                                                $autosuggestorConfig["options"]["conditions"]['base_course'][] = $setAutoSuggestorOption['baseCourseId'];
                                            }
									?>
									initializeAutoSuggestorInstanceSearchV2('<?php echo json_encode($autosuggestorConfig["options"]);?>');								
								<?php } 	
							 }
							 if($product == 'shiksha_analytics') {
							 	$autosuggestorConfigAnalytics = Modules::run('common/GlobalShiksha/getHeaderSearchConfig', array('analyticsSearch')); 
							 	foreach($autosuggestorConfigAnalytics as $autosuggestorConfig) { ?>
									initializeAutoSuggestorInstanceSearchV2('<?php echo json_encode($autosuggestorConfig["options"]);?>');								
								<?php } 
							 }
							 if($suggestorPageName == 'all_tags') {
								$autosuggestorConfigHomepage = Modules::run('common/GlobalShiksha/getHeaderSearchConfig', array('viewAllTags'));
								foreach($autosuggestorConfigHomepage as $autosuggestorConfig) {?>
									tagsASConfig = '<?php echo json_encode($autosuggestorConfig["options"]);?>';
									initializeAutoSuggestorInstanceSearchV2(tagsASConfig);
								<?php } 
								$autosuggestorConfigHomepage = Modules::run('common/GlobalShiksha/getHeaderSearchConfig', array('viewAllTagsQDP'));
								foreach($autosuggestorConfigHomepage as $autosuggestorConfig) {?>
									initializeAutoSuggestorInstanceSearchV2('<?php echo json_encode($autosuggestorConfig["options"]);?>');
								<?php } 
							 }
							 if($suggestorPageName == 'allReviewsPage') {	
								$autosuggestorConfigHomepage = Modules::run('common/GlobalShiksha/getHeaderSearchConfig', array('allReviewsPage'));
								foreach($autosuggestorConfigHomepage as $autosuggestorConfig) {?>
									tagsASConfig = '<?php echo json_encode($autosuggestorConfig["options"]);?>';
									initializeAutoSuggestorInstanceSearchV2(tagsASConfig);
								<?php } 
							 }
						?>
					}
					catch(e) {
						console.log(e);
					}
                }
				if(typeof(initializeAutoSuggestorInstancesForCollgeReviews) == 'function') {
			initializeAutoSuggestorInstancesForCollgeReviews();
		}
			if(typeof(initializeAutoSuggestorInstancesForCampusConnect) == 'function') { 
	 	    	    initializeAutoSuggestorInstancesForCampusConnect(); 
	 	    } 
	
				
				if(typeof(initializeAutoSuggestorInstanceAlt) == 'function') {
			initializeAutoSuggestorInstanceAlt();
                }
			}
		},1000);
	  }
	});
	<?php //lazy loading ask now widget
	if( ($product == 'anaDesktopV2' || ($product == 'allContentPage' && $pageType == 'questions')) && $lazyLoadJsFiles == true){ ?>
		LazyLoadAnADesktopCallback();
	<?php	}else if(in_array($product, array('institutePage', 'allContentPage')) && $lazyLoadJsFiles == true){?>
		LazyLoadInstituteDetailsCallback();
	<?php } else if($product == 'coursePage' && $lazyLoadJsFiles == true){?>
		LazyLoadCourseDetailsCallback();
	<?php } else if($product == 'nationalCompare' && $lazyLoadJsFiles == true){?>
			if(typeof(LazyLoadForCompareQP) == 'function'){
				LazyLoadForCompareQP();
			}
	<?php } else if($product == 'examPage' && $lazyLoadJsFiles == true){?>
		LazyLoadJsCssExamPage();
	<?php } else if($product == 'Contact Us' && $lazyLoadJsFiles == true) {?>
			LazyLoadAnAContactUsCallback();
	<?php } ?>
  }
  <?php /*-- LABJs utility loaded in parallel */ ?>
  (function(g,b,d){var c=b.head||b.getElementsByTagName("head"),D="readyState",E="onreadystatechange",F="DOMContentLoaded",G="addEventListener",H=setTimeout;
  function f(){ loadJsFilesInParallel(); /*if(typeof CTACallBackSearch == 'function') CTACallBackSearch();*/} H(function(){if("item"in c){if(!c[0]){H(arguments.callee,25);return}c=c[0]}var a=b.createElement("script"),e=false;a.onload=a[E]=function(){if((a[D]&&a[D]!=="complete"&&a[D]!=="loaded")||e){return false}a.onload=a[E]=null;e=true;f()};
  a.src="//<?php echo JSURL; ?>/public/js/LAB.min.js";c.insertBefore(a,c.firstChild)},0);if(b[D]==null&&b[G]){b[D]="loading";b[G](F,d=function(){b.removeEventListener(F,d,false);b[D]="complete"},false)}})(this,document);
</script>


<?php
if(isset($loadCareerWidget)) {
	if($loadCareerWidget === true && $product !='home') { ?>
		<script async src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("homePageWidget"); ?>" ></script>
	<?php }
}?>
