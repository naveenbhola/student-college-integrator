<div class="qa-cont">
<?php
$description = $blogObj->getDescription();
foreach($description as $qnaObj){
	if($qnaObj->getQuestion()){
?>
    	<div class="qa-wrap">
        	<h4>
            	<span class="ques-icn"></span>
                <p><?=html_escape($qnaObj->getQuestion())?></p>
            </h4>
            
            <div class="ans">
            	<span class="ans-icn"></span>
                <div class="ans-details">
                	<?=addAltText($blogObj->getTitle(), $qnaObj->getAnswer());?>
                </div>
            </div>
			<div class="spacer20 clearFix"></div>
        </div>
<?php
	}else{
?>
	<div>
	<p>&nbsp;</p><?=addAltText($blogObj->getTitle(), $qnaObj->getAnswer());?>
    </div>
<?php
	}
}
?>
</div>