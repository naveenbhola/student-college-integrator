	<div class="lineSpace_10">&nbsp;</div>
        <div class="raised_greenGradient"> 
            <b class="b1"></b><b class="b2"></b><b class="b3" style="background-color:#F5FDE6"></b><b class="b4" style="background-color:#F5FDE6"></b>
            <div class="boxcontent_greenGradient1">				  	
                <div class="mar_left_10p">
                    <div class="lineSpace_10">&nbsp;</div>
		    <ul style="margin:0; padding:0;list-style-type:none">	
                    <?php if(array_key_exists(1,$sumsUserInfo['sumsuseracl']))
                    {?>
                    <li class="<?php if(stripos($_SERVER['REQUEST_URI'],'profile')) echo 'sumsleftPanelPreSelected'; else 'sumsleftPanelPreUnSelected'; ?>"><a href="#" class="lineSpace_22"><a href="/sums/Manage/profile/<?php echo $prodId; ?>" class="fontSize_12p">Add Client Profile</a></li>
                    <div class="lineSpace_10">&nbsp;</div>
		    <?php } ?>
	            <?php if(array_key_exists(2,$sumsUserInfo['sumsuseracl']))
                    {?>
		    <li class="<?php if(stripos($_SERVER['REQUEST_URI'],'searchUser')) echo 'sumsleftPanelPreSelected'; else 'sumsleftPanelPreUnSelected'; ?>"><a href="/sums/Manage/searchUser/<?php echo $prodId; ?>" class="fontSize_12p">Create New Quotation</a></li>
                    <div class="lineSpace_10">&nbsp;</div>
		    <?php } ?>
		    <?php if(array_key_exists(3,$sumsUserInfo['sumsuseracl']))
                    {?>
                    <li class="<?php if(stripos($_SERVER['REQUEST_URI'],'searchQuotation')) echo 'sumsleftPanelPreSelected'; else 'sumsleftPanelPreUnSelected'; ?>"><a href="/sums/Quotation/searchQuotation/<?php echo $prodId; ?>" class="fontSize_12p">Edit Quotation</a></li>
                    <div class="lineSpace_10">&nbsp;</div>
		    <?php } ?>
                    <!--li class="<?php if(stripos($_SERVER['REQUEST_URI'],'searchUserForListingPost')) echo 'sumsleftPanelPreSelected'; else 'sumsleftPanelPreUnSelected'; ?>"><a href="/sums/Manage/searchUserForListingPost/<?php echo $prodId; ?>" class="fontSize_12p">Post a Listing for a Client</li>
                    <div class="lineSpace_10">&nbsp;</div-->
		    </ul>	
                </div>
            </div>
            <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
        </div>
