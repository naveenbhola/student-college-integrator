<?php 
$blogDescriptionObj = $blogObj->getDescription();
$blogDescription = $blogDescriptionObj[0]->getDescription();
$lastBlogPageTag = $nextBlogPageTag = $lastPageTag = '';
$nextBlogPage  = $lastBlogPage = $currentPage = 0;
$pageNumber = 0;

foreach($blogPagesIndex as $blogPageNum => $blogPageTag) {
	++$pageNumber;
	if($currentPage > 0) {
		$nextBlogPage = $pageNumber;
		$nextBlogPageTag = $blogPageTag == '' ? 'Next Page >>' : 'Next Page - '. $blogPageTag .' >>';
	}
	if ($blogPageNum == $blogDescriptionObj[$pageNum]->getDescriptionId()) {
		if($pageNumber > 1) {
			$lastBlogPageTag = $lastPageTag == '' ? '<< Previous Page' : '<< Previous Page - '. $lastPageTag;
			$lastBlogPage = $pageNumber;
		}
		$currentPage = $pageNumber;
	}
	if($nextBlogPage > 0) {
		break;
	}
	$lastPageTag = $blogPageTag;
}
?>
<section class="content-wrap2">
	<div class='articleDetails'>
		<div class="article-content">
			<?php 
				if($blogId == 13726 && 0){
					 if($blogId == '13149'){
					 	$displayData['examType'] = 'VITEEE';
					 	$displayData['examName'] = 'VIT';
					 	$displayData['examUrl'] = 'http://www.vit.ac.in';
					 	$displayData['logoUrl'] = '/public/mobile5/images/vit_m.png';
					 	$displayData['altTxt'] = 'vitee result 2017 jpg';
					 	$displayData['resultUrl'] = '/vitResult';
					 	$displayData['trackingKeyId']='1132';
					 	$displayData['gaAttrExamLink'] = 'VIT_WEBSITE';
					 }else if($blogId == '13726'){
					 	$displayData['examType'] = 'SRMJEEE';
					 	$displayData['examName'] = 'SRM';
					 	$displayData['examUrl'] = 'http://www.srmuniv.ac.in';
					 	$displayData['logoUrl'] = '/public/mobile5/images/srm_m.png';
					 	$displayData['altTxt'] = 'SRM result 2017 jpg';
					 	$displayData['resultUrl'] = '/srmResult';
					 	$displayData['trackingKeyId']='1161';
					 	$displayData['gaAttrExamLink'] = 'SRM_WEBSITE';
					 }
					$this->load->view('collegeResultInputPage',$displayData);
			}?>
			<p>
			<?php echo addAltTextMobile($blogObj->getTitle(), $blogDescriptionObj[$pageNum]->getDescription(), 1);?>                     
			
			</p>
			<?php //$this->load->view('pollWidgetMobile'); ?>
			
		      
			<?php
			$urlseg = $this->uri->segment(1);
			$url_segments = explode("-", $urlseg);
			if ($url_segments[0] != 'getArticleDetail') {
			
			if ($lastBlogPage == 0) { $lastBlogPage = 1;}
			$next = $lastBlogPage + 1;
			$prev = $lastBlogPage - 1;
			if ($prev == 0) {
			$prev = 1;
			}
			$BASEURL = substr($_SERVER['REQUEST_URI'],0,strpos($_SERVER['REQUEST_URI'],"".$blogId)+strlen($blogId))."-";
			?>
						
						<?php if(!empty($lastBlogPageTag) || !empty($nextBlogPageTag)){?>
						<nav id="paging" class="clearfix">
							<?php if(!empty($lastBlogPageTag)):?>
							
							<a class="prev" href="<?php echo $BASEURL . $prev; ?>"><i class="icon-prev"></i> Previous</a>
							<?php endif;?>
												<?php if(!empty($nextBlogPageTag)):?>
							<a class="next" href="<?php echo $BASEURL . $next; ?>">Next <i class="icon-next"></i></a>
							<?php endif;?>
						
						</nav>
						<?php } ?>
			
			<?php
			} else {
					if(!empty($lastBlogPageTag) || !empty($nextBlogPageTag)){ ?>
						<nav id="paging" class="clearfix" >
			                             <?php   if($lastBlogPage!=0): ?>
						     <a  class="next" href="<?php echo substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'],'?')) .'?token=aa&page='. ($lastBlogPage-1); ?>" ><i class="icon-prev"></i>Previous</a>
						     <?php endif; ?>
						     <?php if(!empty($nextBlogPageTag)):?>
			                            <a class="prev" href="<?php echo substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'],'?')) .'?token=aa&page='. ($lastBlogPage-1); ?>" >Next <i class="icon-next"></i></a>
							<?php endif;?>
						</nav>
					<?php } ?>
			<?php
			}
			?>        
		</div>
	</div>
	<?php if($_REQUEST['test'] != '1234') { ?>
		<div class="article-readmore">
			<a class="link-blue-medium articleViewMore" href="javascript:void(0)">Read Full Article</a>
		</div>	
	<?php } ?>
</section>

<div class="social-wrapper-btm">
	<?php $this->load->view("mcommon5/socialSharingBand", array('widgetPosition' => 'ADP_Bottom')); ?>
</div>
<?php $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'LAA'));?>
<script>
/*if(typeof(loadPollWidget) == 'function'){
	loadPollWidget();
}*/
</script>
<?php 
if($validateuser == 'false' || empty($validateuser)){
?>
<section class="content-wrap2">
<div class="search-block txt-cntr">
  	<h3 class="signup-h3">Taking an Exam? Selecting a College?</h3>
	<p class="inf-txts">Find insights & recommendations on colleges and exams that you <strong>won't</strong> find anywhere else</p>
	<a class="nw-btn" onclick="return showArticleUserRegistation('<?=$trackingKeyIdForRegnWidget?>');">Sign Up & Get Started </a>
	 <p class="background z-ind"><span>On Shiksha, get access to</span></p>
	<ul class="inf-li">
	<li><strong><?=$entityCount['collegeCount']?></strong> Colleges</li>
	<li><strong><?=$entityCount['examCount']?></strong> Exams</li>
	<li><strong><?=$entityCount['reviewCount']?></strong> Reviews</li>
	<li><strong><?=$entityCount['answerCount']?></strong> Answers</li>
	</ul>
</div>
</section>
<?php 
}
?>
