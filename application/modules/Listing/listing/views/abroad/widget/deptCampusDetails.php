<?php if($universityAddress || $universityEmail || $universityWebsite || ($campuses['campus_name'] || $campuses['campus_website_url'] || $campuses['campus_address']) || $university_contact_number) { ?>
	<div class="widget-wrap clearwidth">
		<h2>Campus Details</h2>
		<div class="location-box clearwidth" style="border:0 none;">
			<div class="scrollbar1" id="scrollbar_uniCampusDetails">
				<div class="scrollbar">
					<div class="track">
						<div class="thumb">
							<div class="end"></div>
						</div>
					</div>
				</div>
				<div class="viewport" style="height: 210px; width:606px;">
					<div class="overview" style="height:120px;">
						<?php if($universityName || $universityAddress || $universityEmail || $universityWebsite) { ?>
							<h3 class="font-12"><strong>Main Campus Address</strong></h3>
							<ul class="location-details clearwidth">
								<?php if($universityName || $universityAddress) { ?>
									<li>
										<i class="listing-sprite loc-icon flLt"></i>
										<div class="campus-details">
											<p><?=$universityName?><?php if($universityAddress && $universityName) { ?>, <?php } ?><?=$universityAddress?></p>
										</div>
									</li>
								<?php } ?>

								<?php if($universityEmail) { ?>
									<li>
										<i class="listing-sprite mail-icon flLt"></i>
										<div class="campus-details">
											<a href="mailto:<?=$universityEmail?>" style="color: #333"><?=$universityEmail?></a>
										</div>
									</li>
								<?php } ?>

								<?php if($universityWebsite) {
									$websiteString = substr($universityWebsite, 0, 30);
									if(strlen($websiteString) >= 30) {
										$websiteString = $websiteString.'..';
									} ?>
									<li>
										<i class="listing-sprite web-icon flLt"></i>
										<div class="campus-details">
											<a href="<?=$universityWebsite?>" onclick="studyAbroadTrackEventByGA('ABROAD_UNIVERSITY_PAGE', 'outgoingLink');" rel="nofollow" style="color: #333" target="_blank"><?=$websiteString?></a>
										</div>
									</li>
								<?php } ?>
							</ul>
						<?php } ?>

						<?php foreach($campuses as $key=>$campus){
							if($campus->getAddress() || $campus->getWebsiteURL()) { ?>
								<div class="clearfix"></div>
								<div class="divider"></div>
								<h3 class="font-12"><strong>Campus <?=$key+1?></strong><?php if($campus->getName()) { ?>: <?php echo ($campus->getName()); } ?></h3>
								<ul class="location-details clearwidth">
									<?php if($campus->getAddress()) { ?>
										<li>
											<i class="listing-sprite loc-icon flLt"></i>
											<div class="campus-details">
												<p><?=$campus->getAddress()?></p>
											</div>
										</li>
									<?php } ?>

									<?php if($campus->getWebsiteURL()) {
										$websiteString = substr($campusFinalWebsite[$key], 0, 30);
										if(strlen($websiteString) >= 30) {
											$websiteString = $websiteString.'..';
										} ?>
										<li>
											<i class="listing-sprite web-icon flLt"></i>
											<div class="campus-details">
												<a href="<?=$campusFinalWebsite[$key]?>" onclick="studyAbroadTrackEventByGA('ABROAD_UNIVERSITY_PAGE', 'outgoingLink');" style="color: #333" rel="nofollow" target="_blank"><?=$websiteString?></a>
											</div>
										</li>
									<?php } ?>
								</ul>
							<?php } ?>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
				<?php
		if(!empty($university_contact_number)) { ?>
			<span id="SA_contact_number_cont_bottom" style="text-decoration:none;" class="contact-btn" onclick="studyAbroadTrackEventByGA('ABROAD_UNIVERSITY_PAGE', 'campusDetailsViewContactNumber'); showUniversityContactNumberOnCoursePage('<?php echo $listingTypeId;?>', '<?php echo $listingType;?>', 'UNI_CAMPUS_DETAILS_WIDGET');">
				<i class="common-sprite phone-icon"></i>
				<span style="color: #008489" id="SA_contact_number_label_bottom">View contact number</span>
				<span id="SA_contact_number_value_bottom" style="color:#333333;display:none;"><?php echo $university_contact_number;?></span>
			</span>
		<?php } ?>
	</div>
<?php } ?>
