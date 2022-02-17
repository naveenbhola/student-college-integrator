<?php
$fromCurrencyUnit = $courseObj->getScholarshipCurrencyCode(); 
?>
<section class="detail-widget navSection" data-enhance="false" id="scholarshipSection">
 <div class="detail-widegt-sec">
        <div class="detail-info-sec">
            <strong>Scholarships</strong>
            <div class="dynamic-content">
            
				<?php if($courseObj->getScholarshipDescription()!=''){?>    
                    <strong class="font-13">Scholarship Description</strong>
                    <p><?php echo htmlentities($courseObj->getScholarshipDescription());?></p>
                <?php } ?>
                
                <?php if($courseObj->getScholarshipEligibility()!=''){?>    
                    <strong class="font-13">Scholarship Eligibility</strong>
                    <p><?php echo htmlentities($courseObj->getScholarshipEligibility());?></p>
                <?php } ?>
                <ul class="schlp-contentList">
                <?php if($courseObj->getScholarshipAmount()!=0){?>
                    <li><strong>Scholarship Amount</strong>
						<p style="font-weight: bold;"><?php if($fromCurrencyUnit!='INR'){echo $fromCurrencyUnit." ".$courseObj->getScholarshipAmount();?> => <?php } echo $scholarshipAmountDetail;?> /-</p>
					</li>
                <?php } ?>
                
                <?php if($courseObj->getScholarshipDeadLine()!=''){?>
                    <li><strong>Scholarship Deadline</strong>
						<p style="font-weight: bold;"><?php echo htmlentities($courseObj->getScholarshipDeadLine());?></p>
					</li>	
                <?php } ?>
                </ul>
                <?php foreach($courseObj->getCustomScholarship() as $key=>$val){?>
                    <strong class="font-13"><?php echo htmlentities($val['caption'])?></strong>
                    <p><?php echo htmlentities($val['value'])?></p>
                <?php }?>
                    
                    <?php if($scholarshiplLink !='' || $courseLevelLink!='' ||  $deptLevelLink!='' || $univLevelLink!=''){?>
                    <p>More details on the following links:</p>
                    <ul class="level-list">
                    <?php if($scholarshiplLink!=''){?><li><a target="_blank" rel="nofollow" href="<?php echo $scholarshiplLink; ?>"><?php echo htmlentities(formatArticleTitle($scholarshiplLink,40)); ?><i class="sprite arrow-icon"></i></a></li>  <?php } ?>    
                	<?php if($courseLevelLink!='') {?><li><a target="_blank" rel="nofollow" href="<?php echo $courseLevelLink; ?>"><?php echo htmlentities(formatArticleTitle($courseLevelLink,40)); ?><i class="sprite arrow-icon"></i></a></li>  <?php } ?>
					<?php if($deptLevelLink!='')   {?><li><a target="_blank" rel="nofollow" href="<?php echo $deptLevelLink;   ?>"><?php echo htmlentities(formatArticleTitle($deptLevelLink,40));   ?><i class="sprite arrow-icon"></i></a></li>  <?php } ?>
                    <?php if($univLevelLink!='')   {?><li><a target="_blank" rel="nofollow" href="<?php echo $univLevelLink;   ?>"><?php echo htmlentities(formatArticleTitle($univLevelLink,40));   ?><i class="sprite arrow-icon"></i></a></li>  <?php } ?>
                    </ul>
                    <?php } ?>
			    
            </div>
            
        </div>
    </div>
</section>