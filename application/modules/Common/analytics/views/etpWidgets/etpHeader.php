<?php
$divClass = "col-md-9 col-sm-9";
$rowClass = "";
if(empty($headerImage)){
	$divClass = "col-md-12 col-sm-12";
}
else{
    $rowClass = "head-img";
}
?>

<div class="row">
            <div class="anl-card clearfix etpHeaderCard">
                <div class="ins-c-blk clearfix">
                    <div class="col-md-12">
                        <div class="row <?php echo $rowClass;?>">
			<?php if(!empty($headerImage)) { ?>
                            <div class="col-md-3 col-sm-3 head-img-container">
                                <img src="<?php echo $headerImage['url'];?>" title="<?php echo htmlentities($headerImage['title']);?>" width="174" height="130" class="ins-img">
																<div class="ins-col-blk pull-left col-sm-12 gradient-only">
																		<h1><?php echo htmlentities($entityName);?></h1>
																		<?php if($locationName) { ?>
																		<span><?php echo ($locationName ? ", ".$locationName : $locationName);?></span>
																		<?php }
																				if($inlineText){
																		?>
																		<p class="ins-stat"><?php echo $inlineText;?></p>
																		<?php } ?>
																 </div>
                            </div>
			<?php } ?>
                            <div class="<?php echo $divClass;?>">
                                <div class="ins-col clearfix">
                                    <div class="ins-col-blk pull-left col-sm-12 non-gradient-only">
                                        <h1><?php echo htmlentities($entityName);?></h1>
                                        <?php if($locationName) { ?>
                                        <span><?php echo ($locationName ? ", ".$locationName : $locationName);?></span>
                                        <?php }
                                            if($inlineText){
                                        ?>
                                        <p class="ins-stat"><?php echo $inlineText;?></p>
                                        <?php } ?>
                                     </div>
                                    <?php //if($popularityIndex){ ?>
                                    <div class="ins-col-blk pull-right text-right visible-lg"><p><span class="popu-sc">Shiksha Popularity Index</span><strong class="ind-nmbr"><?php echo $popularityIndex;?></strong> <i class="t-icons t-info help-txt" helptext="Popular Colleges are identified using the student visits within college pages on Shiksha in the last 12 months. Scoring is on a relative scale from 0 to 100, where 100 signifies the college with most visits and a value of 50 signifies a college which received half as many visits." headertitle="Shiksha Popularity Index"></i></p></div>
                                    <?php //} ?>
                                </div>
                                <div class="ins-dv clearfix">
				<?php if($entityType=='institute' || $entityType =='university' ) { ?>
                                    <div class="ins-BtnCol pull-right text-right">
                <?php
                    if(!(($_COOKIE['ci_mobile'] == 'mobile') || ($GLOBALS['flag_mobile_user_agent'] == 'mobile'))){
                ?>
                                        <a href="javascript:void(0);" title="Save your shortlist, get updates, college recommendations etc" class="shortlist-btn" cta-type="shortlist" onclick="ajaxDownloadEBrochure(this,<?php echo $entityId;?>,'<?php echo $entityType;?>','<?php echo htmlentities($entityName);?>','ND_ShikshaETP','<?php echo $shortlistTrackingId;?>')" customcallback="trendsShortlistCallback" customactiontype="ND_ShikshaETP"><span class="glyphicon glyphicon-star"></span></a>
                                        <a href="javascript:void(0);" onclick="showCourseLayer('compare', {'instId':<?php echo $entityId;?>}, 'Select a course to compare','<?php echo $compareTrackingId;?>');" title="Compare colleges on ranking, placements, reviews, fees etc."><span>Add to</span> Compare</a>


                                        <a href="javascript:void(0);" class="dl-bro deb-btn" trackingId="<?php echo $brochureTrackingId;?>" listingId="<?php echo $entityId;?>" listingType="<?php echo $entityListingType;?>" listingName="<?php echo htmlentities($entityName);?>" cta-type="download_brochure" title="Download details of eligibility, admissions, fees, infra etc." hideReco="true">Download Brochure</a>
                <?php
                    }
                    else{
                ?>
                                        <a href="javascript:void(0);" class="shortlist-btn" cta-type="shortlist" onclick="listingShortlist('<?php echo $entityId;?>','<?php echo $shortlistTrackingId;?>','<?php echo $entityType;?>', {'pageType':'shikshaTrendsPageMob','listing_type':'<?php echo $entityType;?>','callbackFunctionParams':{'pageType':'shikshaTrendsPageMob'}});"><span class="glyphicon glyphicon-star"></span></a>
                                        <a href="javascript:void(0);" ga-attr="COMPARE_SHIKSHA_TREND" onclick="showCourseCompareLayer(<?php echo $entityId;?>,'<?php echo $entityType;?>','<?php echo $compareTrackingId;?>');" title="Compare colleges on ranking, placements, reviews, fees etc.">Compare</a>
                                        <a class="dl-bro deb-btn" ga-attr="SHIKSHA_TRENDS_DEB_MOB" cta-type="download_brochure" onclick="downloadCourseBrochure('<?php echo $entityId;?>','<?php echo $brochureTrackingId;?>',{'pageType':'shikshaTrendsPageMob','listing_type':'<?php echo $entityType;?>','callbackFunctionParams':{'pageType':'shikshaTrendsPageMob','thisObj':this}});" cta-type="download_brochure" title="Download details of eligibility, admissions, fees, infra etc.">Request Brochure</a>
                <?php
                    }
                ?>
                                    </div>
				<?php }
                if($entityType=='exam') { ?>
                                    <div class="ins-BtnCol pull-right text-right">
                                        <a href="javascript:void(0);" class="prime__btn dwn-eguide <?php if(isset($guideDownloaded) && $guideDownloaded){?> disable-btn <?php }?>" trackingId="<?php echo $guideTrackingId;?>" onclick='downloadGuide("<?php echo $guideTrackingId;?>");' title="Download exam information to read offline">
                                        <?php if(isset($guideDownloaded) && $guideDownloaded){
                                            echo "Guide Mailed";
                                        }else{
                                            echo "Download Guide";
                                        }
                                        ?>
                                        </a>
                                    </div>
                <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
