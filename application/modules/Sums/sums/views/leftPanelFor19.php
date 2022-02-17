        <div class="raised_greenGradient"> 
            <b class="b1"></b><b class="b2"></b><b class="b3" style="background-color:#F5FDE6"></b><b class="b4" style="background-color:#F5FDE6"></b>
            <div class="boxcontent_greenGradient1">				  		
                <div class="mar_left_10p">
                    <div class="lineSpace_10">&nbsp;</div>
		    <ul style="margin:0; padding:0;list-style-type:none">	
		    <?php if(array_key_exists(19,$sumsUserInfo['sumsuseracl'])) {?>
                    <li class="<?php if(stripos($_SERVER['REQUEST_URI'],'addUser')) echo 'sumsleftPanelPreSelected'; else 'sumsleftPanelPreUnSelected'; ?>"><a href="/sums/Manage/addUser/<?php echo $prodId; ?>" class="fontSize_12p">Add New User</a></li>
                    <div class="lineSpace_10">&nbsp;</div>
		    <?php }?>
		    <?php if(array_key_exists(20,$sumsUserInfo['sumsuseracl'])) {?>
                    <!--li class="<?php if(stripos($_SERVER['REQUEST_URI'],'#')) echo 'sumsleftPanelPreSelected'; else 'sumsleftPanelPreUnSelected'; ?>"><a href="#" class="fontSize_12p">Edit User Profile</a></li>
                    <div class="lineSpace_10">&nbsp;</div-->
		    <?php }?>
		    </ul>	
                </div>
            </div>
            <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
        </div>
