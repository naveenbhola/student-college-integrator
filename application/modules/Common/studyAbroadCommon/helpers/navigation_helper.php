<?php

	function generateSubMenu($mainSectionBucket,$mainSectionHeading,$groupName) {
		$forString = (str_replace(' ', '-', strtolower($mainSectionHeading))).'_nav_'.rand(0,99);
		$mainSectionWidget = '<label for="'.$forString.'" class="menuTab-div mesublabel">'.$mainSectionHeading.'<label title="Toggle Drop-down" class="drop-icon" for="'.$forString.'"></label></label><input type="radio" id="'.$forString.'" name="tab-cntry">';
		


		if(in_array($mainSectionHeading,array('Find Colleges by Exam','Scholarships','Education Loans'))){
			if(in_array($mainSectionHeading,array('Scholarships'))){
				$chunkSize = 2;
			} else if(in_array($mainSectionHeading,array('Education Loans'))){
				$chunkSize = 3;
			}else{
				$chunkSize = 4;
			}
			$mainSectionBucketChunk = array_chunk($mainSectionBucket['sub_section_name'], $chunkSize,true);
			$prepareSubSectionWidget = '';
			foreach ($mainSectionBucketChunk as $key => $mainSectionBucket) {
				$prepareSubSectionWidget.= '<ul class="clg-exList">'; 
				$prepareSubSectionWidget.= generateSubSubMenu($mainSectionBucket,$groupName);				
				$prepareSubSectionWidget.= '</ul>'; 
			}
			
			return $mainSectionWidget.'<div class="clgByExm-Dv">'.$prepareSubSectionWidget.'</div>';
		}else{
			$prepareSubSectionWidget = generateSubSubMenu($mainSectionBucket['sub_section_name'],$groupName);
			return $mainSectionWidget.'<ul class="sub-sub-menu mesubdiv">'.$prepareSubSectionWidget.'</ul>';	
		}
	}


	function generateSubSubMenu($mainSectionBucket,$groupName){
		foreach ($mainSectionBucket as $sub_section_heading => $subSectionBucket) {
			$subSectionHeadingHTML = '';
			$prepareLinks ='';
			if($sub_section_heading != 'empty'){
				$subSectionHeadingHTML =  '<label href="javascript:void(0)" class="fnt-wt menuTab-div">'.$sub_section_heading.'</label>';
				$prepareLinks = '<ul class="sub-sub-menu2">';
			}
			
			foreach ($subSectionBucket['links'] as $key => $links) {
				if(!empty($links['text']))
					$prepareLinks .= '<li><a class="pnl_a gaTrack" gaParams="ABROAD_GNB,'.$groupName.'" href="'.$links['url'].'">'.$links['text'].'</a></li>';
			}
			if($sub_section_heading != 'empty'){
				$prepareLinks .= '</ul>';			
			}	
			
			$prepareSubSectionWidget .= '<li>'.$subSectionHeadingHTML.$prepareLinks.'</li>';
		}

		return $prepareSubSectionWidget;
	}