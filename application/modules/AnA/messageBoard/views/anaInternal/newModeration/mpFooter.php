	<div class="row"><!-- Footer Code -->
	</div>
</div>
<script src="https://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
<script type="text/javascript" src="//<?php echo JSURL;?>/public/js/<?php echo getJSWithVersion('ana_common'); ?>"></script>
<script type="text/javascript" src="//<?php echo JSURL;?>/public/js/<?php echo getJSWithVersion('myShiksha'); ?>"></script>
<script type="text/javascript" src="//<?php echo JSURL;?>/public/js/<?php echo getJSWithVersion('user'); ?>"></script>
<script type="text/javascript" src="//<?php echo JSURL;?>/public/js/<?php echo getJSWithVersion('moderation_panel'); ?>"></script>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script> -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script type="text/javascript" src="//<?php echo JSURL;?>/public/js/<?php echo getJSWithVersion('taggingcms'); ?>"></script>	
<script type="text/javascript" src="//<?php echo JSURL;?>/public/js/stringDiff/diff_match_patch_uncompressed.js"></script>
<script type="text/javascript">
var browserType = window.navigator.userAgent;
if((browserType.match(/Chrome/g) == null && browserType.match(/Firefox/g) == null)){alert('Sorry, this interface is supported in Chrome and Mozilla/Firefox only.');}

$j = $.noConflict( true );
var COOKIEDOMAIN = '<?php echo COOKIEDOMAIN; ?>';
var hasModeratorAccess = '<?=$hasModeratorAccess?>';
//added by akhter
bindClick();
var userId = '<?=isset($validateuser[0]['userid'])?$validateuser[0]['userid']:0?>';
bindAutoSuggestor('cafeModeration');
var dmp = new diff_match_patch();
</script>
</body>
</html>