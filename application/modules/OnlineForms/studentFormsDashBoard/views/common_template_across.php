<?php 
function of_seo_url_string($uri = '',$separator="-",$numOfWords =40)
    {
        $uri = htmlspecialchars_decode($uri);
        $uri = htmlspecialchars_decode($uri);
        $uri = preg_replace("`\[.*\]`U","",$uri);
        $uri = preg_replace('`&(amp;)@%#!?#?[^A-Za-z0-9]+;`i','-',$uri);
        $uri = preg_replace(array("`[^a-z0-9]`i","`[-]+`") , "-", $uri);
        $uri = strtolower(trim($uri, '-'));
        $uri_array = explode('-',$uri);
        $uri_array = array_slice($uri_array,0,($numOfWords-1));
        $uri = ucwords(implode(' ',$uri_array));
        $uri = str_replace(" ", $separator, $uri);
        return $uri;
    }
?>
<div class="recommendedItems2">
            <ul>
            <?php  foreach ($instituteList as $inst_id=>$instituteList_object): 
            $inst_id_arry = explode("_",$inst_id);$inst_id = $inst_id_arry[0]; 
            $last_date_apply = strtotime($institute_features[$inst_id]['last_date']);
			$todyas_date = strtotime(date('Y-m-d'));
			$exp_date = strtotime(date('2014-09-01'));
			if($last_date_apply <= $exp_date)
			{
				    continue;
			}
			$form_expired = "0";
			if($todyas_date > $last_date_apply) {
				$form_expired = 1;
			}
			//$course = $instituteList_object->getFlagshipCourse();
            $courses = $instituteList_object->getCourses();
            
            $course = $courses[0];
            if(!$course || !$instituteList_object->getId()) {
				continue;
			}
			$course_id = $course->getId();
            /*if($inst_id=='22073'){
                $institute_features[$inst_id]['discount'] = '8.3333';
                $institute_features[$inst_id]['fees'] = '1200';
            }*/

    		if($inst_id=='36085' || $inst_id=='32728' || $inst_id=='47130'){
    			continue;
    		}

    		$otherCourses = array();
    		if(!empty($institute_features[$inst_id]['otherCourses'])) {
    		       $otherCourses = explode(",",$institute_features[$inst_id]['otherCourses']);
    		}
        
            ?>
            <li>
                	<div class="recommendedItemsDetail">
                    	<div class="checkCol"><span class="checkIcon"></span></div>
                        <div class="collegeDetailCol"> 
                        	<h4><a target="blank" href="<?php echo $instituteList_object->getUrl();?>"><?php echo $instituteList_object->getName();?>,</a> <span><?php  echo ((is_object($instituteList_object->getMainLocation()))?$instituteList_object->getMainLocation()->getCityName().","."India":'');?></span></h4>
                            <div class="collegeDetailsWrapper">
                                <?php 
                                $headerImg = $instituteList_object->getHeaderImage();
                                if(is_object($headerImg)){
                                    $headerImageURL = getImageVariant($instituteList_object->getHeaderImage()->getUrl(), 3); 
                                }
                                if(!$headerImageURL) 
                                $headerImageURL = '/public/images/recommendation-default-image.jpg'; ?>
                                <div class="collegePic"><img src="<?php echo $headerImageURL;?>" width="124px"; height="104px"/></div>
                                <div class="collegeDescription2">
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
                                    <h5><a href="<?php echo $course->getURL(); ?>"><?php echo $course->getName();?></a><?php endif;?></h5>
									<p>
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
                                </p>

									<div style="margin-top:3px;">
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
                                    <div style="margin-top:3px;">
                                    <?php
                                    $courseStatusArray = $CourseDetailLib->getCourseStatus(array($course->getId()));
                                    foreach ($courseStatusArray as $key => $value) {
                                        $courseStatus = $value['courseStatusDisplay'];
                                    }
                                    echo implode(", ", $courseStatus);
                                    ?>
                                    </div>
                                    <div style="margin-top:3px;">
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
  
                                    </div>									
									
									<?php if(count($otherCourses) > 0) { ?>
                                    <div id="otherCourses_<?php echo $institute_features[$inst_id]['id'];?>" class="more-othrtuples">
                                        <div class="otherCoursesLink_<?php echo $institute_features[$inst_id][id];?>">
                                            <a href="javascript:void(0);" class="more-tuples" onclick="getOnlineFormOtherCoursesDetails('<?php echo $institute_features[$inst_id][id];?>','departmentpage')">View more courses</a>
                                        </div>
                                    </div>
                                    <?php } ?>

                                    </div>
                                    
                                </div>
                                <div class="clearFix spacer10"></div>
				<?php if($institute_features[$inst_id]['externalURL']==''){ ?>
                                
                                <?php if(!empty($config_array[$course_id])) { ?>
                    <div class="approvalStrip">
                        <p>This online application  is</p> <span class="approvedStamp" <?php if($config_array[$course_id]['auth_text'] != '') { ?> onmouseover="if(typeof(OnlineForm!='undefined')) {OnlineForm.displayAdditionalInfoForInstitute('block','<?php echo 'authorisedLayerWrap'.$inst_id.$inst_id_arry[1];?>')}" onmouseout="if(typeof(OnlineForm!='undefined')){OnlineForm.displayAdditionalInfoForInstitute('none','<?php echo 'authorisedLayerWrap'.$inst_id.$inst_id_arry[1];?>')}" <?php } ?>></span> <p>by <strong><?php echo ( isset($config_array[$course_id]['institute_alias']) && ($config_array[$course_id]['institute_alias']!=''))?$config_array[$course_id]['institute_alias']:$instituteList_object->getName();?></strong></p>
                        <!--Authorised Layer Starts here-->
                        <div class="authorisedLayerWrap" style="display:none;" id="authorisedLayerWrap<?php echo $inst_id.$inst_id_arry[1];?>">
                            <span class="authorisedPointer"></span>
                            <div class="authorisedContent">
                                <div class="universityAvatar"><img src="<?php echo $config_array[$course_id]['auth_image'];?>" alt="" /></div>
                                <div class="universityDescription">
                                    <em><?php echo $config_array[$course_id]['auth_text'];?></em>
                                    
                                 <div class="signRow">
                                    <p class="signBlock"><a href="#"><?php echo $config_array[$course_id]['auth_sign_name'];?></a><br />
                                   <span><?php echo $config_array[$course_id]['auth_sign_post'];?></span></p>
                                   
                                   <p style="float:right;"><img src="<?php echo $config_array[$course_id]['auth_sign_image']?>" alt="<?php echo $config_array[$course_id]['auth_sign_name'];?>" /></p>
                                 </div>
                                    
                                </div>
                                <div class="clearFix"></div>
                            </div>
                        </div>
                        <!--Authorised Layer Ends here-->
                    </div>
                    <?php } ?>
                    
                                <div class="clearFix spacer10"></div>
				<?php } ?>
                                <div class="eligibilityBox" id="eligibilityBox<?php echo $inst_id; ?>">
	                                <a class="viewCriteria" href="javascript:void(0)" onmouseover="if(typeof(OnlineForm!='undefined')){OnlineForm.displayAdditionalInfoForInstitute('block','<?php echo 'eligibilityLayerWrap'.$inst_id.$inst_id_arry[1];?>'); $('eligibilityBox<?php echo $inst_id; ?>').style.zIndex='2';}" onmouseout="if(typeof(OnlineForm!='undefined')){OnlineForm.displayAdditionalInfoForInstitute('none','<?php echo 'eligibilityLayerWrap'.$inst_id.$inst_id_arry[1];?>'); $('eligibilityBox<?php echo $inst_id; ?>').style.zIndex='0';}">View eligibility criteria</a>
                                    <!--Eligibility Layer Starts here-->
                                    <div class="eligibilityLayerWrap" style="display:none;" id="eligibilityLayerWrap<?php echo $inst_id.$inst_id_arry[1];?>">
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
						<?php if($institute_features[$inst_id]['instituteId']=='28230' || $institute_features[$inst_id]['instituteId']=='36454') {?>
                                            <span <?php if($institute_features[$inst_id]['discount']):?>class="line-through"<?php endif;?>>Rs.<?php echo $institute_features[$inst_id]['fees'];?> till 15<sup>th</sup> Feb 2016 and Rs. 1,500 from 16<sup>th</sup> Feb 2016 till 24<sup>th</sup> Feb 2016.</span>
					    <?php } else { ?>
					    <span <?php if($institute_features[$inst_id]['discount']):?>class="line-through"<?php endif;?>>Rs.<?php echo $institute_features[$inst_id]['fees'];?></span>
					    <?php } ?>
						
                                        </li>
                                        <?php endif;?>
                                        <?php if($institute_features[$inst_id]['discount']):?>
                                        <li>
                                        	<label>Pay only:</label>
                                            <span><strong>Rs.<?php echo round($institute_features[$inst_id]['fees']-$institute_features[$inst_id]['discount']*$institute_features[$inst_id]['fees']/100)?></strong></span>
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
                                          <?php if($institute_features[$inst_id]['last_date'] && $inst_id!='35413' && $inst_id!='35407'):?>
                                        <li><div class="lastDateNotify">Last Date to Apply: <span <?php if($form_expired == 1): echo "style='text-decoration:line-through;'"; endif;?>><?php echo date('d M, Y',strtotime($institute_features[$inst_id]['last_date']));?></span></div>
                                        	
                                        </li>
                                         <?php endif;?>
                                        <?php if($form_expired == 1):?>
                                        <li class="lastDateNotify" style="padding-top:5px"><p style="color:red; font-size:14px">The application deadline for Year <?php echo date('Y',strtotime($institute_features[$inst_id]['last_date']));?> has expired</p></li>
                                        <?php endif;?>
                                        </ul>
                                            <div class="clearFix"></div>
                                        </div>
                                    </div>
                            		<!--Eligibility Layer Ends here-->
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if(!empty($institute_features)):?>
                    <div class="recommendedOffers2">
                    <div class="applyWrapper">
                    <p <?php if($form_expired == 1): echo "style='text-decoration:line-through;'"; endif;?>><?php if(!empty($institute_features[$inst_id]['last_date']) && $inst_id!='35413' && $inst_id!='35407'):?>Last Date: <span class="bld" style="font-size:15px;text-decoration:none;"><?php echo date("d M",strtotime($institute_features[$inst_id]['last_date']));?></span><br /><?php endif;?></p>
                        <?php 
                        $seoURL = str_replace('<courseName>', strtolower(of_seo_url_string($course->getName(),'-',30)), $config_array[$course_id]['seo_url']);
                        $seoURL = str_replace('<courseId>', $course_id, $seoURL);
                            $dls_ipl_url = ($seoURL!='') ? $seoURL : SHIKSHA_HOME.'/Online/OnlineForms/showOnlineForms/'.$course->getId();
                        ?>                    
                         <?php if($form_expired == 0):
            				$externalURL = '/Online/OnlineForms/showPage/'.base64_encode($institute_features[$inst_id]['externalURL']);
                            if($institute_features[$inst_id]['externalURL']==''){
            			?>
                    	
                        <?php }else{ ?>

<?php
/**
 * Define click action for external online form
 */
// $clickAction = "checkUserLogin('".$course->getId()."',false,true);";
// if($validateuser != 'false') {
// 	$clickAction = "showExternalFormOverlay('/Online/OnlineFormConversionTracking/send/".$course->getId()."');";
// }
//redirect to seo online form page for External forms.

?>
                        <?php } ?>
                    	<?php else:?>
                    	<div style="text-align:center; z-index:99999999999; position:relative;" onmouseover="if(typeof(OnlineForm!='undefined')){OnlineForm.displayAdditionalInfoForInstitute('block','<?php echo 'applylayerWrap'.$inst_id.$inst_id_arry[1];?>')}" onmouseout="if(typeof(OnlineForm!='undefined')){OnlineForm.displayAdditionalInfoForInstitute('none','<?php echo 'applylayerWrap'.$inst_id.$inst_id_arry[1];?>')}">
                    	<img src="<?php echo SHIKSHA_HOME;?>/public/images/onlineforms/expired-img.gif"/><br />
                    	</div>
                    	<?php endif;?>
                    	
                            </div>
			<p>
				<?php if(!empty($institute_features[$inst_id]['fees'])):?>Form Fees: <?php if($institute_features[$inst_id]['instituteId']=='28230' || $institute_features[$inst_id]['instituteId']=='36454') {?>
				            <span <?php if($institute_features[$inst_id]['discount']) {echo "style='text-decoration:line-through;'";} else {echo "style='text-decoration:none;'";}?>>Rs.<?php echo $institute_features[$inst_id]['fees'];?> till 15<sup>th</sup> Feb 2016 and Rs. 1,500 from 16<sup>th</sup> Feb 2016 till 24<sup>th</sup> Feb 2016.</span>
					    <?php } else { ?>
					    <span <?php if($institute_features[$inst_id]['discount']) {echo "style='text-decoration:line-through;'";} else {echo "style='text-decoration:none;'";}?>>Rs.<?php echo $institute_features[$inst_id]['fees'];?></span>
					    <?php } ?>
					    <?php endif;?>
				<?php if($institute_features[$inst_id]['fees']=='0'){ ?>
					Form Fees: <span style='text-decoration:none;'>FREE</span>
				<?php } ?>
			</p>
                        <?php if(!empty($institute_features[$inst_id]['discount'])):?>
                        <!--div class="discount"><?php echo $institute_features[$inst_id]['discount'];?>%<br />Off</div -->
                        <p class="payAmount">Pay only: <strong>Rs.<?php echo round($institute_features[$inst_id]['fees']-$institute_features[$inst_id]['discount']*$institute_features[$inst_id]['fees']/100)?></strong></p>
                        <?php endif;?>

            <?php 
            $clickAction = "window.location='".$dls_ipl_url."?tracking_keyid=".$trackingPageKeyId."'";
            ?>
			<?php if($institute_features[$inst_id]['externalURL']==''){ ?>
			<div style="margin-top:12px;text-align:center;"><img src="/public/images/app-stamp1.gif" border=0 /></div><div class="clear_B">&nbsp;</div>
			<div style="position: relative;">
			<a class="orange-button" style="color:white; padding-bottom:6px !important;  font: bold 14px Arial,Helvetica,sans-serif !important; height: 20px !important;" title="Apply Now"  onmouseover="if(typeof(OnlineForm!='undefined')){OnlineForm.displayAdditionalInfoForInstitute('block','<?php echo 'applylayerWrap'.$inst_id.$inst_id_arry[1];?>');return true;}" onmouseout="if(typeof(OnlineForm!='undefined')){OnlineForm.displayAdditionalInfoForInstitute('none','<?php echo 'applylayerWrap'.$inst_id.$inst_id_arry[1];?>');return true;}" href="<?=$dls_ipl_url.'?tracking_keyid='.$trackingPageKeyId?>"  onclick="<?php echo $clickAction; ?>"><img src="/public/images/applyIcon.png"  style="visibility: hidden;"  /> Apply Now <span class="nxt-arr" style="visibility: hidden;">&nbsp;</span></a>
			<!--Apply Layer Starts here-->
                            <div class="applylayerWrap" id="applylayerWrap<?php echo $inst_id.$inst_id_arry[1];?>" style="display:none;<?php if($form_expired == 1) {"top:40px;";} if($inst_id=='28230'){echo "top:40px;";} ?>">
                            	<span class="applylayerPointer"></span>
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
                                            <?php if($institute_features[$inst_id]['instituteId']=='28230' || $institute_features[$inst_id]['instituteId']=='36454') {?>
                                            <span <?php if($institute_features[$inst_id]['discount']):?>class="line-through"<?php endif;?>>Rs.<?php echo $institute_features[$inst_id]['fees'];?> till 15<sup>th</sup> Feb 2016 and Rs. 1,500 from 16<sup>th</sup> Feb 2016 till 24<sup>th</sup> Feb 2016.</span>
					    <?php } else { ?>
					    <span <?php if($institute_features[$inst_id]['discount']):?>class="line-through"<?php endif;?>>Rs.<?php echo $institute_features[$inst_id]['fees'];?></span>
					    <?php } ?>
                                        </li>
                                        <?php endif;?>
                                        <?php if($institute_features[$inst_id]['discount']):?>
                                        <li>
                                        	<label>Pay only:</label>
                                            <span><strong>Rs.<?php echo round($institute_features[$inst_id]['fees']-$institute_features[$inst_id]['discount']*$institute_features[$inst_id]['fees']/100)?></strong></span>
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
                                        	<label>Courses Available:</label>
											<span><?php echo $institute_features[$inst_id]['courses_available']?></span>
                                        </li>
                                         <?php endif;?>
                                          <?php if($institute_features[$inst_id]['last_date'] && $inst_id!='35413' && $inst_id!='35407'):?>
                                        <li><div class="lastDateNotify">Last Date to Apply: <span <?php if($form_expired == 1): echo "style='text-decoration:line-through;'"; endif;?>><?php echo date('d M, Y',strtotime($institute_features[$inst_id]['last_date']));?></span></div>
                                        	
                                        </li>
                                         <?php endif;?>
                                         <?php if($form_expired == 1):?>
                                        <li class="lastDateNotify" style="padding-top:5px"><p style="color:red; font-size:14px">The application deadline for Year <?php echo date('Y',strtotime($institute_features[$inst_id]['last_date']));?> has expired</p></li>
                                        <?php endif;?>
                                    </ul>
                                    <div class="clearFix"></div>
                                </div>
                                <div class="clearFix"></div>
                            </div>
                            <!--Apply Layer Ends here-->
			    </div>
			<?php }
			else{
				    ?>
				    <div style="margin-top:12px;text-align:center; visibility: hidden;"><img src="/public/images/app-stamp1.gif" border=0 /></div><div class="clear_B">&nbsp;</div>
				    <div style="position: relative;">
				    <button class="orange-button" type="button" style="font: bold 14px Arial,Helvetica,sans-serif !important;" title="Apply Now"  onmouseover="if(typeof(OnlineForm!='undefined')){OnlineForm.displayAdditionalInfoForInstitute('block','<?php echo 'applylayerWrap'.$inst_id.$inst_id_arry[1];?>');return true;}" onmouseout="if(typeof(OnlineForm!='undefined')){OnlineForm.displayAdditionalInfoForInstitute('none','<?php echo 'applylayerWrap'.$inst_id.$inst_id_arry[1];?>');return true;}" onclick="<?php echo $clickAction; ?>" ><img src="/public/images/applyIcon.png" /> Apply Now </button>
				    <p class="college-website-text">[Redirects to college website]</p>
				    <!--Apply Layer Starts here-->
                            <div class="applylayerWrap" id="applylayerWrap<?php echo $inst_id.$inst_id_arry[1];?>" style="display:none;<?php if($form_expired == 1) {"top:40px;";} if($inst_id=='28230'){echo "top:40px;";} ?>">
                            	<span class="applylayerPointer"></span>
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
                                            <?php if($institute_features[$inst_id]['instituteId']=='28230' || $institute_features[$inst_id]['instituteId']=='36454') {?>
                                            <span <?php if($institute_features[$inst_id]['discount']):?>class="line-through"<?php endif;?>>Rs.<?php echo $institute_features[$inst_id]['fees'];?> till 15<sup>th</sup> Feb 2016 and Rs. 1,500 from 16<sup>th</sup> Feb 2016 till 24<sup>th</sup> Feb 2016.</span>
					    <?php } else { ?>
					    <span <?php if($institute_features[$inst_id]['discount']):?>class="line-through"<?php endif;?>>Rs.<?php echo $institute_features[$inst_id]['fees'];?></span>
					    <?php } ?>
                                        </li>
                                        <?php endif;?>
                                        <?php if($institute_features[$inst_id]['discount']):?>
                                        <li>
                                        	<label>Pay only:</label>
                                            <span><strong>Rs.<?php echo round($institute_features[$inst_id]['fees']-$institute_features[$inst_id]['discount']*$institute_features[$inst_id]['fees']/100)?></strong></span>
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
                                        	<label>Courses Available:</label>
											<span><?php echo $institute_features[$inst_id]['courses_available']?></span>
                                        </li>
                                         <?php endif;?>
                                          <?php if($institute_features[$inst_id]['last_date'] && $inst_id!='35413' && $inst_id!='35407'):?>
                                        <li><div class="lastDateNotify">Last Date to Apply: <span <?php if($form_expired == 1): echo "style='text-decoration:line-through;'"; endif;?>><?php echo date('d M, Y',strtotime($institute_features[$inst_id]['last_date']));?></span></div>
                                        	
                                        </li>
                                         <?php endif;?>
                                         <?php if($form_expired == 1):?>
                                        <li class="lastDateNotify" style="padding-top:5px"><p style="color:red; font-size:14px">The application deadline for Year <?php echo date('Y',strtotime($institute_features[$inst_id]['last_date']));?> has expired</p></li>
                                        <?php endif;?>
                                    </ul>
                                    <div class="clearFix"></div>
                                </div>
                                <div class="clearFix"></div>
                            </div>
                            <!--Apply Layer Ends here-->
				    </div>
				    <?php
			}?>
                    </div> 

                    

                    <?php endif;?>
                </li>
		  
                <?php endforeach;?>
            </ul>
        </div>

