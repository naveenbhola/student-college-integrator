<div id="compareCourseBottomLayer" style="z-index: 5;" class="other-compare-page-layer">
	<div class="compare-col flLt">
		<?php $cookie = json_decode($_COOKIE['compareCourses'],true);?>
		<?php if(count($cookie) > 1){?>
			<?php global $useSingleSignUpForm; 
			$useSingleSignUpForm = Modules::run('common/ABTesting/executeABTesting',NEW_SINGLESIGNUPFORM_EXPOSURE_PERCENTAGE,ABROAD_SIGNLESIGNUPFORM_ABTESTNAME);?> 
			<a class="button-style" href="javascript:void(0);" onclick="compareCoursesCatergoryPage('compare',<?php echo ($trackingId == -1?655:$trackingId); ?>,'<?php echo ($useSingleSignUpForm==1)?'new':'old';?>');"><strong>Compare Colleges &gt;</strong></a>
		<?php }else{ ?>
			<a class="button-style disabled" href="javascript:void(0);" onclick=""><strong>Compare Colleges &gt;</strong></a>
		<?php } ?>
		<a class="clear-link" href="javascript:void(0);" onclick="clearCompareSelection();">Clear all</a>
		<ul class="compare-list">
			
			<?php foreach($courseData as $courseId => $courseDataObj){ ?>
				<li>
					<div class="flLt comp-ins-img">
						<img width="76" height="53" alt="<?=htmlentities($courseDataObj['universityName'])?>" src="<?=$courseDataObj['photoURL']?>">
						<?php if($courseId > 0) {?><a class="img-remove-mark" href="javascript:void(0)" onclick="addRemoveFromCompare(<?=$courseDataObj['id']?>,'layer');">&times;</a><?php } ?>
					</div>
					 <div class="comp-ins-detail">
						<p><?=formatArticleTitle(htmlentities($courseDataObj['universityName']),63)?></p>
						<span><?=$courseDataObj['countryName']?></span>
					</div>
					<?php if($courseId > 0) {?><input type="hidden" class="comparedCourse" value="<?=($courseId)?>"><?php } ?>
					<div class="clearfix"></div>
				</li>
			<?php } ?>
			<?php if(sizeof($courseData) < 3){ ?>
				<li id="compareLayerRecommendations" class="compare-suggestion-box">
					<div id = "recommendationDiv" style="display:none;height:110px;">
						<p style="margin-bottom:5px;">Compare with similar colleges</p>
						<div class="pointer-seprator">
						<ul class="sticky-suggestion-list">
						</ul>
						<i class="common-sprite left-pointer"></i>
						</div>
					</div>
					<div id = "lastCellDiv" style="display:none;">
                    	<div class="flLt comp-ins-img">
							<img width="76" height="53" alt="" src="">
                        </div>
                        <div class="comp-ins-detail">
                            <p></p>
                            <span></span>
                        </div>
                        <div class="clearfix"></div>
                    </div>
				</li>
			<?php } ?>
		</ul>
	</div>
</div>
