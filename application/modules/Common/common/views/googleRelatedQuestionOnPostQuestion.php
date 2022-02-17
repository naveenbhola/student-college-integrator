<?php 
if((count($googleRes['title'])>0))
{ 
    if(count($googleRes['title'])>=3)
        $height = '128px';
    else
        $height = ((count($googleRes['title']) * 45)) + 'px';
?>
	<div class="similar-ques-section" style="position:relative;">
	    <h4>We have found <span><?php echo count($googleRes['title']);?> similar <?php if(count($googleRes['title'])==1){echo "question";}else{echo "questions";}?>!</span> which may have the answer you looking for</h4>
	    <ul style="height:<?=$height?>">
		<?php for($count=0;$count<count($googleRes['title']);$count++)
		{	$questionTitle =  preg_replace('/(Ask Questions on various .*)/','',$googleRes['description'][$count]);?>
			<?php $lenOfQuestionTitle = strlen($questionTitle);
			$tmpLink = explode('/',$googleRes['link'][$count]);
			$linkId  = $tmpLink[4];
			?>
			<li>
				<p id="actualRelatedQuestionId<?php echo $linkId;?>" style="display:none;"><a onClick="trackEventByGA('SEARCH_RESULTS','SHIKSHA_CAFE_INTERMEDIATE_SOURCE_GOOGLE');" href="<?php echo $googleRes['link'][$count];?>#RelQues" target= "_blank" ><?php echo $questionTitle; ?></a></p>
				<p id="partialRelatedQuestionId<?php echo $linkId;?>"><a onClick="trackEventByGA('SEARCH_RESULTS','SHIKSHA_CAFE_INTERMEDIATE_SOURCE_GOOGLE');" href="<?php echo $googleRes['link'][$count];?>#RelQues" target= "_blank" ><?php if($lenOfQuestionTitle >140){ echo substr($questionTitle,0,140); ?><a href="javascript:void(0)" onClick="showFullRelatedQuestionAnA('<?php echo $linkId;?>');">...more</a><?php }else echo $questionTitle; ?></a></p>
				<span>
					    <?php
					    $str= array();
					    if($googleRes['viewCount'][$count]>0){
						    if($googleRes['viewCount'][$count] != 1){
								    $caption = "Views";
						    }else{
							    $caption = "View";
						    }
						    $str[] = $googleRes['viewCount'][$count]." ".$caption;
					    }
					    if($googleRes['answers'][$count]>0){
						    if($googleRes['answers'][$count] != 1){
								    $caption = "Answers";
						    }else{
							    $caption = "Answer";
						    }
						    $str[] = $googleRes['answers'][$count]." ".$caption;
					    }
					    if($googleRes['comments'][$count]>0){
						    if($googleRes['comments'][$count] != 1){
								    $caption = "Comments";
						    }else{
							    $caption = "Comment";
						    }
						    $str[] = $googleRes['comments'][$count]." ".$caption;
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
window.onload=function(){trackEventByGA('SEARCH_RESULTS','SHIKSHA_SIMILAR_QUESTION_SOURCE_GOOGLE_SEARCH');};
</script>

