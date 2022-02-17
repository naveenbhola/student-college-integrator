<?php
 //_p($countriesRepresentedTabData);
?>
<div class="overview-details flLt servicesOffered-tab" style="width:100%;">
	<h2>Services offered by <?=(htmlentities($consultantObj->getName()))?> for students</h2>
	<div id="studentAdmitted-tab-scrollbar1" class="clearwidth cons-scrollbar1 scrollbar1 soft-scroller">
		<div class="cons-scrollbar scrollbar" style="visibility:hidden; left: 8px;">
			<div class="track">
				<div class="thumb"></div>
			</div>
		</div>
		<div class="viewport" style="height:400px">
			<div style="width:98%" class="overview clearwidth dyanamic-content">
				<div class="mt10 clearwidth">
					<p class="consultant-subtitle">Consultant services</p>
					<?php
						if($consultantObj->hasPaidServices() == 'yes'){
							echo $consultantObj->getPaidServicesDetails();
						} else { ?>
						<ul class="paid-services-list">
							<li style="list-style:none;">This consultant does not provide paid services</li>
						</ul>
					<?php } ?>
				</div>
				<div class="mt10 clearwidth">
					<p class="consultant-subtitle">Test preparation</p>
					<?php
						if($consultantObj->hasTestPrepServices() == 'yes'){
							echo $consultantObj->getTestPrepServicesDetails();
						} else { ?>
						<ul class="paid-services-list">
							<li style="list-style:none;">This consultant does not provide test preparation services</li>
						</ul>
					<?php } ?>
				</div>
				<?php if($consultantObj->getCEOName() != ''){ ?>
				<div class="mt10 clearwidth">
					<p class="consultant-subtitle">CEO of the consultancy: <span style="font-weight:normal"><?=htmlentities($consultantObj->getCEOName())?></span></p>
					<?php if($consultantObj->getCEOQualification() != ''){ ?>
					<p><?=($consultantObj->getCEOQualification())?></p>
					<?php } ?>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>