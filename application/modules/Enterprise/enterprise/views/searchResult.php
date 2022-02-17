<?php
$flag_extra_course_type = '';
foreach($resultResponse['result'] as $arr)
{
    foreach ($arr['PrefData'] as $data)
    {
        $flag_extra_course_type = $data['ExtraFlag'];
    }
}

?>
<input type="hidden" id ="flag_extra_course_type" name="flag_extra_course_type" value="<?php echo $flag_extra_course_type; ?>" />

<input type="hidden" id ="courseName" name="courseName" value="<?php echo $course_name; ?>" />

<?php

$new_course_name =$course_name ;
$ldbObj = new LDB_client();
//code starts to get groupID for a particular tab
$split_array0 = explode('?',$_REQUEST['referer_url']);
$split_array1 = explode('&',$split_array0['1']);
$split_array2 = explode('=',$split_array1['0']);
$course_name = $actual_course_name;
?>
<div style="width:100%">
	<div style="width:100%">
		<!--Start_Margin_10px-->
		<div style="margin:0 10px">
			<div id="searchFormSubContents" style="display:none">
				<input type="hidden" id="startOffSetSearch" name="startOffSetSearch" value="<?php echo isset($_POST['startOffSetSearch'])?$_POST['startOffSetSearch']:'0';?>"/>
				<input type="hidden" id="countOffsetSearch" name="countOffsetSearch" value="<?php echo isset($_POST['countOffsetSearch'])?$_POST['countOffsetSearch']:'100';?>"/>
				<input type="hidden" id="filterOverride" name="filterOverride" value="<?php echo isset($displayArray['DateFilter'])?$displayArray['DateFilter']:'1 month';?>"/>
				<input type="hidden" id="requestTime" name="requestTime" value="<?php echo isset($resultResponse['requestTime'])?$resultResponse['requestTime']:'';?>"/>

				<?php if($new_course_name == 'Study Abroad') { ?>
					<input type="hidden" id="inputData" name="inputData" value="<?php echo $inputData; ?>"/>
					<input type="hidden" id="inputDataMR" name="inputDataMR" value="<?php echo $inputDataMR; ?>"/>
					<input type="hidden" id="displayData" name="displayData" value="<?php echo $displayData; ?>"/>
				<?php } else { ?>
					<input type="hidden" id="inputData" name="inputData" value="<?php echo isset($_POST['inputData'])?$_POST['inputData']:$inputData;?>"/>
					<input type="hidden" id="inputDataMR" name="inputDataMR" value="<?php echo isset($_POST['inputDataMR'])?$_POST['inputDataMR']:$inputDataMR;?>"/>
					<input type="hidden" id="displayData" name="displayData" value="<?php echo isset($_POST['displayData'])?$_POST['displayData']:$displayData;?>"/>
				<?php } ?>

				<input type="hidden" id="methodName" name="methodName" value="<?php echo isset($_POST['methodName'])?$_POST['methodName']:'populateSearchResults';?>"/>
				<!-- LDB 2.0 START -->
				<input type="hidden" id="ldb_unviewed_flag" name="ldb_unviewed_flag" value="<?php echo isset($_POST['ldb_unviewed_flag'])?$_POST['ldb_unviewed_flag']:'1';?>"/>
				
				<input type="hidden" id="ldb_viewed_flag" name="ldb_viewed_flag" value="<?php echo isset($_POST['ldb_viewed_flag'])?$_POST['ldb_viewed_flag']:'0';?>"/>
				<input type="hidden" id="ldb_emailed_flag" name="ldb_emailed_flag" value="<?php echo isset($_POST['ldb_emailed_flag'])?$_POST['ldb_emailed_flag']:'0';?>"/>
				<input type="hidden" id="ldb_underLimit_flag" name="ldb_underLimit_flag" value="<?php echo isset($_POST['ldb_underLimit_flag'])?$_POST['ldb_underLimit_flag']:'0';?>"/>
				<input type="hidden" id="ldb_smsed_flag" name="ldb_smsed_flag" value="<?php echo isset($_POST['ldb_smsed_flag'])?$_POST['ldb_smsed_flag']:'0';?>"/>
				<input type="hidden" id="ldb_total_result_count" name="ldb_total_result_count" value="<?php echo isset($resultResponse['numrows'])?$resultResponse['numrows']:"0";?>"/>
				<input type="hidden" id="ldb_client_id" name="ldb_client_id"  value="<?php echo $ClientId; ?>" />
				<input type="hidden" name="streamId" value="<?php echo $_POST['streamId'];?>" />
				<input type="hidden" id="tabFlag" name="tabFlag" value="<?php echo isset($_POST['tabFlag'])?$_POST['tabFlag']: $tabFlag;?>" />
				<!-- LDB 2.0 END -->
			</div>
			<!--Start_ShowResutlCount-->
			<div style="width:100%">
				<div style="margin:0 10px">
					<div style="width:100%" class="txt_align_r"><?php  if ($flag_manage_page != 'true') { ?>[<a id='modifySearch' href="#" onClick="<?php if($new_course_name == 'Study Abroad') { ?> showSearchForm(); <?php } ?> hideSuccessMessage(); showhideDIVOverlay(); return false" style="">Modify Search</a>]<?php } ?></div>
					<div style="width:100%">
						<?php //if(isset($resultResponse['numrows'])) {
							if (1==1) {
								
								
						?>
							<div>
								<div style="width:70%">
									<div style="width:100%">
										<div class="fontSize_16p" style="padding-bottom:7px">
											Total <span class="OrgangeFont" id="resultCount"><?php echo
(isset($resultResponse['numrows']) && $resultResponse['numrows'] > 0)?$resultResponse['numrows']:"0";?></span> student<?php if($resultResponse['numrows'] >1){echo 's';}?> found <?php if ($flag_manage_page != 'true') { ?><span id="save_search_agent_container_div">[<a
onclick="validateForCMSAdmin('add', '<?php echo $actual_course_id;?>','<?php echo $groupId;?>');return false;" uniqueattr="Enterprise/CreateALeadGenie" href="#">Create a
Genie</a>]</span> <?php } ?>
										</div>
									</div>
								</div>
							</div>
						<?php }
						/*
						commented code to hide no result error
						elseif(isset($resultResponse['error'])) {
							echo '<div class="fontSize_18p" style="padding-bottom:7px">'.$resultResponse['error'].'</div>';
						}
						else {
							echo '<div class="fontSize_18p" style="padding-bottom:7px">There are no matching students as per your criteria.</div>';
						}*/
						?>
						<div id="search_agent_from_container_div"></div>
						<b><span id='MRMeshComm' class='dandaSepGray'></span></b><br>
						<div id="search_result_display_array_content_div" class="dandaSepGray">Searched Criteria:
							<?php
								$searchVariableArray = array();
								if(isset($displayArray['streamData']) && $displayArray['streamData']!="") {
									array_push($searchVariableArray,"<b>Stream</b>: ".implode(', ', array_values($displayArray['streamData'])));
								}
								if(isset($displayArray['subStreamData']) && $displayArray['subStreamData']!="") {
									array_push($searchVariableArray,"<b>Substream</b>: ".implode(', ', array_values($displayArray['subStreamData'])));
								}
								if(isset($displayArray['specializationData']) && $displayArray['specializationData']!="") {
									array_push($searchVariableArray,"<b>Specializations</b>: ".implode(', ', array_values($displayArray['specializationData'])));
								}
								if(isset($displayArray['courseData']) && $displayArray['courseData']!="") {
									array_push($searchVariableArray,"<b>Base Course</b>: ".implode(', ', array_values($displayArray['courseData'])));
								}

								if(isset($displayArray['MRLocation']) && $displayArray['MRLocation']!="") {
									array_push($searchVariableArray,"<b>Preferred Location</b>: ".$displayArray['MRLocation']);
								}

								if(isset($displayArray['currentLocation']) && $displayArray['currentLocation']!="") {
									array_push($searchVariableArray,"<b>Current Location</b>: ".$displayArray['currentLocation']);
								}

								

								if(isset($displayArray['modeDataDisplay']) && $displayArray['modeDataDisplay'] != '') {
									array_push($searchVariableArray, "<b>Mode</b>: ".$displayArray['modeDataDisplay']);
								}
								if(isset($displayArray['cityLocalityDisplay']) && $displayArray['cityLocalityDisplay']!="") {
									array_push($searchVariableArray,"<b>Current Location</b>: ".$displayArray['cityLocalityDisplay']);
								}
								// if(isset($displayArray['localitiesIdValuesArray']) && $displayArray['localitiesIdValuesArray']!="") {
								// 	array_push($searchVariableArray,"<b>Current Localities</b>: ".implode(', ', array_values($displayArray['localitiesIdValuesArray'])));
								// }
								if(isset($displayArray['exams']) && $displayArray['exams']!="") {
									array_push($searchVariableArray,"<b>Exams</b>: ".implode(', ', array_values($displayArray['exams'])));
								}
								if(isset($displayArray['workex']) && $displayArray['workex']!="") {
									array_push($searchVariableArray,"<b>Work Experience</b>: ".$displayArray['workex']);
								}
								// if ($course_name != 'IT Courses') {
								// 	if(isset($displayArray['Specialization']) && $displayArray['Specialization']!="") {
								// 		array_push($searchVariableArray,"<b>Specialization</b>: ".$displayArray['Specialization']);
								// 	}
								// }
								if(isset($displayArray['matchedCourses']) && $displayArray['matchedCourses'] != "") {
								 	/*array_push($searchVariableArray,"<b>Matching for</b>: ".implode(', ', array_values($displayArray['matchedCourses'])));*/

								 	$str = '';
								 	foreach ($displayArray['matchedCoursesInstitute'] as $courseId => $InstiName) {
								 		$str .= ' '.$InstiName.' - '.$displayArray['matchedCourses'][$courseId].',';
								 	}

								 	$str = substr($str, 0,-1);
								 	
								 	array_push($searchVariableArray,"<b>Matching for</b>: ".$str);
								}
								// if(isset($displayArray['currentCityId']) && $displayArray['currentCityId']!="") {
								// 	array_push($searchVariableArray,"<b>Current City</b>: ".$displayArray['currentCityId']);
								// }
								// if(isset($displayArray['CurrentCity']) && $displayArray['CurrentCity']!="") {
								// 	array_push($searchVariableArray,"<b>Current City</b>: ".$displayArray['CurrentCity']);
								// }
								// if(isset($displayArray['currentLocation']) && $displayArray['currentLocation']!="") {
								// 	array_push($searchVariableArray,"<b>Current Location</b>: ".$displayArray['currentLocation']);
								// }
								if(isset($displayArray['prefLocation']) && $displayArray['prefLocation']!="") {
									array_push($searchVariableArray,"<b>Preferred Location</b>: ".$displayArray['prefLocation']);
								}
								// if(isset($displayArray['CurrentLocalities']) && $displayArray['CurrentLocalities']!="") {
								// 	array_push($searchVariableArray,"<b>Current Localities</b>: ".$displayArray['CurrentLocalities']);
								// // }
								// if(isset($displayArray['currLocation']) && $displayArray['currLocation']!="") {
								// 	array_push($searchVariableArray,"<b>Current Locality</b>: ".$displayArray['currLocation']);
								// }
								// if(isset($displayArray['Mode']) && $displayArray['Mode']!="") {
								// 	array_push($searchVariableArray,"<b>Mode</b>: ".$displayArray['Mode']);
								// }
								// if(isset($displayArray['DegreePref']) && $displayArray['DegreePref']!="") {
								// 	array_push($searchVariableArray,"<b>Degree Preference</b>: ".$displayArray['DegreePref']);
								// }
								// if(isset($displayArray['UGCourse']) && $displayArray['UGCourse']!="") {
								// 	array_push($searchVariableArray,"<b>Under Graduate Course</b>: ".$displayArray['UGCourse']);
								// }
								// if(isset($displayArray['instituteList']) && $displayArray['instituteList']!="") {
								// 	array_push($searchVariableArray,"<b>Under Graduate Institute</b>: ".$displayArray['instituteList']);
								// }
								// if(isset($displayArray['graduationDate']) && $displayArray['graduationDate']!="") {
								// 	array_push($searchVariableArray,"<b>Under Graduation Date</b>: ".$displayArray['graduationDate']);
								// }
								// if(isset($displayArray['graduationMarks']) && $displayArray['graduationMarks']!="") {
								// 	array_push($searchVariableArray,"<b>Under Graduation Marks</b>: ".$displayArray['graduationMarks']);
								// }
								// if(isset($displayArray['Min12Marks']) && $displayArray['Min12Marks']!="") {
								// 	array_push($searchVariableArray,"<b>Minimum 12th Marks</b>: ".$displayArray['Min12Marks']);
								// }
								// if(isset($displayArray['12Stream']) && $displayArray['12Stream']!="") {
								// 	array_push($searchVariableArray,"<b>12th Stream</b>: ".$displayArray['12Stream']);
								// }
								// if(isset($displayArray['XIIGraduationDate']) && $displayArray['XIIGraduationDate']!="") {
								// 	array_push($searchVariableArray,"<b>XII Completion Date</b>: ".$displayArray['XIIGraduationDate']);
								// }
								// if(isset($displayArray['age']) && $displayArray['age']!="") {
								// 	array_push($searchVariableArray,"<b>Age</b>: ".$displayArray['age']);
								// }
								// if(isset($displayArray['Gender']) && $displayArray['Gender']!="") {
								// 	array_push($searchVariableArray,"<b>Gender</b>: ".$displayArray['Gender']);
								// }
								// if(isset($displayArray['sourcesOfFunding']) && $displayArray['sourcesOfFunding']!="") {
								// 	array_push($searchVariableArray,"<b>Sources of Funding </b>: ".$displayArray['sourcesOfFunding']);
								// }
								if(isset($displayArray['examTaken']) && $displayArray['examTaken']!="") {
									array_push($searchVariableArray,"<b>Exams </b>: ".$displayArray['examTaken']);
								}
							if($new_course_name == 'Study Abroad') {
                                    if(isset($displayArray['DesiredCourseName']) && !empty($displayArray['DesiredCourseName'])){
                                        array_push($searchVariableArray,"<b>Course</b>: ". implode(', ', array_unique($displayArray['DesiredCourseName'])));
                                    }
									
									if(isset($displayArray['abroadSpecializations']) && !empty($displayArray['abroadSpecializations'])){
                                        array_push($searchVariableArray,"<b>Specialization</b>: ". implode(', ', array_unique($displayArray['abroadSpecializations'])));
                                    }
									
                                    if(isset($displayArray['DesiredCourseLevels']) && !empty($displayArray['DesiredCourseLevels'])){
                                        $courseLevels = array();
                                        $courseLevelsMap = array('UG'=> 'Bachelors','PG' =>'Masters','PhD' => 'PhD');
                                        foreach($displayArray['DesiredCourseLevels'] as $desiredCourselevel) {
                                            $courseLevels[] = $courseLevelsMap[$desiredCourselevel];
                                        }
                                        array_push($searchVariableArray,"<b>Course Level</b>: ". trim(implode(', ', $courseLevels),', '));
                                    }
									if($displayArray['budget']) {
										array_push($searchVariableArray,"<b>Budget</b>: ". trim(implode(', ', $displayArray['budget']),', '));
									}
									if($displayArray['passport']) {
										array_push($searchVariableArray,"<b>Passport</b>: ". $displayArray['passport']);
									}
									if($displayArray['planToStart']) {
										array_push($searchVariableArray,"<b>Plan to Start</b>: ". trim(implode(', ', $displayArray['planToStart']),', '));
									}
								}

						if ($displayArray['type'] != "response"){
							if(isset($displayArray['isActiveUser']) && $displayArray['isActiveUser']!="") {
								array_push($searchVariableArray,"<b>Active User Included</b>: Yes");
							}

							if(!isset($displayArray['isActiveUser']) || $displayArray['isActiveUser']=="") {
								array_push($searchVariableArray,"<b>Active User Included</b>: No");
							}
						}
							$searchParamTitle = implode("&nbsp;<i>|</i>&nbsp;",$searchVariableArray);
							echo $searchParamTitle;
							?>
						</div>
					</div>
				</div>
			</div>
			<!--End_ShowResutlCount-->
			<div class="lineSpace_10">&nbsp;</div>
			<!--Start_NavigationBar-->
			<?php
// 			if(isset($resultResponse['numrows'])) {
				if($new_course_name == 'Study Abroad') {
					$this->load->view('enterprise/searchResultForAbroad', array('displayArray'=>$displayArray));
				} else {
					$this->load->view('enterprise/searchResultIndia', array('courseDetails'=>$result));
				}
//			}
// 			else
// 			{
// 				echo "<div style='line-height:100px'>&nbsp;</div>";
// 			}
			?>
			<!--End_NavigationBar-->
		</div>
		<!--End_Margin_10px-->
	</div>
</div>

