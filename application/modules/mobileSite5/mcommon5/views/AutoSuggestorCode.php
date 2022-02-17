<script>
var isAutoSugegstorActive = false;
var isAutoSugegstorActiveExam = false;
var isAutoSugegstorActiveQuestion = false;
var isAutoSugegstorActiveOnPage = {};
var courseInstituteSearchOptions;
var examAutosuggestorOptions;
var questionAutosuggestorOptions;
function loadJsFilesInParallel(){
	$LAB
	.script("//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("AutoSuggestor"); ?>")
	.wait(function(){
    	autosuggestorInstanceCheck = setInterval(function(){
            var fileLoaded = false;
            try{
                var aso = new AutoSuggestor();
                fileLoaded = true;
            } catch(e) {
                fileLoaded = false;
            }
            if(!fileLoaded){
                return;
            }
            clearInterval(autosuggestorInstanceCheck);
    		//condition added for tracking on homepage and search page
		    if(typeof(initializeAutoSuggestorInstanceSearchV2) == 'function') {
		    	try {
            		<?php $autosuggestorConfigArray = Modules::run('common/GlobalShiksha/getHeaderSearchConfig',array('courseInstituteSearch'),true);
                    
					if(!empty($autosuggestorConfigArray)) {
						foreach($autosuggestorConfigArray as $autosuggestorConfig) { ?>
							if(!isAutoSugegstorActive) {
                                courseInstituteSearchOptions = '<?php echo json_encode($autosuggestorConfig["options"]);?>';
								isAutoSugegstorActive = initShikshaSearch(courseInstituteSearchOptions);
							}
						<?php }
					}
					$autosuggestorConfigArray = Modules::run('common/GlobalShiksha/getHeaderSearchConfig',array('exams'),true);
					if(!empty($autosuggestorConfigArray)) {
						foreach($autosuggestorConfigArray as $autosuggestorConfig) { ?>
							if(!isAutoSugegstorActiveExam) {
                                examAutosuggestorOptions  = '<?php echo json_encode($autosuggestorConfig["options"]);?>';
								isAutoSugegstorActiveExam = initShikshaSearch(examAutosuggestorOptions);
							}
						<?php }
					}
                    $autosuggestorConfigArray = Modules::run('common/GlobalShiksha/getHeaderSearchConfig',array('question'),true);
                    if(!empty($autosuggestorConfigArray)) {
                        foreach($autosuggestorConfigArray as $autosuggestorConfig) { ?>
                            if(!isAutoSugegstorActiveQuestion) {
                                questionAutosuggestorOptions  = '<?php echo json_encode($autosuggestorConfig["options"]);?>';
                                isAutoSugegstorActiveQuestion = initShikshaSearch(questionAutosuggestorOptions);
                            }
                        <?php }
                    }
					if($boomr_pageid == 'MobileMyShortlistHomepage') {
						$autosuggestorConfigArray = Modules::run('common/GlobalShiksha/getHeaderSearchConfig', array('myShortlistInstituteSearchOnMobile')); 
						if(!empty($autosuggestorConfigArray)) {
							foreach($autosuggestorConfigArray as $autosuggestorConfig) {
								?>
								initShikshaSearch('<?php echo json_encode($autosuggestorConfig["options"]);?>');
								<?php 
							}
						}
					}
					if($boomr_pageid == 'CampusConnectHomepage') {
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
                                    	$autosuggestorConfig['options']['suggestionCount'] = 6;
                                    ?>
							initShikshaSearch('<?php echo json_encode($autosuggestorConfig["options"]);?>');						
						<?php }
					}?>

            	}
            	catch(e) {
					console.log(e);
				}
            }

			<?php if(isset($pageName) && ($pageName == 'collegeReview')){ ?>
						<?php
    							$autosuggestorConfigHomepage = Modules::run('common/GlobalShiksha/getHeaderSearchConfig', array('instituteSearchReviewsMobile'));  
                                foreach($autosuggestorConfigHomepage as $autosuggestorConfig) {?>
                                	if(!isAutoSugegstorActiveOnPage['collegeReview']) {
                                    	isAutoSugegstorActiveOnPage['collegeReview'] = initializeAutoSuggestorInstanceSearchV2('<?php echo json_encode($autosuggestorConfig["options"]);?>');                              
                                	}
                                <?php } ?>
			<?php }
			if($product == 'shiksha_analytics') {
				$autosuggestorConfigHomepage = Modules::run('common/GlobalShiksha/getHeaderSearchConfig', array('analyticsSearch'), true);
				foreach($autosuggestorConfigHomepage as $autosuggestorConfig) {?>
					initializeAutoSuggestorInstanceSearchV2('<?php echo json_encode($autosuggestorConfig["options"]);?>');								
				<?php } 
			 }
			?>

			<?php if($boomr_pageid == 'College_review_homepage') { ?>
				<?php $autosuggestorConfigHomepage = Modules::run('common/GlobalShiksha/getHeaderSearchConfig', array('instituteReviewsHomepage'));
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
                        $autosuggestorConfig["options"]["conditions"]['min_course_review'] = 3; ?>

    					initializeAutoSuggestorInstanceSearchV2('<?php echo json_encode($autosuggestorConfig["options"]);?>');
				<?php } ?>
                             
				/*initializeAutoSuggestorInstances(fromSearchPage,isPopulate,searchFrom,totalResult,schemaName,inputKeyId,container);*/
			<?php } ?>
			
			if(typeof(initializeAutoSuggestorInstancesForCollgeReviews) == 'function') {
			    initializeAutoSuggestorInstancesForCollgeReviews();
			}
    	},1000);
		$(document).trigger('labLoadedJs');
	});
}

function initShikshaSearch(options) {
	return initializeAutoSuggestorInstanceSearchV2(options);
}
</script>

<script type="text/javascript">
	(function(g,b,d){var c=b.head||b.getElementsByTagName("head"),D="readyState",E="onreadystatechange",F="DOMContentLoaded",G="addEventListener",H=setTimeout;
	function f(){ loadJsFilesInParallel();} H(function(){if("item"in c){if(!c[0]){H(arguments.callee,25);return}c=c[0]}var a=b.createElement("script"),e=false;a.onload=a[E]=function(){if((a[D]&&a[D]!=="complete"&&a[D]!=="loaded")||e){return false}a.onload=a[E]=null;e=true;f()};
	a.src="//<?php echo JSURL; ?>/public/js/LAB.min.js";c.insertBefore(a,c.firstChild)},0);if(b[D]==null&&b[G]){b[D]="loading";b[G](F,d=function(){b.removeEventListener(F,d,false);b[D]="complete"},false)}})(this,document);
</script>