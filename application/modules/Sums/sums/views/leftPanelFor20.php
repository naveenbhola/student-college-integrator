	<div class="lineSpace_10">&nbsp;</div>
        <div class="raised_greenGradient"> 
            <b class="b1"></b><b class="b2"></b><b class="b3" style="background-color:#F5FDE6"></b><b class="b4" style="background-color:#F5FDE6"></b>
            <div class="boxcontent_greenGradient1">				  		
                <div class="mar_left_10p">
                    <div class="lineSpace_10">&nbsp;</div>
		<ul style="margin:0; padding:0;list-style-type:none">
		<?php if(array_key_exists(16,$sumsUserInfo['sumsuseracl']))
                {?>
                    <li class="<?php if(stripos($_SERVER['REQUEST_URI'],'viewTrans')) echo 'sumsleftPanelPreSelected'; else 'sumsleftPanelPreUnSelected'; ?>"><a href="/sums/Manage/validationQueue/viewTrans/<?php echo $prodId; ?>" class="fontSize_12p">View/Cancel Transaction</a></li>
                    <div class="lineSpace_10">&nbsp;</div>
		<?php } ?>
                <?php 
                    //if(array_key_exists(15,$sumsUserInfo['sumsuseracl']))
                    if(false)
                {?>
                    <li class="<?php if(stripos($_SERVER['REQUEST_URI'],'validateFinance')) echo 'sumsleftPanelPreSelected'; else 'sumsleftPanelPreUnSelected'; ?>"><a href="/sums/Manage/validateFinance/<?php echo $prodId; ?>" class="fontSize_12p">Finance Approval Queue</a></li>
                    <div class="lineSpace_10">&nbsp;</div>
		<?php }?>
		<?php if(array_key_exists(6,$sumsUserInfo['sumsuseracl']))
                {?>
                    <li class="<?php if(stripos($_SERVER['REQUEST_URI'],'searchQuotation')) echo 'sumsleftPanelPreSelected'; else 'sumsleftPanelPreUnSelected'; ?>"><a href="/sums/Quotation/searchQuotation/<?php echo $prodId; ?>" class="fontSize_12p">Create Transaction (from Quotation)</a></li>
                    <div class="lineSpace_10">&nbsp;</div>
		<?php }?>
		<?php if(array_key_exists(13,$sumsUserInfo['sumsuseracl']))
                {?>
                    <li class="<?php if(stripos($_SERVER['REQUEST_URI'],'validationQueue') && ($viewTrans == -1)) echo 'sumsleftPanelPreSelected'; else 'sumsleftPanelPreUnSelected'; ?>"><a href="/sums/Manage/validationQueue/-1/<?php echo $prodId; ?>" class="fontSize_12p">Approve high discount Transaction</a></li>
                    <div class="lineSpace_10">&nbsp;</div>
		<?php } ?>
		<?php if(array_key_exists(24,$sumsUserInfo['sumsuseracl']))
                { ?>
                    <li class="<?php if((stripos($_SERVER['REQUEST_URI'],'editPayment')) && ($viewTrans == 'editPayment')) echo 'sumsleftPanelPreSelected'; else 'sumsleftPanelPreUnSelected'; ?>"><a href="/sums/Manage/validationQueue/editPayment/<?php echo $prodId; ?>" class="fontSize_12p">View/ Edit payment</a></li>
                    <div class="lineSpace_10">&nbsp;</div>
		<?php } ?>
		</ul>
                </div>
            </div>
            <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
        </div>
