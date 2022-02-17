              
	        <tr>
                	<td class="compare-title last" colspan="5"><h2>Like any of these colleges?</h2></td>
                </tr>
	      
	        <tr>
                        <td width="165" valign="top"><div class="compare-items"><label>Vote now </label></div></td>
                    	<div class="compare-items cnfirm-vote">
				<?php
					$numberOfVotes = $votesInfoArray['numberOfVotes'];
					$votedCourses = $votesInfoArray['votedCourses'];
				        $j=0;$k=0;
           				foreach($institutes as $institute){
					$k++;
					echo '<td valign="top" align="center" ';if($k==4){echo "class='last'";} echo '>';
					$j++;
					$course = $institute->getFlagshipCourse();
					$course_id=$course->getId();
					$votes = $numberOfVotes[$course_id];

					$showSuccess = false;
					if(in_array($course_id,$votedCourses)){					    
					       $showSuccess = true;
					}
					
		    			echo '<a id="vote_'.$j.'" href="javascript:void(0);" class="vote-btn" votes="'.$votes.'" onClick="getvote('.$course_id.','.$j.')" >Vote</a>';
                        	        if($votes<1){
					        echo '<p id="vote_count'.$j.'" class="vote-count" style="display:none;">';
					}
					else if($votes==1) {
						echo '<p id="vote_count'.$j.'" class="vote-count">'.$votes.' person voted for this </p>';
					}
					else{
					        echo '<p id="vote_count'.$j.'" class="vote-count">'.$votes.' people voted for this </p>';
					}
					echo '<div class="compare-items cnfirm-vote">';
					echo '<i id="vote_confirm_image'.$j.'" class="compare-sprite confirm-vote-icon" style="display:none;"></i>';
                        	        
					echo '<p id="vote_confirm_text'.$j.'" style="display:none;"></p>';
										
					if($showSuccess){
						echo "<script>$('vote_'+$j).style.display = 'none';</script>";
						echo "<script>$('vote_count'+$j).style.display = 'none';</script>";
						echo "<script>$('vote_confirm_image'+$j).style.display = '';</script>";
						echo "<script>$('vote_confirm_text'+$j).style.display = '';</script>";
						echo "<script>$('vote_confirm_text'+$j).innerHTML = '$votes Students preferred this college.';</script>";												
					}
					echo '</div>';
					
			echo '</td>'; } ?>
                        	
                        </div>
		             
                </tr>
