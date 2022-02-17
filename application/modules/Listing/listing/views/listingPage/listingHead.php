<?php
	$defaultJs = array('listingPage','common','customCityList','processForm','ajax-api');
	if($js){
		$defaultJs = array_merge($defaultJs,$js);
	}
	if($pageType == 'course'){
		$url = $course->getURL();
	}else{
		$anaTabUrl = $askNAnswerTabUrl;
		$coursesTabUrl = $courseTabUrl;
		$urlVar = $tab."TabUrl";
		$url = $$urlVar;
	}

	$currentURL =  explode("?",$url);
	$canonicalurl = $currentURL[0];
	
	if($pageType == 'course' && $institute->getFlagshipCourse()->getId() == $course->getId() ) {
		$canonicalurl = $institute->getURL();
	}

        //Create URL in case of Campus ambassador project
        if($pageType == 'course' && isset($campusRepTabUrl) && $campusRepTabUrl!='' && $tab=='campusRep'){
                $url = $campusRepTabUrl;
		$canonicalurl = $url;
                if($page_no > 0){
                        $title = "Page" . (($page_no/LISTING_QUESTIONS_PER_PAGE) + 1) . " - " . $title;
                        $metaDescription = "Page" . (($page_no/LISTING_QUESTIONS_PER_PAGE) + 1) . " - " . $metaDescription;
                        $canonicalurl = $url . '-' . $page_no;
                }
        }

	$noIndexMetaTag = false;
	if( $tab=='campusRep' ){
	    $noIndexMetaTag = true;
	}
	
	$headerComponents = array(
		'js'=>$defaultJs,
		'jsFooter'=>array('onlinetooltip'),
		'css'=>array('listing','recommend'),
		'product'=>'categoryHeader',
		'bannerProperties' => array('pageId'=>'CATEGORY', 'pageZone'=>$categoryData['page'] .'_TOP', 'shikshaCriteria' => $criteriaArray),
		'canonicalURL' => $canonicalurl,
                'page_is_listing' => 'YES',
                'title' => $title,
                'metaDescription' => $metaDescription,
		'noIndexMetaTag' => $noIndexMetaTag
	);
	
	if($inlineView) {
		$this->load->view('common/headerWithoutHTML', $headerComponents);
	}
	else {
		$this->load->view('common/header', $headerComponents);
	}
?>
<script> 
var universal_page_type = "<?php echo $pageType;?>";
if(universal_page_type == 'institute') { 
	universal_page_type = 'Insti';
} else if(universal_page_type == 'course') {
	universal_page_type = 'Course';
} 
var universal_page_type_id = "<?php echo $typeId;?>";
</script>
<input type="hidden" name="getmeCurrentCity" id="getmeCurrentCity" value="<?php echo $currentLocation->getCity()->getId();?>"/>
<input type="hidden" name="getmeCurrentLocaLity" id="getmeCurrentLocaLity" value="<?php echo $currentLocation->getLocality()->getId();?>"/>
<div id="listing-cont">
	
	<!-- breadcrumbs have been displayed from header file -->
	
	<?php 
	
	// below code added for course pages related changes
	if(empty($course_page_required_category))
	$course_page_required_category = 0;
	$cpgs_backLinkArray = array();
	//_P($breadCrumb);
	
	
	if($pageType == 'course') { 

		$coursepage_sub_cat_array = $googleRemarketingParams['subcategoryId'];
		foreach ($coursepage_sub_cat_array as $coursepage_subcat) {
			if(checkIfCourseTabRequired($coursepage_subcat)){
				$course_page_required_category = $coursepage_subcat;
				break;
			}
		}
		
		$cpgs_backLinkArray = array("MESSAGE" => "View all institutes and courses", "LANDING_URL" => $breadCrumb[0]['url']);
			
	} else if($pageType == 'institute') {
            if($_REQUEST['cpgs']>0){
				$course_page_required_category = $_REQUEST['cpgs'];
             }
		// need to write logic 	
		$cpgs_backLinkArray = array("MESSAGE" => "View all institutes and courses", "LANDING_URL" => $breadCrumb[0]['url']);	
	}
	
	
	
	/*if($course_page_required_category>0) {
		$cpgs_backLinkArray['AUTOSCROLL'] = 1;
		 echo '<div style="padding: 15px 0px 0px 0px">';
			echo Modules::run('coursepages/CoursePage/loadCoursePageTabsHeader', $course_page_required_category, "Institutes", FALSE,$cpgs_backLinkArray,FALSE);
		 echo '</div>';
	}*/
	
	?>
	<script>
	if(document.getElementById('cpgs_param_id')) {
		document.getElementById('cpgs_param_id').value = '<?php echo $course_page_required_category?>'+"_Institutes";
		document.getElementById('cpgs_param_id1').value = '<?php echo $course_page_required_category?>'+"_Institutes";
	}
	</script>
	<?php if($editedData){ ?>
		<div class="clear-width" style="width:99%; color:#000; background:#fffdc0; padding:8px 5px;">
		    <p>This listing was last updated on <?=date('d/m/Y',strtotime($editedData['modifiedDate']))?><?php if($editedData['modifiedBy']) { ?> by <?=$editedData['modifiedBy']?> <?php } ?></p>
		</div>
	<?php } ?>
	<div id="page-header">
		<table width="100%" cellpadding="0" cellspacing="0">
			<tr>
				<td>
					<div id="header-details">
						<h1>
						
                                                <?php if($pageType == 'course'): ?>                                               
						<?php if($tab == 'campusRep'){echo "Discussion for ";} ?> 
                                                 <?php echo html_escape($course->getName());						 
						 ?>
						<strong>
						<br/>
						<?=html_escape($institute->getName())?>, <?=(($currentLocation->getLocality()&&$currentLocation->getLocality()->getName())?$currentLocation->getLocality()->getName().", ":"")?><?=$currentLocation->getCity()->getName()?></strong>
						<?php else: if($tab == 'ana'): echo "Connect to Current Students of"; endif;?>
						<?=html_escape($institute->getName())?>, <strong><?=(($currentLocation->getLocality()&&$currentLocation->getLocality()->getName())?$currentLocation->getLocality()->getName().", ":"")?><?=$currentLocation->getCity()->getName()?></strong>
						<?php endif;?>
						<?php
							if($validateuser != 'false') {
								if($validateuser[0]['usergroup'] == 'cms' || $validateuser[0]['usergroup'] == 'enterprise' || $validateuser[0]['usergroup'] == 'sums' || $validateuser[0]['usergroup'] == 'saAdmin' || $validateuser[0]['usergroup'] == 'saCMS'){
									if(is_object($course)){
										if($course->isPaid()){
											echo '<label style="color:white; font-weight:normal; font-size:13px; background:#b00002; text-align:center; padding:2px 6px;">Paid</label>';
										} else {
											echo '<label style="color:white; font-weight:normal; font-size:13px; background:#1c7501; text-align:center; padding:2px 6px;">Free</label>';	
										}
									}
								}
							}
						?>
						<?php echo Modules::run('listing/ListingPageWidgets/seeAllBranches',$institute,FALSE,"yes"); ?>
						</h1>
                                                
						<div id="inst-details">
							<?php
								if($institute->getEstablishedYear()){
							?>
							<strong class="flLt">Established in <?=$institute->getEstablishedYear()?></strong>
							<?php
								}
							?>
							<?php
								if($currentLocation->getCountry()->getId() > 2)
								{
									$studyAbroad = 1;
								}
								else
								{
									$studyAbroad = 0;
								}
								//$lastUpdatedDate = $institute->getLastUpdatedDate();
								$lastUpdatedDate = ($pageType == 'institute') ? $institute->getLastUpdatedDate() : $course->getLastUpdatedDate();
								//$date6monthsBack = date("Y-m-d H:i:s",strtotime('-6 months'));
								$shikshaDataLastUpdated = date("Y-m-d H:i:s",strtotime(SHIKSHA_DATA_LAST_UPDATED));
								if ($lastUpdatedDate < $shikshaDataLastUpdated || $studyAbroad == 1)
								{
										echo Modules::run('listing/ListingPageWidgets/contactDetails',$institute, $course,$currentLocation,"yes");?>
										<span id="topLinks">
										<a href="#"><span class="sprite-bg email-icn"></span> Email Contact Details</a>
										<a href="#"><span class="sprite-bg sms-icn"></span> SMS Contact Details</a>
										<a href="#"><span class="sprite-bg save-icn"></span> Save Info </a>
										</span>
						  <?php } else { ?>
										<span id="topLinks">
											<a href="#"><span class="sprite-bg sms-icn"></span>Send Contact Details to Email/SMS</a>
											<a href="#"><span class="sprite-bg save-icn"></span> Save Info </a>
										</span>
						  <?php } ?>
							<?php if($paid){ ?>
							<?php if($tab != 'ana' && $tab != 'courses' && $tab != 'campusRep'){ ?>
								<a href="#" class="bld" onclick="hideFloatingRegistration(); $j('body,html').animate({scrollTop:$j('#bottomWidget').offset().top - 70},500); return false;"><span class="sprite-bg apply-icn"></span> Apply Now</a>
							<?php } ?>
							<?php } ?>
							<div id="responseFormNew" style="display:none"></div>
						    <div id="contactLayerTop" style="display:none"></div>

						</div>
					</div>
				</td>
				<td>
					<?php if($institute->getLogo()){ ?>
					<div id="header-logo">
						<a href="<?=$overviewTabUrl?>"><img src="<?=$institute->getLogo()?>" title=" <?=html_escape($institute->getName())?>, <?=(($currentLocation->getLocality()&&$currentLocation->getLocality()->getName())?$currentLocation->getLocality()->getName().", ":"")?><?=$currentLocation->getCity()->getName().' Logo'?>" alt="<?=html_escape($institute->getName())?>, <?=(($currentLocation->getLocality()&&$currentLocation->getLocality()->getName())?$currentLocation->getLocality()->getName().", ":"")?><?=$currentLocation->getCity()->getName().' Logo'?>"/></a>
					</div>
					<?php } ?>
				</td>
			</tr>
		</table>
		
		<?php $this->load->view('/listing/listingPage/listingPageTabs', array('instituteRep'=>$instituteRep,'alumniFeedbackRatingCount' => $alumniFeedbackRatingCount,'course_page_required_category'=>$course_page_required_category)); ?>
	</div>
	<div class="header-sticky">
        <div id="page-header">
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td>
						<div id="header-details">
							<div class="sticky-title" style="margin-bottom:0"><?=html_escape($institute->getName())?>, <strong><?=$currentLocation->getCity()->getName()?></strong>
							</div>	
						</div>
					</td>
				</tr>
			</table>
                <?php $this->load->view('/listing/listingPage/listingPageTabs',array("apply"=>1)); ?>
        </div>
	</div>
