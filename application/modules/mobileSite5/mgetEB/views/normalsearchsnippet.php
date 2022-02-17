<?php
//global $shiksha_site_current_refferal;
//$shiksha_site_current_url = "http://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
$shiksha_site_current_url = SHIKSHA_HOME."/getEB/showSearchHome";


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

<?php
if($normalResultCount<=1){
	echo "<script>if(!document.getElementById('showSelectedFilters')){ $('#showFilterButton').hide();}</script>";    
}
?>

<ul>
    	
    <?php       
                $row_count = 1; 
		$result_type= 'normal';
		foreach ($final_modified_array as $institute_id=>$courses):
			$other_courses = array();
			$addReqInfoVars = array();
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
					$other_courses[$course_id]['current_url'] = $shiksha_site_current_url;
					$other_courses[$course_id]['referral_url'] = $referral_url;
					$other_courses[$course_id]['affiliations'] = $course->getAffiliations();
					$other_courses[$course_id]['approvals'] = $course->getApprovals();
					
					$institute_id = $courseDocument->getInstitute()->getId();
					$other_courses[$course_id]['instituteId'] = $institute_id;

					if($course->isPaid()=="TRUE" || true){
						$arr['isMultiLocation'.$institute_id] = $course->isCourseMultilocation();
						foreach($course->getAllLocations() as $course_location){
								$locality_name = $course_location->getLocality()->getName();
								if($locality_name !='') $locality_name = ' |'.$course_location->getLocality()->getName();
								$addReqInfoVars[$course->getName().' | '.$course_location->getCity()->getName().$locality_name]=$course->getId()."*".html_escape($courseDocument->getInstitute()->getName())."*".$course->getUrl()."*".$course_location->getCity()->getId()."*".$course_location->getLocality()->getId();
								if($arr['isMultiLocation'.$institute_id]=='false'){
									$arr['rebLocallityId'.$institute_id] = $course_location->getLocality()->getId();
									$arr['rebCityId'.$institute_id] = $course_location->getCity()->getId();
								}else{
									$arr['rebLocallityId'.$institute_id] = '';
									$arr['rebCityId'.$institute_id] = '';
						}
						}		
					}
					//$addReqInfoVars=serialize($addReqInfoVars);
					//$addReqInfoVars=base64_encode($addReqInfoVars);
					//$other_courses[$course_id]['addReqInfoVars'] = $addReqInfoVars;
					$other_courses[$course_id]['isMultiLocation'] = $arr['isMultiLocation'.$institute_id];
					$other_courses[$course_id]['rebLocallityId'] = $arr['rebLocallityId'.$institute_id];
					$other_courses[$course_id]['rebCityId'] = $arr['rebCityId'.$institute_id];
				}
			}
			
			foreach($courses as $course_id=>$course) {
				$addReqInfoVarsFinal = array();
				if($course_id != $courseDocument->getId()) {
					$addReqInfoVarsFinal=serialize($addReqInfoVars);
					$addReqInfoVarsFinal=base64_encode($addReqInfoVarsFinal);
					$other_courses[$course_id]['addReqInfoVars'] = $addReqInfoVarsFinal;
					break;
				}
			}
    ?>
    
	<section class="content-wrap2 <?php if($row_count==1 && $ajaxRequest!='true'){ echo "";} ?> clearfix" >

	
		<!-- Sort BY Block Starts -->
		<?php if($row_count==1 && $ajaxRequest!='true'){ ?>
		<div class="sorting" id="sortDiv">
			<?php if($searchurlparams['sort_type'] == "popular"):
				$searchurlparams['sort_type'] = "best";
			?>
			<strong>Sort by: </strong>
			<a href="<?php echo urldecode($search_lib_object->makeSearchURL($current_url,$searchurlparams));?>">Best Match</a> | Popular
			<?php else :
			$searchurlparams['sort_type'] = "popular";
			?>
			<strong>Sort by: </strong>
			Best Match | <a href="<?php echo urldecode($search_lib_object->makeSearchURL($current_url,$searchurlparams));?>">Popular</a>
			<?php endif;?>
		</div>
		<?php } ?>
		<!-- Sort BY Block Ends -->
	
	
	<?php
		$additionalURLParams = "?city=".$courseDocument->getLocation()->getCity()->getId()."&locality=".$courseDocument->getLocation()->getLocality()->getId()."&result_clicked_id=".$courseDocument->getInstitute()->getId()."&result_clicked_type=institute&result_clicked_row_count=".$row_count."&result_search_id=".$result_search_id."&page_id=".$page_id."&result_type=".$result_type."&source=mobile";
		$courseDocument->setAdditionalURLParams($additionalURLParams);
		$courseDocument->getInstitute()->setAdditionalURLParams($additionalURLParams);
		$instituteURL = getSeoUrl($courseDocument->getInstitute()->getId(),'institute');
	?>
	<article <?php if(in_array($courseDocument->getInstitute()->getId(), $sponsoredInstituteIds)) : $result_type='sponsored';?>style="background:#fffddc !important"<?php endif;?> class="req-bro-box clearfix"  >
		<?php if(in_array($courseDocument->getInstitute()->getId(), $sponsoredInstituteIds)):?><div style="text-align:right; color:#898884; margin-bottom:5px;">Sponsored Results</div><?php endif;?>

			<div class="details" style="cursor: pointer;" onclick="setCookie('currentCourse','<?php echo $courseDocument->getId();?>','','');window.location='<?php echo $instituteURL; ?>';"> 

				<h4 title="<?php echo htmlspecialchars_decode($courseDocument->getInstitute()->getName()).' , '?><?php echo $courseDocument->getLocation()->getLocality()->getName() ? $courseDocument->getLocation()->getLocality()->getName().", ":"";?><?php echo $courseDocument->getLocation()->getCity()->getName();?>">				
					<?php echo htmlspecialchars_decode($courseDocument->getInstitute()->getName()); ?>,
					<span><?php echo $courseDocument->getLocation()->getLocality()->getName() ? $courseDocument->getLocation()->getLocality()->getName().", ":"";?><?php echo $courseDocument->getLocation()->getCity()->getName();?></span>
				</h4>
						
			</div>

			
			<!-- Display Other courses link -->
			<?php if(count($other_courses)>0 && false):
			$title ="";
			if(count($other_courses)>1) {
				$title = count($other_courses)." similar courses in this Institute.";
			} else {
				$title = count($other_courses)." similar course in this Institute.";
			}
			?>

			<section class="other-opt" style="margin-top: 10px; cursor: pointer;">
				<form action="/msearch5/Msearch/showSimilarCourses" method="post">
					<input type="hidden" name="to_send_data" value="<?php echo base64_encode(gzcompress(json_encode($other_courses)));?>"/>
					<a href="javascript:void(0);" onclick="$(this).closest('form').submit();"><?php echo $title;?>
						<i class="icon-arrow-r2"></i>
					</a>
				</form>
			</section>			
			<?php endif; ?>

			
			<div id= "thanksMsg<?php echo $courseDocument->getId();?>" class="thnx-msg" <?php if(!in_array($courseDocument->getId(),$applied_courses)){?>style="display:none"<?php } ?>>
				<i class="icon-tick"></i>
				<p>Thank you for your request. You will be receiving E-brochure of the selected institute(s) in your mailbox shortly.</p>
			</div>

    
	<!-- Display E-Brochure link if Course is Paid Start -->
		<?php
			$addReqInfoVars = array();
			foreach($courses as $c){
				if($c->isPaid()=="TRUE" || true){
					$arr['isMultiLocation'.$courseDocument->getInstitute()->getId()] = $c->isCourseMultilocation();
					foreach($c->getAllLocations() as $course_location){
							$locality_name = $course_location->getLocality()->getName();
							if($locality_name !='') $locality_name = ' |'.$course_location->getLocality()->getName();
							$addReqInfoVars[$c->getName().' | '.$course_location->getCity()->getName().$locality_name]=$c->getId()."*".html_escape($courseDocument->getInstitute()->getName())."*".$c->getUrl()."*".$course_location->getCity()->getId()."*".$course_location->getLocality()->getId();
							if($arr['isMultiLocation'.$courseDocument->getInstitute()->getId()]=='false'){
								$arr['rebLocallityId'.$courseDocument->getInstitute()->getId()] = $course_location->getLocality()->getId();
								$arr['rebCityId'.$courseDocument->getInstitute()->getId()] = $course_location->getCity()->getId();
							}else{
								$arr['rebLocallityId'.$courseDocument->getInstitute()->getId()] = '';
								$arr['rebCityId'.$courseDocument->getInstitute()->getId()] = '';
							}
					}		
				}
			}
			$addReqInfoVars=serialize($addReqInfoVars);
			$addReqInfoVars=base64_encode($addReqInfoVars);
			$pageName = 'SEARCH_PAGE';
			$from_where = 'MOBILE5_GETEB_SEARCH_PAGE';
		?>

		<?php if($courseDocument->isPaid()=="TRUE" || true){?>
		    <p>
			<?php //if(in_array($courseDocument->getId(),$applied_courses)){?>
			<!--<a class="button blue small disabled" href="javascript:void(0);" id="request_e_brochure<?=$courseDocument->getId();?>"><i class="icon-pencil" aria-hidden="true"></i><span>Request Brochure</span></a>-->
			<?php //}else{ ?>
			<a  class="button blue small" href="javascript:void(0);" id="request_e_brochure<?=$courseDocument->getId();?>" onClick="setCookie('getEBCourseId','<?php echo $courseDocument->getId();?>'); setCookie('getEBInstituteName','<?php echo base64_encode($courseDocument->getInstitute()->getName()); ?>'); trackReqEbrochureClick('<?php echo $courseDocument->getId();?>'); $('#brochureForm<?php echo $courseDocument->getId();?>').submit(); "><i class="icon-pencil" aria-hidden="true"></i><span>Request Brochure</span></a>
			<?php //} ?>
		    </p>

		<form action="/muser5/MobileUser/renderRequestEbrouchre" method="post" id="brochureForm<?=$courseDocument->getId();?>">
			<input type="hidden" name="courseAttr" value = "<?php echo $addReqInfoVars; ?>" />
			<input type="hidden" name="current_url" value = "<?php echo url_base64_encode($shiksha_site_current_url); ?>" /> 
			<input type="hidden" name="referral_url" value = "<?php echo url_base64_encode($shiksha_site_current_refferal); ?>" />
			<input type="hidden" name="selected_course" value = "<?php echo $courseDocument->getId(); ?>" />
			<input type="hidden" name="list" value="<?php echo $courseDocument->getId(); ?>" />
			<input type="hidden" name="institute_id" value="<?php echo $courseDocument->getInstitute()->getId(); ?>" />
			<input type="hidden" name="pageName" value="<?php echo $pageName;?>" />
			<input type="hidden" name="from_where" value="<?php echo $from_where;?>" />
			<input type="hidden" name="getEBCall" value="1" />
		</form>
		<?php }?>
	<!-- Display E-Brochure link if COurse is Paid Ends -->

	</article>
				    
	</section>
	
	<?php 
		$result_type= 'normal';
		$row_count++;
		endforeach;
	?>
</ul>
