<?php 
	$userId = isset($validateuser[0]['userid'])?$validateuser[0]['userid']:0;
	$userGroup = isset($validateuser[0]['usergroup'])?$validateuser[0]['usergroup']:'normal';
	foreach($userQuestion as $temp){
		if(is_array($temp) && isset($temp['url'])){
		$threadId = $temp['threadId'];
		$viewText = ($temp['viewCount'] <= 1)?$temp['viewCount'].' view':$temp['viewCount'].' views';
		$answerCount = isset($temp['answerCount'])?$temp['answerCount']:0;
		$idOfanswerCountHolder = 'answerCountHolderForQuestion'.$threadId;
		$noOfAnswerText = ($answerCount <= 1)?'<span id="'.$idOfanswerCountHolder.'">'.$answerCount.'</span> answer':'<span id="'.$idOfanswerCountHolder.'">'.$answerCount.'</span> answers';
		
?>
<div class="lineSpace_10">&nbsp;</div>
<div class="raised_lgraynoBG">
	<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
		<div class="boxcontent_lgraynoBG">
			<div style="padding:0 10px">
				<div style="padding-top:5px">
					<a href="<?php echo $temp['url']; ?>" class="fontSize_12p"><?php echo insertWbr($temp['msgTxt'],30); ?></a>
				</div>
				<div class="lineSpace_10">&nbsp;</div>
				<div>
					<span class="grayFont"> <?php echo $temp['creationDate']; ?>&nbsp;,<?php echo $viewText; ?>&nbsp;,<?php echo $noOfAnswerText; ?></span>
				</div>
				<div class="lineSpace_10">&nbsp;</div>
				<div style="width:90%;">
					<?php
						$questionUrl = $temp['url']."/askHome#gRep";
						if(($temp['status'] == 'live') || ($temp['userAnswerFlag'] > 0)){
							if($temp['userAnswerFlag'] <= 0){
								$dataArray = array('userGroup' =>$userGroup,'threadId' =>$threadId,'ansCount' => $answerCount,'detailPageUrl' =>$questionUrl,'callBackFunction' => 'try{ addMainComment('.$threadId.',request.responseText,\'-1\',true); } catch (e) {}');
								$this->load->view('messageBoard/InlineForm',$dataArray);
							}else{ ?>
								<div class="yelloBackForAlreadyAnswer"><img src="/public/images/greenChk.gif" align="absmiddle" />&nbsp;<a href="<?php echo $questionUrl; ?>"  style="color:#000000;">You have already answered to this question</a></div>
						<?php }
						}else{
					?>
					<div class="fontSize_12p"><strong>This question has been closed for answering.</strong></div>
					<?php } ?>					
				</div>
			</div>
			<div id="confirmMsg<?php  echo $temp['answerId']; ?>" class="errorMsg mar_left_10p"></div>
			<div class="clear_L withClear" style="line-height:5px">&nbsp;</div>
		</div> 
	<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
</div>
<?php  } } ?>
