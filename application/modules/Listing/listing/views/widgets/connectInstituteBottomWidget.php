<?php if($details['packType']!='1' && $details['packType']!='2'){?>
<div style="width:100%">
    <div class="cMainWrapper">
        <div class="cChildWrapper">
            <span class="cTopRtCrnr"><span class="cLftCrnr"></span></span>
            <div class="cContentWrapper2">
				<div class="lineSpace_10">&nbsp;</div>
            	<div class="wBook" style="height:150px">
	                <div style="padding:0 75px 0 10px">
                    	<span class="Fnt24 fcOrg">Let's <b>connect you</b> to the <b>right institute</b></span>
                        <div class="lineSpace_15">&nbsp;</div>
		        	    <span class="Fnt16"><b>Select your area of interest and get a step closer</b></span>
                        <div class="clear_B lineSpace_15">&nbsp;</div>
                        <div>
							<ul class="lcri">
                            	<li class="ani"><a href="http://www.shiksha.com/public/mmp/218/index.html">Animation</a></li>
                                <li class="mang"><a href="http://www.shiksha.com/marketing/Marketing/index/pageID/230">Management</a></li>
                                <li class="sa"><a href="http://www.shiksha.com/marketing/Marketing/index/pageID/232">Study Abroad</a></li>
                                <li class="eng"><a href="http://www.shiksha.com/marketing/Marketing/index/pageID/227">Engineering</a></li>
                                <li class="it"><a href="http://www.shiksha.com/marketing/Marketing/index/pageID/228">IT</a></li>
                                <li class="dm"><a href="http://www.shiksha.com/marketing/Marketing/index/pageID/62">Distance MBA</a></li>
                            </ul>
                        </div>
                        <div class="clear_B lineSpace_1">&nbsp;</div>
                    </div>
				</div>
            </div>
            <span class="cBotRtCrnr"><span class="cLftCrnr"></span></span>
        </div>
    </div>
</div>
<div class="lineSpace_10">&nbsp;</div>
<?php $details['lastModifyDate'] = explode("T",$details['lastModifyDate']);
$date1 = explode("-",$details['lastModifyDate']['0']);
$date2['0'] = $date1['1'];
$date2['1'] = $date1['2'];
$date2['2'] = $date1['0'];
$details['lastModifyDate']['0'] = implode("-",$date2);
?>

<div class="Fnt11 mt5" style="color:#5b5b5b">** This information has been collected from <?php if(!empty($details['url'])){echo $details['url'];}else{ echo "institute's website and
brochures" ;}?>. Trade Marks belong to the respective owners.<br />The listing was last modified on <?php echo $details['lastModifyDate']['0'];?></div>
<?php }?>
