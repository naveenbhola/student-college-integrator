<?php 
if(count($resultArr) > 0) {
?>
<!-- Start_Related Institutes -->
<div class="raised_greenGradient_ww">
    <b class="b1"></b><b class="b2" style="background:#DCF5A9"></b><b class="b3"></b><b class="b4"></b>
    <div class="boxcontent_greenGradient_ww" id="">	
    <div class="lineSpace_5">&nbsp;</div>
    <div class="bld mar_left_5p" style="font-size:14px">You might be interested in these Scholarships.</div>
    <div class="lineSpace_10">&nbsp;</div>

    <?php 
    for($i = 0; $i < count($resultArr); $i++) {
        $thisResult = (array)$resultArr[$i];
        $title = $thisResult['title'];
        $urlName = $thisResult['url'];
    ?>

        <div class="quesAnsBullets fontSize_12p mar_left_5p OrgangeFont">
            <a href="<?php echo $urlName; ?>" class="fontSize_12p orangeFont"><?php echo $title; ?></a>
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

