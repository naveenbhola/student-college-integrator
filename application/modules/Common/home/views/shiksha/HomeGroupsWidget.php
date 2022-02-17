				<?php
					foreach($networks['results'] as $network) {
						$collegeId = isset($network['college']) ? $network['college'] : '';
						$collegeName = isset($network['name']) ? $network['name'] : '';
						$numUsers = isset($network['noofusers']) ? $network['noofusers'] : '0';
						$collegeImage = isset($network['imageurl']) && !empty($network['imageurl']) ? $network['imageurl'] : '/public/images/noPhoto_s.gif';
						
						$collegeImage = getSmallImage($collegeImage);
						if($numUsers == 1) {
							$numUsers = '1 User';
						} else if($numUsers == 0){
							$numUsers = '';
						}else {
							$numUsers = $numUsers .' Users';
						}
						if(strlen($collegeName) > $characterLength){
		                	$displayCollegeName = substr($collegeName,0,$characterLength - 3).'...';
	                    } else {
	                    	$displayCollegeName = $collegeName;
	                    }
	                    	$displayCollegeName = $collegeName;
						
						$detailUrl = $network['url'];
				?>
				<div class="row" style="padding:10px 0px;" >
					<div>
						<img src="<?php echo $collegeImage; ?>" align="left" style="border:1px solid #E2DDDC" alt="<?php echo $displayCollegeName; ?>"  title="<?php echo $displayCollegeName; ?>" />
					</div>
					<div class="normaltxt_11p_blk arial" style="margin-left:67px">
						<div style="margin-bottom:2px">
							<a title="<?php echo $collegeName; ?>" href="<?php echo $detailUrl; ?>" class="fontSize_12p">
								<?php echo $displayCollegeName; ?>
							</a>
						</div>
						<div>
							<?php echo $numUsers; ?>
						</div>
					</div>
					<div class="clear_L"></div>
				</div>
				<?php
					}	
					if(count($networks['results']) < $networks['totalCount']){	
				?>
				<div align="right"><a href="<?php echo SHIKSHA_GROUPS_HOME; ?>" title="View All">View All</a></div>
				<?php
					}
				?>

