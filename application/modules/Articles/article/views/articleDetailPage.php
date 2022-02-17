<?php
ob_start('sanitize_output');

$metaDescription = ($blogObj->getTitle() != '') ? $blogObj->getTitle() . ' - education & career article by ' :'education & career article by ';
if($blogObj->getType() == 'kumkum') { 
    $metaDescription .=  'Kumkum Tandon ';
} elseif ($blogObj->getType() == 'ht') {
    $metaDescription .=  'HT Horizons ';
} else {
    $metaDescription .=  ucwords($blogUserData['name']) . " ";
}
$order   = array("\r\n", "\n", "\r","\t","<br>","<br />","<br/>","&nbsp;");
$replace = '';
//$blogDescription = json_decode($blogInfo[0]['blogText'], true);
$blogDescriptionObj = $blogObj->getDescription();
if($blogObj->getBlogLayout() == 'qna'){
	$text = $blogDescriptionObj[0]->getQuestion();
}else if($blogObj->getBlogLayout() == 'slideshow'){
	$text = $blogDescriptionObj[0]->getDescription();
}else{
	$text = $blogDescriptionObj[0]->getDescription();
}

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
if($blogObj->getSeoDescription() != '') {
    $metaDescription = $blogObj->getSeoDescription();
}
$metaKeywords = '';
if($blogObj->getSeoKeywords() != '') {
    $metaKeywords = $blogObj->getSeoKeywords();
}
$seoTitle = 	$blogObj->getTitle() . ' – Articles on higher education';
if($blogObj->getSeoTitle() != '') {
    $seoTitle = $blogObj->getSeoTitle();
}

$noIndexMetaTag = false;
/*if( isset($blogInfo[0]['noIndex']) && $blogInfo[0]['noIndex']==true ){
    $noIndexMetaTag = true;
}*/
if($blogObj->getType() == 'exam' || $blogObj->getType() == 'examstudyabroad'){
    $noIndexMetaTag = true;
}

$articleImage = ($blogObj->getBlogImageURL() != '') ? $blogObj->getBlogImageURL() : IMGURL_SECURE.'/public/images/nshik_ShikshaLogo2.gif';
$articleImageArray = explode("_s",$articleImage);
$articleImage = $articleImageArray[0];
if($articleImageArray[1]){
    $articleImage .= "_m".$articleImageArray[1];
}
$creationDate = date ("Y-m-d", strtotime($blogObj->getCreationDate()));

if($blogObj->getType() == 'boards') {
        $ampURL = getAmpPageURL('boards',$canonicalURL);
}
else if($blogObj->getType() == 'coursesAfter12th') {
        $ampURL = getAmpPageURL('coursesAfter12th',$canonicalURL);
}
else{
        $ampURL = getAmpPageURL('blog',$canonicalURL);
}

$headerComponents = array(
						 'jsFooter'           => array(),
						'title'               => $seoTitle,
						'tabName'             => 'Articles',
						'taburl'              => $_SERVER['REQUEST_URI'],
						'bannerProperties'    => array('pageId'=>'ARTICLES_DETAILS', 'pageZone'=>'HEADER'),
						'metaKeywords'        => $metaKeywords,
						'metaDescription'     => $metaDescription,
						'category_id'         => (isset($CategoryId)?$CategoryId:1),
						'country_id'          => (isset($country_id)?$country_id:2),
						'product'             => 'ArticlesD',
						'displayname'         => (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
						'callShiksha'         => 1,
						'noIndexMetaTag'      => $noIndexMetaTag,
						'canonicalURL'        => $canonicalURL,
						'searchEnable'        => true,
						'articleImage'        => $articleImage,
						'nextURL'             => $nexturl,
						'previousURL'         => $previousurl,
						'articleCreationDate' => $creationDate,
						'ampUrl'  	      => $ampURL,
						'cssFooter'       => array('socialSharingBand')
					);
$this->load->view('common/header', $headerComponents);
?>

<div class="clearFix"></div>
<div style="padding: 0px 10px 0px 10px"></div>
<?php //$this->load->view('blogs/adsLink'); ?>
<?php
	// $this->load->view('common/userCommonOverlay');
	// $this->load->view('network/mailOverlay',$data);
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
 

<script>
	var categoryTreeMain = eval(<?php echo $category_tree; ?>);
	var eventsCountArr = eval(<?php echo $categoryCount; ?>);		
</script>

<?php 
function formatRelatedArticleTitle($content, $charToDisplay) {
	if(strlen($content) <= $charToDisplay)
		return($content);
	else
		return (preg_replace('/\s+?(\S+)?$/', '', substr($content, 0, $charToDisplay))."...") ;
}
?>

<div id="article-wrapper" class="article-wrapper">
<!--Start_MidPanel-->
	<?php 
	if($blogId == '13726' && 0) {
		if($blogId == '13149'){
		 	$displayData['examType'] = 'VITEEE';
		 	$displayData['examName'] = 'VIT';
		 	$displayData['examUrl'] = 'http://www.vit.ac.in';
		 	$displayData['logoUrl'] = '/public/images/vit_d.png';
		 	$displayData['altTxt'] = 'vitee result 2017 jpg';
		 	$displayData['resultUrl'] = '/vitResult';
		 	$displayData['trackingKeyId']='1131';
		 	$displayData['gaAttrExamLink'] = 'VIT_WEBSITE';
		 }else if($blogId == '13726'){
		 	$displayData['examType'] = 'SRMJEEE';
		 	$displayData['examName'] = 'SRM';
		 	$displayData['examUrl'] = 'http://www.srmuniv.ac.in';
		 	$displayData['logoUrl'] = '/public/images/srm_d.png';
		 	$displayData['altTxt'] = 'SRM result 2017 jpg';
		 	$displayData['resultUrl'] = '/srmResult';
		 	$displayData['trackingKeyId']='1160';
		 	$displayData['gaAttrExamLink'] = 'SRM_WEBSITE';
		 }
		?>

        <div class="article-header">
            <div class="float_R">
                <div id="articleDeleteMsg" class="bld fontSize_16p" style="display:none"></div>
                <div id="articleOperations">
                    <?php if(is_array($validateuser[0]) && ($blogObj->getCreatorId() == $validateuser[0]['userid'] || $validateuser[0]['usergroup'] == 'cms')){ ?>
                    <button  class="btn-submit13 w3" type="Submit" onclick="location.replace('/enterprise/Enterprise/addBlogCMS/<?php echo $blogId.'/'.$blogObj->getStatus(); ?>');">
                        <div class="btn-submit13"><p class="btn-submit14 btnTxtBlog">Edit Article</p></div>
                    </button>
                    &nbsp;
                    <button  class="btn-submit5 w3" type="button" onclick="deleteArticle('<?php  echo $blogId; ?>');">
                        <div class="btn-submit5"><p class="btn-submit6">Delete Article</p></div>
                    </button>
                    <?php } ?>
                </div>
            </div>
            
            <div>
                <h1><?php if($blogObj->getTitle()!=''):echo $blogObj->getTitle(); endif; ?></h1>
                <p class="post-details">
                    <?php
                        $commentCountForTopic = isset($commentCountForTopic)?$commentCountForTopic: 0;
                        switch($commentCountForTopic) {
                            case 0: $commentsCaption = '<span><span id="commentCountHolder">Ask or</span> Comment</span>'; break;
                            case 1: $commentsCaption = '<span><span id="commentCountHolder">1</span> Comment</span>'; break;
                            default: $commentsCaption = '<span><span id="commentCountHolder">'.$commentCountForTopic.'</span> Comments</span>'; break;
                        }
                    ?>
                </p>
	
            </div>

            <div class="social-cont">
                <div class="socila-icons">
                	<table cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td>
								<div class="flLt">
					            	<?php 
					            		$source = 'BLOG_BLOGDETAIL'; 
					            		if(!is_array($validateuser[0])){ 
					            			?>
					            			<a href="javascript:void(0);" class="mailArw" onClick="if(checkForUnifiedObjectAndMethod()){registerNowOnBlog('emailthiswidget','false','209');} return false;"  title="Email this"></a>
					            			<?php 
					            		} 
					            		else { ?>
					            			<a href="javascript:void(0);" class="mailArw" onClick="showSearchMailOverlay('blogEmailThis','<?php echo $blogId; ?>','<?php echo $blogObj->getUrlNew(); ?>');return false;"  title="Email this"></a>
					            			<?php 
					            		} 
					            	?>
					            </div>

	    
	                            <script>
		                            var blogId_f = <?php echo $blogId;?>;
		                            var boardId_f = '';
		                            var action = 'followArticle';
		                            paraString_facebook = 'action='+action+'&blogId='+blogId_f;
		                            eventTriggered = action;
	                            </script>
                    		</td>
                    	</tr>
                	</table>
	   
            	</div>
         
                <div class="comment-section">
                    <a href="<?php if($tab_required_course_page){ ?>javascript:void(0);<?php } else {?>#blogCommentSection<?php } ?>" <?php if($tab_required_course_page){ ?>onclick="blogCommentSectionWithCPGSHeader();"<?php } ?> class="art-comnts"><?php echo $commentsCaption; ?></a>
                </div>
            </div>
 
        </div>

    	<?php 
    	$this->view('examResultInputView',$displayData);
    }
    ?>

	<div id="article-left-col">
    	<div id="<?php echo in_array($blogId,$MARKETING_BLOGS) ? 'marketingBlog' : 'normalBlog'; ?>"><?php $this->load->view('article/articleDetailPanel'); ?></div>						
        <div class="spacer15 clearFix"></div>
    </div>
		<!--Start Articles Right panel-->
    <div id="article-right-col">
		<?php $this->load->view('article/articleDetailsRight'); ?>
	</div>                
	<!--End Right Panel-->
        
        
    <!--End_MidPanel-->
	<!-- <div itemscope itemtype="https://schema.org/Article"> </div> -->
    <div class="lineSpace_10">&nbsp;</div>
    <div class="lineSpace_10">&nbsp;</div>
	<?php
 	    echo Modules::run("Interlinking/InstituteWidget/getRelatedInstituteWidget", $relatedEntityIds,'articleDetailPage');
        echo Modules::run("Interlinking/ExamWidget/getRelatedExamWidget", $relatedEntityIds,'articleDetailPage');
      ?>

      <!-- CHP Interlinking -->
      <?php $this->load->view('common/chpInterLinking');?>
      <!-- CHP Interlinking END-->

</div>
<?php 
	$bannerProperties = array('pageId'=>'', 'pageZone'=>'', 'lazyLoadUserRegistrationCss' => true, 'cssFilePlugins' => array('userRegistrationDesktop'), 'lazyLoadJsFiles' => array('ana_common'));
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
	
	// $this->load->view('network/mailOverlay',$maildataForOverlay);

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
<script type="text/javascript">

var is_mmp_popup_shown = 0;
var mmp_form_id_on_popup = "<?=$mmp_details['page_id']?>";
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

<?php }

if(in_array($blogObj->getBlogLayout(), array('slideshow','qna'))) { ?>
	<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("jquery.bxSlider"); ?>" type="text/javascript"></script>
<?php } ?>

<img id='beacon_img' width=1 height=1 />
<script type="text/javascript">
var img = document.getElementById('beacon_img');
var randNumForBeacon = Math.floor(Math.random()*Math.pow(10,16));
img.src = '<?php echo BEACON_URL; ?>/'+randNumForBeacon+'/0000003/<?php echo $blogId; ?>';
var isCompareEnable = true;
var GA_currentPage = "<?=$GA_currentPage?>";

$j(function(){
    if(typeof($j('#_article-login'))) {
        $j('#article-right-col').on('click','#_article-login',function(){
            return showArticleUserRegistation('<?=$trackingKeyIdForRightRegnWidget?>');
        });
    }
});
<?php if(!in_array($blogObj->getBlogLayout(), array('slideshow','qna'))){ ?>
	$j(window).on('load',function(){
		if(typeof $j("img.lazy").lazyload == "function"){
	        $j("img.lazy").lazyload({effect:"fadeIn",threshold:100});
	    }
	});
<?php } ?>
</script>
<?php //$this->load->view('common/newMMPForm'); ?>
