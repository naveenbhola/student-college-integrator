<?php	//$displayThreadType = ($threadType == 'question')?"Question":($threadType == 'discussion')?"Discussion":"";
		if($threadType == 'question'){
			$displayThreadType = 'Question';
			$GA_currentPage		= 'QUESTION DETAIL PAGE';
			$GA_Tap_On_Submit	= 'SUBMIT_LINKING_QUEST_QUESTIONDETAIL_DESKAnA';
		}elseif ($threadType == 'discussion'){
			$displayThreadType = 'Discussion';
			$GA_currentPage		= 'DISCUSSION DETAIL PAGE';
			$GA_Tap_On_Submit	= 'SUBMIT_DISC_QUEST_DISCUSSIONDETAIL_DESKAnA';
		}
		if (!$loadOnlyTuples){?>
			<div id="linkThreadLayer" class="an-layer" >
				<div class="ana-wrap">
     				<div class="tags-head">
     					Link <?= $displayThreadType?>
     					<a class="cls-head" href="javascript:void(0);" onclick="hideLinkThreadOverlay();"></a>
     				</div>
        			<div class="post-col" style="margin-top:0px;display:block">
        				<div class="opacticy-col"><img border="0" src="//<?php echo IMGURL; ?>/public/mobile5/images/ShikshaMobileLoader.gif" alt="" class="small-loader"></div>
	            		<div class="post-qstn">
	                		<h2 class="post-h2">Your <?= $displayThreadType?></h2>
	                		<p class="qstn-title"><?=htmlspecialchars_decode($threadTitle) ?></p>
	            		</div>
            			<div class="more-qstns">
	            			<div class="srch-box">
	            				<div class="in-box">
	            					<input type="text" name="search" placeholder="Search for a <?php echo $threadType;?> you want to link.." class="link-text" />
	            				</div>
	            				<div class="link-col">
	            					<a href="javascript:void(0)" onclick="getRelatedThreadsBasedOnText(<?= $threadId?>,'<?= $threadType?>')" class="ana-btns a-btn">Search</a>
	            				</div>
	            				<p class="clr"></p>
	            			</div>
                            <div class="similar-qstns">
            					<div class="similar-col" id="linkedLayer">
            						<p class="txt-p">Check out similar <?= $displayThreadType.'s'?></p>
                                    <!-- Track HTML -->
<!--            						<div class="scrollbar">
            							<div class="track">
											<div class="thumb">
												<div class="end"></div>
											</div>
										</div>
									</div>
									 Track HTML End
									<div class="viewport" style="">
										<div class="overview" style="top: 0px;">-->
            								<ul>
<?php	}
		//if($loadOnlyTuples){
			foreach($response['content'] as $threadObject){
				$answerCommentCountDisplayText = "";
				if($threadType == 'question'){
					if($threadObject->getAnswersCount() > 1){
						$answerCommentCountDisplayText	= $threadObject->getAnswersCount()." Answers";
					}else{
						$answerCommentCountDisplayText	= $threadObject->getAnswersCount()." Answer";
					}
				}elseif($threadType == 'discussion'){
					if($threadObject->getCommentCount() > 1){
						$answerCommentCountDisplayText	= $threadObject->getCommentCount()." Comments";
					}else{
						$answerCommentCountDisplayText	= $threadObject->getCommentCount()." Comment";
					}
				}
				$viewCountText = "";
				if($threadObject->getViewCount() == 1){
					$viewCountText = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1 View";
				}elseif ($threadObject->getViewCount() >= 1){
					$viewCountText = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$threadObject->getViewCount()." Views";
				}
				$seoUrl	= getSeoUrl($threadObject->getThreadId(), $threadType, $threadObject->getTitle(), array(),'', date("Y-m-d h:i:s",strtotime($threadObject->getCreatedTime())));
?>
				            					<li>
				            						<a href="javascript:void(0)">
                                                        <input id="<?= $threadObject->getThreadId()?>" type="radio" name="<?= $threadId?>" value="<?= trim($threadObject->getThreadId()).'_'.trim($threadObject->getUserId())?>">
				            							<label onclick="enableSubmitLinkButton(<?= $threadObject->getThreadId()?>)" for="<?= $threadObject->getThreadId()?>"><i class="rd-bt"></i></label>
				            							<p onclick="window.open('<?= $seoUrl?>','_top')"><?= htmlspecialchars_decode($threadObject->getTitle())?>
				            							</p>
				            						</a>
                                                    <span class="mrgnLspn"><?= $answerCommentCountDisplayText.$viewCountText?></span>
				            					</li>
				            					<!-- <li>
				            						<a href="#">
				            							<input id="yes54" type="radio" name="yesNo5" value="yes54">
				            							<label for="yes54">I have just completed my 12th.i want to know  about btech courses,my stream is pcm.
				            								<span>3 Answers</span>
				            							</label>
				            						</a>
				            					</li>
				            					<li>
				            						<a href="#">
				            							<input id="yes545" type="radio" name="yesNo5" value="yes545">
				            							<label for="yes545">I have got 70% marks in 12th.what i should do for my carrer
				            								<span>2 Answers</span>
				            							</label>
				            						</a>
				            					</li> -->
<?php		}
		//}
        if(!is_array($response['content']) || count($response['content']) == 0){
?>
			<p>No Related Threads</p>
<?php		}
		if (!$loadOnlyTuples){?>
											</ul>
<!--										</div>
									</div>-->
	            				</div>
	            				<div class="btns-col">
									<span class="right-box">
										<a class="exit-btn" onclick="hideLinkThreadOverlay();" href="javascript:void(0)">Cancel</a>
                                        <a id="linkQuestionSubmitButton" class="d-btn1" onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','<?php echo $GA_Tap_On_Submit;?>','<?php echo $GA_userLevel;?>');submitLinkThread(this,<?= $threadId?>,'<?= $threadType?>');" href="javascript:void(0)">Link <?= $displayThreadType?></a>
									</span>
									<p class="clr"></p>
								</div>
							</div>
							<p class="clr"></p>
						</div>
					</div>
				</div>
			</div>
<?php	}?>