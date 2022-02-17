<?php
$totalCmp[] = '';
$cookieCmpName = 'mob-compare-global-data';
if($_COOKIE[$cookieCmpName]){$totalCmp = explode('|',$_COOKIE[$cookieCmpName]);$totalCmp = count($totalCmp);}else{$totalCmp = 0;}

if($noJqueryMobile)
{
    $ananoitifyHref     = ' href="javascript:void(0);" data-page-id="AnANotifications" ';
}else{
    $ananoitifyHref     = ' href="#AnANotifications" ';
}
?>
<div id="myrightpanel" data-position="right" data-display="overlay" data-role="panel" data-position-fixed="true" style="overflow: auto;z-index:99999;">
	<section class="layer-shade clearfix" data-enhance="false" id="main-rightpanel">
        <div class="clearfix">
            <ul class="list-items added-items"> 
                <?php if($signedInUser !='false'){ ?>
                    <li><a href="javascript:void(0);" data-param="profile" id="my-profile">My Profile</a></li>
                    <?php
                    if(!in_array($pageName,array('helpline', 'aboutus','communityGuideline','userPointSystem'))) { ?>
                    <li><a <?=$ananoitifyHref;?> id="ananoitify" data-inline="true" data-rel="dialog" data-transition="fade">Notifications (<span id='anaNotificationsCount'><?=$anaInAppNotificationCountForMobileSite;?></span>)</a></li>
                    <?php } ?>
                <?php } else { ?>
                <li><a href="javascript:void(0);" data-param="login" id="my-profile">My Profile</a></li>
                <?php } ?>


    		    <li><a href="<?php echo SHIKSHA_HOME.'/resources/colleges-shortlisting';?>" id="total-shortlisted-colleges">My Shortlist (0)</a></li>
                <li style="display: none;" id="predictorList"><a href="">Predictor List</a></li>
    		    <li><a href="javascript:void(0);" style="cursor:default;" id="total-college-compare">Compare Colleges (<?php echo $totalCmp;?>)</a></li>
            </ul>
		</div>
		<div id="_createCmp" class="compare-colg-slist">
            <ul id="_comparedList">
                <?php if($signedInUser !='false'){?>  
                    <li><a href="javascript:void(0);" id="_cmpBtn" data-cmp="comparePage"><input type="button" value="Compare" class="compare-btn"/></a></li>
                <?php }else{?>
                    <li><a href="javascript:void(0);" id="_cmpBtn" data-cmp="login" onclick=""><input type="button" value="Compare" class="compare-btn"/></a></li>
                <?php }?>
            </ul>      
        </div>	
    </section>
</div>
<?php global $isHamburgerMenu;
$isHamburgerMenu = true; ?>
<script type="text/javascript">
var loginUrl = '<?php echo $loginUrl;?>';
</script>
