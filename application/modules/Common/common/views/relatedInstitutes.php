<?php 
/*echo "<pre>";
print_r($validateuser);
echo "</pre>";*/
if(count($resultArr) > 0) {
?>
<!-- Start_Related Institutes -->
<div class="raised_greenGradient_ww">
    <b class="b1"></b><b class="b2" style="background:#DCF5A9"></b><b class="b3"></b><b class="b4"></b>
    <div class="boxcontent_greenGradient_ww" id="">	
    <div class="lineSpace_5">&nbsp;</div>
    <div class="bld mar_left_5p" style="font-size:14px">You might be interested in these Institutes.</div>
    <div class="lineSpace_10">&nbsp;</div>

    <?php 
    for($i = 0; $i < count($resultArr); $i++) {
        $thisResult = (array)$resultArr[$i];
        $collegeName = $thisResult['title'];
        $urlName = $thisResult['url'];
        $location = $thisResult['location'];
        if($thisResult['isSponsored'] == '1'){
            if(!isset($validateuser) || !is_array($validateuser))
            {
                $requestInfoUrl = "javascript:showuserLoginOverLay('myShiksha',2);";
            }
            else
            {
                if($validateuser[0]['quicksignuser'] == 1 && $validateuser[0]['requestinfouser'] == 1)
                {
                    $base64url = base64_encode($_SERVER['REQUEST_URI']);
                    $quickClickAction = "javascript:location.replace('/user/Userregistration/index/".$base64url."/1');return false;";
                    $requestInfoUrl = $quickClickAction;
                }
                else
                {
                    $typeIdArr = getListingTypeAndId($urlName);
                    $requestInfoUrl = "javascript:setRequestInfoForSearchParams('".$typeIdArr['type']."','".$typeIdArr['typeId']."','".$collegeName."','".$urlName."','".mencrypt("")."');";
                }
            }
            
            $requestInfo= '<div class="fontSize_10p"><a href="javascript:void(0);" onclick ="'.$requestInfoUrl.'" >Send Query to this Institute<a/></div>&nbsp;';
        }
    ?>

        <div class="quesAnsBullets fontSize_12p mar_left_5p OrgangeFont">
            <a href="<?php echo $urlName; ?>" class="fontSize_12p orangeFont"><?php echo $collegeName; ?></a>
        </div>
        <div class="fontSize_12p mar_left_20p">
            <span class="blackFont"><?php echo $location ?></span>
            <?php echo $requestInfo; ?>
        </div>
        <div class="lineSpace_10">&nbsp;</div>

    <?php
    }
    ?>

    </div>				
    <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
</div>			
<div class="lineSpace_10">&nbsp;</div>
<!-- End_Related Institutes -->
<?php 
}
?>
