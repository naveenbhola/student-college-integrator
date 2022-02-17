<?php
  $index = 0;

   $levelMapping = array(
   		'phd' => 'PHD',
   		'masters' => 'PG',
   		'bachelors' => 'UG'
   	);
   $marksSelect = array(''=>'Select Marks','50'=>'< than 50%','60'=>'50% to 60%','70' => '60% to 70%','80'=>'70% to 80%','90'=>'80% to 90%','100' => '90% or above'); 
   
   foreach($levelMapping as $level=>$fieldVariable){ 
   		if(empty(${$fieldVariable})){
   			continue;
   		}

   	?>
   		<section class="workexp-cont clearfix <?php if($isAddMore == 'YES'){ echo 'initial_hide';} ?>" >
	      <article class="workexp-box">
	          <div class="dtls">
	              <a href="#" class="cross-sec clearfix">
	                  <i class="up-cross">&times;</i>
	              </a>
	              <ul class="wrkexp-ul">
	              	<li>
	              		<div class="text-show filled1">
			                <label class="form-label">Course level</label>
			                <input type="text" id="level_<?php echo $index; ?>_input" class="user-txt-flds ssLayer" value="<?php echo $fieldVariable; ?>" readonly="readonly"/>
			                <a class="ui-btn ui-btn-inline ui-corner-all select-hide" data-rel="popup" href="#myPopup" aria-haspopup="true" aria-owns="myPopup" aria-expanded="false"></a>
			            	<em class="pointerDown"></em>
			            </div>
			            <div>
			                <div class="regErrorMsg" id="level_<?php echo $index; ?>_input_error"></div>
			            </div>
			            <div class="select-Class">
			                <select id="level_<?php echo $index; ?>" class="select-hide existingLevel <?php if($isAddMore != 'YES'){ echo 'levelChange';} ?>" name="EducationBackground[]">
			                    <option value="">Change Level</option>
			                    <option value="10"> X </option>
			                    <option value="12" selected> XII </option>
			                    <option value="UG" <?php if($fieldVariable == 'UG'){ echo 'selected';} ?> > UG </option>
			                    <option value="PG" <?php if($fieldVariable == 'PG'){ echo 'selected';} ?> > PG </option>
			                    <option value="PHD" <?php if($fieldVariable == 'PHD'){ echo 'selected';} ?> > PHD </option>
			                </select>
			            </div>
	              	</li>

	              	<li>
	              		<?php $UniName = isset(${$fieldVariable}['Board'])? ${$fieldVariable}['Board']:''; ?>
	              		<div class="text-show <?php if(!empty($UniName)){ echo 'filled1'; } ?>">
						    <label class="form-label">University Name</label>
						    <input type="text" name="<?php echo $level;?>Univ" value="<?php echo $UniName; ?>" maxlength="150" class="user-txt-flds"/>
						</div>
	              	</li>

	              	<li>
	              		<?php $CollegeName = isset(${$fieldVariable}['InstituteName'])? ${$fieldVariable}['InstituteName']:''; ?>
	              		<div class="text-show <?php if(!empty($CollegeName)){ echo 'filled1'; } ?>">
						    <label class="form-label">College Name</label>
						    <input type="text" id="<?php echo $level;?>College_<?php echo $regFormId; ?>" name="<?php echo $level;?>College" value="<?php echo $CollegeName; ?>" maxlength="150" mandatory="1" profanity="1" label="College Name" default="College Name" caption="your College Name" regfieldid="<?php echo $level;?>College" class="user-txt-flds"/>
						</div>
						<div><div id="<?php echo $level;?>College_error_<?php echo $regFormId; ?>" class="regErrorMsg"> </div></div>
	              	</li>

	              	<li>
	              		<?php $DiplomaName = isset(${$fieldVariable}['Name'])? ${$fieldVariable}['Name']:''; ?>
	              		<div class="text-show <?php if(!empty($DiplomaName)){ echo 'filled1'; } ?>">
						    <label class="form-label">Degree/Diploma Name</label>
						    <input type="text" id="<?php echo $level;?>Degree_<?php echo $regFormId; ?>" name="<?php echo $level;?>Degree" value="<?php echo $DiplomaName; ?>" maxlength="150" mandatory="1" profanity="1" label="Degree/Diploma Name" default="Degree/Diploma Name" caption="your Degree/Diploma Name" regfieldid="<?php echo $level;?>Degree" class="user-txt-flds"/>
						</div>
						<div><div id="<?php echo $level;?>Degree_error_<?php echo $regFormId; ?>" class="regErrorMsg"> </div></div>

	              	</li>

	              	<li>
	              		<?php $Stream = isset(${$fieldVariable}['Subjects'])? ${$fieldVariable}['Subjects']:''; ?>
	              		<div class="text-show <?php if(!empty($Stream)){ echo 'filled1'; } ?>">
							    <label class="form-label">Specialization</label>
							    <input type="text" maxlength="150" name="<?php echo $level;?>Stream" value="<?php echo $Stream; ?>" class="user-txt-flds"/>
						</div>
	              	</li>

	              	<li>
	              		<?php 
	              			//if($level != 'phd'){
		              			$year = (is_object(${$fieldVariable}['CourseCompletionDate']) && ${$fieldVariable}['CourseCompletionDate']->format('Y') != '-0001')? ${$fieldVariable}['CourseCompletionDate']->format('Y'):''; 
							    $fieldName = ($level == 'bachelors')? 'graduationCompletionYear': $level.'CompletionYear';
						?>
		              		<div class="text-show <?php if(!empty($year)){ echo 'filled1'; } ?>">
							    <label class="form-label">Course Completion Year</label>
							    <input type="number" maxlength="4" name="<?php echo $fieldName;?>" label="Completion Year" mandatory="0" default="completion year" caption="completion year" regfieldid="<?php echo $fieldName;?>" id="<?php echo $fieldName;?>_<?php echo $regFormId; ?>" value="<?php echo $year; ?>" class="user-txt-flds"/>
							</div>
							<div><div id="<?php echo $fieldName;?>_error_<?php echo $regFormId; ?>" class="regErrorMsg"> </div></div>

						<?php //} ?>
	              	</li>

	              	<li>
	              		<?php if($level != 'phd'){ ?>
		              		<?php $userMarks = isset(${$fieldVariable}['Marks'])? ${$fieldVariable}['Marks']:'';?>
		              		<div class="text-show <?php if(!empty($userMarks)){ echo 'filled1'; } ?>">
				                <label class="form-label">Marks</label>
				                <input class="user-txt-flds ssLayer" id="<?php echo $level;?>Marks_input" readonly="readonly" type="text" value="<?php if(!empty($userMarks)){echo $marksSelect[$userMarks];} ?>">
				                <a class="ui-btn ui-btn-inline ui-corner-all select-hide" data-rel="popup" href="#myPopup" aria-haspopup="true" aria-owns="myPopup" aria-expanded="false"></a>
				              	<em class="pointerDown"></em>  
				              </div>
				              <div class="select-Class">
				                  <select id="<?php echo $level;?>Marks" class="select-hide" name="<?php echo $level;?>Marks">
				                      <?php foreach($marksSelect as $value=>$displayText){ ?>
					                        <option value="<?php echo $value; ?>" <?php if($userMarks == $value){ echo 'selected'; }?>><?php echo $displayText; ?> </option>
				                      <?php } ?>
				                  </select>
				              </div>
			              <?php } ?>
	              	</li>
	           	  </ul> 
	      	 </div>
	      </article> 
	    </section>



   	<?php $index++;  } 
   	?>

	<?php if(isset($xiith) && count($xiith) > 0){ 

	    $xiithMarks = array(''=>'Select Marks','50'=>'< than 50%','60'=>'50% to 60%','70' => '60% to 70%','80'=>'70% to 80%','90'=>'80% to 90%','100' => '90% or above'); 
	    $board = array('CBSE'=>'CBSE','ICSE'=>'ICSE/State Boards','IGCSE' => 'Cambridge IGCSE');
    ?>
    <section class="workexp-cont clearfix <?php if($isAddMore == 'YES'){ echo 'initial_hide';} ?>">
      <article class="workexp-box">
          <div class="dtls">
              <a href="#" class="cross-sec clearfix">
                  <i class="up-cross">&times;</i>
              </a>
              <ul class="wrkexp-ul">
		           <li>
		            <div class="text-show filled1">
		                <label class="form-label">Course level</label>
		                <input type="text" id="level_<?php echo $index; ?>_input" class="user-txt-flds ssLayer" value="<?php echo 'XII'; ?>" readonly="readonly"/>
		                <a class="ui-btn ui-btn-inline ui-corner-all select-hide" data-rel="popup" href="#myPopup" aria-haspopup="true" aria-owns="myPopup" aria-expanded="false"></a>
		            	<em class="pointerDown"></em>
		            </div>
		            <div>
		                <div class="regErrorMsg" id="level_<?php echo $index; ?>_input_error"></div>
		            </div>
		            <div class="select-Class">
		                <select id="level_<?php echo $index; ?>" class="select-hide existingLevel <?php if($isAddMore != 'YES'){ echo 'levelChange';} ?>" name="EducationBackground[]">
					        <option value="">Change Level</option>
		                    <option value="10"> X </option>
		                    <option value="12" selected> XII </option>
		                    <option value="UG"> UG </option>
		                    <option value="PG"> PG </option>
		                    <option value="PHD"> PHD </option>
		                </select>
		            </div>
		          </li>

		          <li>
		            <div class="text-show <?php if(!empty($xiith['InstituteName'])){ echo 'filled1'; } ?>">
		                <label class="form-label">School/College Name</label>
		                <input type="text"  maxlength="150" mandatory="1" profanity="1" label="School Name" default="School Name" caption="your School Name" regfieldid="xthSchool" id="xiithSchool_<?php echo $regFormId; ?>" name="xiithSchool" value="<?php echo $xiith['InstituteName']; ?>" class="user-txt-flds"/>
		            </div>
		            <div><div id="xiithSchool_error_<?php echo $regFormId; ?>" class="regErrorMsg"></div></div>
		          </li>

		          <li>
		          	<?php $xiithCompletionYear = (is_object($xiith['CourseCompletionDate']) && $xiith['CourseCompletionDate']->format('Y') != '-0001')? $xiith['CourseCompletionDate']->format('Y'): ''; ?>
		            <div class="text-show <?php if(!empty($xiithCompletionYear)){ echo 'filled1'; } ?>">
		                <label class="form-label">Course Completion Year</label>
		                <input type="number" class="user-txt-flds" id="xiiYear_<?php echo $regFormId; ?>" label="Completion Year" mandatory="0" default="completion year" caption="completion year" regfieldid="xiiYear" maxlength="4" name="xiiYear" value="<?php echo $xiithCompletionYear; ?>">
		            </div>
		            <div><div id="xiiYear_error_<?php echo $regFormId; ?>" class="regErrorMsg"> </div></div>
		          </li>

		          <li>
		            <div class="text-show <?php if(!empty($xiith['Specialization'])){ echo 'filled1'; } ?>">
		                <label class="form-label">Stream</label>
		                <input type="text" name="" id="xiiSpecialization_input" class="user-txt-flds ssLayer" readonly="readonly" value="<?php echo $xiith['Specialization']; ?>" />
		                <a aria-expanded="false" aria-owns="myPopup" aria-haspopup="true" href="#myPopup" data-rel="popup" class="ui-btn ui-btn-inline ui-corner-all select-hide"></a>
		            	<em class="pointerDown"></em>
		            </div>
		            <div class="select-Class">
		                <select class="select-hide" name="xiiSpecialization" id="xiiSpecialization">
		                    <option value=""> Select Stream </option>
		                    <option value="Science" <?php if($xiith['Specialization'] == 'Science'){ echo 'selected'; } ?>> Science </option>
		                    <option value="Commerce" <?php if($xiith['Specialization'] == 'Commerce'){ echo 'selected'; } ?>> Commerce </option>
		                    <option value="Arts" <?php if($xiith['Specialization'] == 'Arts'){ echo 'selected'; } ?>> Arts </option>
		                </select>
		            </div>
		          </li>
		          <li>
		      		<div class="text-show <?php if(!empty($xiith['Board'])){ echo 'filled1'; } ?>">
						    <label class="form-label">Board</label>
						    <input type="text" id="xiiBoard_input" class="user-txt-flds ssLayer" value="<?php echo $board[$xiith['Board']]; ?>" readonly="readonly"/>
						    <a class="ui-btn ui-btn-inline ui-corner-all select-hide" data-rel="popup" href="#myPopup" aria-haspopup="true" aria-owns="myPopup" aria-expanded="false"></a>
						</div>
						<div class="select-Class">
						    <select class="select-hide changeBoard" name="xiiBoard" id="xiiBoard">
						    	<option value=""> Board </option>
						    	<?php foreach($board as $value=>$displayText){ ?>
						    		<option value="<?php echo $value;?>" <?php if($xiith['Board'] == $value){ echo 'selected'; } ?>><?php echo $displayText; ?> </option>
						    	<?php } ?>
						    </select>
						</div>
		          </li>
		          <li>
		            <div class="text-show <?php if(!empty($xiith['Marks'])){ echo 'filled1'; } ?>">
		                <label class="form-label">Marks</label>
		                <input class="user-txt-flds ssLayer" id="xiiMarks_input" readonly="readonly" type="text" value="<?php  if(!empty($xiith['Marks'])){echo $xiithMarks[$xiith['Marks']];} ?>">
		                <a class="ui-btn ui-btn-inline ui-corner-all select-hide" data-rel="popup" href="#myPopup" aria-haspopup="true" aria-owns="myPopup" aria-expanded="false"></a>
		              	<em class="pointerDown"></em>
		              </div>
		              <div class="select-Class">
		                  <select id="xiiMarks" class="select-hide" name="xiiMarks">
		                      <?php foreach($xiithMarks as $value=>$displayText){ ?>
			                        <option value="<?php echo $value; ?>" <?php if($xiith['Marks'] == $value){ echo 'selected'; }?>><?php echo $displayText; ?> </option>
		                      <?php } ?>
		                  </select>
		              </div>
		          </li>
          </ul> 
      </div>
    </article> 
  </section>
  <?php 
    $index++; 
  } ?>


<?php
	
	$fieldsMapping = array(
			'xth'=> array('schoolName' => array('fieldName'=>'', 'fieldVariable'=> '')),
		);

	
	if(isset($xth) && count($xth) > 0){ ?>
	<?php 
			$tenthMarks = array();
			switch($xth['Board']){
				case 'CBSE':
					$tenthMarks = array(''=>'Select Marks','4 - 4.9'=>'4 - 4.9', '5 - 5.9'=>'5 - 5.9', '6 - 6.9'=>'6 - 6.9', '7 - 7.9'=>'7 - 7.9', '8 - 8.9'=>'8 - 8.9', '9 - 10.0'=>'9 - 10.0');
				break;

				case 'ICSE':
				case 'NIOS':
					$tenthMarks = array(''=>'Select Marks','50'=>'< than 50%','60'=>'50% to 60%','70' => '60% to 70%','80'=>'70% to 80%','90'=>'80% to 90%','100' => '90% or above');
				break;

				case 'IGCSE':
					$tenthMarks = array(''=>'Select Marks','A*'=>'A*','A'=>'A','B' => 'B','C'=>'C','D'=>'D','E' => 'E','F'=>'F','G'=>'G');
				break;

				case 'IBMYP':
					$tenthMarks = array(''=>'Select Marks','28'=>'28','29'=>'29','30'=>'30','31'=>'31','32'=>'32','33'=>'33','34'=>'34','35'=>'35','36'=>'36','37'=>'37','38'=>'38','39'=>'39','40'=>'40','41'=>'41','42'=>'42','43'=>'43','44'=>'44','45'=>'45','46'=>'46','47'=>'47','48'=>'48','49'=>'49','50'=>'50','51'=>'51','52'=>'52','53'=>'53','54'=>'54','55'=>'55','56'=>'56');
				break;
			}

			$board = array('CBSE'=>'CBSE','ICSE'=>'ICSE/State Boards','IGCSE' => 'Cambridge IGCSE','IBMYP'=>'International Baccalaureate','NIOS'=>'NIOS');
	?>
		<section class="workexp-cont clearfix <?php if($isAddMore == 'YES'){ echo 'initial_hide';} ?>">
	    <article class="workexp-box">
	        <div class="dtls">
	            <a href="#" class="cross-sec clearfix">
	                <i class="up-cross">&times;</i>
	            </a>
	            <ul class="wrkexp-ul">
	            	<li>
						<div class="text-show filled1">
						    <label class="form-label">Course level</label>
						    <input type="text" id="level_<?php echo $index; ?>_input" class="user-txt-flds ssLayer" value="<?php echo 'X'; ?>" readonly="readonly"/>
						    <a class="ui-btn ui-btn-inline ui-corner-all select-hide" data-rel="popup" href="#myPopup" aria-haspopup="true" aria-owns="myPopup" aria-expanded="false"></a>
							<em class="pointerDown"></em>
						</div>
						<div>
                            <div class="regErrorMsg" id="level_<?php echo $index; ?>_input_error"></div>
                        </div>
						<div class="select-Class">
						    <select id="level_<?php echo $index; ?>" class="select-hide existingLevel <?php if($isAddMore != 'YES'){ echo 'levelChange';} ?>" name="EducationBackground[]">
						        <option value="">Change Level</option>
						        <option value="10" selected > X </option>
						        <option value="12"> XII </option>
						        <option value="UG"> UG </option>
						        <option value="PG"> PG </option>
						        <option value="PHD"> PHD </option>
						    </select>
						</div>
					</li>

					<li>
						<div class="text-show <?php if(!empty($xth['InstituteName'])){ echo 'filled1'; } ?>">
						    <label class="form-label">School/College Name</label>
						    <input type="text"  maxlength="150" mandatory="1" profanity="1" label="School Name" default="School Name" caption="your School Name" regfieldid="xthSchool" id="xthSchool_<?php echo $regFormId; ?>" name="xthSchool" value="<?php echo $xth['InstituteName']; ?>" class="user-txt-flds"/>
						</div>
			            <div><div id="xthSchool_error_<?php echo $regFormId; ?>" class="regErrorMsg"></div></div>

					</li>

					<li>
						
					   <?php $xthCompletionYear = (is_object($xth['CourseCompletionDate']) && $xth['CourseCompletionDate']->format('Y') != '-0001')? $xth['CourseCompletionDate']->format('Y'): ''; ?>
						<div class="text-show <?php if(!empty($xthCompletionYear) && $xthCompletionYear != '0000'){ echo 'filled1'; } ?>">
						    <label class="form-label">Course Completion Year</label>
						    <input type="number" class="user-txt-flds" label="Completion Year" default="completion year" caption="completion year" regfieldid="xthCompletionYear" mandatory="0" id="xthCompletionYear_<?php echo $regFormId; ?>" maxlength="4" name="xthCompletionYear" value="<?php echo $xthCompletionYear; ?>">
					    </div>
						<div><div id="xthCompletionYear_error_<?php echo $regFormId; ?>" class="regErrorMsg"> </div></div>
					</li>

					<li>
						<div class="text-show <?php if(!empty($xth['Board'])){ echo 'filled1'; } ?>">
						    <label class="form-label">Board</label>
						    <input type="text" id="tenthBoard_input" class="user-txt-flds ssLayer" value="<?php echo $board[$xth['Board']]; ?>" readonly="readonly"/>
						    <a class="ui-btn ui-btn-inline ui-corner-all select-hide" data-rel="popup" href="#myPopup" aria-haspopup="true" aria-owns="myPopup" aria-expanded="false"></a>
							<em class="pointerDown"></em>
						</div>
						<div class="select-Class">
						    <select class="select-hide changeBoard" name="tenthBoard" id="tenthBoard">
						    	<option value=""> Board </option>
						    	<?php foreach($board as $value=>$displayText){ ?>
						    		<option value="<?php echo $value;?>" <?php if($xth['Board'] == $value){ echo 'selected'; } ?>><?php echo $displayText; ?> </option>
						    	<?php } ?>
						    </select>
						</div>
					</li>

					<li>
						<div class="text-show <?php if(!empty($xth['Marks'])){ echo 'filled1'; } ?>">
						    <label class="form-label">Marks</label>
						    <input class="user-txt-flds ssLayer" id="tenthmarks_input" readonly="readonly" type="text" value="<?php if(!empty($xth['Marks'])){echo $tenthMarks[$xth['Marks']];} ?>">
					        <a class="ui-btn ui-btn-inline ui-corner-all select-hide" data-rel="popup" href="#myPopup" aria-haspopup="true" aria-owns="myPopup" aria-expanded="false"></a>
					    	<em class="pointerDown"></em>
					    </div>
					    <div class="select-Class">
					        <select id="tenthmarks" class="select-hide" name="tenthmarks">
					            <?php foreach($tenthMarks as $value=>$displayText){ ?>
					            	<option value="<?php echo $value; ?>" <?php if($xth['Marks'] == $value){ echo 'selected'; }?>><?php echo $displayText; ?> </option>
					            <?php } ?>
					        </select>
					    </div>
					</li>
				</ul> 
			</div>
		</article> 
	</section>

	<?php 
		$index++; 
	} 

   	$MandatoryFields =  array(); ?>

   	<script type="text/javascript">
   		var edubackgroundBlocks = '<?php echo $index; ?>';
	    var shikshaUserProfileForm = {};

    	shikshaUserProfileForm['<?php echo $regFormId; ?>'] = new ShikshaUserProfileForm('<?php echo $regFormId; ?>');
	</script>




