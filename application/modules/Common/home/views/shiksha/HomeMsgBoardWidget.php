	<?php
					$clientWidth =  (isset($_COOKIE['client']) && $_COOKIE['client'] != '') ? $_COOKIE['client'] : 1024;
					$characterLength = ($clientWidth < 1000) ?20 : 50;
					$characterLength = 250;
					if(is_array($msgBoards))
					foreach($msgBoards['results'] as $msgBoard) {
					$msgBoardTopic = isset($msgBoard['msgTxt']) ? $msgBoard['msgTxt'] : '';
					$msgBoardId = isset($msgBoard['msgId']) ? $msgBoard['msgId'] : '';
					$numComments = isset($msgBoard['msgCount']) ? $msgBoard['msgCount'] : '';
					$numUsers = isset($msgBoard['viewCount']) ? $msgBoard['viewCount'] : '';
					$status = isset($msgBoard['status']) ? $msgBoard['status'] : '';
					$userImage = isset($msgBoard['userImage']) && !empty($msgBoard['userImage']) ? $msgBoard['userImage'] : '/public/images/unkownUser_s.gif';
					/*$userImage = getSmallImage($userImage);*/ //Commented as image is not shown to the user
                    $askedBy = $msgBoard['displayname'];
					
					if($numComments == 1) {
						$comments = 'View 1 Answer';
					} else if($numComments == 0) {
						$comments = '';
					} else {
						$comments = 'View '. $numComments .' Answers';
					}
				    
					if($numUsers == 1) {
						$users = '1 View';
					} else if($numUsers == 0) {
						$users = '';
					} else {
						$users= $numUsers .' Views';
					}

					if(strlen($msgBoardTopic) > $characterLength){
	                   $displayMsgBoardTopic = substr($msgBoardTopic,0,$characterLength - 3).'...' ;
                    } else {
                    	$displayMsgBoardTopic = $msgBoardTopic;
                    }
					$detailUrl = $msgBoard['url'];
				?>
				<div class="row" style="padding:10px 0px;">
					<!--<div>
						<img src="<?php echo $userImage; ?>" align="left" style="border:1px solid #E2DDDC" />
					</div>-->
					<div class="normaltxt_11p_blk arial qmarked" style="/*margin-left:67px*/">
						<div style="margin-bottom:2px">
							<a title="<?php echo $msgBoardTopic; ?>" href="<?php echo $detailUrl; ?>" class="fontSize_12p">
								<?php echo insertWbr($displayMsgBoardTopic, 20); ?>
							</a>
						</div>
						<div style="margin-bottom:2px">
                        <span class="grayFont">Asked by</span> <a href="/getUserProfile/<?php echo $askedBy; ?>" title="Asked By <?php echo $askedBy; ?>"><?php echo $askedBy; ?></a><span class="grayFont"> , <?php echo $users; ?></span>
                        </div>
						<div>
							<?php if(strtolower($status) != 'closed') { ?>
								<a href="<?php echo $detailUrl; ?>/5#gRep" onclick="<?php echo $onClick; ?>"><b>Answer Now</b></a>
							<?php } ?>&nbsp; 
							<a href="<?php echo $detailUrl; ?>"><?php echo $comments; ?></a> &nbsp;
						</div>
					</div>
					<div class="clear_L"></div>
				</div>
				<?php	} 
					if(count($msgBoards['results']) > 2 && strpos($countryId,',') === false){	
				?>				
                    <div align="right"><a href="<?php echo SHIKSHA_ASK_HOME_URL.'/messageBoard/MsgBoard/discussionHome/'.$categoryId.'/2/'.$countryId; ?>" title="View All">View All</a></div>
				<?php 
					}
				?>
