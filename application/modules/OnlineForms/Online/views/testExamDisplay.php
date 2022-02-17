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
				       
				       echo '<div class="formColumns">';
					   echo '<label><strong>Score:</strong></label>';
					   echo '<span>'.$profile_data[$scoreVal].'</span>';
							       echo '</div>';
				   echo '</li>';
				   
				   echo '<li>';
				       echo '<div class="formColumns">';
					   echo '<label><strong>Roll Number:</strong></label>';
					   echo '<span>'.$profile_data[$rollNumberVal].'</span>';
							       echo '</div>';
				       if($showRank=='false'){
				       echo '<div class="formColumns">';
					   echo '<label><strong>Percentile:</strong></label>';
					   echo '<span>'.$profile_data[$percentileVal].'</span>';
							       echo '</div>';
				       }
				       else{
				       echo '<div class="formColumns">';
					   echo '<label><strong>Rank:</strong></label>';
					   echo '<span>'.$profile_data[$rankVal].'</span>';
							       echo '</div>';
				       }
				   echo '</li>';
			       echo '</ul>';
			       echo '<div class="clearFix"></div>';
			    echo '</div>';
		    }
		
	     }
	     ?>
	    <?php createExamBlock('cat',$profile_data); ?>
	    <?php createExamBlock('mat',$profile_data); ?>
	    <?php createExamBlock('gmat',$profile_data); ?>
	    <?php	    
		    if(
		       (isset($profile_data['xatDateOfExamination']) && $profile_data['xatDateOfExamination']!='') ||
		       (isset($profile_data['xatScore']) && $profile_data['xatScore']!='') || 
		       (isset($profile_data['xatRollNumberG']) && $profile_data['xatRollNumberG']!='') ||
		       (isset($profile_data['xatPercentile']) && $profile_data['xatPercentile']!='')
		       ){
	    ?>
             <div class="educationBlock">
                	<div class="educationTitle educationTitleBg">
                    	<p class="educationCol widthAuto">XAT Score Details</p>
                    </div>
                   
                <ul class="qualifyingDetails">
                	<li>
                        <div class="formColumns">
                            <label><strong>Date of examination:</strong></label>
                            <span><?php echo $profile_data['xatDateOfExamination'];?></span>
						</div>
                        
                        <div class="formColumns">
                            <label><strong>Score:</strong></label>
                            <span><?php echo $profile_data['xatScore'];?></span>
						</div>
                    </li>
                    
                    <li>
                        <div class="formColumns">
                            <label><strong>Roll Number:</strong></label>
                            <span><?php echo $profile_data['xatRollNumberG'];?></span>
						</div>
                        
                        <div class="formColumns">
                            <label><strong>Percentile:</strong></label>
                            <span><?php echo $profile_data['xatPercentile'];?></span>
						</div>
                    </li>
                </ul>
                <div class="clearFix"></div>
             </div>
	    <?php } ?>
	    <?php createExamBlock('atma',$profile_data); ?>
	    <?php createExamBlock('cmat',$profile_data,'true'); ?>
	    <?php createExamBlock('iift',$profile_data); ?>
	    <?php createExamBlock('nmat',$profile_data); ?>
	    <?php createExamBlock('irma',$profile_data); ?>
	    <?php createExamBlock('snap',$profile_data); ?>
	    <?php createExamBlock('ibsat',$profile_data); ?>
	    <?php createExamBlock('tiss',$profile_data); ?>
	    <?php createExamBlock('gcet',$profile_data); ?>
	    <?php createExamBlock('tancet',$profile_data); ?>
	    <?php createExamBlock('icet',$profile_data); ?>
	    <?php createExamBlock('mhcet',$profile_data,'true'); ?>
	    <?php createExamBlock('kmat',$profile_data); ?>
	    <?php createExamBlock('upsee',$profile_data); ?>
            <?php
    
	    if($courseId == '9190' && $sample=='1'){
		  echo '<div class="educationBlock"><div class="educationTitleBg" style="background:#F4F4F4; width:100%; height:30px;><p class=""></p></div></div>';
	    }
            else if(!$isExamShown){
    	        echo '<div class="educationBlock"><div class="educationTitle educationTitleBg"><p class="educationCol widthAuto">No Qualifying Exam filled.</p></div></div>';
	    }?>
