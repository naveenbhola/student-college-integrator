<?php
$seoTitle = 	$content['data']['strip_title'];
if($content['data']['seo_title'] != '') {
	$seoTitle = $content['data']['seo_title'];
}
$canonicalURL = $content['data']['contentURL'];
if($content['data']['seo_description'] == '') {
	$text = strip_tags($content['data']['summary']);
}else {
	$text = strip_tags($content['data']['seo_description']);
}
$text = str_replace('&nbsp;',' ',trim($text));
if(strlen($text) > 150) {
	$newText = substr($text,150,160);
	$spaceAfter150 = stripos($newText,' ');
	$text = substr($text,0,150+$spaceAfter150);
}else {
	$text = substr($text, 0, 150);
}
$metaDescription = $text;
$imageUrl = str_replace('_s','',$content['data']['contentImageURL']);
$pgType = ($content['data']['type'] == 'guide') ? 'guidePage' : 'articlePage';

$robots = 'ALL';
if($content['data']['content_id']==246){
        $robots = 'NOINDEX';
}

$headerComponents = array(
        'css'=>array('studyAbroadCommon', 'studyAbroadHomePage','studyAbroadGuide'),
        'canonicalURL'      => $canonicalURL,
        'title'             => $seoTitle,
        'metaDescription'   => $metaDescription,
		'hideSeoRevisitFlag' => true,
		'hideSeoRatingFlag' => true,
		'hideSeoPragmaFlag' => true,
		'hideSeoClassificationFlag' => true,
		'articleImage' => $imageUrl,
		'pgType'	        => $pgType,
		'robotsMetaTag' => $robots,
        'pageIdentifier'    => $beaconTrackData['pageIdentifier']
);
?>

<?php
 
$this->load->view('common/studyAbroadHeader', $headerComponents);
echo jsb9recordServerTime('SA_CONTENT_PAGE',1);
?>
<div itemscope itemtype="http://schema.org/Article">
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
<div class="content-wrap clearfix">                                                                        
    <!-- Article Guide Starts Here -->
   	  <div id="article-header">
   	    <?php $this->load->view('saContent/SAContentHeader'); ?>
      </div>
    	<div id="left-col">
    	   <?php $this->load->view('saContent/SAContentDesc'); ?>
    	   <?php //echo modules::run('abroadContentOrg/AbroadContentOrgPages/getContentOrgWidget'); ?>
	   <?php $this->load->view('saContent/authorWidget');?>
             
	   <?php $this->load->view('saContent/commentSection',array('content_id' => $content['data']['content_id']));?>
          </div>
    	<div id="right-col">
            <?php //$this->load->view('saContent/topRankedInstitutes');?>
	    
            <?php $this->load->view('saContent/popularArticles');?>
            
	    <?php $this->load->view('saContent/guideRightWidget');?>
        <?php $this->load->view('listing/abroad/widget/facebookWidget');?>
	    <?php $totalResultCount = count($relatedGuideArticles);?>
        <div  id="rightStickyPanel" class="clearwidth"  style="margin-top: 15px; width: 308px;"> 
	    <?php if($is_downloadable!= 'no' || $totalResultCount >0){ ?>
	    <?php $this->load->view('saContent/downloadGuideRightWidget');?>
		<?php } ?>
	    <?php echo Modules::run('applyContent/applyContent/loadFindCollegesWidgetOnContentPage', array('contentType'=>$content['data']['type'])); ?>
	     	<!-- Related Guide Article Widget : STARTS -->
		<?php
			if($totalResultCount >0){
				$this->load->view('saContent/relatedGuideArticleRightWidget');
			}
		?>
        	<!-- Related Guide Article Widget : ENDS -->
		</div>
        </div>
          <?php if($content['data']['type'] == 'guide' && count($content['data']['sections'])>=1){ ?>
		<div class="guide-menu" id="jump" style="position:absolute;" onmouseover='showJumpSection();' onmouseout='hideJumpSection();'>
		<a id = "span1"   style="display: none;" href="javascript:void(0);" class="guide-btn"><i class="article-sprite guide-menu-icon"></i>
		<span>Guide Menu</span>	<i class="article-sprite guide-arrow"></i>
		</a>
		
		<a href="javascript:void(0);" id = "span2" style="display: none;" class="guide-btn"><i class="article-sprite guide-menu-icon"></i>
			<i class="article-sprite guide-arrow"></i>
		</a>
		<p class="guide-info" id="guideInfo" style="display: none;">Quickly access the <br/> guide topics</p>
			<?php $this->load->view('saContent/jumpSection');?>		
		</div>
	<?php }?>
        <!-- Article Guide Ends Here -->
    </div>
<?php
	$footerComponents = array(
			'js'=>array('studyAbroadCategoryPage','studyAbroadGuide','studyAbroadContent', 'studyAbroadHomepage','facebook','jquery.royalslider.min','jquery.tinycarouselV2.min'),
			'asyncJs'=>array('jquery.royalslider.min','jquery.tinycarouselV2.min')
			);
	$this->load->view('common/studyAbroadFooter',$footerComponents); ?>
<?php if($content['data']['type'] == 'article') :?>
</div>
<?php endif;?>

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
	             //$('thankMsg').style.display = 'inline';
	             $j('#thankMsg').css('display','inline');
              
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
?>
<script>
var isContentPage = true;
$j(document).ready(function(){
	$j('#relatedGuidesArticleWidget').show();
	if(isScrolledIntoViewSticky('#footer')){
			$j('#rightStickyPanel').hide();
	}
	else{
			$j('#rightStickyPanel').show();
	}

	$j("#jump_section").show();
	$j('#sections_guide').tinyscrollbar();
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
		if(isScrolledIntoViewSticky('#footer')){ 
			$j('#rightStickyPanel').hide();
		}
        else{
            $j('#rightStickyPanel').show();
        }
	    
	    if(isScrolledIntoViewSticky('#footer')){ 
            $j('#jump').hide();
        }
        else{
            $j('#jump').show();
			if($j("#jump").length > 0){
	            $j("#jump").css('position','fixed');
			}
        }
    });
});

if(contentType==='article'){
    initializeSliderWidget();
}
</script>
<?php //} ?>

