<?php
if($pageType == 'course')
{
    $isMultilocation = count($course->getLocations()) > 1?true:false;
}
else{
    $isMultilocation = count($institute->getLocations()) > 1?true:false;
}
?>
<div class="management-title">  
    
    <!-- TITLE STARTS -->
        <?php if($pageType == 'course') { ?>
            <div class="ins-name-box clear-width">
            <a class="inst-title" href="<?=$institute->getURL()?>"> <?=html_escape($institute->getName())?>
	    <?php if(!$isMultilocation){?>
	    ,
	    <?=(($currentLocation->getLocality() && $currentLocation->getLocality()->getName())?
                                                                      $currentLocation->getLocality()->getName().", ":"")?>
                                                                        <?=$currentLocation->getCity()->getName()?>
	    <?php }?>
            </a>
	    <?php
		if($validateuser != 'false') {
		    if($validateuser[0]['usergroup'] == 'cms' || $validateuser[0]['usergroup'] == 'enterprise' || $validateuser[0]['usergroup'] == 'sums' || $validateuser[0]['usergroup'] == 'saAdmin' || $validateuser[0]['usergroup'] == 'saCMS'){
			if(is_object($course)){
			    if($course->isPaid()){
				echo '<label style="color:white; font-weight:normal; font-size:13px; background:#b00002; text-align:center; padding:2px 6px;">Paid</label>';
			    }else{
				echo '<label style="color:white; font-weight:normal; font-size:13px; background:#1c7501; text-align:center; padding:2px 6px;">Free</label>';	
			    }
			}
		    }
		}
	    ?>
        <?php 
        } else { ?>
            <h1>
                <?php echo html_escape($institute->getName()); ?>
		<?php if(!$isMultilocation){?>
		,
                <span class="ins-city">
                    <?=(($currentLocation->getLocality() && $currentLocation->getLocality()->getName()) ? $currentLocation->getLocality()->getName().", ":"")?><?=$currentLocation->getCity()->getName()?>
                </span>
		<?php }?>

            </h1>
	    <?php if($pageType == 'institute') {
		echo Modules::run('listing/ListingPage/seeAllBranches',$institute,FALSE,"link");
	    } ?>
		
            <div class="ins-name-box clear-width">
            <?php if($institute->getEstablishedYear() || !empty($reviews['ratings']['Overall Feedback']['ratings'])) { ?>
				<ul class="inst-details-cont clear-width">
					<?php if($institute->getEstablishedYear()) { ?>
						<li>
							<label>Established in <?=$institute->getEstablishedYear()?></label>
						</li>
					<?php } ?>
					<?php 
					if(!empty($reviews['ratings']['Overall Feedback']['ratings']) && $showAlumniReviewsSection) { ?>
					<li>
					<label>Alumni Reviews :</label>
					<div class="rating-cols2" itemscope itemtype="http://data-vocabulary.org/Review-aggregate" style="margin:0;">
						<div class="rating-cols2" itemprop="rating" itemscope itemtype="http://data-vocabulary.org/Rating">
							<?php
							for($var=0; $var < round($reviews['ratings']['Overall Feedback']['ratings']); $var++) {
							?>
									<i class="sprite-bg rat-star-active"></i>
							<?php
							}
							for($cnt= $var; $cnt < 5; $cnt++){
							?>
									<i class="sprite-bg rat-star-inactive"></i>
							<?php
							}
							?>
							<span class="rating-points">
								<span itemprop="average"><?php echo round($reviews['ratings']['Overall Feedback']['ratings'], 1);?></span>/<span itemprop="best">5</span>
								<span itemprop="count"> (<?php echo count($reviews['reviews_by_email']); echo count($reviews['reviews_by_email']) > 1 ? ' reviews' : ' review';?>) </span>
							</span>
						</div>
					</div>
					</li>
					<?php
					}
					?>
				</ul>
				<?php }?>
        
        <?php
		}
		if($pageType == 'course') {
            echo Modules::run('listing/ListingPage/seeAllBranches',$course,FALSE,"link"); ?>
            <h1>
                <?php echo html_escape($course->getName()); ?>
            </h1>
        <?php } ?>
    <!-- TITLE ENDS -->
    
    <!-- SEND CONTACT DETAILS STARTS -->
        
        <?php
        $this->load->view('listing/national/widgets/listingsOverlay');
        if($isMBATemplate) {
        $this->load->view('listing/national/widgets/coursePageShortlistTopWidget');
        }
        if($updated == "true")
        {
			$listingType = "Top_".$pageType;
			
			$preferredCity = $_REQUEST['city'];
			if(empty($preferredCity)){
				$preferredCity = 0;
			}
			
			$preferredLocality = $_REQUEST['locality'];
			if(empty($preferredLocality)){
				$preferredLocality = 0;
			} ?>
            
                        <a href="#" class="manage-contact-btn flLt" uniqueattr="LISTING_INSTITUTE_PAGES/showLCDFormTop" onclick="showResponseForm('responseFormNew', '<?=$listingType?>', '<?=$typeId?>', 'listingPageTopLinks'); activatecustomplaceholder(); return false;"><i class="sprite-bg sms-icon"></i>Get contact details on email/SMS</a>
						
						<?php
						/**
						  * Online Form Button
						  */
						?>
						<?php if(count($OF_DETAILS) > 0) {
							$OF_DETAILS['of_seo_url'] = $OF_DETAILS['of_seo_url'].'?tracking_keyid='.$applyTrackingPageKeyId;
						 ?>
						<?php if(!empty($OF_DETAILS['of_external_url'])) { ?>
								<a href="javascript:void(0);" class="orange-button" onclick="gaTrackEventCustom('NATIONAL_COURSE_PAGE', 'Apply_Online_Extrn_Form', '', this, '<?php echo $OF_DETAILS['of_seo_url'];?>');" style="margin-left:10px; position: relative; top: 5px;"><i class="sprite-bg apply-nw-icon"></i>Apply Online</a>
						<?php } else { ?>
								<a href="javascript:void(0);" class="orange-button" onclick="gaTrackEventCustom('NATIONAL_COURSE_PAGE', 'Apply_Online', '', this, '<?php echo $OF_DETAILS['of_seo_url'];?>');" style="margin-left:10px; position: relative; top: 5px;"><i class="sprite-bg apply-nw-icon"></i>Apply Online</a>
						<?php } ?>								
						<?php } ?>

						
						
                        
			<input type="hidden" id="preferredCity" name="preferredCity" value="<?=$preferredCity?>">
			<input type="hidden" id="preferredLocality" name="preferredLocality" value="<?=$preferredLocality?>">
			
			<?php if($pageType !== 'institute' && $brochureURL->getCourseBrochure($course->getId()) && $courseType !='') { ?>
			
			<!--------------------compare tool---------------------------> 
			<div style="display:none">
			
			<input type="hidden" name="compare<?php echo $institute->getId();?>-<?=$course->getId()?>list[]"  value= "<?=$course->getId()?>" />	 
			 </div><br/><br/>
			 <a target="_blank" class="flLt" id="topShortlistlink" style = "margin:3px 20px 0 0;display:<?=($courseShortlistedStatus == 1 ? 'block' : 'none')?>" href="<?=SHIKSHA_HOME.'/my-shortlist-home'?>">View Shortlist</a>
			<p class="flLt">
			    <input onclick="updateCompareText('compare<?php echo $institute->getId();?>-<?=$course->getId()?>');
			    setCompareCookie('<?php echo $comparetrackingPageKeyId;?>');updateAddCompareList('compare<?php echo $institute->getId();?>-<?=$course->getId()?>');checkactiveStatusOnclick();trackEventByGA('LinkClick','ADD_TO_COMPARE_ON_COURSE_PAGE');"
			    type="checkbox" name="compare" id="compare<?php echo $institute->getId();?>-<?=$course->getId()?>" class="compare<?php echo $institute->getId();?>-<?=$course->getId()?>" value="<?php echo $institute->getId().'::'.' '.'::'.($institute->getMainHeaderImage()?$institute->getMainHeaderImage()->getThumbURL():'').'::'.htmlspecialchars(html_escape($institute->getName())).', '.$currentLocation->getCity()->getName().'::'.$course->getId().'::'.$course->getURL();?>"/>
			    <a  href="javascript:void(0);" onclick="checkactiveStatusOnclick();trackEventByGA('LinkClick','ADD_TO_COMPARE_ON_COURSE_PAGE');toggleCompareCheckbox('compare<?php echo $institute->getId();
			    ?>-<?=$course->getId()?>');setCompareCookie('<?php echo $comparetrackingPageKeyId;?>');updateAddCompareList('compare<?php echo $institute->getId();
			    ?>-<?=$course->getId()?>');return false;" id="compare<?php echo $institute->getId();?>-<?=$course->getId()?>lable" class="compare<?php echo $institute->getId();?>-<?=$course->getId()?>lable">Add to Compare</a>
			</p>
                         <!--------------------compare tool--end--------------------------->
			
		<?php }
		}
        else {
                echo Modules::run('listing/ListingPageWidgets/contactDetailsNotUpdated',$institute, $course,$currentLocation,"yes");?>
                <a href="#" class="manage-contact-btn" onclick="showContactDetails(); return false;"><i class="sprite-bg phone-icon2"></i>View Phone Number</a>
        <?php } ?>
        
        
            </div><!-- ins-name-box ends-->
</div>
<div id="newOverlay" class="newOverlay"></div>
<div id="responseFormNew" class="responseFormContactNew" style="display:none"></div>
<div id="contactLayerTop" style="display:none"></div>

    <!-- SEND CONTACT DETAILS ENDS -->
<script>
compareDiv = 1;
var currentPageName= 'Course Page';
</script>
<style>
.responseFormContactNew {
    display: none;
    left: 448px;
    top: 449px;
    width: 400px;
    background: none repeat scroll 0 0 #fff;
    border: 2px solid #5e5e5e;
    font-family: Tahoma,Geneva,sans-serif;
    font-size: 14px;
    margin: 0 auto;
    padding: 10px 15px 15px;
    position: absolute;
    z-index: 100000001;
}
.newOverlay {
    background-color: #000;
    display: block;
    height: 2923px;
    left: 0;
    opacity: 0.4;
    position: absolute;
    top: 0;
    width: 1296px;
    z-index: 1000;
    display: none;
    filter: alpha(opacity=40);
}
</style>