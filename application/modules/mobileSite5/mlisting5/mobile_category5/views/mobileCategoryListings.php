<?php
	global $shiksha_site_current_url;global $shiksha_site_current_refferal ;

        //Check for Request E-Brochure from Also on Shiksha institutes. From here, the referral URL will be the current URL
        if(strpos($shiksha_site_current_url,'alsoOnShiksha')!==false){
                $shiksha_site_current_url = $shiksha_site_current_refferal;
        }
?>

<?php if(isset($_COOKIE['MOB_A_C'])){
	$appliedCourseArr = explode(',',$_COOKIE['MOB_A_C']);
}
?>

	<?php   $mobile_details = json_decode($_COOKIE['ci_mobile_capbilities'],true);
    		$screenWidth = $mobile_details['resolution_width'];
    		$screenHeight = $mobile_details['resolution_height'];
    ?>

        <?php  // check compare btn is added or not
	$cookieCmp = 'compare-mobile-global-categoryPage';
	$courseCmpTot = array();
	$checkCmpIdVal = array();
	if($_COOKIE[$cookieCmp] !=''){
	$courseCmpTot = explode('|||',$_COOKIE[$cookieCmp]);
		foreach($courseCmpTot as $courseCmpTot)
		{
		$expVal = explode('::',$courseCmpTot);
		array_push($checkCmpIdVal,$expVal[1]);	
		}
	}
	?>

<script src="//<?php echo JSURL; ?>/public/mobile5/js/<?php echo getJSWithVersion("jquery.flexslider-min","nationalMobile"); ?>";></script>
<!-- Display Institute List -->
<script> var pageType='';</script>
<?php
	$courses_list;
	$count = 0;
	$listings = array();
	
	foreach($institutes as $institute) {
		$course = $institute->getFlagshipCourse();

                global $appliedFilters;
                if($request){
                      $appliedFilters = $request->getAppliedFilters();
                      $course->setCurrentLocations($request);
                }
                $displayLocation = $course->getCurrentMainLocation();
                $courseLocations = $course->getCurrentLocations();
                if($appliedFilters){
                        foreach($courseLocations as $location){
                               $localityId = $location->getLocality()?$location->getLocality()->getId():0;
                               if(in_array($localityId,$appliedFilters['locality'])){
                                       $displayLocation = $location;
                                       break;
                               }
                               if(in_array($location->getCity()->getId(),$appliedFilters['city'])){
                                       $displayLocation = $location;
                                       break;
                               }
                         }
                }
                if(!$courseLocations || count($courseLocations) == 0){
                         $courseLocations = $course->getLocations();
                }
                if(!$displayLocation){
                         $displayLocation = $course->getMainLocation();
                }

		//$courses = $institute->getCourses();  Memory Optimization
		$count++;
		$locations = $institute->getLocations();

                $additionalURLParams = "";
                if($request){
                      if(count($course->getLocations()) > 1){
                           if($request->getCityId() > 1){
                                $additionalURLParams = "?city=".$displayLocation->getCity()->getId();
                                if($request->getLocalityId()){
                                        $additionalURLParams .= "&locality=".$request->getLocalityId();
                                }
                           }
                           $course->setAdditionalURLParams($additionalURLParams);
                           $institute->setAdditionalURLParams($additionalURLParams);
                      }
                }

?>


		<section class="content-wrap2 <?php if($count==1 && $ajaxRequest!='true'){ echo "";} ?> clearfix" >
		        <?php if(isset($alsoOnShiksha) && $alsoOnShiksha=="true" && $count==1 && isset($pageType) && $pageType=='course'){echo '<h2 class="ques-title">
			    <p>
			     Institutes Offering Similar courses
			   </p>
			</h2>';} ?>
			<?php if(isset($alsoOnShiksha) && $alsoOnShiksha=="true" && $count==1 && $pageType!='course'){echo '<h2 class="ques-title">
			    <p>
			     Check out Similar '.$collegeOrInstituteRNR.'s
			   </p>
			</h2>';} ?>

		<article class="req-bro-box shortlist-box clearfix" id="categoryPageShortListing<?php echo $course->getId();?>">

				<div class="details">
					<div class="comp-detail-item">
					<div>
					<h4 title="<?php echo html_escape($institute->getName()).' , '  ?><?php if($displayLocation){ echo $displayLocation->getLocality()->getName()?$displayLocation->getLocality()->getName().", ":"";?><?=$displayLocation->getCity()->getName();}else{
						 echo $institute->getMainLocation()->getLocality()->getName()?$institute->getMainLocation()->getLocality()->getName().", ":"";?>	<?=$institute->getMainLocation()->getCity()->getName();}?>">
						 <a href="<?php echo $institute->getURL(); ?>">
						 <?php echo html_escape($institute->getName()); ?>,
						 
						<span><?php if($displayLocation){ echo $displayLocation->getLocality()->getName()?$displayLocation->getLocality()->getName().", ":"";?><?=$displayLocation->getCity()->getName();}else{
						 echo $institute->getMainLocation()->getLocality()->getName()?$institute->getMainLocation()->getLocality()->getName().", ":"";?><?=$institute->getMainLocation()->getCity()->getName();}?>
						 
						 <!-- Display Country name in case of Study Abroad -->
						 <?php if(isset($isStudyAbroad) && $isStudyAbroad == "true"){
							echo ", ".$institute->getMainLocation()->getCountry()->getName();
						 }?>
						 </span>
						 </a>
					</h4>
				<div style="font-size: 0.9em; margin-bottom: 8px; width: 100%;"><a href="<?php echo $course->getURL(); ?>" onClick="setCookie('currentCourse','<?php echo $course->getId();?>','','');" style="color:#000"><?php echo html_escape($course->getName()); ?></a></div>
					<ul style="color:#000;" onClick="setCookie('currentCourse','<?php echo $course->getId();?>','',''); location.href = '<?=$course->getURL()?>';">
						

                                                <?php   $affiliations = $course->getAffiliations();
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

                                                <?php if($course->getFees($displayLocation->getLocationId()) != ''){ ?>
 					                                                     <li><i class="icon-rupee"></i><p><label>Fees:</label> <?=$course->getFees($displayLocation->getLocationId())?></p></li>
                                                <?php } ?>


						<?php
						$exams = $course->getEligibilityExams();
						if(count($exams) > 0){
							if($institute->getInstituteType() == "Test_Preparatory_Institute"){
							?>
								<li><i class="icon-eligible"></i><p><label>Exams Prepared for: </label><?php
							}else{
							?>
								<li><i class="icon-eligible"></i><p><label>Eligibility: </label><?php
							}
							$examAcronyms = array();
							foreach($exams as $exam) {
								if($exam->getMarks() > 0){
                                                                          $examAcronyms[] = $exam->getAcronym() . "(" . $exam->getMarks() . ")";
                                                                 } else {
                                                                          $examAcronyms[] = $exam->getAcronym();
                                                                }
							}
							echo implode(', ',$examAcronyms); ?> </p></li>
						<?php } ?>

							
						<?php if(isset($reviewData[$course->getId()]['overallRating'])) {?>
                                                        <li><i class="icon-rating"></i><p><label class="flLt">Alumni Rating:&nbsp;</label><span class="ranking-bg"><strong><?php if(strpos($reviewData[$course->getId()]['overallRating'],'.')){echo $reviewData[$course->getId()]['overallRating'];}else{echo $reviewData[$course->getId()]['overallRating'].'.0';} ?></strong><sub>/<?php echo $reviewData[$course->getId()]['ratingCount'];?> </sub></span></p></li>
                                                <?php } ?>
						
					</ul>
					</div>
				</div>
		                <!----shortlist-course---->
				
				<?php
				$data['courseId'] = $course->getId();
				if(!$pageType) {
					$data['pageType'] = 'mobileCategoryPage';
				} else {
					$data['pageType'] = $pageType;
				}
				$this->load->view('/mcommon5/mobileShortlistStar',$data);
				?>
				
				<!-----end-shortlist------>
				</div>
					
				<div id= "thanksMsg<?php echo $course->getId();?>" class="thnx-msg" <?php if(!in_array($course->getId(),$appliedCourseArr)){?>style="display:none"<?php } ?>>
			                <i class="icon-tick"></i>
			                <p>Thank you for your request. You will be receiving E-brochure of the selected institute(s) in your mailbox shortly.</p>
			        </div>


		<?php
			$addReqInfoVars = array();
			$count1 =0;
			/*   Memory Optimization : need not to format array as build in below code, It has been handled in registration/views/mobile/forms.php
			foreach($courses as $c){
				if(checkEBrochureFunctionality($c)){
					$arr['isMultiLocation'.$institute->getId()] = $c->isCourseMultilocation();
					foreach($c->getLocations() as $course_location){
							$locality_name = $course_location->getLocality()->getName();
							if($locality_name !='') $locality_name = ' |'.$course_location->getLocality()->getName();
							$addReqInfoVars[$c->getName().' | '.$course_location->getCity()->getName().$locality_name]=$c->getId()."*".html_escape($institute->getName())."*".$c->getUrl()."*".$course_location->getCity()->getId()."*".$course_location->getLocality()->getId();
							if($arr['isMultiLocation'.$institute->getId()]=='false'){
								$arr['rebLocallityId'.$institute->getId()] = $course_location->getLocality()->getId();
								$arr['rebCityId'.$institute->getId()] = $course_location->getCity()->getId();
							}else{
								$arr['rebLocallityId'.$institute->getId()] = '';
								$arr['rebCityId'.$institute->getId()] = '';
							}
					}
		
			 } }
			 */
			/*   Memory Optimization : send only Similar courses ids */
			
			foreach ( $course->getLocations () as $course_location ) 
				 {
		  			$locality_name = $course_location->getLocality ()->getName ();
		            if ($arr ['isMultiLocation' . $institute->getId ()] == 'false')
		              {
			            $arr ['rebLocallityId' . $institute->getId ()] = $course_location->getLocality ()->getId ();
			            $arr ['rebCityId' . $institute->getId ()] = $course_location->getCity ()->getId ();
					  } else 
						{
						$arr ['rebLocallityId' . $institute->getId ()] = '';
						$arr ['rebCityId' . $institute->getId ()] = '';
						}
				  }		
			if(isset($instituteIdWithCourseIdMapping)) {		
			 $courseIds = array_keys($instituteIdWithCourseIdMapping[$institute->getId()]);
			 } else {

			 	$courses = $institute->getCourses();
			 	$courseIds = array();
				foreach ($courses as $key => $c) {
			 		$courseIds[] = $c->getId();
			 	}
			 } 
			
			$addReqInfoVars =$courseIds;
			$addReqInfoVars=serialize($addReqInfoVars);
			$addReqInfoVars=base64_encode($addReqInfoVars);
		?>

			<?php if(checkEBrochureFunctionality($course)){?>
			    <p>
				<?php if(in_array($course->getId(),$appliedCourseArr)){?>
				<a class="button blue small disabled" href="javascript:void(0);"
				id="request_e_brochure<?=$course->getId();?>"><i class="icon-pencil"
				aria-hidden="true"></i><span>Request E-Brochure</span></a>
				<?php }else{ ?>
				<?php if($alsoOnShiksha == 'true'):?>
				<a class="button blue small" hreflang="javascript:void(0);"
				id="request_e_brochure<?=$course->getId();?>"
				onClick="trackReqEbrochureClick('<?php echo $course->getId();?>');validateRequestEBrochureFormData('<?=$institute->getId();?>','<?=$arr['rebLocallityId'.$institute->getId()];?>','<?=$arr['rebCityId'.$institute->getId()];?>','<?=$arr['isMultiLocation'.$institute->getId()];?>','<?php echo $course->getId();?>','category_recommendation','<?php echo $trackingPageKeyId;?>'); setCookie('hide_recommendation','yes',30);"><i
				class="icon-pencil" aria-hidden="true"></i><span>Request E-Brochure</span></a>
				<?php else:?>
				<a class="button blue small" hreflang="javascript:void(0);"
				id="request_e_brochure<?=$course->getId();?>"
				onClick="trackReqEbrochureClick('<?php echo $course->getId();?>');validateRequestEBrochureFormData('<?=$institute->getId();?>','<?=$arr['rebLocallityId'.$institute->getId()];?>','<?=$arr['rebCityId'.$institute->getId()];?>','<?=$arr['isMultiLocation'.$institute->getId()];?>','<?php echo $course->getId();?>','','<?php echo $trackingPageKeyId;?>');"><i
				class="icon-pencil" aria-hidden="true"></i><span>Request E-Brochure</span></a>
				<?php endif;?>
				<?php } ?>

			<!--Add-to-compare--->
			<?php
			$data['instituteId'] = $institute->getId();
			$data['courseId']    = $course->getId();
			$data['isPaid'] = $course->isPaid();
			$data['comparetrackingPageKeyId'] = $comparetrackingPageKeyId;
			$this->load->view('/mcommon5/mobileAddCompare',$data);
			?>
			<!--end-->
			 
			</p>
					
			    
			    <!--<div class="shortlist"><i class="icon-heart" aria-hidden="true"></i><span>Shortlist</span></div>-->
			<!--<input class="brochure-btn orange-button" type="submit" value="Request E-brochure" />-->
			<?php if($pageType=='course'){
				$pageName = 'SIMILAR_COURSE_DETAIL_PAGE';
				$from_where = 'MOBILE5_SIMILAR_COURSE_DETAIL_PAGE';
				?><script>pageType='course';</script><?php
			}else if($pageType=='institute'){
				$pageName = 'SIMILAR_INSTITUTE_DETAIL_PAGE';
                                $from_where = 'MOBILE5_SIMILAR_INSTITUTE_DETAIL_PAGE';
				?><script>pageType='institute';</script><?php
			}else{
				$pageName = 'CATEGORY_PAGE';
                                $from_where = 'MOBILE5_CATEGORY_PAGE';
			} ?>
			<form action="/muser5/MobileUser/renderRequestEbrouchre" method="post" id="brochureForm<?=$course->getId();?>">
				<input type="hidden" name="courseAttr" value = "<?php echo $addReqInfoVars; ?>" />
				<input type="hidden" name="current_url" value = "<?php echo url_base64_encode($shiksha_site_current_url); ?>" /> 
				<input type="hidden" name="referral_url" value = "<?php echo url_base64_encode($shiksha_site_current_refferal); ?>" />
				<input type="hidden" name="selected_course" value = "<?php echo $course->getId(); ?>" />
				<input type="hidden" name="list" value="<?php echo $course->getId(); ?>" />
				<input type="hidden" name="institute_id" value="<?php echo $institute->getId(); ?>" />
				<input type="hidden" name="pageName" value="<?php echo $pageName;?>" />
				<input type="hidden" name="from_where" value="<?php echo $from_where;?>" />
				<input type="hidden" name="tracking_keyid" id="tracking_keyid<?php echo $course->getId();?>" value="">
			</form>
			<?php }?>

		    </article>
				    
		</section>
		<div id="recomendations_<?php echo $course->getId();?>" style="display:none; background:#fff;"></div>
	
<?php 
if($count==10 && isset($showGuideRegWidget) && $showGuideRegWidget && isset($mmp_details['page_id'])) {
$guidedata['mmp_details'] = $mmp_details;
$guidedata['trackingPageKeyId'] = $guidetrackingPageKeyId;
$this->load->view('/mcommon5/guideRegWidget', $guidedata);
}
	// Load the College Review Widget after 4th Listing
	if($count == 8)
	{
		if(isset($collegeReviewWidget) && isset($subCategoryIdForWidgetCheck) && $subCategoryIdForWidgetCheck == "23"){
			?>
			<div id="mbaToolsWidget" data-enhance="false">
				<div style="margin: 20px; text-align: center;"><img border="0" src="/public/mobile5/images/ajax-loader.gif"></div>
				<?php
					echo $collegeReviewWidget;	
				?>
			</div>
			<?php
		}
		
		if($subCategoryId == 56){
			$bannerProperties1 = array('pageId'=>'CATEGORY_BTECH', 'pageZone'=>'MOBILE');
	        	$this->load->view('common/banner',$bannerProperties1);
		}
	}
if($count == 4 && isset($subCategoryIdForWidgetCheck) && $subCategoryIdForWidgetCheck == "23" && IIM_CALL_INTERLINKING_FLAG == 'true')
{?>
	<section class="content-wrap2 clearfix">
	<?php $fromPage = 'categoryPage';
		echo Modules::Run('mIIMPredictor5/IIMPredictor/getIIMCallPredictorWidget',$fromPage);?>
	</section>
<?php }?>

<?php } ?>
<!-- End Display Institute List -->

<script>
function trackReqEbrochureClick(courseId){
try{
	if(pageType=='institute'|| pageType=='course'){
	_gaq.push(['_trackEvent', 'HTML5_Similar_Institutes_Request_Ebrochure', 'click',courseId]);
}

	else{
		_gaq.push(['_trackEvent', 'HTML5_Category_Page_Request_Ebrochure', 'click',courseId]);
	}
}catch(e){}
}

var ajaxReq =  '<?php echo $ajaxRequest;?>';

<?php if($responseCreatedInstituteId>0 && $responseCreatedCourseId>0){ ?>
CP_lastREBCourseId = '<?=$responseCreatedCourseId?>';
CP_lastREBInstituteId = '<?=$responseCreatedInstituteId?>';
<?php } ?>

if(ajaxReq != 'true') {

	var show_recommendation = getCookie('show_recommendation');
	var recommendation_course = getCookie('recommendation_course');
	var hide_recommendation = getCookie('hide_recommendation');
	
	if(show_recommendation == 'yes' && hide_recommendation != 'yes') {
		$(document).ready(function(){
				var isRankingPage = 'NO';
				var brochureAvailable = 'YES';
				var pageType = 'CP_MOB_Reco_ReqEbrochure';
		 		var screenWidth =  window.jQuery('#screenwidth').val();
				var screenHeight = window.jQuery('#screenheight').val();
				var trackingPageKeyId = '<?php echo $recommendationTrackingPageKeyId;?>';

				var urlRec = '/muser5/MobileUser/showRecommendation/'+recommendation_course+'/CP_Reco_popupLayer'+'/0/0/0/'+brochureAvailable+'/'+isRankingPage + '/' + pageType+'/\'\'/0/'+trackingPageKeyId;
				jQuery.ajax({
					url: urlRec,
					type: "POST",
					success: function(result)
					{
		        		   if((result.trim()) != ''){       	
								trackEventByGAMobile('HTML5_RECOMMENDATION_CATEGORY');
								setCookie('show_recommendation','no',30);
				    				setCookie('recommendation_course','no',30);
								$('#recomendation_layer_listing').html(result);							
								$('#popupBasic-popup').css('width',screenWidth);
								$('#popupBasic-popup').css('max-width',screenWidth);
								
								var window_width = $('#wrapper').width();
								var popup_width = window_width - 5 ;
								
								var top_pos = 10 + $('body').scrollTop() + 'px';
								$('#popupBasic').css({'position':'absolute','z-index':'99999' , 'cursor' : 'pointer' , 'top':top_pos , 'background-color' : '#efefef' , 'margin' : '5px' , 'width' : popup_width });
$('#popupBasic').addClass('ui-popup ui-overlay-shadow ui-corner-all ui-body-c');

//$('#wrapper').css({'background' : '#000' , 'z-index' : '100' , 'opacity' : '0.4'})

								var window_height = $(document).height();
								var window_width = $('#wrapper').width();
$('#popupBasicBack').css({'background' : '#000' , 'opacity' : '0.4' , 'z-index' : '9999' , 'display' : 'block' , 'width'  : window_width , 'height' : window_height , 'position':'absolute'});



								$('#popupBasic').show();
						}
										},
					error: function(e){
					}
				});		            	
		});
	}

	setCookie('hide_recommendation','no',30);        
	setCookie('show_recommendation','no',30);
		
}

</script>

