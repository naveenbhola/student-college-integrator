<?php
global $onlineFormsDepartments;
$department = $this->courselevelmanager->getCurrentDepartment();

?>
<div style="width:946px; margin:0 auto; text-align:left">
<?php if($courseId>0):?>
    <div id="appsFormHeader" style="padding:0">
	<!--Starts: Institute Header -->
	<?php $this->load->view('Online/customizedLoginHeader'); ?>
	<!--Ends: Institute Header-->
	<?php endif;?>
</div>
<div class="spacer10 clearFix"></div>
	<?php 			 
	if($userId == '' || $isValidResponseUser == 'no'){
		$this->load->view('shortRegistrationWidget');
	 }?>
<div style="display:none;" id="userLoginOverlay_online" >
	<div>
		<input type = "hidden" name = "loginaction_ForAnA" id = "loginaction_ForAnA" value = ""/>
		<input type = "hidden" name = "loginflag_ForAnA" id = "loginflag_ForAnA" value = ""/>
		<input type = "hidden" name = "loginflagreg_ForAnA" id = "loginflagreg_ForAnA" value = ""/>
		<input type = "hidden" name = "loginactionreg_ForAnA" id = "loginactionreg_ForAnA" value = ""/>
		<input type = "hidden" name = "tyepeOfRegistration_ForAnA" id = "tyepeOfRegistration_ForAnA" value = ""/>


		<!--Login Layer Starts here-->
		<div class="loginLayer" id="loginLayer">
			
			<?php if(isset($courseId) && $courseId>0):?>
           		 <?php if(!empty($instituteList) && is_array($instituteList)):?>
           	<div class="loginRight2">
           <div class="other-wrapper">     
           	<div class="shadedBox" style="background-color:#f5f3f4">	 
        	<div class="recommendedItems3" style="margin-top:0">
            
            <?php foreach ($instituteList as $inst_id=>$instituteList_object): 
            $inst_id_arry = explode("_",$inst_id);
            $inst_id = $inst_id_arry[0];
                $course = $instituteList_object->getCourses()[0];
                 // $course = $instituteList_object->getFlagshipCourse();
            if(!$course || !$instituteList_object->getId()) { 
                continue;
			}

            $otherCourses = array();
            if(!empty($institute_features[$inst_id]['otherCourses'])) {
                   $otherCourses = explode(",",$institute_features[$inst_id]['otherCourses']);
            }
            ?>
                	<div class="recommendedItemsDetail" style="width:100%">
                    	<div class="collegeDetailCol" style="width:100%; text-align:left">
                        	<div class="collegeDetailsWrapper" style="margin-top:0">
							<?php $headerImg = $instituteList_object->getHeaderImage();
                                if(is_object($headerImg)){
                                    $headerImageURL = getImageVariant($instituteList_object->getHeaderImage()->getUrl(), 3); 
                                }
                                if(!$headerImageURL) 
                                $headerImageURL = '/public/images/recommendation-default-image.jpg'; ?>                                <div class="collegePic2 shadedBox"><img width="120" height="100" src="<?php echo $headerImageURL;?>" /></div>
                               <div class="collegeDtlsBox" id="otherCourses_<?php echo $institute_features[$inst_id]['id'];?>">
                                <div class="collegeDescription2" >
                                	<h2 style="margin-bottom:0; padding-bottom:0; border:0; font-size:16px"><a target="blank" href="<?php echo $instituteList_object->getURL();?>"><?php echo $instituteList_object->getName();?>,</a></h2> 
                                    <span><?php echo $instituteList_object->getMainLocation()->getCityName().","."India";?></span>
                            
									<?php $reviewData = Modules::run('CollegeReviewForm/CollegeReviewController/getAverageRatingAndCountByCourseId', $course->getId());
                                    if($reviewData['averageRating']){
                                        $alumniRating = round($reviewData['averageRating']);?>						
                                        <div class="alumniRating">
                                        <span>Alumni Rating:&nbsp;&nbsp;</span>
                                        <span>
                                        <?php for($i=0;$i<$alumniRating;$i++):?>
                                        <img border="0" src="/public/images/nlt_str_full.gif">
                                        <?php endfor;?>
                                        </span>
                                        <span class="rateNum">&nbsp;<?php echo $alumniRating;?>/5</span>
                                        </div>
                                    <?php } ?>
                                   
                                    <?php if($course):?>
                                    <div id="OF_INST_COURSE_DETAILS">

                                    <h2 style="font-size:14px; line-height:18px; font-weight:bold; padding-bottom:3px; display:block"><a href="<?php echo $course->getURL();?>"><?php echo $course->getName();?></a></h2>
                                    <div class="clearFix"></div>
									 
                                    <?php 
                                    $instInfo = getExtraInfo($course);
                                    echo $instInfo['extraInfo']['duration']?$instInfo['extraInfo']['duration']:"";
                                    echo $instInfo['extraInfo']['educationType']?", ".$instInfo['extraInfo']['educationType']:"";
                                    if ($instInfo['courseLevel'] || $instInfo['courseCredential']){
                                        echo ", ".$instInfo['courseLevel'];
                                        if($instInfo['courseCredential']){
                                        echo ' '.$instInfo['courseCredential'];
                                        }
                                    }
                                    ?>
                                    <div class="Fnt11 mb6">
									<?php
									$approvalsArray = array();
									$approvals = $course->getRecognition();
                                    foreach($approvals as $approval) {
								        if($approval->getName() != 'NBA'){
                                           $approvalsArray[] = $approval->getName().' Approved'; 
                                        }
									}
									echo implode(', ',$approvalsArray);
									?>
									</div>
                                    <div class="feeStructure2">
                                    <?php 
                                    if($instInfo['fees']){
                                        echo $instInfo['fees'];}
                                    $feeObj = $course->getFees();
                                    if(is_object($feeObj)){
                                    	$feeDisc = $feeObj->getFeeDisclaimer();
                                    }
                                    if($feeDisc){ 
                                        echo FEES_DISCLAIMER_TEXT; 
                                       }
                                    ?>

                                    <?php endif;?>
                                    </div>
                                    
                                    <div class="clearFix spacer10"></div>
                                
                                </div>
                                
                                
                            </div>
                        </div>
                        </div>
                    </div>
                
        </div>
        <div class="clearFix"></div>
        </div>
        	<div class="clearFix"></div>

        </div>
        <?php if(count($otherCourses) > 0) { ?>
         <div class="exapnadBox otherCoursesLink_<?php echo $institute_features[$inst_id][id];?>"><a  href="javascript:void(0);" onclick="getOnlineFormOtherCoursesDetails('<?php echo $institute_features[$inst_id][id];?>', 'clientpage')">+<?php echo count($otherCourses);?> More Courses</a></div>
        <?php } ?>
         <div  class="eligibilityBox" id="eligibilityBox<?php echo $inst_id; ?>" style="margin: 5px 0 0 0">
                                    <a class="viewCriteria" href="javascript:void(0)" onmouseover="if(typeof(OnlineForm!='undefined')){OnlineForm.displayAdditionalInfoForInstitute('block','<?php echo 'eligibilityLayerWrap'.$inst_id.$inst_id_arry[1];?>'); $('eligibilityBox<?php echo $inst_id; ?>').style.zIndex='2';}" onmouseout="if(typeof(OnlineForm!='undefined')){OnlineForm.displayAdditionalInfoForInstitute('none','<?php echo 'eligibilityLayerWrap'.$inst_id.$inst_id_arry[1];?>'); $('eligibilityBox<?php echo $inst_id; ?>').style.zIndex='0';}">View eligibility criteria</a>
                                     
                                    <!--Eligibility Layer Starts here-->
                                    <div class="eligibilityLayerWrap" style="display:none; left:-35px" id="eligibilityLayerWrap<?php echo $inst_id.$inst_id_arry[1];?>">
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
                                            <?php if($institute_features[$inst_id]['instituteId']=='28230') {?>
                                            <span <?php if($institute_features[$inst_id]['discount']):?>class="line-through"<?php endif;?>>Rs.<?php echo $institute_features[$inst_id]['fees'];?> till Feb 15 (Thereafter Rs.1500)</span>
                        <?php } else { ?>
                        <span <?php if($institute_features[$inst_id]['discount']):?>class="line-through"<?php endif;?>>Rs.<?php echo $institute_features[$inst_id]['fees'];?></span>
                        <?php } ?>
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
                                        <li><div class="lastDateNotify">Last Date to Apply: <span><?php echo date('d M, Y',strtotime($institute_features[$inst_id]['last_date']));?></span></div>
                                            
                                        </li>
                                         <?php endif;?>
                                        </ul>
                                            <div class="clearFix"></div>
                                        </div>
                                    </div>
                                    <!--Eligibility Layer Ends here-->
                                </div>
       </div> 
        <div class="clearFix spacer20"></div>
        <div class="clearFix spacer10"></div>
             <div class="other-wrapper">
                <div class="shadedBox">
                	<div class="approvalStrip2">
                                <p>This online application  is</p> <span class="approvedStamp"></span> <p>by <strong><?php echo ( isset($config_array[$courseId]['institute_alias']) && ($config_array[$courseId]['institute_alias']!=''))?$config_array[$courseId]['institute_alias']:$instituteList_object->getName();?></strong></p>
                                </div>
                	<div class="clearFix spacer10"></div>                
					<!--Authorised Layer Starts here-->
                                    <div class="authorisedLayerWrap0" id="authorisedLayerWrap<?php echo $inst_id.$inst_id_arry[1];?>">
                                        <div class="authorisedContent0">
                                            <div class="universityAvatar"><img src="<?php echo $config_array[$courseId]['avatar_image_url'];?>" alt="" /></div>
                                            <div class="universityDescription" style="width:352px">
                                                <em><?php echo $config_array[$courseId]['auth_text'];?></em>
                                                
                                             <div class="signRow">
                                                <p class="signBlock"><?php echo $config_array[$courseId]['auth_sign_name'];?><br />
                                               <span><?php echo $config_array[$courseId]['auth_sign_post'];?></span></p>
                                               
                                               <p style="float:right;"><img src="<?php echo $config_array[$courseId]['auth_sign_image']?>" alt="<?php echo $config_array[$courseId]['auth_sign_name'];?>" /></p>
                                             </div>
                                                
                                            </div>
                                            <div class="clearFix"></div>
                                        </div>
                                    </div>
                                    <!--Authorised Layer Ends here-->
               </div>                     
				<div class="clearFix"></div>
        </div>
         </div>
          <?php endforeach;?>
          <?php endif;?>
			<?php endif;?>
			<div class="clearFix"></div>
		</div>
		<!--Login Layer Ends here-->

  

    </div>
</div>
<?php if($courseId>0):?>
<div id="specialComment">
<div class="spacer20 clearFix"></div>
<div class="whyApplyCont">
			    <h5>Why Apply through shiksha is a good idea?</h5>
                <div class="loginLeft2">
			    <ul>
				<li>
				    <div class="bestCollege bestCollegePosition"></div>
				    <strong>Best Colleges</strong>
				    <p>Top colleges of your choice, all at one place</p>
				</li>
				<li>
				    <div class="multipleSubmissions multipleSubmissionsPos"></div>
				    <strong>Multiple Submissions</strong>
				    <p>Fill once, apply to multiple colleges</p>
				</li>
				
				<li>
				    <div class="saveTime2"></div>
				    <strong>Save time</strong>
				    <p>Cut the queue, submit forms online conveniently</p>
				</li>
				</ul>
                </div>
                
                <div class="loginRight2">
                	<ul>
                    	<li>
                            <div class="trackSubmissions2"></div>
                            <strong>Track submissions</strong>
                            <p>Know which stage of application process your form is at</p>
                        </li>
                        <li>
                            <div class="genuineForms"></div>
                            <strong>Genuine Forms</strong>
                            <p>All forms are genuine and authorised by the <br />institutes. <!--a href="#">View authorisation</a--></p>
                        </li>
                    </ul>
                </div>
			</div>

            <div class="spacer20 clearFix"></div>
            <!--How it Works Layer Starts here-->
            <div class="howItWorks2">
            <h5>How it works?</h5>
            <div class="howitWorksLayerContent0" id="howitWorksLayerContent">
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
                        <strong>Select Colleges </strong>
                        <p>Compare and shortlist colleges that you wish to apply</p>
                        
                    </li>
                    
                    <li class="secItem">
                        <strong>Submit form</strong>
                        <p>Fill the application form once and use for multiple college applications. Also, attach documents and make online payment</p>
                        
                    </li>
                    
                    <li class="thirdItem">
                        <strong>Institute receives form</strong>
                        <p>Institute receives and reviews your form. You get instant update as soon as institute reviews the form</p>
                        
                    </li>
                    
                    <li class="fourthItem">
                        <strong>Get <?=$onlineFormsDepartments[$department]['gdPiName']?> Updates</strong>
                        <p>Institute sends the <?=$onlineFormsDepartments[$department]['gdPiName']?> updates. You also track your application status at all the stages of admission process</p>
                        
                    </li>
                    
                    <li class="fifthItem">
                        <strong>Know your result online</strong>
                        <p>Get updated about the final decision of the institute towards your admission application</p>
                    </li>
                </ul>
				<div class="clearFix"></div>
				<div class="studentNotice">Shiksha.com facilitates application form submission and tracking throughout online process. It does not, however, guarantees admissions. The final decision lies with the <br />institute itself.</div>
				</div>
<!--How it Works Layer Ends here-->
</div>
</div>
<?php endif;?>
<div class="clearFix"></div>
</div>
<script>
if('<?php echo $form_is_expired?>' == 'expired') {
	alert('This form has been expired');
	window.location= '<?php echo SHIKSHA_HOME."/Online/OnlineForms/showOnlineFormsHomepage/".$department;?>';
}
 //   addOnBlurValidate($('RegistrationForm_ForAnA'));
   // addOnBlurValidate($('LoginForm_ForAnA'));
    var OnlineForm = {};
    OnlineForm.displayAdditionalInfoForInstitute = function (style,divId) {
    	if($(divId)) {
    		$(divId).style.display = style;
    	}
    }

if(<?php echo (isset($_REQUEST['login']))?'true':'false'; ?>)
        showHideLoginOnline('','none');
</script>

<?php if($courseId==23548){ ?>
<!--Start of Zopim Live Chat Script-->
<script type="text/javascript">
window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=
d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
_.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute('charset','utf-8');
$.src='//v2.zopim.com/?2dFVgfJXa3zUqH4em2v5U2szBuOo6IQr';z.t=+new Date;$.
type='text/javascript';e.parentNode.insertBefore($,e)})(document,'script');
</script>
<!--End of Zopim Live Chat Script-->
<?php } ?>
