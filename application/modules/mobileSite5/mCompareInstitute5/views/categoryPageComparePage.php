<?php
global $shiksha_site_current_url;
global $shiksha_site_current_refferal;
global $isFullRegisteredUser;
if(isset($_COOKIE['MOB_A_C'])){
        $appliedCourseArr = explode(',',$_COOKIE['MOB_A_C']);
}
?>

<?php
	$compare_count = count($institutes);
	global $empty_compares;
	$empty_compares = $compare_count_max - $compare_count;
	$filled_compares = 1;
?>
<section class="content-wrap2">
	<script>
	    var emailDataArray = new Array();
	</script>
	<?php
	if($compare_count==0)
	{
	?>
		<div class="no-collge-to-compare-msg" id="no-collge-to-compare-msg">Confused between colleges? Compare them here to make the right choice.<br/>You can compare a maximum of 2 colleges at a time.</div>
	<?php
	}
	?>
	<table class="compare-table" cellpadding="0" cellspacing="0" width="100%">
    	<tr>
            <?php
	    if($compare_count <= $compare_count_max)
	    {
		$j = 0;
		foreach($institutes as $institute)
		{
		    $j++;
		    $course = $institute->getFlagshipCourse();
		    $course->setCurrentLocations($request);	
		    if(strlen($institute->getName()) > 100){
				$instStr  = preg_replace('/\s+?(\S+)?$/', '',html_escape($institute->getName()));
				$instStr .= "...";
			}else{
				$instStr = html_escape($institute->getName());
			}
		    ?>
		    <th class="<?php echo ($j<$compare_count_max)?'border-right':'';?>">
			
			<script>
			var stringTemp = '<?=$course->getInstId()?>::<?=$course->getId()?>::<?=html_escape($institute->getName())?>::<?php echo $course->getCurrentMainLocation()->getCity()->getId();?>::<?=$course->getCurrentMainLocation()->getLocality()->getId()?>';
			emailDataArray.push(stringTemp);
			updateTrackingPageKey('<?=$course->getId();?>');
			</script>
			
			<a href="javascript:void(0);" class="close-link" onClick="removeCollege('<?=$j?>');">&times;</a>
			<div class="clearfix"></div>
			<div class="compare-item" style="height: 90px;overflow: hidden;">
			    <strong><a href="<?=$course->getURL();?>" title="<?=htmlspecialchars($institute->getName())?>"><?php echo $instStr; ?></a>, <span><?=$course->getCurrentMainLocation()->getCity()->getName()?></span></strong>
			    
			    <p><?php if($yoe = $institute->getEstablishedYear()){ echo "Year of Establishment: ".$yoe;}?></p>
			</div>
			
			<!-- Request Brochure STARTS -->
			<div style="display:none;">
                        <div id= "thanksMsg<?php echo $course->getId();?>" class="thnx-msg" <?php if(!in_array($course->getId(),$appliedCourseArr)){?>style="display:none"<?php } ?>>
                                <i class="icon-tick"></i>
                                <p>Thank you for your request.</p>
                        </div>
			</div>

			<?php
				$institute_id = $course->getInstId();
				$courseList =  Modules::run('mranking5/RankingMain/getCourses',$institute_id);
				if($courseList){
					$institute = reset($instituteRepository->findWithCourses(array($institute_id => $courseList)));
					$courses = $institute->getCourses();
				}

				$addReqInfoVars = array();
				foreach($courses as $c){
					$arr['isMultiLocation'.$institute_id] = $c->isCourseMultilocation();
					foreach($c->getLocations() as $course_location){
							$locality_name = $course_location->getLocality()->getName();
							if($locality_name !='') $locality_name = ' |'.$course_location->getLocality()->getName();
							$addReqInfoVars[$c->getName().' | '.$course_location->getCity()->getName().$locality_name]=$c->getId()."*".html_escape($course->getInstituteName())."*".$c->getUrl()."*".$course_location->getCity()->getId()."*".$course_location->getLocality()->getId();
							if($arr['isMultiLocation'.$institute_id]=='false'){
								$arr['rebLocallityId'.$institute_id] = $course_location->getLocality()->getId();
								$arr['rebCityId'.$institute_id] = $course_location->getCity()->getId();
							}else{
								$arr['rebLocallityId'.$institute_id] = '';
								$arr['rebCityId'.$institute_id] = '';
							}
					}
				}
				$addReqInfoVars=serialize($addReqInfoVars);
				$addReqInfoVars=base64_encode($addReqInfoVars);
				$pageName = 'COMPARE_PAGE';
				$from_where = 'MOBILE5_COMPARE_PAGE';
			?>

			<?php
			if(in_array($course->getId(),$appliedCourseArr)){
			?>			
			<a style="font-size: 0.7em;" class="button blue small disabled" href="javascript:void(0);" id="request_e_brochure<?=$course->getId();?>"><i class="icon-pencil" aria-hidden="true"></i><span>Request Brochure</span></a>
			<?php }else{ ?>
			<a style="font-size: 0.7em;" class="button blue small" href="javascript:void(0);" id="request_e_brochure<?=$course->getId();?>" onClick="trackReqEbrochureClick('<?php echo $course->getId();?>');REBfromComparePage=true;validateRequestEBrochureFormData('<?=$institute->getId();?>','<?=$arr['rebLocallityId'.$institute->getId()];?>','<?=$arr['rebCityId'.$institute->getId()];?>','<?=$arr['isMultiLocation'.$institute->getId()];?>','<?php echo $course->getId();?>','',308);"><i class="icon-pencil" aria-hidden="true"></i><span>Request Brochure</span></a>
			<?php }
			?>


			<form action="/muser5/MobileUser/renderRequestEbrouchre" method="post" id="brochureForm<?=$course->getId()?>">
				<input type="hidden" name="courseAttr" value = "<?php echo $addReqInfoVars; ?>" />
				<input type="hidden" name="current_url" value = "<?php echo url_base64_encode($shiksha_site_current_url); ?>" />
				<input type="hidden" name="referral_url" value = "<?php echo url_base64_encode($shiksha_site_current_refferal); ?>" />
				<input type="hidden" name="selected_course" value = "<?php echo $course->getId(); ?>" />
				<input type="hidden" name="list" value="<?php echo $course->getId(); ?>" />
				<input type="hidden" name="institute_id" value="<?php echo $institute_id; ?>" />
				<input type="hidden" name="pageName" value="<?php echo $pageName;?>" />
				<input type="hidden" name="from_where" value="<?php echo $from_where;?>" />
				<input type="hidden" name="action_type" value="MOB_COMPARE_EBrochure" />
				<input type="hidden" name="tracking_keyid" id="tracking_keyid" value="308"/>
			</form>
			<!-- Request Brochure ENDS -->
			
		    </th>
		    <?php		
		     $signedInUser = $this->userStatus;
		     if($signedInUser!='false')
		     {
			$formData_mobile = false;
			$formData_mobile = Modules::run('registration/Forms/isValidResponseUser', $course->getId(), $signedInUser[0]['userid']);
			$isFullRegisteredUser = ($formData_mobile===true || $formData_mobile==1)?1:0;
			if($isFullRegisteredUser != 0){
				
				//added by akhter, added pageKey on course
		        $trackeyStr = $_COOKIE['compare-tracking'];
		        $trackeyStrArr = explode('|||',$trackeyStr);
		        if(count($trackeyStrArr)>0){
		            foreach ($trackeyStrArr as $value) {
		                $v= explode('::',$value);
		                    $key[$v[1]] = $v[2];
		            }
		        }
		        $pageKey = ($key[$course->getId()]>0) ? $key[$course->getId()] : $compareHomePageKeyId; 
		     ?>
		    <script>
			// make viewed auto response
			var isComparePage = true;
			if (makeViewedResponse <= 1) {
				validateRequestEBrochureFormData('<?=$institute->getId();?>','<?=$arr['rebLocallityId'.$institute->getId()];?>','<?=$arr['rebCityId'.$institute->getId()];?>','<?=$arr['isMultiLocation'.$institute->getId()];?>','<?php echo $course->getId();?>','mob_compare_viewed',<?=$pageKey;?>);
				makeViewedResponse++;
			}
		    </script>
		    <?php }
		     }
		    ?>
		    <?php
		    $filled_compares++;
		} ?>
	   <?php }
	   
	   ?>
	   
	   <?php
	    $filled_compares--;
	    $makeDisable = false;
	    if($empty_compares > 0)
	    {
		for($e = $filled_compares+1; $e<=$compare_count_max; $e++)
		{
		?>
		<th class="<?php echo ($e<$compare_count_max)?'border-right':'';?>" <?php if(!$makeDisable){ ?>id="newInstituteSection"<?php } ?>>
		    <div class="shortlist-number"><?=$e?></div>
		    
			<?php
			if($makeDisable){ ?>
				<div>
				    <input type="text" class="shortlist-field" disabled="disabled" value="Enter College Name"/>
				</div>
			<?php }else{ ?>
			<div id="searchContainerDiv" class="home-search">
				<div class="full-width">
				    <input class="shortlist-field" id="keywordSuggest" type="search" name="keyword"  minlength="1" placeholder="Enter College Name" />
	    
				</div>

				<ul id="suggestions_container" class="suggestion-box" style="display: none; top:28px;width:205%;<?php if(count($institutes)==1){ echo "right:0;";} ?>">		
				</ul>
			</div>
			<?php } ?>
       
	        </th>
		<?php
		$makeDisable = true;
		}
	    }
	    ?>
	      
            
        </tr>

        <tr id="importantInfoDiv" <?php if(!($institutes && count($institutes)>0)){ echo "style='display:none;'";} ?>>
        	<td colspan="2" class="compare-title"><h2>Course Name</h2></td>
        </tr>

	<tr id="courseDisplayDiv" <?php if(!($institutes && count($institutes)>0)){ echo "style='display:none;'";} ?>>	
            <?php
		$j = 0;$k = 0;
		if($compare_count <= $compare_count_max)
		{
		    foreach($institutes as $institute){
			$k++;
			$course = $institute->getFlagshipCourse();
			$courseId = $course->getId();
			if( isset( $courseLists[$courseId] ) && count($courseLists[$courseId])>0 ){
				$j++;
		?>
		    <td class="<?php echo ($k<$compare_count_max)?'border-right':'';?>" >
			
			<div class="ui-select" >
			    <div data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" data-icon="arrow-d" data-iconpos="right" data-theme="c" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-icon-right ui-btn-up-c" >
				<span class="ui-btn-inner">
				    <span class="ui-btn-text">
					<span id="courseSelectedText<?=$courseId?>"></span>
				    </span>
				    <span class="ui-icon ui-icon-arrow-d ui-icon-shadow">&nbsp;</span>
				</span>
				
				<select class=""  id="courseSelect<?=$j?>" onChange="resetComparePage();" style="width:100%">
				    <?php
				    foreach ($courseLists[$courseId] as $courseD){
					    $selected = "";
					    if($courseD['course_id']==$courseId){
						    $selected = "selected='selected'";
						    echo "<script>$('#courseSelectedText$courseId').html('".substr($courseD['courseTitle'],0,12)."');</script>";
					    }
				    ?>
					    <option title="<?=$courseD['courseTitle']?>" value='<?=$courseD['course_id']?>' <?=$selected?> ><?=$courseD['courseTitle']?></option>
				    <?php   }
				    ?>
				    </select>
			    </div>
			</div>

		    </td>
		<?php }else{ ?>
		    <td class="<?php echo ($k<$compare_count_max)?'border-right':'';?>">
			<strong style="font-size:22px; color:#828282">-</strong>
		    </td>
		<?php }
		    }
		    
			$showId = true;
			if($j<$compare_count_max){       //Case when Compare tool has less than 4 courses to compare
				for ($x = $k+1; $x <=$compare_count_max; $x++){
					echo '<td class="'.(($x<$compare_count_max)?'border-right':'').'" ';
					if($showId){ echo " id='newCourseSection' ";}
					echo '>&nbsp;</td>';
					$showId = false;
				}
			}
		    
		}
		
		?>
        </tr>
	
	<?php
		//We will have to check the Category of the First course. If it is MBA, we will load another view
		if($showMBA){
		    $this->load->view('compareFieldsMBA');
		}
		else{
		    $this->load->view('compareFieldsDefault');
		}
		?>
        
        
       
        <?php
	if($empty_compares != $compare_count_max)
	{
	?>
        <tr>
        	<td colspan="2" class="compare-title"><h2>Like any of these colleges? Shortlist Now!</h2></td>
        </tr>
        <tr>
		
		<?php
	    if($compare_count <= $compare_count_max)
	    {
		$j = 0;
		foreach($institutes as $institute)
		{
		    $j++;
		    $course = $institute->getFlagshipCourse();
		    ?>
			<td class="<?php echo (($j<$compare_count_max)?'border-right':'');?>" style="padding:25px 10px;">
				<!----shortlist-course---->
				
				<?php
				$data['courseId'] = $course->getId();
				$data['pageType'] = 'mobileComparePage';
				$data['tracking_keyid']=$shortlistTrackingPageKeyId;
				$this->load->view('/mcommon5/mobileShortlistStar',$data);
				?>
				
				<!-----end-shortlist------>	
			</td>
	<?php   }
	
		if($j < $compare_count_max)
		{
		    for ($x = $j+1; $x <=$compare_count_max; $x++)
		    {
			?>
			<td class="<?php echo ($x<$compare_count_max)?'border-right':'';?>">&nbsp;</td>
			<?php
		    }
		}
	    }?>
        	
        </tr>
	<?php
	}
	?>
	</table>
	<?php
	if($empty_compares != $compare_count_max)
	{
		$this->load->view('socialShare'); 
	}
	?>
	<div class="clearfix"></div>
</section>

<?php if($institutes && count($institutes)>0){
	if(isset($mainCourseSubCatId) && $mainCourseSubCatId>0){
		echo Modules::run('mCompareInstitute5/compareInstitutes/getPopularCoursesForComparision',$mainCourseSubCatId);
	}
}else{ ?>
<div style="margin-top: 20px;">
</div>
<?php
	echo Modules::run('mCompareInstitute5/compareInstitutes/getPopularCoursesForComparision',23);
	echo Modules::run('mCompareInstitute5/compareInstitutes/getPopularCoursesForComparision',56);
} ?>

<?php
$this->load->view('autoSuggestorInstitute');

foreach ($courseIdArr as $key => $courseId) {
	if($userComparedData[$courseId]['instituteId'] && preg_match('/^\d+$/',$userComparedData[$courseId]['instituteId'])){
		$instituteId = '::'.$userComparedData[$courseId]['instituteId'];	
	}
	$trackey_Id = empty($userComparedData[$courseId]['trackeyId']) ? $compareHomePageKeyId : $userComparedData[$courseId]['trackeyId'];
	if(preg_match('/^\d+$/',$courseId) && preg_match('/^\d+$/',$trackey_Id)){	
		$cookieDataArray[] = $courseId.'::'.$trackey_Id.$instituteId;
	}
}
$cookieDataStr = implode('|', $cookieDataArray);
?>


<img id = 'tracking_img' src="/public/images/blankImg.gif" width=1 height=1 />

<script>
	var cookieDataStr       = '<?php echo $cookieDataStr; ?>';
	//Set compared data
	if (cookieDataStr) {
		setCookie("mob-compare-global-data",cookieDataStr,30,'/',COOKIEDOMAIN);
	}else{
		setCookie("mob-compare-global-data","");	
	}

	window.onload=function(){
		
		<?php if(count($institutes)<2){ 
		//if(typeof(initializeAutoSuggestorInstancesCompare) == "function") {
		//	initializeAutoSuggestorInstancesCompare(); //For initiating AutoSuggestor Instance
		$autosuggestorConfigHomepage = Modules::run('common/GlobalShiksha/getHeaderSearchConfig', array('instituteSearchCompareMobile'));
								foreach($autosuggestorConfigHomepage as $autosuggestorConfig) {?>
									initializeAutoSuggestorInstanceSearchV2('<?php echo json_encode($autosuggestorConfig["options"]);?>');								
								<?php } ?>
		//}
		//Event listener for hiding dropdown suggestions when user clicks outside the suggestion container
		if(typeof(handleClickForAutoSuggestorCompare) == "function") {
		    if(window.addEventListener){
			document.addEventListener('click', handleClickForAutoSuggestorCompare, false);
		    } else if (window.attachEvent){
			document.attachEvent('onclick', handleClickForAutoSuggestorCompare);
		    }
		}
		<?php } ?>

	        setCookie('hide_recommendation','no',30);
        	setCookie('show_recommendation','no',30);
		
		updateNotification();
		$('#emailCompareDiv').click(function(){
			$(this).hide();
		});

		//window.onscroll = floatCompareWidgetScroll;
		
		//Track the courses, static/dynamic page, source
		<?php   if(isset($isStaticPage) && $isStaticPage){
				echo "var pageType = 'static';";
			}
			else{
				echo "var pageType = 'dynamic';";
			}
		?>
		var source = 'mobile';
		if(cookieDataStr){
			var randNum = Math.floor(Math.random()*Math.pow(10,16));
			document.getElementById('tracking_img').src = '<?php echo SHIKSHA_HOME;?>/comparePage/comparePage/trackComparePage/'+randNum+'/'+pageType+'/'+source+'/'+cookieDataStr;	
		}
	};
	
</script>
<script>
function trackReqEbrochureClick(courseId){
try{
	_gaq.push(['_trackEvent', 'HTML5_Compare_Page_Request_Ebrochure', 'click',courseId]);
}catch(e){}
}


function floatCompareWidgetScroll() {
	if ( ($(window).scrollTop()+$( window ).height()+20) >= $('#page-footer').offset().top ) {
		$('#emailCompareDiv').hide();
	}
	else if(!hamburgerFlag && typeof(hamburgerFlag) !='undefined'){ 
		$('#emailCompareDiv').show();
	}
}

</script>
