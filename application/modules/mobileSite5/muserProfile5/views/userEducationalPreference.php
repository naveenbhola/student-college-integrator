<?php if(empty($desiredCourseDetails['courseName']) && empty($desiredCourseDetails['stream'])) { ?>
<div id="educationalPreferenceSection">

	<div class="profile-col" id="userEducationalPreference">
	    <div class="profile-col-heading">
	        <p class="flLt">EDUCATION PREFERENCES</p>
	<?php if(!$publicProfile){ ?>
	         <div class="flRt" id="educationalPreferenceEdit">
	            <!-- <a href="#pagetwo" data-transition="slideup" > -->
	            	<i class="profile-sprite profile-edit-icon"></i>
	            <!-- </a> -->
	        </div>
	<?php } ?>
	        <div class="clearFix"></div>
	    </div>   
	    <div class="profile-info-col">
	        <div class="profile-info-detail borderNone">
	        	<a href="#pagetwo" data-transition="slideup" class="ui-link">
	              <i class="profile-sprite plus-icon flRt " id="educationalPreferenceAdd"></i>
	           </a>
	        </div>
	    </div>
	</div>

</div>

<?php } else { ?>

<div id="educationalPreferenceSection">

	<div class="profile-col">
	    <div class="profile-col-heading">
	        <p class="flLt">EDUCATION PREFERENCES</p>
			<?php if(!$publicProfile){ ?> 
	         <div class="flRt" id="educationalPreferenceEdit">
	            <!-- <a href="#pagetwo" data-transition="slideup" id="educationalPreferenceEdit"> -->
	            	<i class="profile-sprite profile-edit-icon"></i>
	            <!-- </a> -->
	        </div>
			<?php }?>
	        <div class="clearFix"></div>
	    </div>           
	    <div class="profile-detail-list">
	        <ul>
	        	<li>
	        		<p class="flLt higlight-p"><i class="profile-sprite graduate-icn"></i><?php if($userPreference['ExtraFlag'] == 'studyabroad') { echo 'Study Abroad'; } else { echo 'India'; } ?></p>
	        		<?php 
	        		if(!$publicProfile){ 
	        		 	$privacyFields = array('0'=>'DesiredCourse'); 
                        $privacyFields = serialize($privacyFields);
						$publicFlag = false;                             
					
                    	if($privacyDetails['DesiredCourse'] == 'public'){
                        	$publicFlag = true;
                      	} 

                      	$this->load->view('userTogglePrivacy',array('privacyFields' =>$privacyFields,'publicFlag'=>$publicFlag));          
               		} ?>  
                         

	       	
	       			<?php if($userPreference['ExtraFlag'] == 'studyabroad') {  ?>

	       				<div class="spl-col">

		        	 		<?php if(!empty($desiredCourseDetails['courseName'])) { ?>  
				                <p class="flLt" style="width:80%;">Interested in studying <span class="bold-text">
				                	<?php 
				                	if($desiredCourseDetails['categoryName'] == 'All' || $desiredCourseDetails['categoryName'] == '') {
				                		echo $desiredCourseDetails['courseName'];
				                		if(!empty($desiredCourseDetails['subCatgoryName'])) { 
				                			echo ' ('.$desiredCourseDetails['subCatgoryName'].')';
				                		} else {
				                			echo ' (All Specializations)';
				                		}
				                	} else {
				                		echo $desiredCourseDetails['categoryName'];
				                		if(!empty($desiredCourseDetails['subCatgoryName'])) { 
				                			echo ' - '.$desiredCourseDetails['subCatgoryName']; 
				                		} else {
				                			echo ' - All Specializations';	
				                		}
				                		echo ' ('.$desiredCourseDetails['courseName'].")";
				                	}
				                	?> 
				                	<?php if(!empty($countryNames)) { ?>in <?php echo implode(", ",$countryNames); } ?></span></p>
				              <?php } else if(!empty($countryNames)) { ?>
				                <p class="flLt" style="width:80%;">Interested in studying from <span class="bold-text"><?php echo implode(", ",$countryNames); ?></span></p>
				              <?php } ?>
			            

		            			<p class="flLt" style="width:80%;">Looking for admission in <span class="bold-text"><?php echo $whenPlanToGo;?></span></p>

		              
				                <?php 
				                if(!empty($personalInfo['Passport'])) {
				                	if($personalInfo['Passport'] ==  'yes') { ?>
				                  		<p class="flLt" style="width:80%;">Has a <span class="bold-text">valid passport</span></p>
				                <?php } else { ?>
				                  		<p class="flLt" style="width:80%;">Don't have a valid passport</p>
				                <?php } 
				            	} ?>
		            

		            
				                <?php if((!empty($budget)) && (!empty($sourceOfFunding))) { ?>

				                        <p class="flLt" style="width:80%;">Has a <span class="bold-text">budget of <?php echo $budget;?> funded by <?php echo $sourceOfFunding;?></span></p>

				                <?php } else { 

				                        if(!empty($budget)) { ?>
				                          
				                          <p class="flLt" style="width:80%;">Has a <span class="bold-text">budget of <?php echo $budget;?></span></p>

				                      <?php } else if(!empty($sourceOfFunding)) { ?>

				                          <p class="flLt" style="width:80%;">Budget funded by <span class="bold-text"><?php echo $sourceOfFunding;?></span></p>

				                      <?php } ?>

				                <?php } ?>
		                
		                	<p class="clr"></p>

						</div>

							<?php if((!empty($additionalInfo['Extracurricular'])) || (!empty($additionalInfo['SpecialConsiderations'])) || (!empty($additionalInfo['Preferences']))) { ?>
							
								<div class="spl-col">

									<?php if(!empty($additionalInfo['Extracurricular'])) { ?>
									<p class="flLt" style="width:80%;">Extra Curricular Activities: <span class="bold-text"><?php echo $additionalInfo['Extracurricular'];?></span></p>
									<?php } ?>

									<?php if(!empty($additionalInfo['SpecialConsiderations'])) { ?>
									<p class="flLt" style="width:80%;">Special Consideration: <span class="bold-text"><?php echo $additionalInfo['SpecialConsiderations'];?></span></p>
									<?php } ?>

									<?php if(!empty($additionalInfo['Preferences'])) { ?>
									<p class="flLt" style="width:80%;">Preferences: <span class="bold-text"><?php echo $additionalInfo['Preferences'];?></span></p>
									<?php } ?>
			                
					                <p class="clr"></p>

								</div>

		             		 <?php } ?>


							<?php 
							if(!empty($competitiveExam['Name'][0])) {

				            	if($examTaken == 'yes') {
				            		?>

									<li><p class="flLt" style="width:80%;">Exam(s) Taken:<span class="bold-text">
					                    <?php for($i=0; $i<count($competitiveExam['Name']); $i++) { ?>
					                      <?php echo $competitiveExam['Name'][$i];?> (<?php if($competitiveExam['Name'][$i] == 'IELTS' || $competitiveExam['Name'][$i] == 'CAE'){ echo $competitiveExam['Marks'][$i];}else{ echo floor($competitiveExam['Marks'][$i]); }?>)<?php if($i != (count($competitiveExam['Name'])-1)) echo ', '; ?> 
					                    <?php } ?></span>
				                    </p></li>

				            <?php } 

				      		}  ?>
            
			        <?php } else { ?>

							<?php 
							
							if(!empty($desiredCourseDetails['stream'])) {  ?>

								<div class="spl-col">
									<br/>
								<!-- 
				                  <p class="flLt" style="width:80%;">Interested in studying <span class="bold-text"><?php echo $desiredCourseDetails['courseName'];?> (<?php echo $desiredCourseDetails['categoryName'];?>)</span></p>  
								-->
							 		<?php if(!empty($desiredCourseDetails)){ ?>
		                          	<p>Stream: <?php echo $desiredCourseDetails['stream']; ?></p>
		                        	<?php } ?>

		                        	<?php if(!empty($desiredCourseDetails['subStreamSpec'])){ ?>
			                        	<div>Specialization<?php if(count($desiredCourseDetails['subStreamSpec']) > 1){ echo 's'; } ?>: <div class="ml20 lh22">
			                        <?php 

			                          $unMappedSpecs = !empty($desiredCourseDetails['subStreamSpec']['ungrouped'])?$desiredCourseDetails['subStreamSpec']['ungrouped']:array();

			                          $subStreamSpec = array();
			                          $unMappedSubstreams = array();
			                          unset($desiredCourseDetails['subStreamSpec']['ungrouped']);

			                          foreach($desiredCourseDetails['subStreamSpec'] as $id=>$data){
			                            $temp = '';
			                            if(!empty($data['specializations'])){
			                              echo $data['name'].' - '.implode(', ', $data['specializations']).'<br>';
			                            }else{
			                              $unMappedSubstreams[] = $data['name'];
			                            }
			                          } 

			                          $unMappedSpecs = array_merge($unMappedSpecs, $unMappedSubstreams);                      
			                          
			                          if(!empty($unMappedSpecs)){    
			                          	asort($unMappedSpecs);                    
			                            echo 'Others - '.implode(', ', $unMappedSpecs);
			                          }
			                         ?>
			                        	</div>
			                        </div>
			                    	<?php } ?>

				                    <?php if(!empty($desiredCourseDetails['baseCourses'])){
				                    	asort($desiredCourseDetails['baseCourses']); ?>
				                      <p>Course<?php if(count($desiredCourseDetails['baseCourses']) > 1){ echo 's'; } ?>: <?php echo implode(', ', $desiredCourseDetails['baseCourses']); ?></p>
				                    <?php } ?>

				                    <?php if(!empty($desiredCourseDetails['educationMode'])){ 
                        				global $educationTypePriorities;

				                    	?>

				                      <div>Education Type<?php if(count($desiredCourseDetails['educationMode']) > 1){ echo 's'; } ?>: 
				                          <?php 
				                          $stringValue = '';
				                          foreach ($desiredCourseDetails['educationMode'] as $key => $value) {
				                            $tmpModeArr[$key] = $value['name'];
				                            if(!empty($value['children'])){
				                              foreach ($value['children'] as $id => $name) {
				                                $tmpModeArr[$id] = $name;
				                              }
				                            }
				                          }

				                    		
				                           $eduType = array_intersect(array_keys($educationTypePriorities), array_keys($tmpModeArr));

				                          foreach($eduType as $key=>$modeId){
				                              // asort($modeData['children']);
				                              $stringValue .= $educationTypePriorities[$modeId].', ';
				                          }
				                          $stringValue = rtrim($stringValue,", ");
				                          echo '<div class="ml20 lh22">'.$stringValue.'</div>';
				                          ?>
				                      </div>
				                    <?php } ?>


				                  	<p class="clr"></p>

								</div>

				        	<?php   }  ?>

				        	<!-- <li><p class="flLt" style="width:80%;">Exam(s) Taken:<span class="bold-text">
			                    <?php 
			                    $examKeys = array_keys($examNames);
			                    
			                    for($i=0; $i<count($competitiveExam['Name']); $i++) { ?>
			                    
			                      <?php
			                      if(in_array($competitiveExam['Name'][$i],$examKeys)) { 
			                        echo $examNames[$competitiveExam['Name'][$i]];
			                      } else {
			                        echo $competitiveExam['Name'][$i];
			                      }
			                      ?>
			                      <?php if($competitiveExam['Marks'][$i] && $competitiveExam['Marks'][$i] != 0) { ?> (<?php echo $competitiveExam['Marks'][$i];?>) <?php } ?><?php if($i != (count($competitiveExam['Name'])-1)) echo ', '; ?> 
			                    <?php } ?></span>
							</p></li> -->

					<?php } ?>						

				</li>


	      		<li class="borderNone">
	      		<?php if(!$publicProfile){ ?>	
	               <a href="#pagetwo" data-transition="slideup" class="ui-link">
		              <i class="profile-sprite plus-icon flRt " id="educationalPreferenceAdd"></i>
		           </a>
	            <?php } ?>    
	            </li>

	        </ul>
	    </div>
	</div>

</div>

<?php } ?>
