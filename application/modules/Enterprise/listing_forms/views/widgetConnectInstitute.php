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
	'animation'    => array('name' => 'Animation', 'url'=>'http://www.shiksha.com/marketing/Marketing/form/pageID/917'),
	'management'   => array('name' => 'Management', 'url' => ' http://www.shiksha.com/marketing/Marketing/form/pageID/918'),
	'studyAbroad' => array('name' => 'Study Abroad', 'url' => 'http://www.shiksha.com/marketing/Marketing/form/pageID/915'),
	'engineering' => array('name' => 'Engineering', 'url' => 'http://www.shiksha.com/marketing/Marketing/form/pageID/919'),
	'it' => array('name' => 'IT', 'url' => 'http://www.shiksha.com/marketing/Marketing/form/pageID/878'),
	'distancelearning' => array('name' => 'Distance MBA', 'url' => 'http://www.shiksha.com/marketing/Marketing/form/pageID/920'),
	'media' => array('name' => 'Media', 'url' => 'http://www.shiksha.com/marketing/Marketing/form/pageID/921'),
	'hospitality' => array('name' => 'Hospitality', 'url' => 'http://www.shiksha.com/marketing/Marketing/form/pageID/922'),
	'banking' => array('name' => 'Banking and Finance', 'url' => 'http://www.shiksha.com/marketing/Marketing/form/pageID/923'),
	'bba' => array('name' => 'BBA', 'url' => 'http://www.shiksha.com/marketing/Marketing/form/pageID/924'),
	'testprep' => array('name' => 'Test Prep', 'url' => 'http://www.shiksha.com/marketing/Marketing/form/pageID/925')
	);
?>
<div style="width:265px;position:relative">
<div style="z-index:9999">
	<div><img src="/public/images/wArea_t.gif" /></div>
	<div class="wArea_brd">
		<div class="wArea_m">
			<div class="wBook">
				<div class="lineSpace_5">&nbsp;</div>
				<div style="padding:0 75px 0 10px">
					<span class="Fnt18 fcOrg">Let's <b>connect you</b> to the <b>right college</b></span>
					<div class="lineSpace_15">&nbsp;</div>
					<span>Select your area of Interest and get a step closer</span>
					<div class="lineSpace_28">&nbsp;</div>            
				</div>
				<div style="padding-left:10px">
				<b>Area:</b>
				<div class="lineSpace_2">&nbsp;</div>
				<div>
				<select style="width:230px" id="gotoMrktg"  onchange="onChangeBlk()">
				<option value="">Select</option>
				<?php foreach($marketingPagesArray as $temp): ?>
						<option value="<?php echo $temp[url]; ?>"><?php echo $temp['name']; ?></option>
				<?php endforeach; ?>
				<!-- <option value="http://shiksha.com/marketing/Marketing/index/animation/animation/<?php echo base64_encode($_SERVER[SCRIPT_URI]); ?>">Animation</option>
				<option value="http://shiksha.com/marketing/Marketing/index/management/management/<?php echo base64_encode($_SERVER[SCRIPT_URI]); ?>">Management</option>
				<option value="http://shiksha.com/marketing/Marketing/index/studyAbroad/studyAbroad/<?php echo base64_encode($_SERVER[SCRIPT_URI]); ?>">Study Abroad</option>
				<option value="http://shiksha.com/marketing/Marketing/index/science/science/<?php echo base64_encode($_SERVER[SCRIPT_URI]); ?>">Engineering</option>
				<option value="http://shiksha.com/marketing/Marketing/index/it/it/<?php echo base64_encode($_SERVER[SCRIPT_URI]); ?>">IT</option>
				<option value="http://shiksha.com/marketing/Marketing/index/distancelearning/management/<?php echo base64_encode($_SERVER[SCRIPT_URI]); ?>">Distance MBA</option> -->
				</select>
				<div id="error_gotoMrktg" style="font-size:11px;color:#ff0000;display:none">Please select the area</div>
				</div>
				<div class="lineSpace_5">&nbsp;</div>
				<input type="button" class="btn_Go" value="&nbsp;" onClick="chkBlnk()"/>
				</div>            
			</div>
		</div>
	</div>    
	<div><img src="/public/images/wArea_b.gif" /></div>
</div>
<div id="showFlashAni"></div>
</div>
<div class="lineSpace_20">&nbsp;</div>
