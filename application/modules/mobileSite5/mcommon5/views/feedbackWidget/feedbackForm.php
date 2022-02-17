<?php 
$tagHtml = '';
$countTag = 0;
$class = 'shortHeight';
foreach($ratingStarTags[$rating] as $tagInfo){
	$countTag++;
	$tagHtml .= '<li><input name="ratingTags[]" value="'.$tagInfo['tag'].'" class="capsule-selection" type="checkbox" id="rating-'.$tagInfo['id'].'"/><label class="capsule" for="rating-'.$tagInfo['id'].'">'.$tagInfo['tag'].'</label></li>';
}
if($countTag >= 4){
	$class = 'fullHeight';
}
?>
<div class="opeclayer" id="feedbackFormHtml">
	<div class="feedbackbox">
	    <div class="form-container">
	    	<div class="form-head">
	    		<strong>Feedback</strong>
	    		<span class="cross" id="closeFeedbackForm">×</span>
	    	</div>
	        <div class="form-container-inner">
		        <?php $this->load->view('feedbackWidget/feedback', array('withForm' => true)); ?>
		        <div class="information-section">
		            <div class="feedback-text" id="feedbackExplanation"><?php echo $ratingStars[$rating]['label'] ?></div>
		            <div class="options-box">
		                <p class="field-head-text">I found the page information:</p>
		                <ul class="inline-list <?php echo $class ?>" id="feedbackTags"><?php echo $tagHtml; ?></ul>
		            </div>
		        </div>
		        <div class="suggetion-section">
		            <div class="suggetion-box">
		                <p class="field-head-text">Any suggestions or inputs for Shiksha.</p>
		                <textarea class="inputbox" name="ratingText" id="ratingText" rows="5" cols="40" maxLength="200" placeholder="Write here (Optional)"></textarea>
		            </div>
		            <div class="btn-container">
		                    <button id="submitFeedback" class="button button--orange">Submit</button>
		            </div>
		        </div>
	    	</div>
	    </div>
	</div>
</div>