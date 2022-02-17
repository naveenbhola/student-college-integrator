<div class="content-tupple update-widget">
	<h2 class="update-head similarExam-head">View Similar Exam</h2>
	<div class="update-form clearfix">
		<ul class="exam-list">
			<?php
			$i   = 0;
			$len = count($similarExams);
			foreach($similarExams as $exam){ 
				if ($i == $len - 1) {
					$class = "class='last'";
				}else{
					$class = "";
				}
			?>
			<li <?php echo $class ?>>
				<div class="exam-col">
					<div class="exam-col-img"><i class="exam-sprite viewedExam-img"></i></div>
				</div>
				<div class="exam-title">
					<a onclick = "examPageTrackEventByGA('NATIONAL_EXAM_PAGE_<?=$trackMainExamName?>', 'similar_exam_clicked', '<?=$exam['exam_name']?>');" href="<?php echo $exam['exam_url'] ?>">
						<?php echo $exam['exam_name'] ?>
					</a>
				</div>
			</li>
			<?php $i++; } ?>
		</ul>
	</div>
</div>
