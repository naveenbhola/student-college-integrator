<?php
$myqnaTab	= "answer";
$actionDone = "default";
$start 		= "0";
$rows 		= "10";
$flag_UnansweredTopics = "1";
$countryId = "2";
$pageurl = SHIKSHA_ASK_HOME . "/messageBoard/MsgBoard/discussionHome/" . $key . "/" . $flag_UnansweredTopics . "/" . $countryId . "/" . $myqnaTab . '/' .  $actionDone .  "/" . $start . "/" . $rows;
?>
<!--
<a style='cursor:pointer' >
<div class="cafe-widget" id="cafe-widget"  style="cursor: pointer;">
	<h3>Shiksha Cafe</h3>
	<p>Make an informed career choice, ask the expert now!</p>
	<input type="button" onclick="javascript:window.location='<?php echo $pageurl; ?>';" style="cursor: pointer;" class="orange-button" value="Ask a question" />
</div>
</a>
<script>
$(document).ready(function(){
    $("#cafe-widget").click(function(){
        var url = "'<?php echo $pageurl; ?>';";
        var _gaq = _gaq || [];
        _gaq.push(['_trackEvent', 'cat_widget_click_india', 'click', url]);
        window.location='<?php echo $pageurl; ?>';
    });
});
</script>
-->
