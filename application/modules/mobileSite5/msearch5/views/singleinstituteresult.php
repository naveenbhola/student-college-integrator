
<?php
global $shiksha_site_current_refferal;
$shiksha_site_current_url = "http://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];

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


<section class="content-wrap2 clearfix" >

    <?php 
		$courseDocument = reset($final_modified_array[$top_cat_id]);
		$top_course_id = $courseDocument->getId();
		$row_count = 1; 
		$result_type= 'normal'; 

		$additionalURLParams = "?city=".$courseDocument->getLocation()->getCity()->getId()."&locality=".$courseDocument->getLocation()->getLocality()->getId()."&result_clicked_id=".$courseDocument->getInstitute()->getId()."&result_clicked_type=institute&result_clicked_row_count=".$row_count."&result_search_id=".$result_search_id."&page_id=".$page_id."&result_type=".$result_type."&source=mobile";
		$courseDocument->setAdditionalURLParams($additionalURLParams);
		$courseDocument->getInstitute()->setAdditionalURLParams($additionalURLParams);
	?>    
	<article <?php if(in_array($courseDocument->getInstitute()->getId(), $sponsoredInstituteIds)) : $result_type='sponsored';?>style="background:#fffddc !important"<?php endif;?> class="req-bro-box clearfix"  >
		<?php if(in_array($courseDocument->getInstitute()->getId(), $sponsoredInstituteIds)):?><div style="text-align:right; color:#898884; margin-bottom:5px;">Sponsored Results</div><?php endif;?>

			<div class="details" style="cursor: pointer;" > 

				<a href="<?php echo $courseDocument->getURL(); ?>" onclick="setCookie('currentCourse','<?php echo $courseDocument->getId();?>','','');">

				<h4>				
					<?php echo htmlspecialchars_decode($courseDocument->getInstitute()->getName()); ?>,
					<span><?php echo $courseDocument->getLocation()->getLocality()->getName() ? $courseDocument->getLocation()->getLocality()->getName().", ":"";?><?php echo $courseDocument->getLocation()->getCity()->getName();?></span>
				</h4>

				<ul style="color: #000;">
				
					<?php
						//$additionalURLParams = "?city=".$courseDocument->getLocation()->getCity()->getId()."&locality=".$courseDocument->getLocation()->getLocality()->getId()."&result_clicked_id=".$courseDocument->getId()."&result_clicked_type=course&result_clicked_row_count=".$row_count."&result_search_id=".$result_search_id."&page_id=".$page_id."&result_type=".$result_type."&source=mobile";
						//$courseDocument->setAdditionalURLParams($additionalURLParams);
					?>				
					<li><?php echo ($courseDocument->getCourseTitle()); ?></li>
				
					<?php   $affiliations = $courseDocument->getAffiliations();
						foreach($affiliations as $affiliation) {
							$Affiliations[] = langStr('affiliation_'.$affiliation[0],$affiliation[1]);
						}
						if($Affiliations[0]){
							echo "<li><i class='icon-medal'></i><p style='padding-top:1px'>";
							echo "<label>Affiliation: </label>";
							echo $Affiliations[0];
							echo "</p></li>";
						}
						unset($Affiliations);
					?>
					
					<?php if($courseDocument->getFees()->getValue()){ ?>
						<li><i class="icon-rupee"></i><p><label>Fees:</label> <?=$courseDocument->getFees()?></p></li>
					<?php } ?>


					<?php
					$exams = $courseDocument->getEligibilityExams();
					if(count($exams) > 0){
						if($courseDocument->getInstitute()->getInstituteType() == "Test_Preparatory_Institute"){
						?>
							<li><i class="icon-eligible"></i><p><label>Exams Prepared for: </label><?php
						}else{
						?>
							<li><i class="icon-eligible"></i><p><label>Eligibility: </label><?php
						}
						$examAcronyms = array();
						foreach($exams as $exam) {
							$examAcronyms[] = $exam->getAcronym();
						}
						echo implode(', ',$examAcronyms); ?> </p></li>
					<?php } ?>


				</ul>
				</a>
			</div>

			<div id= "thanksMsg<?php echo $courseDocument->getId();?>" class="thnx-msg" <?php if(!in_array($courseDocument->getId(),$applied_courses)){?>style="display:none"<?php } ?>>
				<i class="icon-tick"></i>
				<p>Thank you for your request. You will be receiving E-brochure of the selected institute(s) in your mailbox shortly.</p>
			</div>

	<!-- Display E-Brochure link if COurse is Paid Start -->
		<?php
			$addReqInfoVars = array();
			$courses_to_send = $search_lib_object->getAllCourses($normalInstitutes);
			foreach($courses_to_send as $c){
				if(checkEBrochureFunctionality($c)){
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
			$from_where = 'MOBILE5_SEARCH_PAGE';
		?>

		<?php if(checkEBrochureFunctionality($courseDocument)){?>
		    <p>
			<?php if(in_array($courseDocument->getId(),$applied_courses)){?>
			<a class="button blue small disabled" href="javascript:void(0);" id="request_e_brochure<?=$courseDocument->getId();?>"><i class="icon-pencil" aria-hidden="true"></i><span>Request E-Brochure</span></a>
			<?php }else{ ?>
			<a  class="button blue small" href="javascript:void(0);" id="request_e_brochure<?=$courseDocument->getId();?>" onClick="trackReqEbrochureClick('<?php echo $courseDocument->getId();?>');validateRequestEBrochureFormData('<?=$courseDocument->getInstitute()->getId();?>','<?=$arr['rebLocallityId'.$courseDocument->getInstitute()->getId()];?>','<?=$arr['rebCityId'.$courseDocument->getInstitute()->getId()];?>','<?=$arr['isMultiLocation'.$courseDocument->getInstitute()->getId()];?>','<?php echo $courseDocument->getId();?>','','<?php echo $trackingPageKeyId;?>');"><i class="icon-pencil" aria-hidden="true"></i><span>Request E-Brochure</span></a>
			<?php } ?>
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
			<input type="hidden" name="tracking_keyid" id="tracking_keyid<?=$courseDocument->getId();?>" value="">
		</form>
		<?php }?>
	<!-- Display E-Brochure link if COurse is Paid Ends -->
			    
	</article>


<?php 
$i=0;$j=0;
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

		<!-- Display Data for Each Category : START -->
		<div id="courseTabDesc" class="inst-detail-list" data-enhance="false" style="margin-top: 20px;">
		<?php foreach ($final_category_array as $key=>$courses):
			if($i>0){
				$hideDivStyling = "display:none";
				$classIcon="icon-arrow-up";
			}
			else{
				$classIcon="icon-arrow-dwn";
			//	$openClass = "icon-arrow-dwn";
			}

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
				$cat_id = $course_category->getId();
			  }	else {
				$cat_name = "Others";
				$cat_id = 1;
			  }
			  $count_courses = count($courses);	
		?>
			
			<!-- Display the Heading for Category courses -->
			
		<dt id="course_count_<?=$i?>" style="cursor: pointer;" >
			   <a onClick="setAccordion('<?=$i?>');" href="javascript:void(0);">
			   <h2>
			   <p >Courses in <?php echo $cat_name;?> - <?php echo getPlural(count($courses), 'course');?></p>
			   </h2>
			   <i id="desc<?=$i;?>" class="<?=$classIcon;?>"></i>
			   </a>
		</dt>
		
			<!-- Display the Heading for Category courses : END -->


			<dd style="height:auto;<?=$hideDivStyling?>" id="desc_count_<?=$i?>">
            
			<!-- Display 5 courses of the Category : START -->
			<?php 
			$start = 0 ; 
			foreach ($courses as $course_object):
				if($start>=5) break;			
			?>	
				<?php
				$additionalURLParams = "?city=".$course_object->getLocation()->getCity()->getId()."&locality=".$course_object->getLocation()->getLocality()->getId()."&result_clicked_id=".$course_object->getId()."&result_clicked_type=course&result_clicked_row_count=".$row_count."&result_search_id=".$result_search_id."&page_id=".$page_id."&result_type=".$result_type."&source=mobile";
				$course_object->setAdditionalURLParams($additionalURLParams);
				?>
				<div class="similar-section">
				<h5 onclick="window.location.href='<?php echo $course_object->getURL();?>'"><?=$course_object->getName();?></h5>
				
				
				<div style="height:auto;" onclick="window.location.href='<?php echo $course_object->getURL();?>'">
				 <div class="notify-details" style="cursor: pointer;" >
					<p>
					    <?php
						echo $course_object->getDuration()->getDisplayValue() ? $course_object->getDuration()->getDisplayValue() : "";
						echo ( $course_object->getDuration()->getDisplayValue() && $course_object->getCourseType() ) ? ", " . $course_object->getCourseType() : ( $course_object->getCourseType() ? $course_object->getCourseType() : "" );
						echo ( $course_object->getCourseLevel() && ($course_object->getCourseType() || $course_object->getDuration()->getDisplayValue())) ? ", ". $course_object->getCourseLevel() : ( $course_object->getCourseLevel() ? $course_object->getCourseLevel() : "");
					    ?>			 
					</p>
			
					<em>

 
					    <?php
						      $approvalsAndAffiliations = array();
						      $approvals = $course_object->getApprovals();
						      foreach($approvals as $approval) {
							      $approvalsAndAffiliations[] = langStr('approval_'.$approval);
						      }
						      $affiliations = $course_object->getAffiliations();
						      foreach($affiliations as $affiliation) {
							      $approvalsAndAffiliations[] = langStr('affiliation_'.$affiliation[0].'_detailed',$affiliation[1]);	
						      }
						      echo implode(', ',$approvalsAndAffiliations);
					    ?>
					</em>
					
					<?php if($course_object->getFees()->getValue() && $course_object->getFees()->getValue()!=''):?>
					<p>Fees: <?=$course_object->getFees()->getValue()?></p>
					<?php endif;?>
				
					<div id= "thanksMsg<?php echo $course_object->getId();?>" class="thnx-msg" <?php if(!in_array($course_object->getId(),$applied_courses)){?>style="display:none"<?php } ?>>
								<i class="icon-tick"></i>
								<p>Thank you for your request. You will be receiving E-brochure of the selected institute(s) in your mailbox shortly.</p>
					</div>
					
				    </div>
				    </div>
					<!-- Display E-Brochure link if Course is Paid Start -->
						<?php
							$addReqInfoVars = array();
							$courses_to_send = $search_lib_object->getAllCourses($normalInstitutes);
							foreach($courses_to_send as $c){
								if(checkEBrochureFunctionality($c)){
									$arr['isMultiLocation'.$course_object->getInstitute()->getId()] = $c->isCourseMultilocation();
									foreach($c->getAllLocations() as $course_location){
											$locality_name = $course_location->getLocality()->getName();
											if($locality_name !='') $locality_name = ' |'.$course_location->getLocality()->getName();
											$addReqInfoVars[$c->getName().' | '.$course_location->getCity()->getName().$locality_name]=$c->getId()."*".html_escape($course_object->getInstitute()->getName())."*".$c->getUrl()."*".$course_location->getCity()->getId()."*".$course_location->getLocality()->getId();
											if($arr['isMultiLocation'.$course_object->getInstitute()->getId()]=='false'){
												$arr['rebLocallityId'.$course_object->getInstitute()->getId()] = $course_location->getLocality()->getId();
												$arr['rebCityId'.$course_object->getInstitute()->getId()] = $course_location->getCity()->getId();
											}else{
												$arr['rebLocallityId'.$course_object->getInstitute()->getId()] = '';
												$arr['rebCityId'.$course_object->getInstitute()->getId()] = '';
											}
									}		
								}
							}
							$addReqInfoVars=serialize($addReqInfoVars);
							$addReqInfoVars=base64_encode($addReqInfoVars);
							$pageName = 'SEARCH_PAGE';
							$from_where = 'MOBILE5_SEARCH_PAGE';
						?>
				
						<?php if(checkEBrochureFunctionality($course_object)){?>
						    <p style="width:99%;">
							<?php if(in_array($course_object->getId(),$applied_courses)){?>
							<a class="button blue small disabled" href="javascript:void(0);" id="request_e_brochure<?=$course_object->getId();?>"><i class="icon-pencil" aria-hidden="true"></i><span>Request E-Brochure</span></a>
							<?php }else{ ?>
							<a  class="button blue small" href="javascript:void(0);" id="request_e_brochure<?=$course_object->getId();?>" onClick="trackReqEbrochureClick('<?php echo $course_object->getId();?>');validateRequestEBrochureFormData('<?=$course_object->getInstitute()->getId();?>','<?=$arr['rebLocallityId'.$course_object->getInstitute()->getId()];?>','<?=$arr['rebCityId'.$course_object->getInstitute()->getId()];?>','<?=$arr['isMultiLocation'.$course_object->getInstitute()->getId()];?>','<?php echo $course_object->getId();?>','','<?php echo $trackingPageKeyId;?>');"><i class="icon-pencil" aria-hidden="true"></i><span>Request E-Brochure</span></a>
							<?php } ?>
						    </p>
						    <!--<div class="shortlist"><i class="icon-heart" aria-hidden="true"></i><span>Shortlist</span></div>-->
				
						<form action="/muser5/MobileUser/renderRequestEbrouchre" method="post" id="brochureForm<?=$course_object->getId();?>">
							<input type="hidden" name="courseAttr" value = "<?php echo $addReqInfoVars; ?>" />
							<input type="hidden" name="current_url" value = "<?php echo url_base64_encode($shiksha_site_current_url); ?>" /> 
							<input type="hidden" name="referral_url" value = "<?php echo url_base64_encode($shiksha_site_current_refferal); ?>" />
							<input type="hidden" name="selected_course" value = "<?php echo $course_object->getId(); ?>" />
							<input type="hidden" name="list" value="<?php echo $course_object->getId(); ?>" />
							<input type="hidden" name="institute_id" value="<?php echo $course_object->getInstitute()->getId(); ?>" />
							<input type="hidden" name="pageName" value="<?php echo $pageName;?>" />
							<input type="hidden" name="from_where" value="<?php echo $from_where;?>" />
							<input type="hidden" name="tracking_keyid" id="tracking_keyid<?=$course_object->getId();?>" value="">
						</form>
						<?php }?>
					<!-- Display E-Brochure link if Course is Paid Ends -->
				</div>
			
			<?php $start++;?>
			<?php endforeach;?>
			<!-- Display 5 courses of the Category : END -->

			<!-- Similar course link ends -->


			<!-- In case, the count of courses is greater than 5, show a link to Similar courses page -->
			<?php if($count_courses>5):?>
			<?php 
				$to_send_array = array();
				$addReqInfoVars= array();
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
					//$addReqInfoVars = $search_lib_object->makeRequestInfo($courses_to_send);
					//$to_send_array[$key][$key1]['addReqInfoVars'] = $addReqInfoVars;
					$to_send_array[$key][$key1]['current_url'] = urldecode($search_lib_object->makeSearchURL($current_url,$searchurlparams));
					$to_send_array[$key][$key1]['referral_url'] = $referral_url;
					$to_send_array[$key][$key1]['affiliations'] = $value1->getAffiliations();
					$to_send_array[$key][$key1]['approvals'] = $value1->getApprovals();
					
					$institute_id = $value1->getInstitute()->getId();
					$to_send_array[$key][$key1]['instituteId'] = $institute_id;

					if(checkEBrochureFunctionality($value1)){
						$arr['isMultiLocation'.$institute_id] = $value1->isCourseMultilocation();
						foreach($value1->getAllLocations() as $course_location){
								$locality_name = $course_location->getLocality()->getName();
								if($locality_name !='') $locality_name = ' |'.$course_location->getLocality()->getName();
								$addReqInfoVars[$value1->getName().' | '.$course_location->getCity()->getName().$locality_name]=$value1->getId()."*".html_escape($value1->getInstitute()->getName())."*".$value1->getUrl()."*".$course_location->getCity()->getId()."*".$course_location->getLocality()->getId();
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
					//$to_send_array[$key][$key1]['addReqInfoVars'] = $addReqInfoVars;
					$to_send_array[$key][$key1]['isMultiLocation'] = $arr['isMultiLocation'.$institute_id];
					$to_send_array[$key][$key1]['rebLocallityId'] = $arr['rebLocallityId'.$institute_id];
					$to_send_array[$key][$key1]['rebCityId'] = $arr['rebCityId'.$institute_id];
					
					
				}
				
				foreach($courses as $key1=>$value1) {
					$addReqInfoVarsFinal = array();
					$addReqInfoVarsFinal=serialize($addReqInfoVars);
					$addReqInfoVarsFinal=base64_encode($addReqInfoVarsFinal);
					$to_send_array[$key][$key1]['addReqInfoVars'] = $addReqInfoVarsFinal;
				}
				
			?>

			<section class="other-opt" style="cursor: pointer;">
				<form action="/msearch5/Msearch/showMoreCourses" method="post">
				<input type="hidden" name="to_send_data" value="<?php echo base64_encode(gzcompress(json_encode($to_send_array)));?>"/>
				<input type="hidden" name="categorySelected" value="<?php echo $cat_id; ?>" />
				<a href="javascript:void(0);" onclick="$(this).closest('form').submit();">View more <?php echo $cat_name?> courses
				<i class="icon-arrow-r2"></i>
				</a>
				</form>
			</section>			
			
			<?php endif;?>			

			

		<?php
		$j++; $i++; 
		endforeach;?>
</dd>
		<script>
		var totalCount = <?=$j?>;
		function trackReqEbrochureClick(courseId){
				try{
				_gaq.push(['_trackEvent', 'HTML5_Search_Single_Courses_Request_Ebrochure', 'click', courseId]);
				}catch(e){}
		}
		</script>
		</div>
		<!-- Display Data for Each Category : END -->
</section>
<div id="recomendations_<?php echo $courseDocument->getId(); ?>" style="display:none; background:#fff;"></div>
<script>
function setAccordion(indexVal){
	if(totalCount>1){
		//Hide all the other courses
		$("dd[id*='_count_']").hide();
	
		//Change the Class of all the other courses
		for(i=0;i<totalCount;i++)	{
			$('#desc'+i).attr('class', 'icon-arrow-up');		
		}
	
		//Display the clicked Course
		id="count_"+indexVal ;
		 $("dd[id*='"+id+"']").show();
		
		//Change the Class of the Clicked Course
		$('#desc'+indexVal).attr('class', 'icon-arrow-dwn');
		
		//Set the clicked Course in Focus
		courseToBeInFocus = parseInt(indexVal)-1;
		if($("dt[id*='count_"+courseToBeInFocus+"']")[0]){
			$("dt[id*='count_"+courseToBeInFocus+"']")[0].scrollIntoView();
		}
		else{
			$("dt[id*='count_"+parseInt(indexVal)+"']")[0].scrollIntoView();
		}
	}
}

</script>
