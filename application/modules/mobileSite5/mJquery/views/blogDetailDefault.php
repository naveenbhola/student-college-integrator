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
	<div class="article-content">
		<?php 
			if($blogId == 13149){
				$this->load->view('collegeResultInputPage');
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
	<?php $this->load->view('articleDetailsOtherInfo');?>
</section>
<script>
/*if(typeof(loadPollWidget) == 'function'){
	loadPollWidget();
}*/
</script>
