<script type="text/javascript">

</script>
<?php
$params = array('instituteId'=>$details['institute_id'],'instituteName'=>$details['title'],'type'=>'course','locality'=>$details['location']['0']['locality'],'city'=>$details['location']['0']['city_name'],'courseName'=>$details['courseDetails']['0']['title'],'courseId'=>$details['courseDetails']['0']['course_id'],'dbFlag'=>'old');
if(!empty($details['courseDetails']['0']['seoListingUrl'])){
$overviewTabUrl = $details['courseDetails']['0']['seoListingUrl'];
}else{
$overviewTabUrl = listing_detail_overview_url($params);
}
$courseTabUrl = listing_detail_course_url($params);
?>
<?php
if($tab != "course") {
    $course_details = $details['courseDetails'][0];
    $course_title_main_heading = '<div class="Fnt15 bld mt13">'. $course_details["title"].'</div>';
} else {
    $course_title_main_heading = "";
}
//echo "<pre>-------<br/>";print_r($course_details);echo "</pre>";
$course_attributes = $course_details['courseAttributes'];
$cattr = array();
foreach($course_attributes as $attr){
    $cattr[$attr['attribute']] = $attr['value'];
}
?>
<div id="course_details"><?php echo $course_title_main_heading; ?>
<div class="Fnt11 bld mb6" style="color:#5b5b5b">
<?php
    if($course_details['duration_unit']=='Years'){
        $course_details['duration_unit']= 'Year';
    }
    if($course_details['duration_unit']=='Weeks'){
        $course_details['duration_unit']= 'Week';
    }
    if($course_details['duration_unit']=='Months'){
        $course_details['duration_unit']= 'Month';
    }
    if($course_details['duration_unit']=='Days'){
        $course_details['duration_unit']= 'Day';
    }
    if($course_details['duration_unit']=='Hours'){
        $course_details['duration_unit']= 'Hour';
    }
    echo $course_details['duration_value'];
    if((!empty($course_details['duration_value']))||($course_details['duration_value']=='0')){
    if($course_details['duration_value']>1)
    {
    echo " ".$course_details['duration_unit']."s";
    }else{
    echo " ".$course_details['duration_unit'];
    }
    echo ", ".$course_details['course_type'];
    }
    $course_level;
    if(!empty($course_details['course_level'])){
      if($course_details['course_level']=='Degree'){
	if($course_details['course_level_1']=='Under Graduate')  
	$course_level = $course_details['course_level_1']." ".$course_details['course_level'];
	if($course_details['course_level_1']=='Post Graduate')  
	$course_level = $course_details['course_level_1']." ".$course_details['course_level'];
	if($course_details['course_level_1']=='Doctorate')  
	$course_level = $course_details['course_level_1']." ".$course_details['course_level'];
	if($course_details['course_level_1']=='Post Doctorate')  
	$course_level = $course_details['course_level_1']." ".$course_details['course_level'];
      }
      if($course_details['course_level']=='Diploma'){
	if($course_details['course_level_1']=='Diploma')  
	$course_level = $course_details['course_level_1'];
	if($course_details['course_level_1']=='Post Graduate Diploma')  
	$course_level = $course_details['course_level_1'];
      }
      if($course_details['course_level']=='Dual Degree'){
      $course_level = $course_details['course_level'];
      }
      if($course_details['course_level']=='Certification'){
      $course_level = $course_details['course_level'];
      }
  if(!empty($course_level)){
  if((!empty($course_details['duration_value']))||($course_details['duration_value']=='0')||!empty($course_details['course_type'])){
  echo ", ".$course_level;
  }
  else{
  echo $course_level;
  }
  }  
    }
?>
</div>
                                    <?php if($instituteType==1){?>
                                    <div class="Fnt11 mb8" style="color:#5b5b5b">
                                            <?php
                                            $recognition = array();
                                            if($cattr['AICTEStatus'] == 'yes')
                                                $recognition[] = "AICTE Approved";
                                            if($cattr['UGCStatus'] == 'yes')
                                                $recognition[] = "UGC Recognised";
                                            if($cattr['DECStatus'] == 'yes')
                                                $recognition[] = "DEC Approved";
                                            echo implode(", ",$recognition);
                                            ?>
                                             <?php if($cattr['AffiliatedToIndianUni']=='yes'|| $cattr['AffiliatedToForeignUni']=='yes'||$cattr['AffiliatedToDeemedUni']=='yes'||$cattr['AffiliatedToAutonomous']=='yes'){?>

                                             <?php if($cattr['AffiliatedToIndianUni']=='yes'){
                                                        if(!empty($cattr['AffiliatedToIndianUniName']))
                                                        echo " (Affiliated to ".$cattr['AffiliatedToIndianUniName'].")";
                                                    }

                                                    if($cattr['AffiliatedToForeignUni']=='yes'){
                                                        if(!empty($cattr['AffiliatedToForeignUniName']))
                                                        echo " (Affiliated to ".$cattr['AffiliatedToForeignUniName'].")";
                                                    }

                                                    if($cattr['AffiliatedToDeemedUni']=='yes'){
                                                        //echo " (Deemed University)";
							  echo $details['title'];
                                                    }

                                                    if($cattr['AffiliatedToAutonomous']=='yes'){
                                                        echo " (Autonomous Program)";
                                                    }

                                             ?>

                                                <?php }?>

                                                </div>

                                             <div>
                                            <ul class="rndBlts">
                                            <?php if(!empty($cattr['AccreditedBy'])) {?>
                                            <li class="Fnt11 mb6"><strong style="color:#5b5b5b">Accreditation:</strong> <?php echo $cattr['AccreditedBy'];?></li>
                                            <? } ?>
                                            </ul>
                                             </div>
                                     <?php }?>
                                             <div>
                                                 <ul class="rndBlts">
                                            <?php if(!empty($course_details['fees_value'])) {?>
                                            <li class="Fnt11 mb6"><strong style="color:#5b5b5b">Fees:</strong> <?php echo $course_details['fees_unit']?> <?php echo $course_details['fees_value']?></li>
                                            <? } ?>

                                            <?php if((!empty($course_details['seats_total']))||(!empty($course_details['seats_general']))||(!empty($course_details['seats_management']))||(!empty($course_details['seats_reserved']))) {
                                                $seats = array();?>
                                            <li class="Fnt11 mb6"><strong style="color:#5b5b5b">Seats:</strong>
                                            <?php if(!empty($course_details['seats_total'])){
                                                $seats[] = "Total"."-".$course_details['seats_total'];
                                            }?>
                                             <?php if(!empty($course_details['seats_general'])){
                                                $seats[] = "General"."-".$course_details['seats_general'];
                                            }?>
                                             <?php if(!empty($course_details['seats_management'])){
                                                $seats[] = "Management"."-".$course_details['seats_management'];
                                            }?>
                                            <?php if(!empty($course_details['seats_reserved'])){
                                                $seats[] = "Reserved"."-".$course_details['seats_reserved'];
                                            }?>
                                             <?php echo implode(" <span class=\"sepClr\">|</span> ", $seats);   ?>
                                            </li>
                                            <? } ?>

                                            <?php if(!empty($course_details['courseExams'])){?>
                                            <?php if($instituteType == 1){?>
                                            <li class="Fnt11 mb6"><strong style="color:#5b5b5b">Eligibility:</strong>
                                                <?php }else{?>
                                                <li class="Fnt11 mb6"><strong style="color:#5b5b5b">Exams Prepared for:</strong>
                                                <?php }?>

                                            <?php
                                            $eligibility_arr = array();
                                            foreach($course_details['courseExams'] as $ex) {
                                                if($ex['marks']>0){
						if($ex['marks_type']=='total_marks'){
						$ex['marks_type']="total marks";
						}
                        if(intval($ex['marks']) == $ex['marks']){
                             $ex['marks'] = intval($ex['marks']);
                        }

                                                $el = $ex['acronym']."-".$ex['marks']." ".$ex['marks_type'];
                                                }else{
                                                    $el = $ex['acronym'];
                                                }
                                                if(!empty($ex['practiceTestsOffered'])){
                                                    $el = $ex['acronym']."(".$ex['practiceTestsOffered'].")";
                                                }
                                                array_push($eligibility_arr, $el);
                                            }
                                            if(!empty($cattr['otherEligibilityCriteria'])){
                                                $oec = $cattr['otherEligibilityCriteria'];
                                                array_push($eligibility_arr, $oec);
                                            }
                                            echo implode(" <span class=\"sepClr\">|</span> ", $eligibility_arr);

                                                ?>

                                            </li>
                                            <?php }?>
                                            <?php if($instituteType==1){?>
                                            <?php if(!empty($course_details['courseFeatures'])){?>
                                                <?php
                                            $salient_features_arr = array();

                                            foreach($course_details['courseFeatures'] as $ex) {
                                                $key = false;
                                                if($ex['feature_name']=='freeLaptop'){
                                                   if($ex['value']=='yes'){
                                                        $value = "Free-Laptop";
                                                        $key = true;
                                                    }
                                                }

                                                if($ex['feature_name']=='hostel'){
                                                    if($ex['value']=='yes'){
                                                        $value = "In-campus hostel";
                                                        $key = true;
                                                    }
                                                }

                                                if($ex['feature_name']=='transport'){
                                                    if($ex['value']=='yes'){
                                                        $value = "Transport facility";
                                                        $key = true;
                                                    }
                                                }

                                                if($ex['feature_name']=='freeTraining'){
                                                    if($ex['value']=='english'){
                                                        $value = "Free Training English";
                                                        $key = true;
                                                    }
                                                    if($ex['value']=='sap'){
                                                        $value = "Free Training SAP";
                                                        $key = true;
                                                    }
                                                    if($ex['value']=='soft_skills'){
                                                        $value = "Free Training Soft Skills";
                                                        $key = true;
                                                    }
                                                    if($ex['value']=='foreign_language'){
                                                        $value = "Free Training Foreign Language";
                                                        $key = true;
                                                    }
                                                }

                                                if($ex['feature_name']=='dualDegree'){
                                                    if($ex['value']=='yes'){
                                                        $value = "MBA + PGDM";
                                                        $key = true;
                                                    }
                                                }

                                                if($ex['feature_name']=='jobAssurance'){

                                                    // echo "value = ".$ex['value'].", ";
                                                
                                                    if($ex['value']=='record'){
                                                        $value = "100% Placement Record";
                                                        $key = true;
                                                    }
                                                    if($ex['value']=='guarantee'){
                                                        $value = "Placement Guaranteed";
                                                        $key = true;
                                                    }
                                                    if($ex['value']=='assurance'){
                                                        $value = "Placement Assured";                                                        
                                                        $key = true;
                                                    }
                                                }

                                                if($ex['feature_name']=='wifi'){
                                                    if($ex['value']=='yes'){
                                                        $value = "Wifi Campus";
                                                        $key = true;
                                                    }
                                                }
                                                if($ex['feature_name']=='foreignStudy'){

                                                    //echo "foreignStudy value = ".$ex['value'];
                                                    if($ex['value']=='yes'){
                                                        $value = "Foreign Study Tour";
                                                        $key = true;
                                                    }

                                                     if($ex['value']=='no'){
                                                        $value = "Foreign Exchange Program";
                                                        $key = true;
                                                    }
                                                }
                                            
						if($ex['feature_name']=='acCampus'){
                                                    if($ex['value']=='yes'){
                                                        $value = "AC Campus";
                                                        $key = true;
                                                    }
                                                }	
					
    
                                            if($key == true){
                                            array_push($salient_features_arr, $value);
                                            }
                                            }?>
                                            <?php if(!empty($salient_features_arr)){?>
                                            <li class="Fnt11 mb6"><strong style="color:#5b5b5b">Salient Features:</strong>
                                            <?php echo implode(" <span class=\"sepClr\">|</span> ", $salient_features_arr);
                                                ?>
                                                </li>
                                                <?php }}?>
                                                 <?php }else{?>
                                                <?php if(!empty($cattr['morningClasses'])||!empty($cattr['weekendClasses'])||!empty($cattr['eveningClasses'])){?>
                                    <!--<li class="Fnt11 mb6"><strong style="color:#5b5b5b">Salient Features:</strong>-->
                                                    <?php
                                                    $salient_features_arr = array();
                                                    if($cattr['morningClasses']=='yes')
                                                        array_push($salient_features_arr, 'Morning Classes');
                                                    if($cattr['eveningClasses']=='yes')
                                                        array_push($salient_features_arr, 'Evening Classes');
                                                    if($cattr['weekendClasses']=='yes')
                                                        array_push($salient_features_arr, 'Weekend Classes');
                                            if(!empty($salient_features_arr)){?>            
                                       <li class="Fnt11 mb6"><strong style="color:#5b5b5b">Salient Features:</strong>
                                       <?php }?>
                                      <?php 
                                       echo implode(" <span class=\"sepClr\">|</span> ", $salient_features_arr);
                                                    ?>
                                                <?php }?>
                                                    </li>
                                                <?php }?>

                                        </ul>
                                    </div>
<?php
/*  This condition is added by Amit Kuksal on 30th Dec 2010 for showing the "View Course Details" link differnetly on Course Tab and Overview tab.
 *  Basically the idea was to put the Course Details code in a single file and bothe the Overview tab and Course Tab will call this file to reduce the code redundancy.
 */

if($tab == "course") { //  Here Course tab's content would be redered..

            if(!empty($course_details['seoListingUrl'])){
	    $url = $course_details['seoListingUrl'];
	    }else{
            $params = array('instituteId'=>$details['institute_id'],'instituteName'=>$details['title'],'type'=>'course','locality'=>$details['location']['0']['locality'],'city'=>$details['location']['0']['city_name'],'courseName'=>$course_details['title'],'courseId'=>$course_details['course_id'],'dbFlag'=>'old');
            $url = listing_detail_overview_url($params);
	    }    
?>
                            <div class="mt2 mb10"><a class="Fnt11" href="<?php echo $url;?>" onClick="trackEventByGA('LinkClick','LISTING_OVERVIEWTAB_VIEW_COURSE_DETAIL_CLICK');">View Course Details</a></div>
                                    <div class="lineSpace_15">&nbsp;</div>
</div>

<?php  } else { //  Now the Overview tab's content would be redered.. ?>
                                    <?php if(($identifier == 'institute') && (!empty($courseWiki))){?>
                                    <div  class="mt2 mb10" onClick ="showCourseWiki(<?php echo $course_id?>,'course')"><a href="<?php echo $overviewTabUrl;?>#courseDescription" class="Fnt11" onClick="trackEventByGA('LinkClick','LISTING_OVERVIEWTAB_VIEW_COURSE_DETAIL_CLICK');">View Course Details</a></div>
                                    <?php }elseif(($identifier == 'course') && (!empty($courseWiki))){?>
                                    <div id ="viewCourseDetails" class="mt2 mb10" onClick ="showCourseWiki(<?php echo $course_id?>,'alreadyOnCoursePage')"><a href="#courseDescription" onClick="trackEventByGA('LinkClick','LISTING_OVERVIEWTAB_VIEW_COURSE_DETAIL_CLICK');">View Course Details</a></div>
                                    <?php }?>
                                    <?php if($details['packType']=='1'||$details['packType']=='2'){?>
                                    
                                    
                                    <div style="float:left; width:180px"><input type="button" value="&nbsp;" class="sprt_nlt_btn nlt_btn11" onClick="trackEventByGA('LinkClick','LISTING_OVERVIEWTAB_COURSE_DETAIL_APPLY_BTN_CLICK'); focusOnField()" /></div>
                                    <?php }?>
                                    
                                    
                                    <?php if($details['courseDetails'][0]['displayOnlineFormButton']==='true'){
					    $urlToRedirectWhenFormExpired    = '/studentFormsDashBoard/MyForms/Index/';
				    ?>                                    
                                    <div class="onlineAppsListing">
<?php $inst_id = $details['institute_id'];if(array_key_exists('seo_url', $online_form_institute_seo_url[$inst_id])) {$seo_url = SHIKSHA_HOME.$online_form_institute_seo_url[$inst_id]['seo_url'];} else {$seo_url = "/Online/OnlineForms/showOnlineForms/".$details['courseDetails'][0]['course_id'];}?>
					<input type="button" class="onlineAppFormButton" value="Online Application Form" onClick="setCookie('onlineCourseId','<?php echo $details['courseDetails'][0]['course_id'];?>',0); checkOnlineFormExpiredStatus('<?php echo $details['courseDetails'][0]['course_id'];?>','<?php echo $urlToRedirectWhenFormExpired;?>','<?php echo $seo_url?>'); return false;"/>
					<span class="new-icon"></span>
					<div class="clear_B spacer10"></div>
					
                    <div style="float:left; padding-right:5px">
					<a  href="javascript:void(0);" onClick="showHowItWorksLayer();" >How it Works</a> |
					<!--How it Works Layer Starts here-->
					<div class="howitWorksLayerWrapListing" id="howitWorksLayerDiv" style="display:none; top:50px">
					    <span class="howitWorksPointerListing"></span>
					    <div class="howitWorksLayerContent">
						    <div>
						    <div class="selectCollege selectCollegeAlign"></div>
						    <div class="horArrow1"></div>
						    <div class="submitForm submitFormAligner"></div>
						    <div class="horArrow2"></div>
						    <div class="receiveForm receiveFormAligner"></div>
						    <div class="horArrow1"></div>
						    <div class="getUpdates getUpdatesAligner"></div>
						    <div class="horArrow2"></div>
						    <div class="onlineResult"></div>
						</div>    
						    <ul class="howWorksLayerDetail">
							<li class="firstItem">
							    <strong>Select Colleges</strong>
							    <p>Compare and shortlist colleges that you wish to apply</p>
							    
							</li>
							
							<li class="secItem">
							    <strong>Submit form</strong>
							    <p>Fill the application form once and use for multiple college applications. Also, attach documents and make online payment</p>
							    
							</li>
							
							<li class="thirdItem">
							    <strong>Institute receives form</strong>
							    <p>Institute receives and reviews your form.You get instant update as soon as institute reviews the form</p>
							    
							</li>
							
							<li class="fourthItem">
							    <strong>Get GD/PI Updates</strong>
							    <p>Institutes sends the GD/PI updates.You also track your application status at all the stages of admission process</p>
							    
							</li>
							
							<li class="fifthItem">
							    <strong>Know your result online</strong>
							    <p>Get updated about the final decision of the institute towards your admission application</p>
							</li>
						    </ul>
						    <div class="clearFix"></div>
						    <div class="studentNotice">Shiksha.com facilitates application form submission and tracking throught online process. It does not, however, gaurantees admissions.The final decision lies with the <br />institute itself.</div>
						    
						    <div class="howitWorkBtn"><input type="button" value="Start Now" title="Start Now" class="startNowBtn" onClick="setCookie('onlineCourseId','<?php echo $details['courseDetails'][0]['course_id'];?>',0); window.location.href='/Online/OnlineForms/showOnlineFormsHomepage';" /></div>
						    
					    </div>
					</div>
					<!--How it Works Layer Ends here-->
					</div>

					<div class="eligibilityBox" style="float:left">
						<a href="javascript:void(0);" onmouseover="if($('eligibilityLayerWrap')) $('eligibilityLayerWrap').style.display = '';" onmouseout="if($('eligibilityLayerWrap')) $('eligibilityLayerWrap').style.display = 'none';">Eligibility Criteria</a>

					    <?php $inst_id = $details['institute_id']; ?>

					    <!--Eligibility Layer Starts here-->
					    <div class="eligibilityLayerWrap" style="display:none;" id="eligibilityLayerWrap">
						<span class="eligibilityPointer"></span>
						<div class="applylayerContent">
						<ul>
						<?php if($institute_features[$inst_id]['min_qualification']):?>
						<li>
							<label>Min Qualification:</label>
						    <span><?php echo $institute_features[$inst_id]['min_qualification'];?></span>
						</li>
						<?php endif;?>
						<?php if($institute_features[$inst_id]['fees']):?>
						<li>
							<label>Form Fees:</label>
						    <span <?php if($institute_features[$inst_id]['discount']):?>class="line-through"<?php endif;?>>Rs.<?php echo $institute_features[$inst_id]['fees'];?></span>
						</li>
						<?php endif;?>
						<?php if($institute_features[$inst_id]['discount']):?>
						<li>
							<label>Pay only:</label>
						    <span><strong>Rs.<?php echo ($institute_features[$inst_id]['fees']-$institute_features[$inst_id]['discount']*$institute_features[$inst_id]['fees']/100)?></strong></span>
						</li>
						<?php endif;?>
						<?php if($institute_features[$inst_id]['exams_required']):?>
						<li>
							<label>Exams Accepted:</label>
						    <span><?php echo $institute_features[$inst_id]['exams_required'];?></span>
						</li>
						<?php endif;?>
						<?php if($institute_features[$inst_id]['courses_available']):?>
						<li>
							<label>Courses Available:</label><br />
												<span><?php echo $institute_features[$inst_id]['courses_available']?></span>
						</li>
						<?php endif;?>
						  <?php if($institute_features[$inst_id]['last_date']):?>
						<li><div class="lastDateNotify">Last Date to Apply: <span><?php echo $institute_features[$inst_id]['last_date'];?></span></div>
							
						</li>
						<?php endif;?>
						</ul>
						    <div class="clearFix"></div>
						</div>
					    </div>
						<!--Eligibility Layer Ends here-->
					</div>
					<div class="clear_B"></div>
				</div>
				<?php } ?>
                <div class="clear_B"></div>
                                    
                                    <div class="lineSpace_25">&nbsp;</div>
                                    <?php if(count(unserialize(base64_decode($details['courseList'])))>'1'){?>
                                    <?php if(count(unserialize(base64_decode($details['courseList'])))==1){?>
                                    <div class="Fnt15">Other Course offered</div>
                                    <?php }else{?>
                                    <div class="Fnt15">Other Courses offered</div>
                                    <?php }?>
                                    <?php 
                                    $count = 0;        
                                    foreach($courseList as $course) {
                                                if($count>=2)
                                                    break;
                                                if(!empty($course['listing_seo_url'])){
						$url = $course['listing_seo_url'];
						}else{
                                                $params = array('instituteId'=>$details['institute_id'],'instituteName'=>$details['title'],'type'=>'course','locality'=>$details['location']['0']['locality'],'city'=>$details['location']['0']['city_name'],'courseName'=>$course['courseTitle'],'courseId'=>$course['course_id'],$dbFlag = 'old');
                                                $url = listing_detail_overview_url($params);
						}

                                                ?>

                                    <div class="mt5">
                                        <ul class="rndBlts">
                                                <?php
                                               $a = (string)preg_replace('/[^A-Za-z0-9]/',' ',(string)$course['courseTitle']);
                                               $b = (string)preg_replace('/[^A-Za-z0-9]/',' ',(string)$course_details['title']);
                                               $a = str_replace('amp','',$a);
                                               $a = str_replace(" ","",$a);
                                               $b = str_replace('amp','',$b);
                                               $b = str_replace(" ","",$b);
                                                if(strcmp($a,$b)!=0){$count++;?>

                                                <li class="mb3"><a href="<?php echo $url;?>"><?php echo $course['courseTitle']?></a></li>
                                                <?php }?>


                                        </ul>
                                    </div>
                                    <?php }?>
                                    <?php }?>
                                    <?php echo "<pre>";
                                        //print_r(unserialize(base64_decode($details['courseList'])));
                                          echo "</pre>";
                                    ?>
                                    <?php if(count(unserialize(base64_decode($details['courseList'])))>'2'){?>
                                    <div class="pt3"><a href="<?php echo $courseTabUrl;?>" class="Fnt11 bld">View all courses</a></div>
                                    <?php }?>

				    <!-- Online form button Start -->
				    <?php if($details['courseDetails'][0]['displayOnlineFormButton']==='true'){?>
				    <div class="clear_B"></div>
				    <?php }?>
				    <!-- Online form button End -->

</div>

<script>
    function focusOnField(){
        var hht = eval(document.getElementById('reqInfoDispName_foralert_detail').offsetTop)-100;
	window.scrollTo(0, hht);
        document.getElementById('reqInfoDispName_foralert_detail').focus();
    }

    var obj = document.getElementById('courseDescription_nl');
    if(obj){
        if(obj.style.display == 'none'){
            var obj1 = document.getElementById('viewCourseDetails');
            if(obj1) obj1.innerHTML = '<a href="#courseDescription">View Course Details</a>';
        }
        if(obj.style.display != 'none'){
            var obj1 = document.getElementById('viewCourseDetails');
            if(obj1) obj1.innerHTML = '<a href="#">View Course Details</a>';
        }
    }
    </script>
<?php } // End of if($tab == "course"). ?>
