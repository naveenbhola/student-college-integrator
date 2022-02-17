<?php 
$searchType = $search_type; //controller variable
$sponsoredInstitutes = $sponsored_institutes; //controller variable
$normalInstitutes = $normal_institutes; //controller variable

$sponsoredInstituteIds = $sponsored_institute_ids; //controller variable
$sponsoredResultCount = count($sponsoredInstitutes);

$normalResultCount = count($normalInstitutes);
$totalResultCount = $sponsoredResultCount +  $normalResultCount;

$final_modified_array = array();
//echo "normalcount_is".$normalResultCount."sponsered_".$sponsoredResultCount;
if($sponsoredResultCount>0) { 
        if($normalResultCount>=$sponsoredResultCount) {
	$chunk_normal_institutes = intval($normalResultCount/$sponsoredResultCount);
	$general_chunked_array = array_chunk($normalInstitutes,$chunk_normal_institutes,true);
	$sponsered_chunk_array = array_chunk($sponsoredInstitutes, 1,true);
	$i=0; 
	foreach ($general_chunked_array as $chunk) {
		foreach ($sponsered_chunk_array[$i] as $key=>$value) {
			$final_modified_array[$key] = $value;
		}
		
		foreach ($chunk as $key=>$val) {
			$final_modified_array[$key] = $val;
		} 
		
		$i++;
	}
        } else {
		$final_modified_array = $sponsoredInstitutes+$normalInstitutes;
	} 

} else {
	$final_modified_array = $normalInstitutes;
}
?>
<?php if(!empty($_COOKIE['applied_courses_message'])) :
	setcookie('applied_courses_message','',time() - 36000,'/',COOKIEDOMAIN);	
?>
<div class="apply_box">
Thank you for your interest. You will be receiving E-brochure of the selected institute(s) in your mailbox shortly. 	
</div>
<?php endif;?>
<ul>
    	
    <?php       
                $row_count = 1; 
		$result_type= 'normal';
		foreach ($final_modified_array as $institute_id=>$courses):
			$other_courses = array();
    			foreach ($courses as $course) {
    				$courseDocument = $course;
    				break;
    			}  
			foreach($courses as $course_id=>$course) {
				if($course_id != $courseDocument->getId()) {

					$other_courses[$course_id]['name'] = $course->getName();
					$other_courses[$course_id]['id'] = $course->getId();
					$other_courses[$course_id]['similarname'] = $courseDocument->getName();
					//$additionalURLParams = "?city=".$course->getLocation()->getCity()->getId()."&locality=".$course->getLocation()->getLocality()->getId();
					$additionalURLParams = "?city=".$course->getLocation()->getCity()->getId()."&locality=".$course->getLocation()->getLocality()->getId()."&result_clicked_id=".$course->getId()."&result_clicked_type=course&result_clicked_row_count=".$row_count."&result_search_id=".$result_search_id."&page_id=".$page_id."&result_type=".$result_type."&source=mobile";

					$course->setAdditionalURLParams($additionalURLParams);
					$other_courses[$course_id]['url'] = $course->getURL();
					$other_courses[$course_id]['courseduration'] = $course->getDuration()->getDisplayValue();
					$other_courses[$course_id]['coursetype'] = $course->getCourseType();
					$other_courses[$course_id]['courselevel'] = $course->getCourseLevel();
					$other_courses[$course_id]['coursefeesunit'] = $course->getFees()->getCurrency();
					$other_courses[$course_id]['coursefeesvalue'] = $course->getFees()->getValue();
					$other_courses[$course_id]['ispaid'] = $course->isPaid();
					$other_courses[$course_id]['addReqInfoVars'] = $search_lib_object->makeRequestInfo($courses);
					$other_courses[$course_id]['current_url'] = $search_lib_object->makeSearchURL($current_url,$searchurlparams);
					$other_courses[$course_id]['referral_url'] = $referral_url;

				}
			}	
    ?>
	<li <?php if(in_array($courseDocument->getInstitute()->getId(), $sponsoredInstituteIds)) : $result_type='sponsored';?>class="highlighted"<?php endif;?>>
		<?php if(in_array($courseDocument->getInstitute()->getId(), $sponsoredInstituteIds)):?><div class="sponsored-txt">Sponsored Results</div><?php endif;?>
		<div class="figure2">
			<?php
				if($courseDocument->getInstitute()->getMainHeaderImage() && $courseDocument->getInstitute()->getMainHeaderImage()->getThumbURL()){
					echo '<img width="74" height="62" src="'.$courseDocument->getInstitute()->getMainHeaderImage()->getThumbURL().'"/>';
				}else{
					echo '<img src ="/public/images/avatar.gif" width="74" height="62"/>';
				}
			?>
		</div>
		<div class="details2" style="padding-top: 0">
			<h2>
				<?php
					$additionalURLParams = "?city=".$courseDocument->getLocation()->getCity()->getId()."&locality=".$courseDocument->getLocation()->getLocality()->getId()."&result_clicked_id=".$courseDocument->getInstitute()->getId()."&result_clicked_type=institute&result_clicked_row_count=".$row_count."&result_search_id=".$result_search_id."&page_id=".$page_id."&result_type=".$result_type."&source=mobile";
					$courseDocument->setAdditionalURLParams($additionalURLParams);
					$courseDocument->getInstitute()->setAdditionalURLParams($additionalURLParams);
				?>
				<a rel="nofollow" href="<?php echo $courseDocument->getURL(); ?>"><?php echo htmlspecialchars_decode($courseDocument->getInstitute()->getName()); ?>,</a>
				<span><?php echo $courseDocument->getLocation()->getLocality()->getName() ? $courseDocument->getLocation()->getLocality()->getName().", ":"";?><?php echo $courseDocument->getLocation()->getCity()->getName();?></span>
			</h2>
			<?php if($courseDocument->getInstitute()->getAlumniRating()): ?>
			<div class="alumini-rating">
				<label>Alumni Rating:</label> 
				<span>
				<?php
				$i = 1;
				while($i <= $courseDocument->getInstitute()->getAlumniRating()){
			     ?>
				<img src="/public/images/gold_star_big.gif" alt="" />
				<?php
				$i++;
				}
				?>
				</span> 
				<span><?php echo $courseDocument->getInstitute()->getAlumniRating();?>/5</span>
			</div>
			<?php endif;?>
			<?php
				$additionalURLParams = "?city=".$courseDocument->getLocation()->getCity()->getId()."&locality=".$courseDocument->getLocation()->getLocality()->getId()."&result_clicked_id=".$courseDocument->getId()."&result_clicked_type=course&result_clicked_row_count=".$row_count."&result_search_id=".$result_search_id."&page_id=".$page_id."&result_type=".$result_type."&source=mobile";
				$courseDocument->setAdditionalURLParams($additionalURLParams);
			?>
			<strong>
				<a href="<?php echo $courseDocument->getURL(); ?>"><?php echo ($courseDocument->getCourseTitle()); ?></a>
			</strong>
			<p>
				- <?php echo $courseDocument->getDuration()->getDisplayValue() ? $courseDocument->getDuration()->getDisplayValue() : ""; ?>
				<?php echo ( $courseDocument->getDuration()->getDisplayValue() && $courseDocument->getCourseType() ) ? ", " . $courseDocument->getCourseType() : ( $courseDocument->getCourseType() ? $courseDocument->getCourseType() : "" ); ?>
				<?php echo ( $courseDocument->getCourseLevel() && ($courseDocument->getCourseType() || $courseDocument->getDuration()->getDisplayValue())) ? ", ". $courseDocument->getCourseLevel() : ( $courseDocument->getCourseLevel() ? $courseDocument->getCourseLevel() : "");?>
				<?php if(count($other_courses)>0):
				$title ="";
				if(count($other_courses)>1) {
					$title = count($other_courses)." similar courses";
				} else {
					$title = count($other_courses)." similar course";
				}
				?> 
				<form action="/msearch/Msearch/showSimilarCourses" method="post">
				<input type="hidden" name="to_send_data" value="<?php echo base64_encode(gzcompress(json_encode($other_courses)));?>"/>
				<input type="submit" class="view-more" style="font-size: 100%" value="<?php echo $title;?>"/>
				</form>
				<?php endif; ?> 
				<br /><?php if($courseDocument->getFees()->getValue()):?>Fees: <?php echo $courseDocument->getFees();?> <br /> <?php endif;?>
				<?php
				$approvalsAndAffiliations = array();
				$approvals = $courseDocument->getApprovals();
				foreach($approvals as $approval) {
					$outString = "";
					if(in_array($approval,array('aicte','ugc','dec'))){
						$outString = "<span style='cursor:default;' >".langStr('approval_'.$approval)."</span>";
					}else{
						$outString = "<span>".langStr('approval_'.$approval)."</span>";
					}
					$approvalsAndAffiliations[] = $outString;
				}
				$affiliations = $courseDocument->getAffiliations();
				foreach($affiliations as $affiliation) {
					$approvalsAndAffiliations[] = langStr('affiliation_'.$affiliation[0],$affiliation[1]);  
				}
				echo implode(', ',$approvalsAndAffiliations);
				?>
			<div class="spacer5 clearFix"></div>
			<?php if($courseDocument->isPaid()):?>
			<?php
			$addReqInfoVars = $search_lib_object->makeRequestInfo($courses);
			?>
			<?php if(in_array($courseDocument->getId(),$applied_courses)):?>
			<div class="apply_confirmation">
			E-Brochure successfully mailed
			</div>
			<?php else:?>
			<form method="post" action="/muser/MobileUser/renderRequestEbrouchre">
				<input type="hidden" name="from_where" value="SEARCH"/>
				<input type="hidden" name="selected_course" value="<?php echo $courseDocument->getId();?>"/>
		    		<input type="hidden" value="<?php echo $addReqInfoVars;?>" name="courseAttr"/>
		    		<input type="hidden" value="<?php echo url_base64_encode(urldecode($search_lib_object->makeSearchURL($current_url,$searchurlparams)));?>" name="current_url"/> 
		    		<input type="hidden" value="<?php echo url_base64_encode($referral_url);?>" name="referral_url"/>
		    		<input class="brochure-btn" type="submit" value="Request E-brochure" />
                    	</form>
			<?php endif;?>
			<?php endif;?>
		</div>
	</li>
	<?php 
		$result_type= 'normal';
		$row_count++;
		endforeach;
	?>
</ul>
<div class="nxt-prev-btn">
		<?php if($total_results>0 && $total_results<=$result_limit) echo "No more Results";?>
		<?php if($searchurlparams['start']>=$result_limit):
			$temp_url = $searchurlparams;
			$temp_url['start'] = $temp_url['start'] - $result_limit;
		?>
		<a href="<?php echo urldecode($search_lib_object->makeSearchURL(SHIKSHA_HOME.'/search/index',$temp_url));?>"><img src="/public/mobile/images/left-arrow.gif" /> Previous <?php echo $result_limit;?></a> 
		<?php endif;?>
		<?php if($searchurlparams['start']>=0 && $searchurlparams['start'] < $max_offset && $total_results>$result_limit):
				$temp_url = $searchurlparams;
				$temp_url['start'] = $temp_url['start'] + $result_limit;
				
		?>
		<a href="<?php echo urldecode($search_lib_object->makeSearchURL(SHIKSHA_HOME.'/search/index',$temp_url));?>">Next <?php echo $next_result;?> <img src="/public/mobile/images/right-arrow.gif" /> </a>
		<?php endif;?>
</div>
