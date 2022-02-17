            <?php
                global $isExamShown;
                $isExamShown = false;
            ?>
             <h3 style="padding-left:12px">Qualifying Examination:</h3>
	     <?php
	     function createExamBlock($examType,$profile_data,$showRank='false'){
		    $dateVal = $examType.'DateOfExamination';
		    $scoreVal = $examType.'Score';
		    $percentileVal = $examType.'Percentile';
		    $rankVal = $examType.'Rank';
		    $rollNumberVal = $examType.'RollNumber';
		    $checkForVar = ($showRank=='false')?$percentileVal:$rankVal;
		    if(
		       (isset($profile_data[$dateVal]) && $profile_data[$dateVal]!='') ||
		       (isset($profile_data[$rollNumberVal]) && $profile_data[$rollNumberVal]!='') || 
		       (isset($profile_data[$scoreVal]) && $profile_data[$scoreVal]!='') ||
		       (isset($profile_data[$checkForVar]) && $profile_data[$checkForVar]!='')
		       ){
                            global $isExamShown;
                            $isExamShown = true;
			    if($examType=='mhcet')
				    $examName = 'MH-CET';
			    else
				    $examName = strtoupper($examType);
			    echo '<div class="educationBlock">';
				       echo '<div class="educationTitle educationTitleBg">';
				       echo '<p class="educationCol widthAuto">'.$examName.' Score Details</p>';
				   echo '</div>';
				  
			       echo '<ul class="qualifyingDetails">';
				       echo '<li>';
				       echo '<div class="formColumns">';
					   echo '<label><strong>Date of examination:</strong></label>';
					   echo '<span>'.$profile_data[$dateVal].'</span>';
							       echo '</div>';
				       
				   echo '</li>';
				   
				   echo '<li>';
				       echo '<div class="formColumns">';
					   echo '<label><strong>Roll Number:</strong></label>';
					   echo '<span>'.$profile_data[$rollNumberVal].'</span>';
							       echo '</div>';
				     
				       echo '<div class="formColumns">';
					   echo '<label><strong>Paper 1 Marks:</strong></label>';
					   echo '<span>'.$profile_data[$scoreVal].'</span>';
							       echo '</div>';
				       
				   echo '</li>';
			       echo '</ul>';
			       echo '<div class="clearFix"></div>';
			    echo '</div>';
		    }
		
	     }
	     ?>

	    <?php createExamBlock('jee',$profile_data,'true'); ?>

            <?php
            if(!$isExamShown){
    	        echo '<div class="educationBlock"><div class="educationTitle educationTitleBg"><p class="educationCol widthAuto">No Qualifying Exam filled</p></div></div>';
            }?>