<?php //resize width class is applied only when there is only one  link in the belly

	   $tracking_keyid = $pageType == 'course' ? DESKTOP_NL_LP_COURSE_MIDDLE_DEB : DESKTOP_NL_LP_INST_MIDDLE_DEB;
      if((count($cta_widget_list)==1 && count($OF_DETAILS)==0) || (count($cta_widget_list)==0 && count($OF_DETAILS)>0))
      {
      	 $resizeWidthClass = "resize-width-class";
      }
      else
      {
	 $resizeWidthClass = "";
      }
      
      $layerHeaderText = "Download E-Brochure";
      if($validateuser != "false")
      {
	    $layerHeaderText = "Please select the course to download the brochure";
      }
      ?>
      <ul class="info-cont <?php echo $resizeWidthClass;?>">
	 <?php if(in_array("download_e_brochure", $cta_widget_list)):?>
          <?php 
          $class = '';
          $style = '';
          $liClass = 'class="course-link-orange"';
          if(!empty($OF_DETAILS['of_external_url']) || !empty($OF_DETAILS['of_seo_url'])) { 
              $class = ' download-icon-3';
              $style = 'style="font-weight:normal;"';
              $liClass = '';
          }
          ?>
	<li <?php echo $liClass;?>>
	    <?php if($showBrochureListOnCoursePageFlag) { ?>
	    <a class="course_layer<?=$course->getId()?> " href="javascript:void(0);" onclick="<?=(!empty($extraClass)?'return false; ':'')?> window.L_tracking_keyid = <?= $tracking_keyid ?>;makeResponseForInstitutePage(<?=$institute->getId()?>,'<?=base64_encode(html_escape($institute->getName()))?>',<?=$course->getId()?>,'','showListingPageRecommendationLayer','listingPageBellyNationalForInstitute','NULL','<?=base64_encode(serialize($courseListForBrochure))?>','<?=$layerHeaderText?>');"><i class="sprite-bg download-icon-2<?php echo $class;?>"></i><strong <?php echo $style;?>>Download brochure for this course</strong></a>
	    <?php } else { ?>
	    <a class="course<?=$course->getId()?>" href="javascript:void(0);" onclick="if(typeof($j)=='undefined'){ return false;} window.L_tracking_keyid = <?= $tracking_keyid ?>; makeResponse(<?=$institute->getId()?>,'<?=base64_encode(html_escape($institute->getName()))?>',<?=$course->getId()?>,'<?=base64_encode(html_escape($course->getName()))?>','showListingPageRecommendationLayer','listingPageBellyNational','NULL');"><i class="sprite-bg download-icon-2"></i><strong id="bellyDownloadText">Download brochure for this course</strong> </a>
	    <?php } ?>
	</li>
	<?php endif;?>
	<?php if(in_array("find_best_institute", $cta_widget_list)):?>
	<li class="course-link-gray">
		<a href="javascript:void(0);" onclick="national_listings_obj.scrollToParticularElement('course_page_response_bottom');"><i class="sprite-bg best-institute-icon"></i><strong>Find the best institute for yourself</strong> </a>
	</li>
	<?php endif;?>
	<?php if(count($OF_DETAILS)>0):?>			
	<li class="apply-nw-bg">
		<?php 
		if( ! empty($OF_DETAILS['of_seo_url']))
				$OF_DETAILS['of_seo_url'] = $OF_DETAILS['of_seo_url'].'?tracking_keyid='.$applyBottomTrackingPageKeyId;
		?>
	<?php if(!empty($OF_DETAILS['of_external_url'])):?>
	<a style="color:#fff !important" title="Apply Online" href="<?=$OF_DETAILS['of_seo_url'] ?>"><i class="sprite-bg apply-nw-icon-2"></i><strong>Apply Online</strong></a>
	
	<?php elseif(!empty($OF_DETAILS['of_seo_url'])):?>
	<a style="color:#fff !important" title="Apply Online" href="javascript:void(0);" onClick="setCookie('onlineCourseId','<?php echo $OF_DETAILS['of_course_id'];?>',0); checkOnlineFormExpiredStatus('<?php echo $OF_DETAILS['of_course_id'];?>','<?php echo '/studentFormsDashBoard/MyForms/Index/'; ?>','<?php echo $OF_DETAILS['of_seo_url'];?>'); return false;"><i class="sprite-bg apply-nw-icon-2"></i><strong>Apply Online</strong></a>
	<?php endif;?>
	</li> 
	<?php endif;?>
	<?php if(in_array("campus_connect", $cta_widget_list)):?>
	<li><a onclick="scrollToSpecifiedElement('campus-connect-sec-id');" uniqueattr="LISTING_COURSE_PAGES/campusRepInsttBellyLink" href="#askQuestionFormDiv"><i class="sprite-bg talk-icon"></i>Ask current students</a></li>
	<?php endif;?>
	<?php if(in_array("ask_question_widget", $cta_widget_list)):?>
	<li><a onclick="jumpTo('ask_ana_question');" uniqueattr="LISTING_COURSE_PAGES/AskExperts" href="javascript:void(0);"><i class="sprite-bg chat-cloud-icon"></i>Ask question to <?php echo $collegeOrInstituteRNR;?></a></li>
	<?php endif;?>
	
</ul>
