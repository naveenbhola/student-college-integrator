<div class="<?php echo $class; ?>">
<input id = "requiredInstitutesListCountOffset" type = "hidden" value = "<?php echo CATEGORY_HOME_PAGE_COLLEGES_COUNT;?>"/>
<input id = "requiredInstitutesListStartOffSet" type = "hidden" value = "0"/>
<input id = "testprepInstitutesListCountOffset" type = "hidden" value = "<?php echo CATEGORY_HOME_PAGE_COLLEGES_COUNT;?>"/>
<input id = "testprepInstitutesListStartOffSet" type = "hidden" value = "0"/>
<input id = "testprepInstitutesListMethodName" type = "hidden" value = "gettestprepExamColleges"/>
<input id = "requiredInstitutesListMethodName" type = "hidden" value = "getrequiredExamColleges"/>
<input id = "producttype" type = "hidden" value = "examdirpage"/>
<input id = "pagetype" type = "hidden" value = "<?php echo $pagetype ?>"/>
	<div>
		<div id="blogTabContainer">
			<div id="blogNavigationTab" style="width:99%;">
				<ul>
<?php if($pagetype != "course") { ?>
                     <li container="category" id="categoryinsti" class = "selected" onClick="return selectTab('categoryinsti','insti');" style="<?php echo $tabMargin; ?>">						
						<a href="#" id = "prepTab" title="<?php echo "Institutes that prepare for ".$examName?>"><?php echo substr("Institutes that prepare for ". $examName,0,30)?> ..</a>						</li>
					<li container="category" id="categoryinstitutes"  onClick="return selectTab('categoryinstitutes','insti');" style="<?php echo $tabMargin; ?>">						
						<a href="#" id = "reqTab" title="<?php echo "Institutes that require ".$examName." for admission"?>"><?php echo substr("Institutes that require ". $examName." for admission",0,30) ?> ..</a>						
                    </li>
					<li container="category" id="categoryarticles"  onClick="return selectTab('categoryarticles','insti');" style="<?php echo $tabMargin; ?>">						
						<a href="#" id = "reqTab" title="<?php echo "Articles for ".$examName?>">Articles</a>						
                    </li>
<?php } else  { ?>
                     <li container="courses" id="courseinsti" class = "selected" onClick="return selectTab('courseinsti','course');" style="<?php echo $tabMargin; ?>">						
						<a href="#" id = "prepTab" title="<?php echo "Courses that prepare for ".$examName?>"><?php echo substr("Courses that prepare for ". $examName,0,30)?> ..</a>						</li>
					<li container="courses" id="coursesinstitutes"  onClick="return selectTab('coursesinstitutes','course');" style="<?php echo $tabMargin; ?>">						
						<a href="#" id = "reqTab" title="<?php echo "Courses that require ".$examName." for admission"?>"><?php echo substr("Courses that require ". $examName." for admission",0,30) ?> ..</a>						
                    </li>
<?php } ?>
				</ul>
			</div>
			<div class="clear_L"></div>
        </div>
<!--Institutes-->
				<div style="border:1px solid #D6DBDF;position:relative;top:1px;width:100%">
<?php 
                                                    $truncateStrLengthForRecord = 30;
                                                    $screen_res = $_COOKIE['client'];
                                                    if($screen_res < 1000)
                                                    $truncateStrLengthForRecord = 16;
if($pagetype != "course") {
    $collegeList = $institutesaccept[0]['results'];
$totalResults = $institutesaccept[0]['total'];?>
            <!-- Institutes Block -->
				<div class="mar_full_10p selected" style="padding:10px 0px;display:none;" id="categoryinstitutesBlock">
									<?php
										$messageText = '';
										if($totalResults > 0) {
											$startNum = 1;
											$endNum = CATEGORY_HOME_PAGE_COLLEGES_COUNT;
											$endNum = $endNum > $totalResults ? $totalResults : $endNum;
											$messageText = 'Showing '. $startNum  . ' - '. $endNum .' institutes out of '. $totalResults;
										} else {
											$messageText = 'No institutes Available.';
										}
									
									?>
									<div><label id="requiredInstitutesListHeading"><?php echo $messageText; ?></label></div>
									<div class="lineSpace_15">&nbsp;</div>
									<div class="fontSize_12p">
										<div>
											<div id="requiredInstitutesListPlace">
											<?php 
                                        foreach($collegeList as $college){
													if(empty($college['instituteId'])) {  continue; }
													$collegeId 		= $college['instituteId'];
													$collegeName 	= ucwords($college['instituteName']);
													$location	= $college['location'];
													$url = $college['detailUrl'];
                                                    
                                                    $sponsoredResult = (isset($college['isSponsored'])  && ($college['isSponsored'] == "true")) ? 'check.gif' : 'grayBullet.gif' ;
                                                    $sponsoredResultMargin = (isset($college['isSponsored'])  && ($college['isSponsored'] == "true")) ? 'margin-left:-6px' : '' ;

													$collegeDisplayName = strlen($collegeName) > $truncateStrLengthForRecord ? (substr($collegeName, 0, $truncateStrLengthForRecord) .'...') : $collegeName;
													$locationDisplayText = strlen($location) > $truncateStrLengthForRecord ? substr($location, 0, $truncateStrLengthForRecord - 3) .'...' : $location;
													$locationDisplayText = $locationDisplayText=='' ? '&nbsp;' : $locationDisplayText;
											?>		
											<div class="w49_per float_L">
												<span class="normaltxt_11p_blk fontSize_12p arial">
													<div class="row" style = "height:50px">
															<div class="float_L" style="padding:5px 5px 5px 0px; <?php echo $sponsoredResultMargin;?>"><img src="/public/images/<?php echo $sponsoredResult; ?>" align="absmiddle"/></div>
															<div class="float_L">
															<div class="normaltxt_11p_blk_arial">
																<a class="fontSize_12p" href="<?php echo $url;?>" title="<?php echo $collegeName ;?>"><?php echo $collegeDisplayName; ?></a>
															</div>
															<div class="normaltxt_11p_blk_arial">
																<span class="fontGreenColor"><?php echo $location ?></span>
															</div>
                                                        <script>
                                                    var isQuickSignUpUser = 0;
                                                    var base64url = '';
                                                    var UserLogged = 1;
                                                    </script>
                                                    <?php /* if($college['isSendQuery'] == 1)
                                                    {
                                                        if(!(is_array($validateuser) && $validateuser != "false")) {?> 
                                                        <script>
                                                    var UserLogged = 0;
                                                    </script>
            <?php                                 		$onClick = "showuserLoginOverLay('',2);return false;";
                                                        }else {
                                                            if($validateuser[0]['quicksignuser'] == 1 && $validateuser[0]['orusergroup'] == "quicksignupuser") {
                                                                $base64url = base64_encode($_SERVER['REQUEST_URI']);?>
                                                            <script>
                                                             isQuickSignUpUser = 1;
                                                            base64url = '<?php echo $base64url ?>';
                                                            </script>
<?php
                                    $onClick = "window.location = '/user/Userregistration/index/$base64url/1'";
		} else {  
            $onClick = "setRequestInfoForSearchParams('institute',$collegeId,'$collegeName','$url','');";
		}
                                                        }
                                                    ?>
															<div class="normaltxt_11p_blk_arial">
																<a class="grayFont" onClick = "<?php echo $onClick?>" style = "cursor:pointer">Send Question to this Institute</a>
                                                            </div>
                                                            <?php } */?>
															<div class="lineSpace_10">&nbsp;</div>
															</div><div class="clear_L"></div>
													</div>
												</span>
											</div>
												<?php } ?>
											
											</div>
											<div class="clear_L"></div>
										</div>
									</div>
                                    <!-- ENds here -->
					<div class="clear_L"></div>
                                <div class="mar_right_10p" align="right">
									<div id="pagingIDc">
										<div id="requiredInstitutesListPaginataionPlace1"></div>
										<div  id="requiredInstitutesListPaginataionPlace2" style="display:none"></div>
									</div>
								</div>
                                <div class="lineSpace_8">&nbsp;</div>	
				</div>

                <!-- Institutes Block Ends -->
				<!-- Accepts Institutes -->
<?php 
$collegeList1 = $institutestestprep[0]['results'];
$totalResults1 = $institutestestprep[0]['total']; ?> 
				<div class="mar_full_10p" style="padding:10px 0px;display:block;" id="categoryinstiBlock">
									<?php
										$messageText = '';
										if($totalResults1 > 0) {
											$startNum = 1;
											$endNum = CATEGORY_HOME_PAGE_COLLEGES_COUNT;
											$endNum = $endNum > $totalResults1 ? $totalResults1 : $endNum;
											$messageText = 'Showing '. $startNum  . ' - '. $endNum .' institutes out of '. $totalResults1;
										} else {
											$messageText = 'No institutes Available.';
										}
									
?>
									<div  align="left"><label style="position:relative;top:8px;" id="testprepInstitutesListHeading"><?php echo $messageText; ?></label>&nbsp;&nbsp;</div>
									<div class="lineSpace_15">&nbsp;</div>
									<div class="fontSize_12p">
										<div>
											<div id="testprepInstitutesListPlace">
											<?php 
                                        foreach($collegeList1 as $college){
													if(empty($college['instituteId'])) {  continue; }
													$collegeId 		= $college['instituteId'];
													$collegeName 	= ucwords($college['instituteName']);
													$location	= $college['location'];
													$url = $college['detailUrl'];
                                                    
                                                    $sponsoredResult = (isset($college['isSponsored'])  && ($college['isSponsored'] == "true")) ? 'check.gif' : 'grayBullet.gif' ;
                                                    $sponsoredResultMargin = (isset($college['isSponsored'])  && ($college['isSponsored'] == "true")) ? 'margin-left:-6px' : '' ;
													$collegeDisplayName = strlen($collegeName) > $truncateStrLengthForRecord ? (substr($collegeName, 0, $truncateStrLengthForRecord) .'...') : $collegeName;
													$locationDisplayText = strlen($location) > $truncateStrLengthForRecord ? substr($location, 0, $truncateStrLengthForRecord - 3) .'...' : $location;
													$locationDisplayText = $locationDisplayText=='' ? '&nbsp;' : $locationDisplayText;
											?>		
											<div class="w49_per float_L">
												<span class="normaltxt_11p_blk fontSize_12p arial">
													<div class="row" style = "height:50px">
															<div class="float_L" style="padding:5px 5px 5px 0px; <?php echo $sponsoredResultMargin;?>"><img src="/public/images/<?php echo $sponsoredResult; ?>" align="absmiddle"/></div>
															<div class="float_L">
															<div class="normaltxt_11p_blk_arial">
																<a class="fontSize_12p" href="<?php echo $url;?>" title="<?php echo $collegeName ;?>"><?php echo $collegeDisplayName; ?></a>
															</div>
															<div class="normaltxt_11p_blk_arial">
																<span class="fontGreenColor"><?php echo $location ?></span>
															</div>
                                                        <script>
                                                    var isQuickSignUpUser = 0;
                                                    var base64url = '';
                                                    var UserLogged = 1;
                                                    </script>
                                                    <?php /* if($college['isSendQuery'] == 1)
                                                    {
                                                        if(!(is_array($validateuser) && $validateuser != "false")) {?> 
                                                        <script>
                                                    var UserLogged = 0;
                                                    </script>
            <?php                                 		$onClick = "showuserLoginOverLay('',2);return false;";
                                                        }else {
                                                            if($validateuser[0]['quicksignuser'] == 1 && $validateuser[0]['orusergroup'] == "quicksignupuser") {
                                                                $base64url = base64_encode($_SERVER['REQUEST_URI']);?>
                                                            <script>
                                                             isQuickSignUpUser = 1;
                                                            base64url = '<?php echo $base64url ?>';
                                                            </script>
<?php
                                    $onClick = "window.location = '/user/Userregistration/index/$base64url/1'";
		} else {  
            $onClick = "setRequestInfoForSearchParams('institute',$collegeId,'$collegeName','$url','');";
		}
                                                        }
                                                    ?>
															<div class="normaltxt_11p_blk_arial">
																<a class="grayFont" onClick = "<?php echo $onClick?>" style = "cursor:pointer">Send Question to this Institute</a>
                                                            </div>
                                                            <?php } */?>
															<div class="lineSpace_10">&nbsp;</div>
															</div><div class="clear_L"></div>
													</div>
												</span>
											</div>
												<?php } ?>
											
											</div>
										</div>
									</div>
                                    <!-- ENds here -->
					<div class="clear_L"></div>
                                <div class="mar_right_10p" align="right">
									<div id="pagingIDc">
										<div id="testprepInstitutesListPaginataionPlace1"></div>
										<div  id="testprepInstitutesListPaginataionPlace2" style="display:none"></div>
									</div>
								</div>
                                <div class="lineSpace_8">&nbsp;</div>	
                        <!-- Pagination Place -->
				</div>
				<div class="lineSpace_10">&nbsp;</div>
                <!-- Accepts ENds -->

<!-- Articles Start -->
		<div class="mar_full_10p" style="padding:10px 0px;display:none;" id="categoryarticlesBlock">
			<div id="articlesBlock">
					<?php 
						$CI_Instance = & get_instance();
						$clientWidth =  $CI_Instance->checkClientData();
						$characterLength = 250;// ($clientWidth < 1000) ? 22 : 33;
						foreach($blogs['results'] as $blog) {
							$blogId = isset($blog['blogId']) ? $blog['blogId'] : '';
							$blogTitle = isset($blog['blogTitle']) ? $blog['blogTitle'] : '';
							$blogUrl = isset($blog['url']) ? $blog['url'] : '';
					?>
                    <div class="normaltxt_11p_blk arial">
                        <div style="margin-bottom:2px" class="quesAnsBullets">
	                        <a class="fontSize_12p" href="<?php echo $blogUrl; ?>" title="<?php echo $blogTitle; ?>"><?php echo (strlen($blogTitle)>$characterLength)?substr($blogTitle,0,$characterLength-3)."...":$blogTitle; ?></a>
                        </div>
                        <div style="line-height:10px">&nbsp;</div>
                    </div>
					<?php
						}
                        if(count($blogs['results']) > $blogs['total']) {
                            $urlParams = '';
                            if($categoryId > 1) {
                                $urlParams .= 'categoryId='. $categoryId;
                            }
                            if($urlParams != '') {$urlParams .='&';}
                            if($countryId > 1 && strpos($countryId, ',')=== false) {
                                $urlParams .= 'countryId='. $countryId;
                            }
                            $selectedExamId = $examId;
                            if(isset($selectedExamId )) {
                                $urlParams .= 'type=exam&parent='. $selectedExamId;
                            }
                    ?>
                   <div align="right"><a href="/blogs/shikshaBlog/showArticlesList?<?php echo $urlParams .'&c='. rand(); ?>">View All</a></div> 
                    <?php
                        }
					?>
                </div>
<!-- Articles ENd -->
                        <script>
doPagination(<?php echo $institutestestprep[0]['total'] ; ?>, 'testprepInstitutesListStartOffSet', 'testprepInstitutesListCountOffset', 'testprepInstitutesListPaginataionPlace1', 'testprepInstitutesListPaginataionPlace2', 'testprepInstitutesListMethodName', <?php echo ($_COOKIE['client']< 1000) ? 5 : 10;?>);
doPagination(<?php echo $institutesaccept[0]['total'] ; ?>, 'requiredInstitutesListStartOffSet', 'requiredInstitutesListCountOffset', 'requiredInstitutesListPaginataionPlace1', 'requiredInstitutesListPaginataionPlace2', 'requiredInstitutesListMethodName', <?php echo ($_COOKIE['client']< 1000) ? 5 : 10;?>);
</script>
<?php } else {?>
<!-- Institutes Ends -->

<!-- Courses Starts -->
<!-- Courses that required exam -->
                    <div class="mar_full_10p" style="display:none;<?php echo count($events) < 1 ? 'height:'. $height .'px' : ''; ?>" id="coursesinstitutesBlock">
                        <div class = "lineSpace_15">&nbsp;</div>
								<?php
									$messageText = '';
									$totalResults = $coursesaccept[0]['total'];
									if($totalResults > 0) {
										$startNum = 1;
										$endNum = CATEGORY_HOME_PAGE_COLLEGES_COUNT;
										$endNum = $endNum > $totalResults ? $totalResults : $endNum;
										$messageText = 'Showing '. $startNum  . ' - '. $endNum .' courses out of '. $totalResults;
									} else {
										$messageText = 'No courses Available.';
									}	
								?>
                                <div><label id="reqCourseCountLabel"><?php echo $messageText; ?></label></div>
                        <div class = "lineSpace_10">&nbsp;</div>
					<div id="coursesinstitutesPlace">
<!--Course Widget-->

<?php 
$truncateStrLength = isset($truncateStrLength) ? $truncateStrLength : 30;
foreach($coursesaccept[0]['courses'] as $course){
    if(empty($course['id'])) {  continue; }
    $courseId 		= $course['id'];
    $courseName 	= ucwords($course['title']);

    $url = $course['url'];
    $courseDisplayName = strlen($courseName) > $truncateStrLengthForRecord ? substr($courseName, 0, $truncateStrLengthForRecord - 3) .'...' : $courseName;
    $locationDisplayText = strlen($location) > $truncateStrLengthForRecord ? substr($location, 0, $truncateStrLengthForRecord - 3) .'...' : $location;
    $collegeUrl = html_entity_decode($course['instituteUrl']);
    $collegeName = html_entity_decode($course['institute_name']);
    $collegeNameDisplayText = strlen($collegeName) > $truncateStrLengthForRecord ? substr($collegeName, 0, $truncateStrLengthForRecord - 3) .'...' : $collegeName;
    $collegeNameDisplayText = $collegeNameDisplayText =='' ? '&nbsp;' : $collegeNameDisplayText;
?>		
    <div class="w49_per float_L">
	    <span class="normaltxt_11p_blk fontSize_12p arial">
		    <div class="row">
				<div class="float_L">
				    <div class="normaltxt_11p_blk_arial">
					    <a class="fontSize_12p" href="<?php echo $url;?>" title="<?php echo $courseName ;?>"><?php echo $courseDisplayName; ?></a>
					</div>
					<div class="normaltxt_11p_blk_arial">
				    <a href="<?php echo $collegeUrl; ?>" title="<?php echo $collegeName; ?>" class="blackFont"><?php echo $collegeNameDisplayText; ?></a>
					</div>
					<div class="lineSpace_10">&nbsp;</div>
				</div>
                <div class="clear_L"></div>
			</div>
		</span>
    </div>
<?php } ?>

<!-- Course Widget    -->
                        <?php $count = $coursesaccept[0]['total'] ;?>
                    </div>
<div class = "clear_L"></div>
                        <div class = "lineSpace_10">&nbsp;</div>
                                <div class="mar_right_10p" align="right">
									<div id="pagingIDc">
									<!--Pagination Related hidden fields Starts-->
										<div  id="paginataionPlace1"></div>
										<div  id="paginataionPlace2" style = "display:none"></div>
									</div>
								</div>
                                <div class="lineSpace_8">&nbsp;</div>	
									<input type="hidden" id="startOffSet" value="0"/>
									<input type="hidden" id="countOffset" value="<?php echo CATEGORY_HOME_PAGE_COLLEGES_COUNT; ?>"/>
									<input type="hidden" id="methodName" value="getTestPrepReqCourses"/>
    <script>
    document.getElementById('startOffSet').value = 0;
doPagination(<?php echo $count ; ?>, 'startOffSet', 'countOffset', 'paginataionPlace1', 'paginataionPlace2', 'methodName', <?php echo ($_COOKIE['client']< 1000) ? 5 : 10;?>);
</script>

</div>
<!-- Courses that required exam  -->

<!-- Courses that prepare for exam -->
                    <div class="mar_full_10p" style="<?php echo count($events) < 1 ? 'height:'. $height .'px' : ''; ?>" id="courseinstiBlock">
                        <div class = "lineSpace_15">&nbsp;</div>
								<?php
									$messageText = '';
									$totalResults = $courseList[0]['total'];
									if($totalResults > 0) {
										$startNum = 1;
										$endNum = CATEGORY_HOME_PAGE_COLLEGES_COUNT;
										$endNum = $endNum > $totalResults ? $totalResults : $endNum;
										$messageText = 'Showing '. $startNum  . ' - '. $endNum .' courses out of '. $totalResults;
									} else {
										$messageText = 'No courses Available.';
									}	
								?>
                                <div><label id="testprepCourseCountLabel"><?php echo $messageText; ?></label></div>
                        <div class = "lineSpace_10">&nbsp;</div>
					<div id="courseinstiPlace">
<!--   Course Widget  -->

<?php 
$truncateStrLength = isset($truncateStrLength) ? $truncateStrLength : 30;
foreach($courseList[0]['courses'] as $course){
    if(empty($course['id'])) {  continue; }
    $courseId 		= $course['id'];
    $courseName 	= ucwords($course['title']);
    
    $collegeCity	= $course['locationArr'][0]['city_name'];
    $collegeCountry = $course['locationArr'][0]['country_name'];
    if($countrySelected != '') {
        for($locationCount = 0; $locationCount < count($course['locationArr']); $locationCount++){
            if($course['locationArr'][$locationCount]['country_id'] == $countrySelected) {
                $collegeCity	= $course['locationArr'][$locationCount]['city_name'];
                $collegeCountry = $course['locationArr'][$locationCount][$country_name];
                break;
            }
        }
    }
    $url = $course['url'];
    $location = $collegeCity;
    if($collegeCity != '' && $collegeCountry!= '') {
        $location .= ' - ';
    }
    $location .= $collegeCountry;
    $courseDisplayName = strlen($courseName) > $truncateStrLengthForRecord ? substr($courseName, 0, $truncateStrLengthForRecord - 3) .'...' : $courseName;
    $locationDisplayText = strlen($location) > $truncateStrLengthForRecord ? substr($location, 0, $truncateStrLengthForRecord - 3) .'...' : $location;
    $locationDisplayText = $locationDisplayText=='' ? '&nbsp;' : $locationDisplayText;
    $collegeUrl = html_entity_decode($course['instituteUrl']);
    $collegeName = html_entity_decode($course['institute_name']);
    $collegeNameDisplayText = strlen($collegeName) > $truncateStrLengthForRecord ? substr($collegeName, 0, $truncateStrLengthForRecord - 3) .'...' : $collegeName;
    $collegeNameDisplayText = $collegeNameDisplayText =='' ? '&nbsp;' : $collegeNameDisplayText;

?>		
    <div class="w49_per float_L">
	    <span class="normaltxt_11p_blk fontSize_12p arial">
		    <div class="row">
				<div class="float_L">
				    <div class="normaltxt_11p_blk_arial">
					    <a class="fontSize_12p" href="<?php echo $url;?>" title="<?php echo $courseName ;?>"><?php echo $courseDisplayName; ?></a>
					</div>
					<div class="normaltxt_11p_blk_arial">
					    <a href="<?php echo $collegeUrl; ?>" title="<?php echo $collegeName; ?>" class="blackFont"><?php echo $collegeNameDisplayText; ?></a>
					</div>
					<div class="lineSpace_10">&nbsp;</div>
				</div>
                <div class="clear_L"></div>
			</div>
		</span>
    </div>
<?php } ?>

<!-- Course Widget    -->
                    </div>
<div class = "clear_L"></div>
                        <div class = "lineSpace_10">&nbsp;</div>
                                <div class="mar_right_10p" align="right">
									<div id="pagingIDc">
									<!--Pagination Related hidden fields Starts-->
									<!--Pagination Related hidden fields Ends  -->
										<div  id="paginataionPlace3"></div>
										<div  id="paginataionPlace4" style = "display:none"></div>
									</div>
								</div>
                                <div class="lineSpace_8">&nbsp;</div>	
									<input type="hidden" id="startOffSet1" value="0"/>
									<input type="hidden" id="countOffset1" value="<?php echo CATEGORY_HOME_PAGE_COLLEGES_COUNT; ?>"/>
									<input type="hidden" id="methodName1" value="getTestPrepCourses"/>
    <script>
    document.getElementById('startOffSet').value = 0;
doPagination(<?php echo $totalResults ; ?>, 'startOffSet1', 'countOffset1', 'paginataionPlace3', 'paginataionPlace4', 'methodName1', <?php echo ($_COOKIE['client']< 1000) ? 5 : 10;?>);
</script>

</div>
<!-- Courses that prepare for exam -->
<?php } ?>

<!-- Courses Ends -->
			</div>
	</div>
</div>
<script>
function selectTab(tabname,type)
{
    if(type == "course")
    {

document.getElementById('coursesinstitutes').className = '';
document.getElementById('courseinsti').className = '';
document.getElementById('coursesinstitutesBlock').style.display = 'none';
document.getElementById('courseinstiBlock').style.display = 'none';
    }
    else
    {
document.getElementById('categoryinstitutes').className = '';
document.getElementById('categoryinsti').className = '';
document.getElementById('categoryinstitutesBlock').style.display = 'none';
document.getElementById('categoryinstiBlock').style.display = 'none';
document.getElementById('categoryarticlesBlock').style.display = 'none';
document.getElementById('categoryarticles').className= '';
    }
document.getElementById(tabname).className = 'selected'; 
document.getElementById(tabname + 'Block').style.display = 'block';
return false;
}
document.getElementById('testprepInstitutesListStartOffSet').value = 0;
document.getElementById('requiredInstitutesListStartOffSet').value = 0;
</script>
