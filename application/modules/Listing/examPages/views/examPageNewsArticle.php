<div class="content-tupple" id="wiki-sec-0">
    	<h2 class="exm-sub-hd" style="margin-bottom: 20px;">News and Articles</h2>
	<?php
if($examPageNews)
{
?>
        <span class="news-badge">NEWS</span>
        <ul class="news-list">
		<?php $this->load->view ('examPages/widgets/newsWidget'); ?>
		 </ul>
<?php }
	?>
	
	<?php
	if($examPageArticles)
	{
	?>
		<span class="news-badge">ARTICLES</span>
		<ul class="article-list">
	<?php
	$this->load->view ( 'examPages/widgets/articlesWidget' );
	?>
		</ul>
	<?php
	}
	?>
	
	<?php
	// blank page condition
		if(empty($examPageNews) && empty($examPageArticles))
		{
	?>
			<p>
			Nothing interesting here!
			<br/>
			Go to <a href="<?=$sectionUrl['home']['url']?>"> <?=$examName;?> homepage </a>
			</p>
	<?php
		}
	?>
    </div>

	<?php $tracking_keyid = DESKTOP_NL_EXAM_PAGE_NEWS_N_ARTICLE_BELLY_REG; ?>
	<?php 
	$this->load->view('examPages/widgets/registrationWidget',array('tracking_keyid'=>$tracking_keyid)); 
	?>    

    <!-- Start : Similar Exam Widget-->
	<?php echo Modules::run('examPages/ExamPageMain/similarExamWidgets',$examId,$examPageData->getExamName()); ?>
    <!-- End : Similar Exam Widget-->
