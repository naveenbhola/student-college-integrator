<ul id="course-tab" class="cs-tab-lst" style="min-height: 359px;">
    	<li><a href="javascript:void(0);" id="leftnav-overview" class="active" elementtofocus="overview-tab"><i class="listing-sprite overview-icon"></i>Overview <i class="listing-sprite tab-pointer"></i></a></li>
        <li><a href="javascript:void(0);" id="leftnav-fees" elementtofocus="fees-tab"><i class="listing-sprite fees-icon"></i>Fees & expenses <i class="listing-sprite tab-pointer"></i></a></li>
        <li><a href="javascript:void(0);" id="leftnav-eligibility" elementtofocus="eligibility-tab"><i class="listing-sprite eligibility-icon"></i>Entry requirements<i class="listing-sprite tab-pointer"></i></a></li>
    <?php   if($applicationProcessDataFlag==1){?>
            <li style="position:relative">
                <a href="javascript:void(0);" id="leftnav-applicationProcess" elementtofocus="applicationProcess-Tab">
                    <i class="listing-sprite cs-process-icon"></i>
                    <span>Application process</span>
                    <i class="listing-sprite tab-pointer"></i>
                </a>
            </li>
    <?php   }?>
	<?php   if($isPlacementFlag){?>
                <li><a href="javascript:void(0);" id="leftnav-placement" elementtofocus="placement-tab"><i class="listing-sprite placement-icon"></i>Placement <i class="listing-sprite tab-pointer"></i></a></li>
	<?php   }?>
	<?php   if($scholarshipTabFlag){?>
                <li>
						<a href="javascript:void(0);" id="leftnav-scholarships" elementtofocus="scholarships-tab"><i class="listing-sprite scholarship-icon"></i>Scholarships <i class="listing-sprite tab-pointer"></i></a>
						<span class="new-badge">New</span>
				</li>
	<?php   }?>
    <?php   if($showClassProfileData){?>
                <li><a href="javascript:void(0);" id="leftnav-placement" elementtofocus="class-profile-tab"><i class="listing-sprite profile-icon"></i> Profile <i class="listing-sprite tab-pointer"></i></a></li>
	<?php   }
            if(!empty($consultantData)){ ?>
                <li style="position:relative">
                    <a href="javascript:void(0);" id="leftnav-consultant" elementtofocus="consultant-tab">
                        <i class="listing-sprite consultant-icon"></i>Consultant<i class="listing-sprite tab-pointer"></i>
                    </a>
                </li>
	<?php   }?>
	<?php if(!empty($studentGuide)){?>
                <li><a href="javascript:void(0);" id="leftnav-studentGuide" elementtofocus="studentGuide-tab"><i class="listing-sprite stu-guide-icon"></i><span>Student Guides </span><i class="listing-sprite tab-pointer"></i></a></li>
    <?php   }?>
    <?php if(isset($scholarshipCardData) && isset($scholarshipCardData['totalCount']) && $scholarshipCardData['totalCount']>0){?>
                <li><a href="javascript:void(0);" class="saScholarshipTab" id="leftnav-saScholarships" elementtofocus="saScholarships-tab"><i class="listing-sprite scholarship-icon"></i><span>Scholarships <sup class="saNew">New</sup></span><i class="listing-sprite tab-pointer"></i></a></li>
    <?php   }?>
</ul>
