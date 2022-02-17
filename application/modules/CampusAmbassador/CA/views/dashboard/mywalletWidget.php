<ul class="left-tab">
<li><a <?php if($pageName != 'walletPage'){?> class="active" <?php }else{?> href="/CA/CRDashboard/getCRUnansweredTab" <?php }?>><i class="campus-sprite dashboard-icn"></i>My Dashboard</a></li>
<li><div class="tab-sep"></div></li>
<li>
	<a <?php if($pageName == 'walletPage'){?> class="active" <?php }else{?> href="/CA/CRDashboard/getMyWallet" <?php }?>>
		<div class="wallet-tab">
		<p>My Wallet</p>
	    <p class="campus-sprite wallet-icn"></p>
	    <p class="earning-point">
		&#8377; <?php if($earning['totalEarn']>0){echo ($earning['totalEarn'] - $totaPaid);}else{echo 0;}?><br><span>CURRENT EARNINGS</span>
	    </p>
	</div>	
	</a>
</li>

<li><a style="color:#16668a; text-align:center" href="javascript:void(0);" onclick='return popitup("<?php if($this->isMentor){echo SHIKSHA_HOME.'/shikshaHelp/ShikshaHelp/earningMentorGuidelines';}else{echo SHIKSHA_HOME.'/shikshaHelp/ShikshaHelp/earningGuidelines';} ?>",500,1030,"yes",50,200);'>How do I earn more?</a></li>
</ul>