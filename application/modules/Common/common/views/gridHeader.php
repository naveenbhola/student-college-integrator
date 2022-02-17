<!DOCTYPE html>
<html>
	<head>
	    <title><?php echo $title?></title>
	    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
	    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>

	      <script>
 			 'article aside footer header nav section time'.replace(/\w+/g,function(n){document.createElement(n)})
		  </script>

	    <!-- load all css -->
	    <?php if(isset($css) && is_array($css)) {
    		foreach($css as $cssFile) { ?>
        		<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion($cssFile); ?>" type="text/css" rel="stylesheet" />
			<?php }
    	} ?>
    	<!-- load all js -->
	    <?php if(isset($js) && is_array($js)) {
	    	$js = getJsToInclude($js);
        	foreach($js as $jsFile) { ?>
                <script language="javascript" src="<?php echo $jsFile;?>"></script>
			<?php }
		} ?>

		<!-- load new gnb -->
	    <?php $this->load->view('common/html5Header'); ?>
        <?php echo Modules::run('common/GlobalShiksha/getHeaderSearch'); ?>

		<?php  global $institutesWithoutUnified;  ?>
	    <script type="text/javascript">
	    	<?php if (is_array($validateuser) && is_array($validateuser[0]) && (isset($validateuser[0]['userid']))&& !empty($validateuser[0]['userid'])): ?>
	    	isUserLoggedIn = true;
	    	<?php else: ?>
	    	isUserLoggedIn = false;
	    	<?php endif; ?>
	    	<?php addJSVariables(); ?>
	    	var COOKIEDOMAIN = '<?php echo COOKIEDOMAIN; ?>';
	    	var institutesWithoutUnified = <?=json_encode($institutesWithoutUnified)?>;
	    	<?php if(SHOW_AUTOSUGGESTOR){ ?>
				var SHOW_AUTOSUGGESTOR_JS = true;
			<?php } else { ?>
				var SHOW_AUTOSUGGESTOR_JS = false;
			<?php } ?>
	    </script>

	    <script type="text/javascript">
		  function loadJsFilesInParallel(){
			$LAB
			.script("//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("AutoSuggestorV2"); ?>")
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
						if(typeof(initializeAutoSuggestorInstance) == 'function') {
							try {
								initializeAutoSuggestorInstance();
							}
							catch(e) {
								console.log(e);
							}
		                }
		                if(typeof(initializeAutoSuggestorInstanceSearchV2) == 'function') {
							try {
								<?php 
									if(!empty($autosuggestorConfigArray)) {
										foreach($autosuggestorConfigArray as $autosuggestorConfig) { 
											?>
											initializeAutoSuggestorInstanceSearchV2('<?php echo json_encode($autosuggestorConfig["options"]);?>');
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
		  }
		</script>

		<!-- LABJs utility loaded in parallel-->
		<script type="text/javascript">
		  (function(g,b,d){var c=b.head||b.getElementsByTagName("head"),D="readyState",E="onreadystatechange",F="DOMContentLoaded",G="addEventListener",H=setTimeout;
		  function f(){ loadJsFilesInParallel();} H(function(){if("item"in c){if(!c[0]){H(arguments.callee,25);return}c=c[0]}var a=b.createElement("script"),e=false;a.onload=a[E]=function(){if((a[D]&&a[D]!=="complete"&&a[D]!=="loaded")||e){return false}a.onload=a[E]=null;e=true;f()};
		  a.src="/public/js/LAB.min.js";c.insertBefore(a,c.firstChild)},0);if(b[D]==null&&b[G]){b[D]="loading";b[G](F,d=function(){b.removeEventListener(F,d,false);b[D]="complete"},false)}})(this,document);
		</script>
	</head>
