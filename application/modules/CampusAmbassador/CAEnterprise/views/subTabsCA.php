<div class="campusambassador-tab">
	<ul>
	    <li <?php if($subTabType == 1){?>  class="active"; <?php } ?>><a href="<?php echo ENTERPRISE_HOME; ?>/CAEnterprise/CampusAmbassadorEnterprise/getAllCADetails">Campus Ambassador Applications</a></li>
	    <li <?php if($subTabType == 2){?>  class="active"; <?php } ?>><a href="<?php echo ENTERPRISE_HOME; ?>/CAEnterprise/CampusAmbassadorEnterprise/getAllDiscussion">Campus Discussion</a></li>
		<li <?php if($subTabType == 3){?>  class="active"; <?php } ?>><a href="<?php echo ENTERPRISE_HOME; ?>/CAEnterprise/CampusAmbassadorEnterprise/myTask">My Task</a></li>
	    <li <?php if($subTabType == 4){?>  class="active";style="display: block;" <?php }else{?> style="display: none;" <?php } ?>><a href="<?php echo ENTERPRISE_HOME; ?>/CAEnterprise/CampusAmbassadorEnterprise/myTask">Task Submissions</a></li>
	    <li <?php if($subTabType == 5){?>  class="active"; <?php } ?>><a href="<?php echo ENTERPRISE_HOME; ?>/CAEnterprise/CampusAmbassadorEnterprise/crWallet">Incentives</a></li>
	    <li <?php if($subTabType == 9){?>  class="active"; <?php } ?>><a href="<?php echo ENTERPRISE_HOME; ?>/CAEnterprise/CampusAmbassadorEnterprise/crPayAll">Bulk Payment</a></li>
	</ul>
</div>
