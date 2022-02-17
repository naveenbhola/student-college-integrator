<?php if($universityAddress || $universityEmail || $universityWebsite || $university_contact_number || ($livingExpense && $livingExpenseCurrencyId) || $livingExpenseURL != "http://" || ($listingType == "university" && ($universityAccomodationDetails || $universityAccomodationURL))) { ?>
	<div class="location-box clearwidth">
            <?php if($listingType == 'course') {
                
            } else {
                if(($livingExpense && $livingExpenseCurrencyId) || $livingExpenseURL != "http://") { ?>
			<h3><strong>Cost of Living :</strong></h3>
			<?php if($livingExpense && $livingExpenseCurrencyId) { ?>
				<div class="cost-table">
					<div class="cost-row">
						<?php if($livingExpenseCurrencyId != 1) {
							if(!empty ($currencyMapping[$livingExpenseCurrencyId])) { ?>
								<div class="cost-columns"><strong><?=$livingExpenseCurrencyObj->getName()?> (<?=$currencyMapping[$livingExpenseCurrencyId]?>)</strong></div>
							<?php } else { ?>
								<div class="cost-columns"><strong><?=$livingExpenseCurrencyObj->getName()?> (<?=$livingExpenseCurrencyObj->getCode()?>)</strong></div>
							<?php } ?>
							<div class="cost-columns" style="padding:0 20px">&nbsp;</div>
						<?php } ?>
						<div class="cost-columns"><strong>Indian Rupee (Rs.)</strong></div>
					</div>
					
					<div class="cost-row row-box">
						<?php if($livingExpenseCurrencyId != 1) { ?>
							<div class="cost-columns">
								<?php if(!empty ($currencyMapping[$livingExpenseCurrencyId])) { ?>
									<p><strong><?=$currencyMapping[$livingExpenseCurrencyId]?> <?=$livingExpense?></strong><span class="price-tag">Monthly</span></p>
								<?php } else { ?>
									<p><strong><?=$livingExpenseCurrencyObj->getCode()?> <?=$livingExpense?></strong><span class="price-tag">Monthly</span></p>
								<?php } ?>
							</div>
							<div class="cost-columns" style="padding:0 20px"> = </div>
						<?php } ?>
						
						<div class="cost-columns">
							<p><strong><span style="font-size:13px;">&#x20B9;</span> <?=$livingExpenseInRupees?></strong><span class="price-tag">Monthly</span></p>
						</div>
					</div>
				</div>
			<?php } ?>
			
			<?php if($livingExpenseURL != "http://") { ?>
				<p class="flRt font-11"><a target="_blank" rel="nofollow" onclick="studyAbroadTrackEventByGA('ABROAD_<?=strtoupper($listingType)?>_PAGE', 'outgoingLink');" href="<?=$livingExpenseURL?>">Living expenses<i class="common-sprite ex-link-icon"></i></a></p>
			<?php } ?>
		<?php }
                
            }
            ?>
		
		<?php if($universityAddress || $universityEmail || $universityWebsite || $university_contact_number) { ?>
			<div class="clearfix"></div>
			<h3 class="font-12"><strong>Contact Details</strong></h3>
			<div class="scrollbar">
				<div class="viewport">
					<div class="overview">
					<ul class="location-details clearwidth">
					<?php if($universityAddress) { ?>
						<li>
							<i class="listing-sprite loc-icon flLt"></i>
							<div class="campus-details">
								<p><?=$universityName?></p>
								<p><?=$universityAddress?></p>
							</div>
						</li>
					<?php } ?>
					
					<?php if($universityEmail) { ?>
					<li>
						<i class="listing-sprite mail-icon flLt"></i>
						<div class="campus-details">
							<a href="mailto:<?=$universityEmail?>" style="color: #333"><?=wordwrap($universityEmail, 35, "\n", true)?></a>
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
								<a href="<?=$universityWebsite?>" onclick="studyAbroadTrackEventByGA('ABROAD_<?=strtoupper($listingType)?>_PAGE', 'outgoingLink');" rel="nofollow" target="_blank" style="color: #333"><?=$websiteString?></a>
							</div>
						</li>
					<?php } ?>
				</ul>
					<?php if(!empty($university_contact_number)) { ?>
						<span style="width: 100%" id="SA_contact_number_cont" class="contact-btn" onclick="studyAbroadTrackEventByGA('ABROAD_<?=strtoupper($listingType)?>_PAGE', 'rightColumn', 'viewContactNumber'); showUniversityContactNumberOnCoursePage('<?php echo $listingTypeId;?>', '<?php echo $listingType;?>', 'RIGHT_COL_WIDGET');">
							<i class="common-sprite phone-icon"></i>
							<span style="color: #008489" id="SA_contact_number_label">View contact number</span>
							<span id="SA_contact_number_value" style="color:#333333;display:none;"><?=$university_contact_number?></span>
						</span>
					<?php } ?>
					</div>
				</div>
			</div>
		<?php } ?>
		<?php if($listingType == "university" && !empty($universityAccomodationDetails)) { ?>
			<div class="clearfix"></div>
			<h3 class="font-12" style="margin-top:12px"><strong>Accommodation details</strong></h3>
			<div class="acco-details dyanamic-content">
				<p><?=$universityAccomodationDetails?></p>
			</div>
			<?php if($universityAccomodationURL != "http://" && !empty($universityAccomodationURL)) { ?>
				<p class="flRt font-11"><a target="_blank" rel="nofollow" href="<?=$universityAccomodationURL?>" onclick="studyAbroadTrackEventByGA('ABROAD_<?=strtoupper($listingType)?>_PAGE', 'outgoingLink');">Accommodation details<i class="common-sprite ex-link-icon"></i></a></p>
			<?php } ?>
		<?php } ?>
	</div>
<?php
}
?>
