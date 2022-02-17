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
<div class="box-shadow" style="width:100%;position:relative;padding:0px;">
<div style="z-index:9999">
	<div  style="width: 100%;">
		<div>
			<div class="wBook">
				<div class="lineSpace_5">&nbsp;</div>
				<div style="padding:0 75px 0 10px">
					<span class="Fnt18 fcOrg">Let's <b>connect you</b> to the <b>right college</b></span>
					<div class="lineSpace_15">&nbsp;</div>
					<span class="Fnt16">Select your area of Interest and get a step closer</span>
					<div class="lineSpace_22">&nbsp;</div>            
				</div>
				<div style="padding-left:10px">
				<b>Area:</b>
				<div class="spacer3 clearFix"></div>
				<div>
				<select class="universal-select" style="width:230px" id="gotoMrktg"  onchange="onChangeBlk()">
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
				<div class="spacer8 clearFix"></div>
				<input type="button" class="orange-button" value="GO" onClick="chkBlnk()"/>
                <div class="spacer8 clearFix"></div>
				</div>            
			</div>
		</div>
	</div>    
</div>
<div id="showFlashAni"></div>
</div>
<div class="lineSpace_20">&nbsp;</div>
