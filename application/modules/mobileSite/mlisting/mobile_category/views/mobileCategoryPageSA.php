<?php
$this->load->view('mobileSA');
if(!$institutes){
    if($request->getPageNumberForPagination() > 1){
        $urlRequest = clone $request;
        $urlRequest->setData(array('pageNumber'=>1));
        $url = $urlRequest->getURL();
        header("location:".$url);
        exit;
    }
?>
        <h1 class="no-result">Sorry, no results were found matching your selection.</h1>
<?php
}else{

    $this->load->view('mobileCategoryListings');

}
?>
<script>
var finalurl = base_url;
    var catId     = '1';
    var countryId = '1';

if($('#select1').length > 0) {
            var catmainsel     = $('#select1').val();
            var categoryArray = catmainsel.split("|");
            var catId = categoryArray[1];
}
if($('#select2').length > 0 ) {
	var countrymainsel = $('#select2').val();
	var countryArray = countrymainsel.split("|");
	var countryId = countryArray[1];
}
var myqnaTab='answer';
var actionDone='default';
var start = "0";
var rows = "10";
var SHIKSHA_ASK_HOME = '<?php echo SHIKSHA_ASK_HOME; ?>';
var flag_UnansweredTopics = "1";

if (countryId != '1' && catId != '1')
{
	finalurl = SHIKSHA_ASK_HOME + "/messageBoard/MsgBoard/discussionHome/" + catId + "/" + flag_UnansweredTopics + "/" + countryId + "/" + myqnaTab + '/' +  actionDone +  "/" + start + "/" + rows;
}
else
{
	var site_url = '<?php echo SHIKSHA_HOME; ?>';
	<?php 
		$ci_mobile_capbilities = $_COOKIE['ci_mobile_capbilities'];
	$wurfl_data = json_decode($ci_mobile_capbilities,true);

	if($wurfl_data['ajax_support_javascript'] == "true") {
		?>
			finalurl = site_url + '/ANA/mobile_messageboard/render_ask_question_page';
		<?php  } else { ?>


			finalurl = site_url + "/messageBoard/MsgBoard/discussionHome" + "/1/1/1/answer/";

			<?php
		}
		?>
}
</script>
<!--<a style='cursor:pointer' >
<div class="cafe-widget" id="cafe-widget"  style="cursor: pointer;">
<h3>Shiksha Cafe</h3>
<p>Make an informed career choice, ask the expert now!</p>
<input type="button" style="cursor: pointer;" class="orange-button" value="Ask a question" />
</div>
</a>-->
<?php 
setcookie('current_cat_page',urlencode($this->shiksha_site_current_url),time() + 2592000 ,'/',COOKIEDOMAIN);
?>
<script>
$(document).ready(function(){
		$("#cafe-widget").click(function(){
			var _gaq = _gaq || [];
			_gaq.push(['_trackEvent', 'cat_widget_click_non_india', 'click', finalurl]);
			window.location= finalurl;
			});
		});
</script>
<?php $this->load->view('/mcommon/footer');?>
