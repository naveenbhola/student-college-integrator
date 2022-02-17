<?php 
if(strlen($content['data']['strip_title']) <= 80)
	$contentTitle = $content['data']['strip_title'];
else
	$contentTitle =  (preg_replace('/\s+?(\S+)?$/', '', substr($content['data']['strip_title'], 0, 80))."...") ;

?>
<div class="layer-header">
    <p style="text-align:center"><?=$contentTitle?></p>
</div>