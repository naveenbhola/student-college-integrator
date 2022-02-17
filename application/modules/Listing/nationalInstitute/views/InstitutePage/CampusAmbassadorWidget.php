<?php if(!empty($campusReps)) { ?>
	<div class="cleaRaLL">
	 		<div class="fltlft qstners__ppsec">
	 		<h2 class="title__query">Ask queries to current students of this college</h2>
	 			<ul class="queries__ul">
	 				<?php foreach ($campusReps as $rep){ ?>
	 					<li>
	 						<div class="n__sec">
	 							<span class="img__a">
	 								<?php
	 								if($rep['imageURL'] != '' && strpos($rep['imageURL'],'photoNotAvailable') === false){?>
	 									<img class="lazy" data-original="<?=getSmallImage($rep['imageURL'])?>" />
	 									<?php }
	 									else{ ?>
	 										<?= ucwords(substr($rep['displayName'],0,1)); ?>
	 								<?php } ?>
	 							</span>
								<div class="new__slide__sec">
									<p class="qstnr__name" title="<?=$rep['displayName']?>"> <?=$rep['displayName']?></p>
									<?php 
										$courseObj = $courseData[$rep['courseId']];
										if(!empty($courseObj)) { ?> <p title="<?=$courseObj->getName();?>" class="course__name"> <?=$courseObj->getName();?></p> <?php } ?>
								</div>
	 						</div>
	 					</li>
	 				<?php } ?>
	 			</ul>
	 		</div>

			<?php $this->load->view('InstitutePage/AskProposition'); ?>
	</div>
<?php } else {
			$this->load->view('InstitutePage/AskProposition');
	} ?>
<script>
	<?php foreach ($campusReps as $rep){ ?>
		$j('span[id="currentStudent_<?=$rep['userId']?>"]').show().closest('a.stu-usr').next('span.g-l').hide();
	<?php } ?>
	handleQnaWidgetPosition();
</script>