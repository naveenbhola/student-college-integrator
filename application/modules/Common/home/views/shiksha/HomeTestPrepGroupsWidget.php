				<?php
					foreach($networks as $network) {
						$collegeId = isset($network['blogId']) ? $network['blogId'] : '';
						$collegeName = isset($network['blogTitle']) ? $network['blogTitle'] : '';
						$numUsers = isset($network['memebers']) ? $network['members'] : '0';
						$numMessages= isset($network['messages']) ? $network['messages'] : '0';
							
						if($numUsers == 1) {
							$numUsers = '1 User';
						} else if($numUsers == 0){
							$numUsers = '';
						}else {
							$numUsers = $numUsers .' Users';
						}

						if($numMessages== 1) {
							$numMessages= '1 Message';
						} else if($numMessages== 0){
							$numMessages= '';
						}else {
							$numMessages= $numMessages.' Messages';
						}
						if(strlen($collegeName) > $characterLength){
		                	$displayCollegeName = substr($collegeName,0,$characterLength - 3).'...';
	                    } else {
	                    	$displayCollegeName = $collegeName;
	                    }
	                    	$displayCollegeName = $collegeName;
                            $stat = $numUsers;
                        if($numMessages != "" && $numUsers != "") {
                            $stat .= ", ";
                        }
                        $stat .= $numMessages;
						
                        $detailUrl= getSeoUrl($collegeId,"collegegroup",$collegeName) .'/1/TestPreparation';
				?>
				<div class="row" style="padding:10px 0px;" >
					<div class="normaltxt_11p_blk arial">
						<div style="margin-bottom:2px">
							<a title="<?php echo $collegeName; ?>" href="<?php echo $detailUrl; ?>" class="fontSize_12p">
								<?php echo $displayCollegeName; ?>
							</a>
						</div>
						<div>
							<?php echo $stat; ?>
						</div>
					</div>
					<div class="clear_L"></div>
				</div>
				<?php
					}	
					if(count($networks) > 2){	
				?>
				<div align="right"><a href="<?php echo SHIKSHA_GROUPS_HOME; ?>" title="View All">View All</a></div>
				<?php
					}
				?>

