<?php 
if(empty($activeSectionName)){
	$activeSectionName = 'homepage';
}
?>
<section>
	  <h2 class="color-3 f16 heading-gap font-w6"></h2>
	  <div class="color-w card-cmn f14 color-3">
	      <strong class="font-w6">Have any doubt related to <?=$examName?>? Ask our experts</strong>
	      <div class="btn-sec">
	            <a class="btn btn-primary color-o color-f f14 font-w6 m-15top ga-analytic" href="<?=SHIKSHA_HOME?>/mAnA5/AnAMobile/getQuestionPostingAmpPage?examId=<?=$examId?>&groupId=<?=$groupId?>&fromwhere=examPageAMP&examPageType=<?=$activeSectionName?>" data-vars-event-name="ASK_NOW">Ask Now</a>
	      </div>
  	  </div>
</section>
