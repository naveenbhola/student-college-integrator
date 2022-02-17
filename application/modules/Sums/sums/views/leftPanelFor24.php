	<div class="lineSpace_10">&nbsp;</div>
        <div class="raised_greenGradient"> 
            <b class="b1"></b><b class="b2"></b><b class="b3" style="background-color:#F5FDE6"></b><b class="b4" style="background-color:#F5FDE6"></b>
            <div class="boxcontent_greenGradient1">
                <div class="mar_left_10p">
                    <div class="lineSpace_10">&nbsp;</div>
		<ul style="margin:0; padding:0;list-style-type:none">
		<?php if(array_key_exists(25,$sumsUserInfo['sumsuseracl']))
                {?>
                    <li class="<?php  if($mis_reprot_type === 'payment') echo 'sumsleftPanelPreSelected'; else 'sumsleftPanelPreUnSelected'; ?>"><a href="/sums/MIS/collection/<?php echo $prodId;?>/1" class="fontSize_12p">Payment MIS</a></li>
                    <div class="lineSpace_10">&nbsp;</div>
		<?php } ?>
		<?php if(array_key_exists(26,$sumsUserInfo['sumsuseracl']))
                {?>
                    <li class="<?php if($mis_reprot_type === 'transaction') echo 'sumsleftPanelPreSelected'; else 'sumsleftPanelPreUnSelected'; ?>"><a href="/sums/MIS/collection/<?php echo $prodId;?>/2" class="fontSize_12p">Transaction MIS</a></li>
                    <div class="lineSpace_10">&nbsp;</div>
		<?php }?>
		<?php if(array_key_exists(27,$sumsUserInfo['sumsuseracl']) || array_key_exists(28,$sumsUserInfo['sumsuseracl']) ||array_key_exists(29,$sumsUserInfo['sumsuseracl'])||array_key_exists(30,$sumsUserInfo['sumsuseracl']))
                {?>
                    <li class="<?php if($mis_reprot_type === 'inventory') echo 'sumsleftPanelPreSelected'; else 'sumsleftPanelPreUnSelected'; ?>"><a href="/sums/MIS/collection/<?php echo $prodId;?>/3" class="fontSize_12p">Inventory MIS</a></li>
                    <div class="lineSpace_10">&nbsp;</div>
		<?php }?>
		</ul>
                </div>
            </div>
            <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
        </div>
