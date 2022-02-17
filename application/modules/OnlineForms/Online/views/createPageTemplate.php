<?php
  //echo "<pre>"; var_dump($pageData['pageTemplate']); exit;
  if(isset($pageData['pageTemplate'])){
	echo "<div class='formChildWrapper'>";
	echo "\r\n\t";
	echo "<div class='formSection'>";
	echo "\r\n\t\t";
    	echo "<ul>";
	echo "\r\n\t";
	addGDPILocation();
	//Now, for each field, create the form
	foreach ($pageData['pageTemplate'] as $template){
	    //var_dump($template);
	    if($template['entitySetType']=='field'){
		    $formField = $template['details'][0];
		    createFieldBasedOnType($formField);
	    }
	    else if($template['entitySetType']=='group'){
		    echo "<div id='".$template['details'][0]['groupId']."'>";
		    foreach ($template['details'] as $fieldSet){
			    $formField = $fieldSet;
			    createFieldBasedOnType($formField);
		    }
		    echo "</div>";
		    if($template['details'][0]['allowMultiple'] != '0' && $template['details'][0]['maxMultiplesAllowed'] > 0){
			    for($i=1; $i<=$template['details'][0]['maxMultiplesAllowed'];$i++){
				echo "<div id='".$template['details'][0]['groupId'].$i."' style='display:none'>";
				
				foreach ($template['details'] as $fieldSet){
					$formField = $fieldSet;
					createFieldBasedOnType($formField,true,$i);
				}
				echo "</div>";
				
				//Unhide the Group in case its values are filled
				$fieldName = $formField['name']."_mul_".$i;
				echo '<?php if(isset($'.$fieldName.') && $'.$fieldName.'!=""){ ?>
				  <script>
				      document.getElementById("'.$fieldName.'").parentNode.parentNode.style.display = "";
				  </script>
				<?php } ?>';
			    }
			    echo "<a id='showMore".$template['details'][0]['groupId']."' href='javascript:void(0);' onClick='showMoreGroups(".$template['details'][0]['groupId'].",".$template['details'][0]['maxMultiplesAllowed'].");'>Add More</a>";
		    }
	    }
	}
	echo "\r\n\t\t";
    	echo "</ul>";
	echo "\r\n\t";
	echo "</div>";
	echo "\r\n";
	echo "</div>";
  }

  function addGDPILocation(){
	echo "
			<?php if(is_array(\$gdpiLocations) && count(\$gdpiLocations)): ?>
			<li>
				<h3 class=upperCase'>GD/PI Location</h3>
				<label style='font-weight:normal'>Preferred GD/PI location: </label>
				<div class='fieldBoxLarge'>
				<select name='preferredGDPILocation' id='preferredGDPILocation' onmouseover=\"showTipOnline('Select your preferred GD/PI location.',this);\" onmouseout='hidetip();'  validate=\"validateSelect\"  minlength=\"1\"   maxlength=\"1500\"  required=\"true\" caption=\"Preferred GD/PI location\">
				<option value=''>Select</option>
				<?php foreach(\$gdpiLocations as \$gdpiLocation): ?>
						<option value=\"<?php echo \$gdpiLocation['city_id']; ?>\"><?php echo \$gdpiLocation['city_name']; ?></option>
				<?php endforeach; ?>
				</select>
				<?php if(isset(\$preferredGDPILocation) && \$preferredGDPILocation!=\"\"){ ?>
				<script>
				var selObj = document.getElementById(\"preferredGDPILocation\"); 
				var A= selObj.options, L= A.length;
				while(L){
					if (A[--L].value== \"<?php echo \$preferredGDPILocation;?>\"){
					selObj.selectedIndex= L;
					L= 0;
					}
				}
				</script>
				  <?php } ?>
				<div style='display:none'><div class='errorMsg' id= 'preferredGDPILocation_error'></div></div>
				</div>
			</li>
			<?php endif; ?>
	
	";
	
  }
  function createFieldBasedOnType($formField,$allowMultiple=false,$multipleNumber=0){

	echo "\r\n\t\t\t";

	$validate = ( isset($formField['validationType']) && $formField['validationType']!='' && $formField['validationType']!=NULL)?' validate="'.$formField['validationType'].'" ':'';
	$required= (isset($formField['required']) && $formField['required']!='' && $formField['required']!=NULL && $formField['required']!="false")?' required="true" ':'';
	$caption= (isset($formField['caption']) && $formField['caption']!='' && $formField['caption']!=NULL)?' caption="'.$formField['caption'].'" ':'';
	$minLength= (isset($formField['minCharactersAllowed']) && $formField['minCharactersAllowed']!='' && $formField['minCharactersAllowed']!=NULL)?' minlength="'.$formField['minCharactersAllowed'].'" ':'';
	$maxLength= (isset($formField['maxCharactersAllowed']) && $formField['maxCharactersAllowed']!='' && $formField['maxCharactersAllowed']!=NULL)?' maxlength="'.$formField['maxCharactersAllowed'].'" ':'';
	$style= (isset($formField['style']) && $formField['style']!='' && $formField['style']!=NULL)?' style="'.$formField['style'].'" ':'';

	//For now, we are not showing this Tool tip for Checkboxes and radio buttons since the tooltip appears for all of the values which is a problem
	$tip = (isset($formField['toopTip']) && $formField['toopTip']!='' && $formField['toopTip']!=NULL)?' tip="'.$formField['toopTip'].'" ':'';
	$mouseOverTip = '';
	$mouseOverTip = (isset($formField['toopTip']) && $formField['toopTip']!='' && $formField['toopTip']!=NULL)?" onmouseover=\"showTipOnline('".$formField['toopTip']."',this);\" onmouseout=\"hidetip();\" ":"";
	
	$title= (isset($formField['title']) && $formField['title']!='' && $formField['title']!=NULL)?' title="'.$formField['title'].'" ':'';

	$other= (isset($formField['other']) && $formField['other']!='' && $formField['other']!=NULL)?$formField['other']:'';
	$allowNA = '';
	if(strpos($other,'allowNA')!==false){
	    $allowNA = " allowNA = 'true' ";
	}

	$onClick= '';
	if($allowMultiple){
		$fieldName = $formField['name']."_mul_".$multipleNumber;
		$required = '';
		$tip = '';
		$title = '';
		 $formField['helpText'] = '';
		$multiCase = true;
	}
	else{
		$fieldName = $formField['name'];
		$multiCase = false;
	}
	$fileValidations = (isset($formField['other']) && $formField['other']!='' && $formField['other']!=NULL)?$formField['other']:'';
	//var_dump($fieldName);
	//Here, while filling in the values, there are three value fields.
	// 1. Pre filled values of an entity. We need to fill these
	// 2. There could be a default value for any field defined. We need to keep this default value selected.
	// 3. There could be a value for this field filled by user like username, email or in case of edit.
	// So, if a value is filled by user, then use it.
	$valueToBeFilled = '';
	if(isset($$formField['name']) && $$formField['name']!=''){
	      $valueToBeFilled = $$formField['name'];
	}
	else if(isset($formField['defaultValue']) && $formField['defaultValue']!=''){
	      $valueToBeFilled = $formField['defaultValue'];
	}

	// In case there is no default value, we will have to check if there is helptext. 
	// If it is available, we will show this Help text, add on focus methods and change the font color
	$extraEventMethods = '';
	$changeColorScript = false;
	if($valueToBeFilled==''){
	      $valueToBeFilled= (isset($formField['helpText']) && $formField['helpText']!='' && $formField['helpText']!=NULL)?$formField['helpText']:'';
	      if($valueToBeFilled!=''){		//If the Helptext is available
		    $extraEventMethods = " default = '".$valueToBeFilled."' onfocus='checkTextElementOnTransition(this,\"focus\");' onblur='checkTextElementOnTransition(this,\"blur\");' ";
		    $changeColorScript = true;
	      }
	}
	
	if($formField['Heading']){
		echo "<li>";
		echo "\r\n\t\t\t\t";
		echo '<h3 class="upperCase">'.$formField['Heading'].'</h3>';
		echo "\r\n\t\t\t\t";
		echo "</li>";
		echo "\r\n\t\t\t\t";
	}
	
	if($formField['Linked'] == "Education"){
		
		$fieldName = $fieldName . "_mul_";
		addEducationMultiHeader($fieldName);
		$fieldName = "<?=\$fieldName?>";
		$formField['label'] = str_replace("<Masters>","<?=\$otherCourseName?>",$formField['label']);
		
	}
	
	if($formField['Linked'] == "Job"){
		
		$fieldName = $fieldName . "_mul_";
		addJobMultiHeader($fieldName);
		$fieldName = "<?=\$fieldName?>";
		$formField['label'] = str_replace("<Company>","<?=\$workCompany?>",$formField['label']);
		
	}

		
	echo "<li>";
	if ($formField['type'] == 'checkbox' && strpos($fieldName, 'Terms')!== false)
	{
	echo '<h3>Declaration</h3>
				<div class="fieldBoxLarge" style="width: 100% ; color: #666666; font-style: italic;">
					<ul>
						<li>
							All entries made in the application form are true to the best of my knowledge and belief. I am willing to produce original certificates on demand at any time. I also undertake that I shall abide by the rules and regulations of Deccan Education Society- Institute of Management Development and Research (IMDR).
						</li>
					</ul>
				</div>';
	}
	echo "\r\n\t\t\t\t";
	echo "<div class='additionalInfoLeftCol'>";
	echo "\r\n\t\t\t\t";
	switch ($formField['type']){
	    case 'text': $readonly = '';
			if($fieldName=='courseCode' || $fieldName=='courseName') $readonly = ' readonly="" ';
			echo "<label>".$formField['label'].": </label>";
			echo "\r\n\t\t\t\t";
			echo "<div class='fieldBoxLarge'>";
			echo "\r\n\t\t\t\t";
			echo "<input type='text' name='".$fieldName."' id='".$fieldName."' $validate $required $caption $minLength $maxLength $style $onClick $tip $title value='$valueToBeFilled' $readonly $extraEventMethods $allowNA/>";
			addChangeColorScript($changeColorScript,$fieldName);
			addScriptForAddingValue('text',$fieldName);
			break;
	    case 'textarea': echo "<label>".$formField['label'].": </label>";
			echo "\r\n\t\t\t\t";
			echo "<div class='fieldBoxLarge'>";
			echo "\r\n\t\t\t\t";
			echo "<textarea name='".$fieldName."' id='".$fieldName."' $validate $required $caption $minLength $maxLength $style $onClick $tip $title $extraEventMethods $allowNA>$valueToBeFilled</textarea>";
			addChangeColorScript($changeColorScript,$fieldName);
			addScriptForAddingValue('textarea',$fieldName);
			break;
	    case 'password': echo "<label>".$formField['label'].": </label>";
			echo "\r\n\t\t\t\t";
			echo "<div class='fieldBoxLarge'>";
			echo "\r\n\t\t\t\t";
			echo "<input type='password' name='".$fieldName."' id='".$fieldName."' $validate $required $caption $minLength $maxLength $style $onClick $tip $title value='$valueToBeFilled' $extraEventMethods $allowNA/>";
			addChangeColorScript($changeColorScript,$fieldName);
			addScriptForAddingValue('password',$fieldName);
			break;
	    case 'hidden': echo "<label>".$formField['label'].": </label>";
			echo "\r\n\t\t\t\t";
			echo "<div class='fieldBoxLarge'>";
			echo "\r\n\t\t\t\t";
			echo "<input type='hidden' name='".$fieldName."' id='".$fieldName."' $style $tip $title value='$valueToBeFilled' $extraEventMethods $allowNA/>";
			addChangeColorScript($changeColorScript,$fieldName);
			addScriptForAddingValue('hidden',$fieldName);
			break;
	    case 'image': echo "<label>".$formField['label'].": </label>";
			echo "\r\n\t\t\t\t";
			echo "<div class='fieldBoxLarge'>";
			echo "\r\n\t\t\t\t";
			echo "<img name='".$fieldName."' id='".$fieldName."' border=0 $style $onClick src='$valueToBeFilled' $tip $title />";
			break;
	    case 'button': echo "<label>".$formField['label'].": </label>";
			echo "\r\n\t\t\t\t";
			echo "<div class='fieldBoxLarge'>";
			echo "\r\n\t\t\t\t";
			echo "<input type='button' name='".$fieldName."' id='".$fieldName."' $style $onClick $tip $title value='$valueToBeFilled'/>";
			break;
	    case 'label': echo "<label>".$formField['label'].": </label>";
			echo "\r\n\t\t\t\t";
			echo "<div class='fieldBoxLarge'>".$valueToBeFilled;
			break;
	    case 'radio': 
			$arr = explode(',',$formField['values']);
			echo "<label>".$formField['label'].": </label>";
			echo "\r\n\t\t\t\t";
			echo "<div class='fieldBoxLarge'>";
			for($i=0;$i<count($arr);$i++){
			      $selected = '';
			      if($arr[$i]==$valueToBeFilled && $valueToBeFilled!='') 
				    $selected = 'checked';
			      else if($i==0 && $valueToBeFilled=='') 
				    $selected = 'checked';
			      
			      if($multiCase) $selected = '';	//IN case of multiple, do not check the Radio button
			      echo "\r\n\t\t\t\t";
			      echo "<input type='radio' $validate $required $caption name='".$fieldName."' id='".$fieldName.$i."' $style $onClick value='".$arr[$i]."'  $selected $title $mouseOverTip></input>";
			      echo "<span $mouseOverTip>".$arr[$i]."</span>&nbsp;&nbsp;";
			  }
			//echo "</div>";
			addScriptForAddingValue('radio',$fieldName);
			break;
	    case 'checkbox': 
			$arr = explode(',',$formField['values']);
			echo "<label>".$formField['label'].": </label>";
			echo "\r\n\t\t\t\t";
			echo "<div class='fieldBoxLarge'>";
			for($i=0;$i<count($arr);$i++){
			      $selected = '';
			      if($arr[$i]==$valueToBeFilled && $valueToBeFilled!='') 
				    $selected = 'checked';
			      else if($i==0 && $valueToBeFilled=='') 
				    $selected = 'checked';
			      if($multiCase) $selected = '';	//IN case of multiple, do not check the Checkbox

			      echo "\r\n\t\t\t\t";
			      echo "<input type='checkbox' $validate $required $caption  name='".$fieldName."[]' id='".$fieldName.$i."' $style $onClick value='".$arr[$i]."'  $selected $title $mouseOverTip></input>";
			      echo "<span $mouseOverTip>".$arr[$i]."</span>&nbsp;&nbsp;";
			  }
			//echo "</div>";
			addScriptForAddingValue('checkbox',$fieldName);
			break;
	    case 'select': 
			$arr = explode(',',$formField['values']);
			echo "<label>".$formField['label'].": </label>";
			echo "\r\n\t\t\t\t";
			echo "<div class='fieldBoxLarge'>";
			echo "\r\n\t\t\t\t";
			if(strpos($fieldName,'country')!==false){
				$onChange = "onchange='getCitiesForCountryOnline(\"\",false,\"\",false);'";
				$varname = '$country';
				if(strpos($fieldName,'Ccountry')!==false){
					$onChange = "onchange='getCitiesForCountryOnlineCorrespondence(\"\",false,\"\",false);'";
					$varname = '$Ccountry';
				}
				echo "<select name='".$fieldName."' id='".$fieldName."' $style $onClick $tip $title $validate $required $caption $onChange $mouseOverTip>";
						echo '<?php foreach($country_list as $countryItem) {
							$countryId = $countryItem["countryID"];
							$countryName = $countryItem["countryName"];
							if($countryId == 1) { continue; }
								if($countryId == '.$varname.') { $selected = "selected"; }
							else { $selected = ""; } ?>
						<option value="<?php echo $countryId; ?>" <?php echo $selected; ?> ><?php echo $countryName; ?></option>
						<?php } ?>';
				echo "<option value='others' > others </option>";
				echo "</select>";
				//echo "</div>";

				//Also, add the Copy address field in this
				if(strpos($fieldName,'Ccountry')!==false){
					echo "<input type='checkbox' onclick='copyAddressFields();'>&nbsp;Same as permanent address";
				}
			}
			else if(strpos($fieldName,'city')!==false){
				echo "<select name='".$fieldName."' id='".$fieldName."' $style $onClick $tip $title $validate $required $caption $mouseOverTip /></select>";
				//echo "</div>";
			}
			else{
				echo "<select name='".$fieldName."' id='".$fieldName."' $style $onClick $tip $title $validate $required $caption $mouseOverTip>";
				for($i=0;$i<count($arr);$i++){
				      $arrTemp = explode('-',$arr[$i]);
				      $selected = '';
				      if($arrTemp[0]==$valueToBeFilled && $valueToBeFilled!='') 
					    $selected = 'selected';
				      else if($i==0 && $valueToBeFilled=='') 
					    $selected = 'selected';

				      if(isset($arrTemp[1])){
					    echo "<option value='".$arrTemp[0]."' $selected>".$arrTemp[1]."</option>";
				      }
				      else{
					    echo "<option value='".$arrTemp[0]."' $selected>".$arrTemp[0]."</option>";
				      }
				  }
				echo "</select>";
				//echo "</div>";
				addScriptForAddingValue('select',$fieldName);
			}
			break;
	    case 'selectMultiple': 
			$arr = explode(',',$formField['values']);
			echo "<label>".$formField['label'].": </label>";
			echo "\r\n\t\t\t\t";
			echo "<div class='fieldBoxLarge'>";
			echo "\r\n\t\t\t\t";
			echo "<select name='".$fieldName."[]' id='".$fieldName."' $style $onClick $tip $title $mouseOverTip MULTIPLE SIZE=5>";
			for($i=0;$i<count($arr);$i++){
			      $arrTemp = explode('-',$arr[$i]);
			      $selected = '';
			      if($arrTemp[0]==$valueToBeFilled && $valueToBeFilled!='') 
				    $selected = 'selected';
			      else if($i==0 && $valueToBeFilled=='') 
				    $selected = 'selected';

			      if(isset($arrTemp[1])){
				    echo "<option value='".$arrTemp[0]."' $selected>".$arrTemp[1]."</option>";
			      }
			      else{
				    echo "<option value='".$arrTemp[0]."' $selected>".$arrTemp[0]."</option>";
			      }
			  }
			echo "</select>";
			//echo "</div>";
			addScriptForAddingValue('selectMultiple',$fieldName);
			break;
	    case 'date': echo "<label>".$formField['label'].": </label>";
			echo "\r\n\t\t\t\t";
			echo "<div class='fieldBoxLarge'>";
			echo "\r\n\t\t\t\t";
			echo "<input type='text' name='".$fieldName."' id='".$fieldName."' readonly maxlength='10' $validate $required $caption $minLength $maxLength $style $onClick $tip $title $extraEventMethods $mouseOverTip onClick=\"var cal = new CalendarPopup('calendardiv'); cal.select($('".$fieldName."'),'".$fieldName."_dateImg','dd/MM/yyyy');\" />";
			echo "\r\n\t\t\t\t";
			echo "&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='".$fieldName."_dateImg' onClick=\"var cal = new CalendarPopup('calendardiv'); cal.select($('".$fieldName."'),'".$fieldName."_dateImg','dd/MM/yyyy'); return false;\" />";
			addChangeColorScript($changeColorScript,$fieldName);
			addScriptForAddingValue('date',$fieldName);
			break;
	    case 'file': echo "<label>".$formField['label'].": </label>";
			echo "\r\n\t\t\t\t";
			echo "<div class='fieldBoxLarge'>";
			echo "\r\n\t\t\t\t";
			echo "<input type='file' name='userApplicationfile[]' id='".$fieldName."' $validate $required $caption $minLength $maxLength $style $onClick $tip $title />";
			echo "\r\n\t\t\t\t";
			echo "<input type='hidden' name='".$fieldName."Valid' value='".$fileValidations."'>";
			break;
	}
	//Also, create the error div for these fields
	echo "\r\n\t\t\t\t";
	echo "<div style='display:none'><div class='errorMsg' id= '".$fieldName."_error'></div></div>";
	echo "\r\n\t\t\t\t";
	echo "</div>";	//End the fieldBoxLarge class Div
	echo "\r\n\t\t\t\t";
	echo "</div>";	//End the additionalInfoLeftCol class Div
	echo "\r\n\t\t\t";
	echo "</li>";
	echo "\r\n";
	if($formField['Linked']){
		echo "<?php
				}
			}
			?>";
	}
	//selectTheCity();
  }

  echo "<script>getCitiesForCountryOnline(\"\",false,\"\",false);</script>";
  echo "<script>getCitiesForCountryOnlineCorrespondence(\"\",false,\"\",false);</script>";


  // PHP function to change the color of the text inside a field in case the HelpText is set
  function addChangeColorScript($changeColorScript,$fieldName){
	if($changeColorScript=='true'){
	      echo '<script>
		    document.getElementById("'.$fieldName.'").style.color = "#ADA6AD";
		</script>';
	}
  }
  
  function addEducationMultiHeader($fieldName){
	echo "
	<?php
	// Find out graduation course name, if available
	\$graduationCourseName = 'Graduation';
	\$graduationYear = '';
	\$otherCourses = array();
	\$otherCourseYears = array();
	
	if(is_array(\$educationDetails)) {
		foreach(\$educationDetails as \$educationDetail) {
			if(\$educationDetail['value']) {
				if(\$educationDetail['fieldName'] == 'graduationExaminationName') {
					\$graduationCourseName = \$educationDetail['value'];
				}
				else if(\$educationDetail['fieldName'] == 'graduationYear') {
					\$graduationYear = \$educationDetail['value'];
				}
				else {
					for(\$i=1;\$i<=4;\$i++) {
						if(\$educationDetail['fieldName'] == 'graduationExaminationName_mul_'.\$i) {
							\$otherCourses[\$i] = \$educationDetail['value'];
						}
						else if(\$educationDetail['fieldName'] == 'graduationYear_mul_'.\$i) {
							\$otherCourseYears[\$i] = \$educationDetail['value'];
						}
					}
				}
			}
		}
	}

	if(isset(\$graduationEndDate) && \$graduationYear!=\"\") {
		\$graduationYear = \$graduationEndDate;
	}

		if(count(\$otherCourses)>0) { 
			foreach(\$otherCourses as \$otherCourseId => \$otherCourseName) {
				\$fieldName = "."'".$fieldName."'."."\$otherCourseId;
		?>
	";
	
  }
  
  
function addJobMultiHeader($fieldName){
	echo "
		<?php
			\$workCompanies = array();
			if(is_array(\$educationDetails)) {
				foreach(\$educationDetails as \$educationDetail) {
					if(\$educationDetail['value']) {
						if(\$educationDetail['fieldName'] == 'weCompanyName') {
							\$workCompanies['0'] = \$educationDetail['value'];
						}
						else {
							for(\$i=1;\$i<=2;\$i++) {
								if(\$educationDetail['fieldName'] == 'weCompanyName_mul_'.\$i) {
									\$workCompanies[\$i] = \$educationDetail['value'];
								}
							}
						}
					}
				}
			}
			
			if(count(\$workCompanies) > 0) {
				foreach(\$workCompanies as \$workCompanyKey => \$workCompany) {
					\$fieldName = "."'".$fieldName."'."."\$workCompanyKey;
			?>
	";
	
  }


  //PHP function to prefill the values in the form (if user values are available or we are Editing the form)
  function addScriptForAddingValue($type, $fieldName){
	echo "\r\n\t\t\t\t";
	$str1 = '"\n"';
	$str2 = "'\\n'";
	$orgFieldName = $fieldName;
	$fieldName = str_replace("<?=","",$fieldName);
	$fieldName = str_replace("?>","",$fieldName);
	if($type == 'text' || $type == 'textarea' || $type == 'date' || $type == 'hidden' || $type == 'password'){
	      echo '<?php if(isset($'.$fieldName.') && $'.$fieldName.'!=""){ ?>
				  <script>
				      document.getElementById("'.$orgFieldName.'").value = "<?php echo str_replace('.$str1.', '.$str2.', $'.$fieldName.' );  ?>";
				      document.getElementById("'.$orgFieldName.'").style.color = "";
				  </script>
				<?php } ?>';
	}
	else if($type=='radio'){
	      echo '<?php if(isset($'.$fieldName.') && $'.$fieldName.'!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["'.$orgFieldName.'"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $'.$fieldName.';?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>';
	}
	else if($type=='checkbox'){
	      echo '<?php if(isset($'.$fieldName.') && $'.$fieldName.'!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["'.$orgFieldName.'[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$'.$fieldName.');
						    for($x=0;$x<count($arr);$x++){ ?>
							  if(objCheckBoxes[i].value == "<?php echo $arr[$x];?>") {
								  objCheckBoxes[i].checked = true;
							  }
					      <?php
						    }
					      ?>
				    }
				</script>
			      <?php } ?>';
	}
	else if($type=='select'){
	      echo '<?php if(isset($'.$fieldName.') && $'.$fieldName.'!=""){ ?>
			      <script>
				  var selObj = document.getElementById("'.$orgFieldName.'"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $'.$fieldName.';?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>';
	}
	else if($type=='selectMultiple'){
	      echo '<?php if(isset($'.$fieldName.') && $'.$fieldName.'!=""){ ?>
			    <script>
				var select = document.getElementById("'.$orgFieldName.'"); 
				var optionsToSelect = Array();
				    <?php $arr = explode(",",$'.$fieldName.'); 
				      for($i=0;$i<count($arr);$i++){ ?>
					  optionsToSelect[<?php echo $i;?>] = "<?php echo $arr[$i];?>";
				    <?php
				      }
				    ?>
				    for ( var i = 0, l = select.options.length, o; i < l; i++ )
				    {
				      o = select.options[i];
				      o.selected = false;
				      if ( optionsToSelect.indexOf( o.text ) != -1 )
				      {
					o.selected = true;
				      }
				    }
			    </script>
			  <?php } ?>';
	}
	echo "\r\n\t\t\t\t";

  }

  //JS code to select the city which has been entered by the user while registration
  echo '<?php if(isset($city) && $city!=""){ ?>
    <script>
	var selObj = document.getElementById("city"); 
	if(selObj){
	      var A= selObj.options, L= A.length;
	      while(L){
		  L = L-1;
		  if (A[L].innerHTML == "<?php echo $city;?>" || A[L].value == "<?php echo $city;?>"){
		      selObj.selectedIndex= L;
		      L= 0;
		  }
	      }
	}
    </script>
  <?php } ?>';

  //JS code to select the Correspondent city which has been entered by the user while registration
  echo '<?php if(isset($Ccity) && $Ccity!=""){ ?>
    <script>
	var selObj = document.getElementById("Ccity"); 
	if(selObj){
	      var A= selObj.options, L= A.length;
	      while(L){
		  L = L-1;
		  if (A[L].innerHTML == "<?php echo $Ccity;?>" || A[L].value == "<?php echo $Ccity;?>"){
		      selObj.selectedIndex= L;
		      L= 0;
		  }
	      }
	}
    </script>
  <?php } ?>';

  //JS function to take care of Allow multiple in case of Groups. This will unhide the group.
  echo "<script>
  function showMoreGroups(groupId,maxAllowed){
	if(document.getElementById(groupId)){
	      for(i=1;i<=maxAllowed;i++){
		  if(document.getElementById(String(groupId)+String(i)).style.display == 'none'){
		      document.getElementById(String(groupId)+String(i)).style.display = '';
		      break;
		  }
	      }
	      if(i==maxAllowed){
		  document.getElementById('showMore'+groupId).style.display = 'none';
	      }
	}
  }";

  // Code to make the Course code as read only
  echo "if(document.getElementById('courseCode')){
	document.getElementById('courseCode').readonly = true;
  }";
  
  // JS function to copy the address fields on the second form of MBA forms
  echo "function copyAddressFields(){
	if(document.getElementById('city') && document.getElementById('Ccity')){
		document.getElementById('ChouseNumber').value = document.getElementById('houseNumber').value;
		document.getElementById('CstreetName').value = document.getElementById('streetName').value;
		document.getElementById('Carea').value = document.getElementById('area').value;
		document.getElementById('Cpincode').value = document.getElementById('pincode').value;

		var sel = document.getElementById('country');
		var countrySelected = sel.options[sel.selectedIndex].value;
		var selObj = document.getElementById('Ccountry'); 
		var A= selObj.options, L= A.length;
		while(L){
		    if (A[--L].value== countrySelected){
			selObj.selectedIndex= L;
			L= 0;
		    }
		}

		getCitiesForCountryOnlineCorrespondence(\"\",false,\"\",false);

		var sel = document.getElementById('city');
		var citySelected = sel.options[sel.selectedIndex].value;
		var selObj = document.getElementById('Ccity'); 
		var A= selObj.options, L= A.length;
		while(L){
		    if (A[--L].value== citySelected){
			selObj.selectedIndex= L;
			L= 0;
		    }
		}

	}
  }

  </script>";


?>
