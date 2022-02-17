<?php
$searchType = $search_type; //controller variable
$normalInstitutes = $normal_institutes; //controller variable
$sponsoredInstituteIds = $sponsored_institute_ids; //controller variable
$final_modified_array = array();
$final_modified_array = $normalInstitutes;
$all_courses = array();
$top_course_id = "";
$course_orders_array = array();
$top_cat_id = "";

foreach ($final_modified_array as $category_id=>$courses) {
	$temp_object = reset($courses);
	$course_orders_array[$category_id] = $temp_object->getOrder();
}

asort($course_orders_array);
foreach($course_orders_array as $cat_id=>$val) {
	$top_cat_id = $cat_id;
	break;	
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
    	$courseDocument = reset($final_modified_array[$top_cat_id]);
		$top_course_id = $courseDocument->getId();
		$row_count = 1; 
		$result_type= 'normal'; 
    ?>
	<li <?php if(in_array($courseDocument->getInstitute()->getId(), $sponsoredInstituteIds)): $result_type='sponsored';?>class="highlighted"<?php endif;?>>
		<?php if(in_array($courseDocument->getInstitute()->getId(), $sponsoredInstituteIds)):?><div class="sponsored-txt">Sponsored Results</div><?php endif;?>
		<div class="figure2">
			<?php
				if($courseDocument->getInstitute()->getMainHeaderImage() && $courseDocument->getInstitute()->getMainHeaderImage()->getThumbURL()){
					echo '<img width="74" height="62" src="'.$courseDocument->getInstitute()->getMainHeaderImage()->getThumbURL().'"/>';
				}else{
					echo '<img src="/public/mobile/images/inst-img.jpg" />';
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
			</p>
			<div class="spacer5 clearFix"></div>
			<?php if($courseDocument->isPaid()):?>
			<?php 
			$courses_to_send = $search_lib_object->getAllCourses($normalInstitutes);
			$addReqInfoVars = $search_lib_object->makeRequestInfo($courses_to_send);
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
</ul>

<?php 
unset($final_modified_array[$top_cat_id][$top_course_id]);
$final_category_array = array();
if(count($final_modified_array[$top_cat_id]) >0 ) {
	$final_category_array[$top_cat_id] = $final_modified_array[$top_cat_id];
}
unset($final_modified_array[$top_cat_id]);
foreach ($final_modified_array as $key=>$value) {
	$final_category_array[$key] = $value;
}
?>
		<div class="search-list-cont">
			<?php foreach ($final_category_array as $key=>$courses):
				  $temp_object = reset($courses); 
				  $course_category = $temp_object->getParentCategory();	
				  foreach ($course_category as $id=>$cat_object) {
				  	if($id == $key) {
				  		$course_category = $cat_object;
				  		break;
				  	}
				  }
				  if(is_object($course_category)) {
				  	$cat_name = $course_category->getName();
				  }	else {
				  	$cat_name = "Others";
				  }
				  $count_courses = count($courses);	
			?>
			<h6>
				Course in <?php echo $cat_name;?> - <a><?php echo getPlural(count($courses), 'course');?></a>
			</h6>
			<ol>
			<?php
			    $start = 0 ; 
				foreach ($courses as $course_object):
					if($start>=5) break;
				?>
				<li>
					<p>
				<?php
				$additionalURLParams = "?city=".$course_object->getLocation()->getCity()->getId()."&locality=".$course_object->getLocation()->getLocality()->getId()."&result_clicked_id=".$course_object->getId()."&result_clicked_type=course&result_clicked_row_count=".$row_count."&result_search_id=".$result_search_id."&page_id=".$page_id."&result_type=".$result_type."&source=mobile";

				//$additionalURLParams = "?city=".$course_object->getLocation()->getCity()->getId()."&locality=".$course_object->getLocation()->getLocality()->getId();
				$course_object->setAdditionalURLParams($additionalURLParams);
				?>
						<a href="<?php echo $course_object->getURL();?>"><?php echo $course_object->getName();?></a>, 
				<span> <?php echo $course_object->getDuration()->getDisplayValue() ? $course_object->getDuration()->getDisplayValue() : ""; ?>
				<?php echo ( $course_object->getDuration()->getDisplayValue() && $course_object->getCourseType() ) ? ", " . $course_object->getCourseType() : ( $course_object->getCourseType() ? $course_object->getCourseType() : "" ); ?>
				<?php echo ( $course_object->getCourseLevel() && ($course_object->getCourseType() || $course_object->getDuration()->getDisplayValue())) ? ", ". $course_object->getCourseLevel() : ( $course_object->getCourseLevel() ? $course_object->getCourseLevel() : "");?>
				</span>
					</p> <?php if($course_object->getFees()->getValue()):?>Fees: <?php echo $courseDocument->getFees();?><?php endif;?>
					<div class="spacer8 clearFix"></div>
					<?php if($course_object->isPaid()):?> 
					<?php 
					$courses_to_send = $search_lib_object->getAllCourses($normalInstitutes);
					$addReqInfoVars = $search_lib_object->makeRequestInfo($courses_to_send);
					?>
					<?php if(in_array($course_object->getId(),$applied_courses)):?>
					<div class="apply_confirmation">
					E-Brochure successfully mailed
					</div>
					<?php else:?>
					<form method="post" action="/muser/MobileUser/renderRequestEbrouchre">
						<input type="hidden" name="from_where" value="SEARCH"/>
						<input type="hidden" name="selected_course" value="<?php echo $course_object->getId();?>"/>
				    		<input type="hidden" value="<?php echo $addReqInfoVars;?>" name="courseAttr"/>
				    		<input type="hidden" value="<?php echo url_base64_encode(urldecode($search_lib_object->makeSearchURL($current_url,$searchurlparams)));?>" name="current_url"/> 
				    		<input type="hidden" value="<?php echo url_base64_encode($referral_url);?>" name="referral_url"/>
				    		<input class="brochure-btn" type="submit" value="Request E-brochure" />
				    	</form>
					<?php endif;?>
					<?php endif;?>
				</li>
				<?php $start++ ;endforeach;?>
				<?php if($count_courses>5):?>
				<?php 
					$to_send_array = array();
					foreach($courses as $key1=>$value1) {
						$to_send_array[$key][$key1]['name'] = $value1->getName();
						$to_send_array[$key][$key1]['id'] = $key1; 
						$to_send_array[$key][$key1]['name'] = $value1->getName();
						$additionalURLParams = "?city=".$value1->getLocation()->getCity()->getId()."&locality=".$value1->getLocation()->getLocality()->getId()."&result_clicked_id=".$value1->getId()."&result_clicked_type=course&result_clicked_row_count=".$row_count."&result_search_id=".$result_search_id."&page_id=".$page_id."&result_type=".$result_type."&source=mobile";
						//$additionalURLParams = "?city=".$value1->getLocation()->getCity()->getId()."&locality=".$value1->getLocation()->getLocality()->getId();
						$value1->setAdditionalURLParams($additionalURLParams);
						$to_send_array[$key][$key1]['url'] = $value1->getURL();
						$to_send_array[$key][$key1]['courseduration'] = $value1->getDuration()->getDisplayValue();
						$to_send_array[$key][$key1]['coursetype'] = $value1->getCourseType();
						$to_send_array[$key][$key1]['courselevel'] = $value1->getCourseLevel();
						$to_send_array[$key][$key1]['coursefeesunit'] = $value1->getFees()->getCurrency();
						$to_send_array[$key][$key1]['coursefeesvalue'] = $value1->getFees()->getValue();
						$to_send_array[$key][$key1]['cat_name'] = $cat_name;
						$to_send_array[$key][$key1]['ispaid'] = $value1->isPaid();
						$courses_to_send = $search_lib_object->getAllCourses($normalInstitutes);
						$addReqInfoVars = $search_lib_object->makeRequestInfo($courses_to_send);
						$to_send_array[$key][$key1]['addReqInfoVars'] = $addReqInfoVars;
						$to_send_array[$key][$key1]['current_url'] = urldecode($search_lib_object->makeSearchURL($current_url,$searchurlparams));
						$to_send_array[$key][$key1]['referral_url'] = $referral_url;
					}
				?>
				<li>
				<form action="/msearch/Msearch/showMoreCourses" method="post">
				<input type="hidden" name="to_send_data" value="<?php echo base64_encode(json_encode($to_send_array));?>"/>
				<input type="submit" class="view-more" style="font-size: 100%" value="View more <?php echo $cat_name?> courses"/>
				</form>
				</li>
				<?php endif;?>
			</ol>
			<?php endforeach;?>
		</div>
