<?php 
if(!empty($TEXT_HEADING))
$TEXT_HEADING = $TEXT_HEADING;
else 
$TEXT_HEADING = $config_data_array['header_text'];
?>
<div style="width:950px;margin: 0 auto">
<div class="clearFix spacer10"></div>
<div>
	<div class="raised_skyWithBG"> 
		    <b class="b2" style="border-color:#a4d3f4;background:#A4D3F4"></b><b class="b3" style="border-color:#a4d3f4"></b><b class="b4" style="border-color:#a4d3f4"></b>
			    <div class="boxcontent_skyWithBG" style="background:#cbe9fd url(/public/images/bgMarketingPage.gif) repeat-x left bottom;border-left-color:#A4D3F4;border-right-color:#A4D3F4">
		    <div class="clearFix spacer5"></div>
		    <div style="height:70px;padding:0 10px">
		    <div class="float_R" style=""><img src="/public/images/mPageLogo.gif" border="0" /></div>
		    <div style="line-height:60px;<?php if(strlen($TEXT_HEADING)>50): echo 'font-size:20px;';else: echo 'font-size:24px;';endif;?>font-family:Trebuchet MS"><?php echo $TEXT_HEADING; ?></div>
		    <div class="clearFix"></div>
		</div>
	    </div>
		    <b class="b1b" style="margin:0;background:#cce8fd"></b>
	</div>
</div>
</div>
