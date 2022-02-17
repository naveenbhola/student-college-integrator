<?php
	$this->load->view('common/studyAbroadHeader', $headerComponents);
	echo jsb9recordServerTime('SA_CONTENT_PAGE',1);
?>

<?php if($content['data']['type'] == 'article') :?>
	<div itemscope itemtype="http://schema.org/Article">
<?php endif;?>
	
<?php 
	if(strlen($content['data']['strip_title']) <= 80){
		$contentTitle = $content['data']['strip_title'];
	}
	else{
		$contentTitle =  (preg_replace('/\s+?(\S+)?$/', '', substr($content['data']['strip_title'], 0, 80))."...") ;
	}
?>
<div id="breadcrumb">
  <span>
    <a href="<?php echo SHIKSHA_STUDYABROAD_HOME;?>">
      <span>Home</span>
    </a> 
    <span class="breadcrumb-arr">›</span>
  </span>
  <span>
    <span><?php echo $contentTitle;?></span>
  </span>
</div>
<?php $this->load->view('blogs/saContent/tabloid/tabloidTemplate'); ?>
<?php $this->load->view('common/studyAbroadFooter'); ?>
<?php if($content['data']['type'] == 'article') :?>
</div>
<?php endif;?>

<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("jquery.tinyscrollbar.min.v3"); ?>"></script>
<img id = 'beacon_img' width=1 height=1 >

<?php
	$contentId = $content['data']['content_id'];
	$contentType = $content['data']['type'];
?>
<script>
var contentId 		= '<?=$contentId?>';
var contentType 	= '<?=$contentType?>';
var email 		    = '<?php echo base64_encode($content['data']['email']); ?>';
var name 		    = '<?php echo base64_encode($content['data']['username']);?>';
var stripTitle 		= '<?php echo base64_encode($content['data']['strip_title']); ?>';
var contentUrl 		= '<?php echo base64_encode($content['data']['contentURL']);?>';
	
if(getCookie('feedback_article_'+contentId) != ''){
	      feedbackVal = getCookie('feedback_article_'+contentId);
	      var res = feedbackVal.split("|");
              
	           if(res[1] == '1'){
		       $j('#yes').addClass('upVote-orng-icon');
	            }
	            else
		       $j('#no').addClass('dwnVote-orng-icon');
	             $('thankMsg').innerHTML = 'Thanks for your feedback. You can also share your comments below.';
	             $('thankMsg').style.display = 'inline';
              
}
var img = document.getElementById('beacon_img');
var randNum = Math.floor(Math.random()*Math.pow(10,16));
img.src = '<?php echo BEACON_URL; ?>/'+randNum+'/0011007/<?=$contentType?>+<?=$contentId?>';
</script>


<!--<a href="javascript:void(0);" class="backtop-btn" id="toTop" style="font-size:12px;z-index:0"><i class="common-sprite bcktop-icon"></i>Back to top</a>-->
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("jquery.tinyscrollbar.min.v3"); ?>"></script>
<script>
    $j(function() {
        scrollBackToTop('header-top-section');	
	});
</script>
<?php
//if($is_downloadable=='no'){
$tabloidContentArray = array();
foreach($popularArticles as $popularArticle)
{
	$tabloidContentArray[$popularArticle['contentId']] = $popularArticle;
}
?>
<script>
var title = '<?=($headerComponents["title"])?>';
var canonicalURL='<?=($headerComponents["canonicalURL"])?>';
var isContentPage = true;
var tabloidSequence = '<?php echo json_encode(array_map(function($a){return $a['contentId'];},$popularArticles));?>';
var tabloidList = '<?php echo json_encode($tabloidContentArray); ?>';
var contentOnPage = [];
var waitForTabloidLoad = false;
var stateStack = [];
$j(document).ready(function(){
	bindPopStateHandler();
	//$j('#relatedGuidesArticleWidget').show();
	//if(isScrolledIntoViewSticky('#footer')){
	//		$j('#rightStickyPanel').hide();
	//}
	//else{
	//		$j('#rightStickyPanel').show();
	//}
	//
	//$j("#jump_section").show();
	//$j('#sections_guide').tinyscrollbar();
	$j("#chooseExamLayerScrollbar").tinyscrollbar();
	bindClickHandlerToHideLayer();
	$j("#jump_section").hide();

	if ($j(window).width()  <= 1024) {
						$j("#span1").hide();
						$j("#span2").show();
						$j("#guideInfo").hide();
	}else{
						$j("#span1").show();
						$j("#span2").hide();
						$j("#guideInfo").show();
	}

    $j(window).scroll(function() {
		// tabloid scroll event binding
		bindTabloidScrollEvent();
    });
});
</script>
<?php //} ?>

