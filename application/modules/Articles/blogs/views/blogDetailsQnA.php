<div class="qa-cont">
<?php
$blogInfo[0]['blogQnA'] = json_decode($blogInfo[0]['blogQnA'],true);
foreach($blogInfo[0]['blogQnA'] as $sn){
	if($sn['question']){
?>
    	<div class="qa-wrap">
        	<h4>
            	<span class="ques-icn"></span>
                <p><?=html_escape($sn['question'])?></p>
            </h4>
            
            <div class="ans">
            	<span class="ans-icn"></span>
                <div class="ans-details">
                	<?=addAltText($blogInfo[0]['blogTitle'],$sn['answer']);?>
                </div>
            </div>
			<div class="spacer20 clearFix"></div>
        </div>
<?php
	}else{
?>
	<div>
	<p>&nbsp;</p><?=addAltText($blogInfo[0]['blogTitle'],$sn['answer']);?>
    </div>
<?php
	}
}
?>
</div>
