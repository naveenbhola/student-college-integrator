<div class="content-tupple">
	<h2 class="exm-sub-hd" style="margin-bottom: 20px;">Prep Tips</h2>
	<?php if(empty($examPagePrepTips['articles'])) {?>
	<p>
	Nothing interesting here!
	<br/>
	Go to <a href="<?=$sectionUrl['home']['url']?>"> <?=$examName;?> homepage </a>
	</p>
	<?php } else {?>
	<ul class="prep-tip-list">
  <?php 
  $count = 1;
  foreach($examPagePrepTips['articles'] as $prepTip) {?>	
  <li class = "<?=count($examPagePrepTips['articles'])== $count ? 'last' : "" ?>">
  <?php 
   $this->load->view ( 'examPages/examPagePrepTipsTuple',array('prepTip'=>$prepTip));
	?>
  </li>
  <?php $count++; }?>
  
  		<?php 
   		if($examPagePrepTips['totalNumRows'] > $offest+$noOfRows) {
         ?>
	    <div class="show-more" onclick="examPageTrackEventByGA('NATIONAL_EXAM_PAGE_<?=$trackMainExamName?>', 'prep_tips_page', 'showMore'); loadMorePrepTips('<?=$params?>',<?=$offest+$noOfRows?>, '<?=$examId?>', this);">
	        <div style="float: left;visibility: hidden;" id="pagination-loder"><img style="height:18px;" src="/public/images/loader_hpg.gif"></div>
        	<a  href="javascript:void(0);">Show More</a>
	    </div>
<?php
		} ?>
  
	</ul>
	<?php }?>

</div>

<?php $this->load->view("widgets/newsArticleSliderWidget"); ?>

<?php $tracking_keyid = DESKTOP_NL_EXAM_PAGE_PREPTIP_REG; ?>
<?php 
$this->load->view('examPages/widgets/registrationWidget',array('tracking_keyid'=>$tracking_keyid)); 
?>    

 <!-- Start : Similar Exam Widget-->
<?php echo Modules::run('examPages/ExamPageMain/similarExamWidgets',$examId,$examPageData->getExamName()); ?>
<!-- End : Similar Exam Widget-->
           