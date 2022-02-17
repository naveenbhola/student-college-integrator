<style type="text/css">
	.pbt-inr-tab {border-bottom: 1px solid #cbcbcb;float: left;margin-bottom: 20px;width: 100%;}
	.pbt-inr-tab ul {padding-left: 15px;}
	.pbt-inr-tab ul li.active {background: #fff none repeat scroll 0 0;cursor: auto;top: 1px;}
	.pbt-inr-tab ul li {-moz-border-bottom-colors: none;-moz-border-left-colors: none;-moz-border-right-colors: none;-moz-border-top-colors: none;background: #ececec none repeat scroll 0 0;border-color: #cbcbcb #cbcbcb -moz-use-text-color;border-image: none;border-style: solid solid none;border-width: 1px 1px 0;cursor: pointer;float: left;font: 18px Arial,Helvetica,sans-serif;margin-right: 10px;padding: 10px 12px;position: relative;}
</style>
<div class="lineSpace_10">&nbsp;</div>
<div class="fontSize_14p bld">Configure Leads & Responses Access</div>
<div class="lineSpace_10">&nbsp;</div>
<div class="pbt-inr-tab">
	<ul>
		<li class="<?php echo ($isManualLDBAccess == true)?'active':'';?>"><a href="<?php echo ($isManualLDBAccess== true)? "javascript:void(0)": "/enterprise/shikshaDB/manageManualLDBAccess"; ?>">Manual LDB Access</a></li>			
		<li class="<?php echo ($isExamResponseAccess == true)?'active':'';?>"><a href="<?php echo ($isExamResponseAccess== true)? "javascript:void(0)": "/enterprise/examResponseAccess/manageManualAccess"; ?>">Exam Responses Access</a></li>
		<li class="<?php echo ($isNaukriLearningLeadsAccess == true)?'active':'';?>"><a href="<?php echo ($isNaukriLearningLeadsAccess== true)? "javascript:void(0)": "/enterprise/NaukriLearningLeadsAccess/manageManualSubscriptionAccess"; ?>">Naukri Learning Leads Access</a></li>
	</ul>
</div>

<div class="lineSpace_10">&nbsp;</div>
<div class="lineSpace_10">&nbsp;</div>
<div class="pbt-inr-tab">
	<ul>
		<?php
			if($isManualLDBAccess == true) { 
				$topLinks = array(
					array(
						'Heading' => "LDB Access",
						'url'     => ($isManualLDBAccessByStream == false)? "javascript:void(0);": "/enterprise/shikshaDB/manageManualLDBAccess",
						'class'   => ($isManualLDBAccessByStream == false)? "active": ""
					),
					array(
						'Heading' => "LDB search access by Stream",
						'url'     => ($isManualLDBAccessByStream == true)? "javascript:void(0);": "/enterprise/shikshaDB/manageManualLDBAccessByStream",
						'class'   => ($isManualLDBAccessByStream == true)? "active": ""
					)
				);
			} else if($isExamResponseAccess == true) {
				$topLinks = array(
					array(
						'Heading' => "Grant Access",
						'url'     => ($isClientSubscription == true)? "javascript:void(0);": "/enterprise/examResponseAccess/manageManualAccess",
						'class'   => ($isClientSubscription == true)? "active": ""
					),
					array(
						'Heading' => "Active Campaigns",
						'url'     => ($isActiveSubscription == true)? "javascript:void(0);": "/enterprise/examResponseAccess/subscription/active",
						'class'   => ($isActiveSubscription == true)? "active": ""
					),
					array(
						'Heading' => "Inactive / Expired Campaigns",
						'url'     => ($isExpiredSubscription == true)? "javascript:void(0);": "/enterprise/examResponseAccess/subscription/expired",
						'class'   => ($isExpiredSubscription == true)? "active": ""
					)
				);
			} else if($isNaukriLearningLeadsAccess == true) {
				$topLinks = array(
					array(
						'Heading' => "Grant Naukri Leads Access",
						'url'     => ($isNaukriLearningSubscription == true)? "javascript:void(0);": "/enterprise/NaukriLearningLeadsAccess/manageManualSubscriptionAccess",
						'class'   => ($isNaukriLearningSubscription == true)? "active": ""
					),
					array(
						'Heading' => "Active Subscriptions",
						'url'     => ($isActiveSubscription == true)? "javascript:void(0);": "/enterprise/NaukriLearningLeadsAccess/subscription/active",
						'class'   => ($isActiveSubscription == true)? "active": ""
					)
				);
			}
			
			foreach ($topLinks as $key => $value) { ?>
				<li class="<?php echo $value['class'];?>">
					<a href="<?php echo $value['url'];?>"><?php echo $value['Heading'];?></a></li>
			<?php } ?>	
	</ul>
</div>
