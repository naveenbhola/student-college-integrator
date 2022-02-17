<?php if($details['packType']!='1'&&$details['packType']!='2'){?>
<div class="wdh100">

<script>
function gotoMarketingPage(){
	val = document.getElementById('gotoMrktg').value;
	window.location.href=val;
}
function chkBlnk(){
       if(document.getElementById('gotoMrktg').value==''){
               document.getElementById('error_gotoMrktg').style.display = "block";
       } else {
               gotoMarketingPage();
       }
}
function onChangeBlk(){
       if(document.getElementById('gotoMrktg').value==''){
               document.getElementById('error_gotoMrktg').style.display = "block";
       } else {
               document.getElementById('error_gotoMrktg').style.display = "none";
       }
}
</script>
<?php
	$marketingPagesArray = array(
	'animation'    => array('name' => 'Animation', 'url'=>'http://www.shiksha.com/public/mmp/218/index.html'),
	'management'   => array('name' => 'Management', 'url' => 'http://www.shiksha.com/marketing/Marketing/form/pageID/230'),
	'studyAbroad' => array('name' => 'Study Abroad', 'url' => 'http://www.shiksha.com/marketing/Marketing/form/pageID/435'),
	'engineering' => array('name' => 'Engineering', 'url' => 'http://www.shiksha.com/marketing/Marketing/form/pageID/227'),
	'it' => array('name' => 'IT', 'url' => 'http://www.shiksha.com/marketing/Marketing/form/pageID/228'),
	'distancelearning' => array('name' => 'Distance MBA', 'url' => 'http://www.shiksha.com/marketing/Marketing/form/pageID/62'),
	'media' => array('name' => 'Media', 'url' => 'http://www.shiksha.com/marketing/Marketing/form/pageID/226'),
	'hospitality' => array('name' => 'Hospitality', 'url' => 'http://www.shiksha.com/marketing/Marketing/form/pageID/229'),
	'banking' => array('name' => 'Banking and Finance', 'url' => 'http://www.shiksha.com/marketing/Marketing/form/pageID/231'),
	'bba' => array('name' => 'BBA', 'url' => ' http://www.shiksha.com/marketing/Marketing/form/pageID/47'),
	'testprep' => array('name' => 'Test Prep', 'url' => 'http://www.shiksha.com/marketing/Marketing/form/pageID/225')
	);
?>
<div class="cMainWrapper">
                                <div class="cChildWrapper">
                                    <span class="cTopRtCrnr"><span class="cLftCrnr"></span></span>
                                    <div class="cContentWrapper2">
                                        <div class="wBook" style="height:180px">
                                            <div class="pl10">
                                                <div class="Fnt16 fcOrg" style="padding-right:90px">Let's <b>connect you</b> to the <b>right institute</b></div>
                                                <div class="lineSpace_15">&nbsp;</div>
                                                <div class="Fnt12" style="padding-right:90px">Select your area of interest and get a step closer</div>
                                                <div class="lineSpace_15">&nbsp;</div>
                                                <div class="mb3 bld">Area</div>
                                                <div class="mb7" >
				<select style="width:175px" id="gotoMrktg"  onchange="onChangeBlk()">
				<option value="">Select</option>
				<?php foreach($marketingPagesArray as $temp): ?>
						<option value="<?php echo $temp[url]; ?>"><?php echo $temp['name']; ?></option>
				<?php endforeach; ?>
				</select>
				<div id="error_gotoMrktg" style="font-size:11px;color:#ff0000;display:none">Please select the area</div>
				</div>                                                
                                                <div><input type="button" onclick="chkBlnk()" value="&nbsp;" class="btn_Go"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="showFlashAni"></div>
                                    <span class="cBotRtCrnr"><span class="cLftCrnr"></span></span>
                                </div>
                            </div>
    </div>
 <div class="lineSpace_20">&nbsp;</div>
 <?php }?>
