<?php
    $mouseover = "this.style.display=''; overlayHackLayerForIE('countrySelectionOverlay', document.getElementById('countrySelectionOverlay'));" ;
    $mouseout = "dissolveOverlayHackForIE();this.style.display='none'";
?>
<div id = 'countrySelectionOverlay' style = 'display:none;z-index:1000000;position:absolute;width:400px;left:250px;top:155px;'>
    <div style="width:100%;background:#FFF">
        <div style="border:1px solid #c3c5c4">
            <!--Start_OverlayTitle-->
            <div style="background:#6391cc;height:35px;width:100%">
				<div class="mlr10">
					<div class="lineSpace_7">&nbsp;</div>
					<div style="width:100%">
						<span class="float_L Fnt16 whiteColor"><b>Please select desired country in this region</b></span>
						<span class="float_R" style="margin-top:3px" onClick="document.getElementById('countrySelectionOverlay').style.display = 'none';dissolveOverlayHackForIE();"><span class="shikIcnClse" style="float:left">&nbsp;</span></span>
						<div class="clear_B">&nbsp;</div>
					</div>					
				</div>
            </div>
            <div class="lineSpace_10">&nbsp;</div>
            <!--End_OverlayTitle-->
            <div class="mlr10">
            <ul class="faqSA_ul">
            <?php
            global $countriesForStudyAbroad;
foreach($countriesForStudyAbroad as $key => $value)
{
    if($key == $countrySelected)
    {
        $strg = explode(',',$value['countryName']);
        $strg1 = explode(',',$value['id']);
        for($i = 0;$i < count($strg); $i++)
        {
        ?>
            <li class="float_L" style="width:110px"><a href= '#' onClick = "seturlval(this,<?php echo $strg1[$i];?>)"><?php echo $strg[$i]?></a></li>
            
        <?php
        }
    }
}
?>
</ul>
            <div class="clear_B">&nbsp;</div>
            </div>
        </div>
    </div>
</div>

<script>
function seturlval(obj,countryid)
{
    obj.href = urltoload + countryid;
}
</script>
