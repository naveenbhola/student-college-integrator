<div id="cateTitleBlock">
	<h1 style="color:black;">
		<?php 
		if(!empty($h1Title)) {
			echo $h1Title;
		}
		else {
			$heading = ' Institutes ';
			if($request->isMainCategoryPage()) {
				if($categoryPage->getCategory()->isTestPrep()) {
					$pageHeading = 'Entrance Exams';
					$heading = ' Coaching Institutes ';
				}
				else {
					$pageHeading = $categoryPage->getCategory()->getName();
					if($categoryPage->getCategory()->getId() == 13){
						$heading = ' Courses';
					}
				}
			}
			elseif($request->isSubcategoryPage()) {
				$pageHeading =  $categoryPage->getSubCategory()->getName();
				if($categoryPage->getCategory()->isTestPrep()) {
					$pageHeading = str_replace('Management', 'MBA', $pageHeading);
					$pageHeading = str_replace('Exams', '', $pageHeading);
					if(stripos($pageHeading,"Coaching") > 0) {
						$heading = ' Institutes ';
					}
					else {
						$heading = ' Exams Coaching Institutes ';
					}
				}
				if( $request->getSubCategoryId() == 18 && ( $request->getCityId() == 64 || $request->getStateId() == 123) ) {
					$pageHeading = 'Mass Communication / Viscomm';
				}

				if($request->getSubCategoryId() == 28){
					$pageHeading = 'BBA';
				}

				if($request->getSubCategoryId() == 55){
					$pageHeading = 'Engineering Diploma';
				}

				if($request->getSubCategoryId() == 59){
					$pageHeading = 'M.Tech';
				}

				if($request->getSubCategoryId() == 69){
					$pageHeading = 'Fashion & Textile Designing';
				}

				if(in_array($request->getSubcategoryId(),array(31,70,71,72,73))){					
					$heading = ' Courses ';
				}else if(in_array($request->getSubcategoryId(),array(59,55,69))){					
					$heading = ' Colleges ';
					if($categoryPage->getTotalNumberOfInstitutes() == 1){
						$heading = ' College ';
					}
				}else if(
					preg_match('/[mba]{1}/i', $request->getSubCategoryName())
//					|| preg_match('/[engineering]{1}/i', $pageHeading)
				) {
					$heading = ' Colleges ';
				}
			}
			elseif($request->isLDBCoursePage()) {
				if(in_array($request->getSubcategoryId(), array(24,25,26,27,28,31))){
					$pageHeading= $categoryPage->getLDBCourse()->getCourseName()." in ".($categoryPage->getLDBCourse()->getSpecialization()!="All"?$categoryPage->getLDBCourse()->getSpecialization():"");
				}else if($request->getSubcategoryId() == 59 || $request->getSubcategoryId() == 55){
					if($request->getSubCategoryId() == 55){
						$pageHeading = 'Diploma';
					}

					if($request->getSubCategoryId() == 59){
						$pageHeading = 'M.Tech';
					}
					$pageHeading = ($categoryPage->getLDBCourse()->getSpecialization()!="All"?$pageHeading." in ".$categoryPage->getLDBCourse()->getSpecialization():"");
				}
				else{
					$pageHeading = $categoryPage->getLDBCourse()->getCourseName()." ".($categoryPage->getLDBCourse()->getSpecialization()!="All"?$categoryPage->getLDBCourse()->getSpecialization():"");					
				}
				if($categoryPage->getCategory()->isTestPrep()) {
					$heading = ' Coaching Institutes ';
				}else if(in_array($request->getSubcategoryId(),array(31,70,71,72,73))){					
					$heading = ' Courses ';
				}else if(in_array($request->getSubcategoryId(),array(59,55))){
					$heading = ' Colleges ';
					if($categoryPage->getTotalNumberOfInstitutes() == 1){
						$heading = ' College ';
					}
				}
				else if( preg_match('/[mba]{1}/i', $request->getSubCategoryName()) ) {
					$heading = ' Colleges ';
				}
				$change = "Course";
			}
			$tcityid = $request->getCityID();
			$tstateid = $request->getStateID();
			if($tstateid=="1" && $tcityid == "1"){
				if(in_array($categoryPage->getSubCategory()->getId(),array(24,25,26,27,28,31,59,55,69,70,71,72,73)) || $categoryPage->getCategory()->getId() == 13){
		
					if($categoryPage->getSubCategory()->getName()=='All'){
						$pageHeading = $categoryPage->getCategory()->getName();
					}else{
						$pageHeading = $categoryPage->getSubCategory()->getName();
					}

					if($request->getSubCategoryId() == 28){
						$pageHeading = 'BBA';
					}

					if($request->getSubCategoryId() == 55){
						$pageHeading = 'Diploma';
					}

					if($request->getSubCategoryId() == 59){
						$pageHeading = 'M.Tech';
					}

					if(in_array($categoryPage->getSubCategory()->getId(),array(31,70,71,72,73)) || ($categoryPage->getCategory()->getId() == 13 && $categoryPage->getSubCategory()->getId() !=69)){					
						$headingSingle = ' Course';
						$headingPlural = ' Courses';
					}else{
						$headingSingle = ' College';
						$headingPlural = ' Colleges';
					}
					if($categoryPage->getLDBCourse()->getSpecialization() == 'All'){
						if($request->getSubCategoryId() == 55){
							$pageHeading = 'Engineering Diploma';
						}
						echo ($pageHeading).(($categoryPage->getTotalNumberOfInstitutes() == 1)?$headingSingle:$headingPlural)." in <span>India</span>";					
					}else{
						echo ($pageHeading)." in ".($categoryPage->getLDBCourse()->getSpecialization()).(($categoryPage->getTotalNumberOfInstitutes() == 1)?$headingSingle:$headingPlural)." in <span>India</span>";					
					}	
				}else{
					echo "<span style='color:black;' id='pageTitleCount'>".$categoryPage->getTotalNumberOfInstitutes()."</span> ".($categoryPage->getSubCategory()->getName()=='All'?$categoryPage->getCategory()->getName():$categoryPage->getSubCategory()->getName()).(($categoryPage->getTotalNumberOfInstitutes() == 1)?" Institute":" Institutes")." Found";					
				}
			}
			else
			{
				echo $pageHeading.$heading;
			
		?>
				in
				<span>
				<?php
					global $locationname;
					if($categoryPage->getLocality()){
						$locationname = $categoryPage->getLocality()->getName();
					}elseif($categoryPage->getZone()){
						$locationname = $categoryPage->getZone()->getName();
					}elseif($request->getCityId() > 1){
						$locationname = $categoryPage->getCity()->getName();
					}elseif($request->getStateId() > 1){
						$locationname = $categoryPage->getState()->getName();
					}else{
						$locationname = $categoryPage->getCity()->getName();
					}
					echo $locationname;
				?>
				</span>
		<?php
			}
		}
		global $pageHeading;
		$change = "Career Option";
		if($request->isMainCategoryPage()) {
			$change = "Career Option";
			if($categoryPage->getCategory()->isTestPrep()) {
				$pageHeading = 'Entrance Exams';
			}
			else {
				$pageHeading = $categoryPage->getCategory()->getName();
			}
		}
		elseif($request->isSubcategoryPage()) {
			$change = "Course";
			$pageHeading =  $categoryPage->getSubCategory()->getName();
			if($categoryPage->getCategory()->isTestPrep()) {
				$pageHeading = str_replace('Management', 'MBA', $pageHeading);
				$pageHeading = str_replace('Exams', '', $pageHeading);
			}
			if( $request->getSubCategoryId() == 18 && ( $request->getCityId() == 64 || $request->getStateId() == 123) ) {
				$pageHeading = 'Mass Communication / Viscomm';
			}
			switch($request->getSubCategoryId()) {
				case 28: $pageHeading = 'BBA';
						 break;
				case 55: $pageHeading = 'Engineering Diploma';
						 break;
				case 59: $pageHeading = 'M.Tech';
						 break;		
				case 69: $pageHeading = 'Fashion & Textile Designing';
						 break;				 
			}
		}
		elseif($request->isLDBCoursePage()) {
			$change = "Course";
			if(in_array($request->getSubcategoryId(), array(24,25,26,27,28,31))){
				$pageHeading= $categoryPage->getLDBCourse()->getCourseName()." in ".($categoryPage->getLDBCourse()->getSpecialization()!="All"?$categoryPage->getLDBCourse()->getSpecialization():"");
			}else if($request->getSubcategoryId() == 59 || $request->getSubcategoryId() == 55){
				switch($request->getSubCategoryId()) {
					case 55: $pageHeading = 'Diploma';
							 break;
					case 59: $pageHeading = 'M.Tech';
							 break;
				}
				$pageHeading = ($categoryPage->getLDBCourse()->getSpecialization()!="All"?$pageHeading." in ".$categoryPage->getLDBCourse()->getSpecialization():"");
			}
			else{
				$pageHeading = $categoryPage->getLDBCourse()->getCourseName()." ".($categoryPage->getLDBCourse()->getSpecialization()!="All"?$categoryPage->getLDBCourse()->getSpecialization():"");					
			}
		}
		$tcityid = $request->getCityID();
		$tstateid = $request->getStateID();
		if($tstateid=="1" && $tcityid == "1"){
			if(in_array($categoryPage->getSubCategory()->getId(),array(24,25,26,27,28,31,59,55,69,70,71,72,73)) || $categoryPage->getCategory()->getId() == 13){
			}
			if($categoryPage->getSubCategory()->getName()=='All'){
				$pageHeading = $categoryPage->getCategory()->getName();
			}else{
				$pageHeading = $categoryPage->getSubCategory()->getName();
			}
			switch($request->getSubCategoryId()) {
				case 28: $pageHeading = 'BBA';
						 break;
				case 55: $pageHeading = 'Diploma';
						 break;
				case 59: $pageHeading = 'M.Tech';
						 break;
			}
		}
		?>
	</h1>
	<div style="position:relative;">
	<div class="changeLocation" id="changeLocationdiv">
		<strong>
			[&nbsp;</strong><a href="#"  id="changeCategorylink" onclick="openCategoryLayer();return false;" onmouseout = "dissolveOverlayHackForIE();$('overlayCategoryHolder').style.display='none'">Change <?=$change?> <span class="orangeColor">&#9660;</span></a><strong>&nbsp;]</strong>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<strong>[&nbsp;</strong><a href="#" id="changeLocationlink" onclick="openLocationLayer();return false;">Change Location <span class="orangeColor">&#9660;</span></a><strong>&nbsp;]</strong>
	</div>

	</div>
	
	
	<?php
	$headerText = $categoryPage->getHeaderText();
	if($headerText && strlen($headerText)>0){
	?>
	<p id="partialHeaderText">
		<?=html_escape(substr($headerText,0,200))?>
		<?php if(strlen($headerText)>201){ ?>
		... <a href="#" onclick="$('partialHeaderText').style.display= 'none'; $('fullHeaderText').style.display= ''; return false;">Read more</a>
		<?php } ?>
	</p>

	<p id="fullHeaderText" style="display:none">
		<?=html_escape($headerText)?>
	</p>
	<?php } ?>
</div>
