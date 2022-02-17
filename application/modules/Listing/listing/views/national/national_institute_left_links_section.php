<?php 
   $tracking_keyid = $pageType == 'course' ? DESKTOP_NL_LP_COURSE_MIDDLE_DEB : DESKTOP_NL_LP_INST_MIDDLE_DEB;



     if(count($cta_widget_list)==1)
      {
      	 $resizeWidthClass = "resize-width-class";
      }
      else
      {
	 $resizeWidthClass = "";
      } ?>
      <ul class="info-cont <?php echo $resizeWidthClass;?>">
   <?php
      //code added for "find best institute" widget , (copied from national_course_left_links_section, needs to be structured based on institute CTA logic)
      if(in_array("download_e_brochure", $cta_widget_list))	{
	 $downloadedCount = 0;
	 foreach( $courseListForBrochure as $key=>$value )
	 {
	    if( $_COOKIE["applied_".$key] )
	    {
	       //_p("here");
	       $downloadedCount++;
	    }
	 }
	 $extraClass = "";
	 
	 if($downloadedCount == count($courseListForBrochure))
	    $extraClass = "disabled-button";
	 ?>
	 <li class='course-link-orange'>
	    <a class="course<?=$course->getId()?> <?=$extraClass?>" href="javascript:void(0);" onclick="<?=(!empty($extraClass)?'return false; ':'')?> window.L_tracking_keyid = <?= $tracking_keyid ?>; makeResponseForInstitutePage(<?=$institute->getId()?>,'<?=base64_encode(html_escape($institute->getName()))?>',0,'','showListingPageRecommendationLayer','listingPageBellyNationalForInstitute','NULL','<?=base64_encode(serialize($courseListForBrochure))?>');"><i class="sprite-bg download-icon-2"></i><strong>Download E-Brochure</strong></a>
	 </li>
      <?php }
	if(in_array("find_best_institute", $cta_widget_list)) { ?>
	<li class="course-link-gray">
		<a href="javascript:void(0);" onclick="institute_national_listings_obj.scrollToParticularElement('institute_page_response_bottom');"><i class="sprite-bg best-institute-icon"></i><strong>Find the best institute for yourself</strong> </a>
	</li>
      <?php }
      	
	if(in_array("campus_connect", $cta_widget_list)):?>
		<li><a onclick="campusRepInsttLayer('campusRepInstt');" uniqueattr="LISTING_INSTITUTE_PAGES/campusRepInsttBellyLink" href="#askQuestionFormDiv"><i class="sprite-bg talk-icon"></i>Ask current students</a></li>
    <?php endif;?>
	
	<?php if(in_array("ask_question_widget", $cta_widget_list)):?>
	<li><a onclick="jumpTo('ask_ana_question');" uniqueattr="LISTING_INSTITUTE_PAGES/AskExperts" href="javascript:void(0);"><i class="sprite-bg chat-cloud-icon"></i>Ask question to <?php echo $collegeOrInstituteRNR;?></a></li>
	<?php endif;?>	
</ul>

<div id="campusRepInstt" style="display:none"></div>

<script>
   function campusRepInsttLayer(id)
   {
	  showListingsOverlay(410,'auto','',$j('#'+id).html());
   }
</script>
