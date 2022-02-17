<script>
	<?php if(!empty($subcatNameForGATracking) && !empty($pageTypeForGATracking)) { ?>
		// customized error object
		function showTrackingMessage(message) {
			this.message = message;
			this.browserInfo = window.navigator.appCodeName;
		}
		
		if(typeof pageTracker != "undefined") {
			var object = new showTrackingMessage("GA tracking Started tracking for <?=$subcatNameForGATracking?>/<?=$pageTypeForGATracking?>");
			logJSErrors(object);
			
			pageTracker._setCustomVar(1, "NationalSubcatLevelTrack", '<?=$subcatNameForGATracking?>/<?=$pageTypeForGATracking?>', 2);
			pageTracker._setCustomVar(5, "NationalSubcatLevelTrack_page", '<?=$subcatNameForGATracking?>/<?=$pageTypeForGATracking?>_page', 3);
			pageTracker._trackEventNonInteractive('dummyTrackingCat', '<?=$pageTypeForGATracking?>', '<?=$subcatNameForGATracking?>', 0, true);
			
			var object = new showTrackingMessage("GA tracking Ended tracking for <?=$subcatNameForGATracking?>/<?=$pageTypeForGATracking?>");
			logJSErrors(object);

			//send a DB tracking for URL and custom variable key-value
			<?php if(DB_TRACK_FOR_GA_PARAM_VERIFICATION) { 
				$currentUrl = get_full_url(); ?>
				$j.ajax({
					url:'/shiksha/dbTrackingForGAParams',
					type:'POST',
					data :{'currentUrl':'<?=$currentUrl?>','gaString':'<?=$subcatNameForGATracking?>/<?=$pageTypeForGATracking?>','source':'desktop'},
					success: function(response){
						//console.log('success');
					}
				});
			<?php } ?>
		}
	<?php } ?>
</script>