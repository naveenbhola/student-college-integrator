<section class="detail-widget navSection" id="consultantSection" data-enhance="false">
    <div class="detail-widegt-sec">
        <div class="detail-info-sec">
            <div>
                <strong>
					Consultant<span class="consultantRegionSControl"><?=count($regionConsultantMapping[$currentRegion['regionId']]['consultantIds'])==1?'':'s'?></span> who can help you with admission related guidance for this university
				</strong>
            </div>
            <p>All consultants here are  <i class="sprite cons-verified-icn"></i><a href="javascript:void(0);">Shiksha Verified</a></p>
			<div>
				<strong style="font-weight:normal; padding-top:4px;" class="flLt font-12">Consultant<span class="consultantRegionSControl"><?=count($regionConsultantMapping[$currentRegion['regionId']]['consultantIds'])==1?'':'s'?></span> located in:</strong>
				<div class="flRt custom-dropdown">
					<select style="width:120px;" class="universal-select font-12 regionSelector" onchange="changeRegionListing(this);">
						<?php foreach($regionConsultantMapping as $regionId => $data){ ?>
							<option value="<?=$regionId?>"><?=htmlentities($data['regionName'])?></option>
						<?php } ?>
					</select>
				</div>
				<div class="clearfix"></div>
			</div>
			<div id="listingConsultantVisibilityDiv">
				<?php foreach($regionConsultantMapping as $regionId => $regionData) { ?>
					<?php if(empty($regionData['consultantIds'])){ ?>
						<div class='courseConsultantTab_<?=$regionId?> noResultDivBlock'>
							<?php $this->load->view('widgets/consultantWidgetNoResult'); ?>
						</div>
					<?php }else{ ?>
						<ul style="<?=($regionId == $currentRegion['regionId'])?'':'display:none;'?>" class="courseConsultantTab_<?=$regionId?> consultant-call-list clearfix">
							<?php foreach($regionData['consultantIds'] as $tconsultantId){ ?>
								<li>
									<div class="consultant-title flLt">
										<strong><?=htmlentities($consultantData[$tconsultantId]['consultantName'])?></strong>
										<span><?=$consultantData[$tconsultantId]['regions'][$regionId]['office']['officeAddress']?></span>
									</div>
									<div class="cons-call-sec flRt">
										<a href="tel:+<?=$consultantData[$tconsultantId]['regions'][$regionId]['office']['phoneNumber']?>">
											<i class="sprite cons-call-icon"></i><br/>
											Call 
											<span>
											</span>
										</a>
									</div>
									<div class="clearfix"></div>
								</li>
							<?php } ?>
						</ul>
					<?php } ?>
				<?php } ?>
			</div>
			<div class="clearfix"></div>
		</div>
		<div class="clearfix"></div>
	</div>
</section>

<script>
	// This function is called on document.ready to fix UI issues that may need the same.
	function fixConsultantWidget(){
		$j("#listingConsultantVisibilityDiv").children("ul").each(
			function(){
				$j(this).children(':last').addClass("last-child");
			}
		);
	}
</script>
