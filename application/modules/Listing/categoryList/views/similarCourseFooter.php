	<div style="display: none;" id="overlayContainer">
	</div>
	
	<div style="position: absolute; z-index: 9999; display: none;" id="loadingImage" class="loader"><img src="public/images/Loader2.GIF"> Loading</div>
</div>
	<?php $this->load->view('common/footerNew',array('loadJQUERY' => 'YES')); ?>
<script>
		<?php if($pageType != "similar_home") { ?>
		var resultSetOffset = <?=$currResultOffsetPos?>;
		var totalSimilarCourseCount = <?=$totalSimilarCourseCount?>;
		var resultSetChunksSize = <?=$resultSetChunksSize?>;
		if(typeof($categorypage) == 'undefined'){
				$categorypage = [];
		}
		studyAbroad = 0;
		$categorypage.LDBCourseId = "1";
		<?php } ?>
		$j(document).ready(function(){
				initializeAutoSuggestorInstanceForSimilarInstitute();
		});
		<?php if($courseToFocus) {
		?>
				if ($j('li[elementtofocus=course<?=$courseToFocus?>]').length && isUserLoggedIn) {
						$j('body,html').animate({scrollTop:$j('li[elementtofocus=course<?=$courseToFocus?>]').offset().top - 100});
				}
		<?php
		}?>
</script>