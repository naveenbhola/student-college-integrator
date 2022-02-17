<?php 
$results_to_be_shown = $googleRes['general']['numfound'] <10 ? $googleRes['general']['numfound'] :10;

if($results_to_be_shown > 0)
{
    
    if($results_to_be_shown>=6)
        $height = '255px';
    else
        $height = (($results_to_be_shown) * 45) + 'px';
    
?>
	<div class="similar-ques-section" style="position:relative;">
	    <h4>We have found <span><?php echo $results_to_be_shown;?> similar <?php if($results_to_be_shown==1){echo "question";}else{echo "questions";}?>!</span> which may have the answer you looking for</h4>
	    <ul style="height:<?=$height?>">
		<?php 
            $questionObjArr = $googleRes['content'];
         foreach($questionObjArr as $questionObj) 
		 {	
            $questionTitle =  preg_replace('/(Ask Questions on various .*)/','',$questionObj->getTitle());
			$lenOfQuestionTitle = strlen($questionTitle);
			$tmpLink = explode('/',$questionObj->getUrl());
			$linkId  = $tmpLink[4];
		?>
			<li>
				<p id="actualRelatedQuestionId<?php echo $linkId;?>" style="display:none;"><a onClick="trackEventByGA('SEARCH_RESULTS','SHIKSHA_CAFE_INTERMEDIATE_SOURCE_SHIKSHA_SEARCH');" href="<?php echo $questionObj->getUrl();?>#RelQues" target= "_blank" ><?php echo $questionTitle; ?></a></p>

				<p id="partialRelatedQuestionId<?php echo $linkId;?>"><a onClick="trackEventByGA('SEARCH_RESULTS','SHIKSHA_CAFE_INTERMEDIATE_SOURCE_SHIKSHA_SEARCH');" href="<?php echo $questionObj->getUrl();?>#RelQues" target= "_blank" ><?php if($lenOfQuestionTitle >140){ echo substr($questionTitle,0,140); ?><a href="javascript:void(0)" onClick="showFullRelatedQuestionAnA('<?php echo $linkId;?>');">...more</a><?php }else echo $questionTitle; ?></a></p>
				<span>
					    <?php
					    $str= array();
					    if($questionObj->getViewCount()>0){
						    if($questionObj->getViewCount() != 1){
								    $caption = "Views";
						    }else{
							    $caption = "View";
						    }
						    $str[] = $questionObj->getViewCount()." ".$caption;
					    }
					    if($questionObj->getAnswerCount()>0){
						    if($questionObj->getAnswerCount() != 1){
								    $caption = "Answers";
						    }else{
							    $caption = "Answer";
						    }
						    $str[] = $questionObj->getAnswerCount()." ".$caption;
					    }
					    if($questionObj->getCommentCount()>0){
						    if( $questionObj->getCommentCount() != 1){
								    $caption = "Comments";
						    }else{
							    $caption = "Comment";
						    }
						    $str[] = $questionObj->getCommentCount()." ".$caption;
					    }
					    echo implode($str,', ');
					    ?>		
				</span>
			</li>
			<?php 
		}	?>

	    </ul>

        <div style="position:absolute;top:16px;left:663px;*left:663px" id ="description_tips">
            <div style="width:269px;overflow:hidden">
                <div class="tooltipAnAm">
                     <div class="tooltipAnAt" style="height:60px">
                         <div class="tar" style="padding:7px 14px 0 0" onclick="closeTipsTool('description')"><img src="/public/images/cBtn.gif" class="pointer"/></div>
                         <div style="padding:0 20px 0 60px">
                             <div class="mb8"><strong>Wait!</strong> We might already have the answer you are looking for:</div>
                         </div>
                     </div>
                     <img src="/public/images/tooltipAnAb.gif" />
                </div>
            </div>
        </div>

	</div>

<?php 
}
?>
<script> 
window.onload=function(){trackEventByGA('SEARCH_RESULTS','SHIKSHA_SIMILAR_QUESTION_SOURCE_SHIKSHA_SEARCH');};

</script>
