     <div class="lineSpace_10">&nbsp;</div>
       <div class="raised_greenGradient"> 
            <b class="b1"></b><b class="b2"></b><b class="b3" style="background-color:#F5FDE6"></b><b class="b4" style="background-color:#F5FDE6"></b>
            <div class="boxcontent_greenGradient1">				  		
                <div class="mar_left_10p">
                    <div class="lineSpace_10">&nbsp;</div>
		<ul style="margin:0; padding:0;list-style-type:none">		
		<?php if(array_key_exists(18,$sumsUserInfo['sumsuseracl']))
                {?>
                    <li class="<?php if(stripos($_SERVER['REQUEST_URI'],'validateOps')) echo 'sumsleftPanelPreSelected'; else 'sumsleftPanelPreUnSelected'; ?>"><a href="/sums/Manage/validateOps/<?php echo $prodId; ?>" class="fontSize_12p">Approve Transaction to Create Subscriptions</a></li>
                    <div class="lineSpace_10">&nbsp;</div>
		<?php }?>
                <?php if(array_key_exists(43,$sumsUserInfo['sumsuseracl']) || array_key_exists(44,$sumsUserInfo['sumsuseracl']))
                {?>
                <li class="<?php if((stripos($_SERVER['REQUEST_URI'],'searchTransForSubsEdit')) || (stripos($_SERVER['REQUEST_URI'],'subscriptionsForTrans'))) echo 'sumsleftPanelPreSelected'; else 'sumsleftPanelPreUnSelected'; ?>"><a href="/sums/Manage/searchTransForSubsEdit/<?php echo $prodId; ?>" class="fontSize_12p">Change Subscription Validity</a></li>
                    <div class="lineSpace_10">&nbsp;</div>
		<?php }?>
                <?php if(array_key_exists(43,$sumsUserInfo['sumsuseracl']) || array_key_exists(44,$sumsUserInfo['sumsuseracl']))
                {?>
                <li class="<?php if((stripos($_SERVER['REQUEST_URI'],'searchTransForConsumedEdit')) || (stripos($_SERVER['REQUEST_URI'],'consumedForSubs')) || (stripos($_SERVER['REQUEST_URI'],'subscriptionsForConsumptions'))) echo 'sumsleftPanelPreSelected'; else 'sumsleftPanelPreUnSelected'; ?>"><a href="/sums/Manage/searchTransForConsumedEdit/<?php echo $prodId; ?>" class="fontSize_12p">Change Posted Start/Expiry Dates</a></li>
                    <div class="lineSpace_10">&nbsp;</div>
		<?php }?>
		</ul>
                </div>
            </div>
            <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
        </div>
