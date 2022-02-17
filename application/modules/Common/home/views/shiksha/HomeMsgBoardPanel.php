<?php
	$messageBoardCaption = isset($messageBoardCaption) && $messageBoardCaption != '' ? $messageBoardCaption : 'Ask & Answer';
	$networkCaption = isset($networkCaption) && $networkCaption != '' ? $networkCaption : 'Groups';
	$messageBoardCaption = 'Ask & Answer';
	$messageBoardPosition = isset($messageBoardPosition) &&  $messageBoardPosition!= '' ?  $messageBoardPosition : 'right';
	$class = $messageBoardPosition == 'left' ? 'float_L' : 'float_R';
?>
<div>
	<div class="careerOptionPanelBrd">
			<div class="careerOptionPanelHeaderBg">
				 <h6><span class="blackFont fontSize_13p">Questions &amp; Answers</span></h6>
			</div>
			<div class="lineSpace_5">&nbsp;</div>
			<div align="right"> 	 
                 <div> 	 
                     <button class="btn-submit5" type="button" onClick="location.href='/messageBoard/MsgBoard/discussionHome';return false;" style="width:120px">
                         <div class="btn-submit5"><p class="btn-submit6">Ask Question</p></div> 	 
                     </button> 	 
                 </div> 	 
			</div>
			<div class="mar_full_10p" style="display:block;<?php echo isset($msgBoardPanelHeight) ? 'height:'. $msgBoardPanelHeight .'px;' : ''; ?>" id="communityMsgBoardBlock">
				<?php $this->load->view('home/shiksha/HomeMsgBoardWidget'); ?>
			</div>
			<div class="lineSpace_12">&nbsp;</div>
	</div>
</div>
