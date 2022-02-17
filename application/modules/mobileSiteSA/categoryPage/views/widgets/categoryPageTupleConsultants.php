<div style="padding: 5px;">
	<div class="admission-consultant-sec clearfix">
        <!--<div class="admission-concultant-btn">
			<a href="javascript:void(0);" onclick="showHideConsultantBlock(this);">
				<strong class="flLt"><span class="right-mark">&#10004; </span> Admission help available <span class="regionChangeConsultantCountSection">(<?=count($regionConsultantMapping[$currentRegion['regionId']]['consultantIds'])?> consultant<?=count($regionConsultantMapping[$currentRegion['regionId']]['consultantIds'])==1?'':'s'?>)</span></strong>
				<i style="top:3px;" class="sprite arr-dwn flRt"></i>
				<div class="clearfix"></div>
			</a>
		</div>-->
		<div class="admission-consultant-detail">
			<p><strong>Admission help available for this university</strong></p>
			<div>
				<strong style="font-weight:normal; padding-top:4px; font-size: 10px;" class="flLt font-12"><i class="sprite verify-sml-icon"></i>Shiksha verified Consultant<span class="consultantRegionSControl">s</span> in:</strong>
				<div class="flRt custom-dropdown-2">
				    <select style="width:106px;background:#fff; font-size:12px;" class="universal-select font-12 regionSelector" onchange="changeRegion(this);">
						<?php foreach($regionConsultantMapping as $regionId => $regionData){ ?>
						<option value="<?=$regionId?>"><?=$regionData['regionName']?></option>
						<?php }?>
					</select>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="listingConsultantVisibilityDiv">
				<?php foreach($regionConsultantMapping as $regionId => $regionData){ ?>
					<?php
						if(empty($regionData['consultantIds'])){
							$this->load->view("categoryPage/widgets/categoryPageTupleConsultantsZeroResults",array('regionId'=>$regionId));
						}else{ ?>
						<ul style="display: none;" class="courseConsultantTab_<?=$regionId?> consultant-call-list clearfix">
							<?php foreach($regionData['consultantIds'] as $consultantId){ ?>
								<li>
									<div class="consultant-title flLt">
										<strong><?=$consultantData[$consultantId]['consultantName']?></strong>
										<span><?=$consultantData[$consultantId]['regions'][$regionId]['office']['officeAddress']?></span>
									</div>
									<?php if(!empty($consultantData[$consultantId]['regions'][$regionId]['office']['phoneNumber'])){?>
										<div class="cons-call-sec flRt">
											<a href="tel:+<?=$consultantData[$consultantId]['regions'][$regionId]['office']['phoneNumber']?>">
												<i class="sprite cons-call-icon"></i><br>
												Call
												<span></span>
											</a>
										</div>
									<?php } ?>
									<div class="clearfix"></div>
								</li>
							<?php } ?>
						</ul>
					<?php } ?>
				<?php } ?>
			</div>
			<div class="clearfix"></div>
        </div>
	</div>
</div>
<script>
</script>
