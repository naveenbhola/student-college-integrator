<?php
$metaDescription = (!empty($blogInfo[0]['blogTitle']))? $blogInfo[0]['blogTitle'] . ' - education & career article by ' :'education & career article by ';
if($blogInfo[0]['blogType'] == 'kumkum') { 
    $metaDescription .=  'Kumkum Tandon ';
} elseif ($blogInfo[0]['blogType'] == 'ht') {
    $metaDescription .=  'HT Horizons ';
} else {
    $metaDescription .=  ucwords($blogInfo[0]['name']) . " ";
}
$order   = array("\r\n", "\n", "\r","\t","<br>","<br />","<br/>","&nbsp;");
$replace = '';
$blogDescription = json_decode($blogInfo[0]['blogText'], true);
$text = $blogDescription[0]['description'];
$text = str_replace($order, $replace, $text);
$text = preg_replace("/(\<script)(.*?)(script>)/si", "", $text);
$text = strip_tags($text);
$text = str_replace("<!--", "&lt;!--", $text);
$text = preg_replace("/(\<)(.*?)(--\>)/mi", "".nl2br("\\2")."", $text);
$search = array('@<script[^>]*?>.*?</script>@si',
		'@<[\/\!]*?[^<>]*?>@si',
		'@<style[^>]*?>.*?</style>@siU',
		'@<![\s\S]*?--[ \t\n\r]*>@'
	    );
$text = preg_replace($search, '', $text);
$metaDescription .= substr(trim($text), 0, 300);
if($blogInfo[0]['seoDescription'] != '') {
    $metaDescription = $blogInfo[0]['seoDescription'];
}
$metaKeywords = 'Education, Colleges, Courses, Institutes, Universities, career, study routes, scholarships, expert articles, admissions, results, study abroad, foreign education, career options, exams, events, expert articles, education articles';
if($blogInfo[0]['seoKeywords'] != '') {
    $metaKeywords = $blogInfo[0]['seoKeywords'];
}
$seoTitle = 	$blogInfo[0]['blogTitle'] . ' – Articles on higher education';
if($blogInfo[0]['seoTitle'] != '') {
    $seoTitle = $blogInfo[0]['seoTitle'];
}

$noIndexMetaTag = false;
if( isset($blogInfo[0]['noIndex']) && $blogInfo[0]['noIndex']==true ){
    $noIndexMetaTag = true;
}
if($blogInfo[0]['blogType'] == 'exam' || $blogInfo[0]['blogType'] == 'examstudyabroad'){
    $noIndexMetaTag = true;
}

$canonicalURLAdd = '';
if( isset($canonicalURL) && $canonicalURL!=''){
    $canonicalURLAdd = $canonicalURL;
}

$articleImage = (isset($blogInfo[0]['blogImageURL']) && $blogInfo[0]['blogImageURL'] != '') ? $blogInfo[0]['blogImageURL'] : SHIKSHA_HOME.'/public/images/nshik_ShikshaLogo2.gif';
$articleImageArray = explode("_s",$articleImage);
$articleImage = $articleImageArray[0];
if($articleImageArray[1])
    $articleImage .= "_m".$articleImageArray[1];

$creationDate = date ("Y-m-d", strtotime($blogInfo[0]['creationDate']));
		$headerComponents = array(
								'css'	=>	array('articles'),
								'js' => array('facebook','ajax-api'),
								 'jsFooter'      =>      array('blog','common','ana_common','myShiksha'),
								'title'	=>$seoTitle,
								'tabName'	=>	'Articles',
								'taburl' =>  $_SERVER['REQUEST_URI'],
								'bannerProperties' => array('pageId'=>'ARTICLES_DETAILS', 'pageZone'=>'HEADER'),
								'metaKeywords'	=>$metaKeywords,
								'metaDescription'	=> $metaDescription,
								'category_id'   => (isset($CategoryId)?$CategoryId:1),
                                                                'country_id'    => (isset($country_id)?$country_id:2),
                                                                'product' => 'ArticlesD',   
                                                                'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
                                                                'callShiksha'=>1,
								'noIndexMetaTag'=>$noIndexMetaTag,
								'canonicalURL'=>$canonicalURLAdd,
								'searchEnable'=>true,
								'articleDetailPage'=>true,
								'articleImage'=>$articleImage,
								'nextURL'=>$nexturl,
								'previousURL'=>$previousurl,
								'articleCreationDate'=>$creationDate
							);
		$this->load->view('common/header', $headerComponents);
		//$this->load->view('blogs/headerSearchPanelForArticles');
?>
<div class="clearFix"></div>
<?php //if($tab_required_course_page):?>
<div style="padding: 0px 10px 0px 10px">
<?php
//$cpgs_backLinkArray['AUTOSCROLL'] = 1;
//echo Modules::run('coursepages/CoursePage/loadCoursePageTabsHeader', $subcat_id_course_page, $course_pages_tabselected, TRUE,$cpgs_backLinkArray,TRUE); ?>
</div>
<?php //endif;?>

<script>if($('tempSearchType')) $('tempSearchType').value = 'blog';</script>
<?php if($tab_required_course_page):?>
<script>if($('tempkeyword')){ $('tempkeyword').value = 'Search for any Institute or Course'; $('tempkeyword').setAttribute('default','Search for any Institute or Course'); }</script>
<?php else:?>
<script>if($('tempkeyword')){ $('tempkeyword').value = 'Search Articles'; $('tempkeyword').setAttribute('default','Search Articles'); }</script>
<?php endif;?>

<?php $this->load->view('blogs/adsLink'); ?>
<?php
	$this->load->view('common/userCommonOverlay');
	$this->load->view('network/mailOverlay',$data);
	echo "<script language=\"javascript\"> "; 	
	if(is_array($validateuser[0]))
	  echo "var loggedInUserId = '".$validateuser[0]['userid']."';";
	else
	  echo "var loggedInUserId = 0;";
	echo "</script> ";

	$quickSignUser = is_array($validateuser)?$validateuser[0]['quicksignuser']:0; 
	$blogDetailUrl = $_SERVER['REQUEST_URI'];
	echo "<script language=\"javascript\"> ";
	echo "var COMPLETE_INFO = ".$quickSignUser.";";
	echo "var URLFORREDIRECT = '".base64_encode($blogDetailUrl)."';";
	echo "var BASE_URL = '".base_url().""."';";	
	echo "</script> ";
?>
<img id = 'beacon_img' width=1 height=1 />
<script>
   var img = document.getElementById('beacon_img');
   var randNumForBeacon = Math.floor(Math.random()*Math.pow(10,16));
   img.src = '<?php echo BEACON_URL; ?>/'+randNumForBeacon+'/0000003/<?php echo $blogId; ?>';
</script>

<script>
	var categoryTreeMain = eval(<?php echo $category_tree; ?>);
	var eventsCountArr = eval(<?php echo $categoryCount; ?>);		
</script>

<?php function formatRelatedArticleTitle($content, $charToDisplay) {
if(strlen($content) <= $charToDisplay)
return($content);
else
return (preg_replace('/\s+?(\S+)?$/', '', substr($content, 0, $charToDisplay))."...") ;
}
?>

<div id="article-wrapper" class="article-wrapper">
<!--Start_MidPanel-->

	<div itemscope itemtype="https://schema.org/Article"> 

	<div id="article-left-col">
    	<div id="<?php echo in_array($blogId,$MARKETING_BLOGS) ? 'marketingBlog' : 'normalBlog'; ?>"><?php $this->load->view('blogs/blogDetailPanel'); ?></div>						
        	<div class="spacer15 clearFix"></div>
                </div>
		<!--Start Articles Right panel-->
                <div id="article-right-col">
			<?php $this->load->view('blogs/blogDetailRight'); ?>
		</div>                
		<!--End Right Panel-->
            
            
        <!--End_MidPanel-->
	</div>
        <div class="lineSpace_10">&nbsp;</div>
        <div class="lineSpace_10">&nbsp;</div>

	</div>
<?php 
	$bannerProperties = array('pageId'=>'', 'pageZone'=>'');
	$this->load->view('common/footer', $bannerProperties);  
	$threadId = $topicId;
	echo "<script> var threadId = '".$threadId."';</script>";
	if(isset($_COOKIE['commentContent']) && $_COOKIE['commentContent'] != '') {
		$cookieStuff1 = explode('@$#@#$$', $_COOKIE['commentContent']);
		$questionId = $cookieStuff1[0];
		$cookieStuff = explode('@#@!@%@', $cookieStuff1[1]);
		$parentId = $cookieStuff[0];
		$cookieStuff[0] = '';
		$content = '';
		foreach($cookieStuff as $stuff) {
			if($stuff != '') {
				$content .= ($content == '') ? $stuff : '@#@!@%@' .$stuff;
			}
		} 
		echo '<script> if(document.getElementById("replyText'. $parentId .'")){ ';
		echo 'reply_form("'.$parentId.'");document.getElementById("replyText'. $parentId .'").value = "'.$content.'"; 
				if('.$questionId.' != threadId)
				{
					document.getElementById("replyText'. $parentId .'").value = "";
				}
			}
		</script>';
	}
	
	$this->load->view('network/mailOverlay',$maildataForOverlay);

if($mmp_details['display_on_page'] == 'newmmparticle') { ?>
	<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("jquery.tinyscrollbar.min.v3"); ?>"></script>
	<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion('nationalCourses'); ?>" type="text/css" rel="stylesheet" />
	<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion('newmmp'); ?>" type="text/css" rel="stylesheet" />

<?php } ?>

<?php if($mmp_details['page_id'] != '') { ?>

<iframe name="iframe_div1" id="iframe_div1" style="width: 99%; position:absolute; display: none; top: 0; left: 0;  z-index: 1000; background-color: rgba(0, 0, 0, 0.3);" scrolling="no" allowtransparency="true"></iframe>

<div id="mmpOverlayForm" class="Overlay" style="display:none; position: fixed; top:20px;"></div>

<style>
    html.noscroll {
    position: fixed; 
    overflow-y: scroll;
    width: 100%;
}    
</style>
<script>

var is_mmp_popup_shown = 0;
var mmp_form_id_on_popup = '<?php echo $mmp_details['page_id'];?>';
var mmp_display_on_page = '<?php echo $mmp_details['display_on_page'];?>';
var showpopup = '<?php echo $showpopup;?>';

if(mmp_form_id_on_popup != '') {
	
	if (mmp_display_on_page == 'article') {
		
		$j(document).ready(function(){
			$j(window).scroll(function () {
				if (is_mmp_popup_shown == 0) {
					var scroll_height = ($j(document).height())*(.40);
					if($j(window).scrollTop()>=scroll_height){
						registrationOverlayComponent.showOverlay('/customizedmmp/mmp/templateform/'+mmp_form_id_on_popup,800,260,'');
						is_mmp_popup_shown = 1;
						setTimeout(enable_scroll,1000);
					}
				} 
			});
		});
	
	} else if(mmp_display_on_page == 'newmmparticle') {

		var mmp_form_heading = '<?php echo $mmp_details['form_heading'];?>';
		var displayName = '';
		var user_id = '';
		
		<?php
		if(is_array($validateuser)) {?>
		   displayName = escape("<?php echo addslashes($validateuser[0]['displayname']); ?>");
		   user_id = '<?php echo $validateuser[0]['userid'];?>';
		<?php }  ?>
		
		$j(document).ready(function(){
		    disable_scroll();
		    setTimeout(loadmmpform,1000);		   
		});
	}
}

function loadmmpform() {
  var form_data = '';
  form_data += 'mmp_id='+mmp_form_id_on_popup;
  form_data += '&mmp_form_heading='+mmp_form_heading;
  form_data += '&isUserLoggedIn='+isUserLoggedIn;
  form_data += '&displayName='+displayName;
  form_data += '&user_id='+user_id;
  form_data += '&mmp_display_on_page='+mmp_display_on_page;
  form_data += '&exam_name='+'<?php echo $examName;?>';
  form_data += '&showpopup='+showpopup;

  $j.ajax({
    url: "/registration/Forms/loadmmponpopup",
    type: 'POST',
    async:false,
    data:form_data,
    success:function(result) {
      showMMPOverlay('530','860','',result);
      ajax_parseJs($('mmpOverlayForm'));
      setTimeout(enable_scroll,1000);
    }
  });
}

function showMMPOverlay(overlayWidth, overlayHeight, overlayTitle, overlayContent, modalLess, left, top) {
        
    if(trim(overlayContent) == '')
            return false;
    
    var body = document.getElementsByTagName('body')[0];
    
    $('iframe_div1').style.height = body.offsetHeight+'px';
    $('iframe_div1').style.width = body.offsetWidth+20+'px';
	$('iframe_div1').style.display = 'block';            
    
    $('mmpOverlayForm').innerHTML = overlayContent;
    $('mmpOverlayForm').style.width = overlayWidth + 'px';
    $('mmpOverlayForm').style.height = overlayHeight + 'px';

    var divX;                
    if(typeof left != 'undefined') {
       divX = left;
    } else {
       divX = (parseInt(body.offsetWidth)/2) - (overlayWidth/2);
    } 

    $('mmpOverlayForm').style.left = divX + 'px';
    $('mmpOverlayForm').style.top =  '20px';

    overlayHackLayerForIE('mmpOverlayForm', body);
    $('mmpOverlayForm').style.display = 'block';
}

</script>

<?php } ?>

<script>
var currentBubbleObj = null;
function showAdBubble(bubbleObj){
    if(bubbleObj != null) {
        currentBubbleObj = bubbleObj;
    }
    var adStuffBubbleStyle = document.getElementById('adStuffBubble').style;    
    if(currentBubbleObj === null) {
        return false;
    }
    bubbleObj = currentBubbleObj;
    adStuffBubbleStyle.display ='';
    adStuffBubbleStyle.left = (obtainPostitionX(bubbleObj) + bubbleObj.offsetWidth + 2 ) + 'px';
    adStuffBubbleStyle.top = (obtainPostitionY(bubbleObj) - (document.getElementById('adStuffBubble').offsetHeight) + 10) + 'px';
}
function hideAdBubble() {
    //currentBubbleObj = null;
    var adStuffBubbleStyle = document.getElementById('adStuffBubble').style;    
    adStuffBubbleStyle.display ='';
    adStuffBubbleStyle.paddingBottom = '0px';
    adStuffBubbleStyle.paddingLeft = '0px';
    document.getElementById('adStuffBubble').style.display = 'none';
}
</script>
<script type="text/javascript" src="/public/js/evercookie/swfobject-2.2.min.js"></script>
<script type="text/javascript" src="/public/js/evercookie/evercookie.js"></script>
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("jquery.bxSlider"); ?>" type="text/javascript"></script>
<script>
if(typeof(loadPollWidget) == 'function'){
	loadPollWidget();
}
if(typeof(startSlideShow) == 'function'){
	startSlideShow();
}
$j(".embed").find("iframe").each(function(){
      var ifr_source = $j(this).attr('src');
      var wmode = "wmode=transparent";
      if(ifr_source.indexOf('?') != -1) $j(this).attr('src',ifr_source+'&'+wmode);
      else $j(this).attr('src',ifr_source+'?'+wmode);
});
</script>
