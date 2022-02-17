<script>
function loadJsFilesInParallelNewAutosuggester(){
	$LAB
	.script("//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("AutoSuggestor"); ?>")
	.wait(function(){
		if(true){
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
             if(typeof(initializeAutoSuggestorInstanceSearchV2) == 'function') {
					try {
						<?php
							 if($suggestorPageName == 'CMS_suggestors') {
								$autosuggestorConfigCMS = Modules::run('common/GlobalShiksha/getHeaderSearchConfig', array('viewAllTagsCMS','instituteSearchCMS', 'vcmsAllTagsCMS'));
								foreach($autosuggestorConfigCMS as $autosuggestorConfig) {?>

									instanceConfig = '<?php echo json_encode($autosuggestorConfig["options"]);?>';
									initializeAutoSuggestorInstanceSearchV2(instanceConfig);
								<?php } 
							 }else if($suggestorPageName == 'CMS_ExamInstSuggestors') {
								$autosuggestorConfigCMS = Modules::run('common/GlobalShiksha/getHeaderSearchConfig', array('instituteSearchExamCMS'));
								foreach($autosuggestorConfigCMS as $autosuggestorConfig) {?>
									instanceConfig = '<?php echo json_encode($autosuggestorConfig["options"]);?>';
									initializeAutoSuggestorInstanceSearchV2(instanceConfig);
								<?php } 
							 }else if($suggestorPageName == 'CMS_University_Admission'){
							 	$autosuggestorConfigCMS = Modules::run('common/GlobalShiksha/getHeaderSearchConfig', array('universitySearchCMS'));
								foreach($autosuggestorConfigCMS as $autosuggestorConfig) {?>

									instanceConfig = '<?php echo json_encode($autosuggestorConfig["options"]);?>';
									initializeAutoSuggestorInstanceSearchV2(instanceConfig);
								<?php } 

							 }else if($suggestorPageName == 'instSuggestorsExamCMS'){
							 	$autosuggestorConfigCMS = Modules::run('common/GlobalShiksha/getHeaderSearchConfig', array('instituteSearchExam'));
								foreach($autosuggestorConfigCMS as $autosuggestorConfig) {?>

									instanceConfig = '<?php echo json_encode($autosuggestorConfig["options"]);?>';
									initializeAutoSuggestorInstanceSearchV2(instanceConfig);
								<?php } 

							 }

						?>
					}
					catch(e) {
						console.log(e);
					}
                }
			}
		},10);
	  }
	});
  }
  <?php /*-- LABJs utility loaded in parallel */ ?>
  (function(g,b,d){var c=b.head||b.getElementsByTagName("head"),D="readyState",E="onreadystatechange",F="DOMContentLoaded",G="addEventListener",H=setTimeout;
  function f(){ loadJsFilesInParallelNewAutosuggester();} H(function(){if("item"in c){if(!c[0]){H(arguments.callee,25);return}c=c[0]}var a=b.createElement("script"),e=false;a.onload=a[E]=function(){if((a[D]&&a[D]!=="complete"&&a[D]!=="loaded")||e){return false}a.onload=a[E]=null;e=true;f()};
  a.src="/public/js/LAB.min.js";c.insertBefore(a,c.firstChild)},0);if(b[D]==null&&b[G]){b[D]="loading";b[G](F,d=function(){b.removeEventListener(F,d,false);b[D]="complete"},false)}})(this,document);
</script>
