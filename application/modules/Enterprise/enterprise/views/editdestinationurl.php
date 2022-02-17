<title>change destination URL of the page pageID=<?php echo $page_id;?></title>
<style>
.rowWDFcms {
  width: 988px!important;
}
.main {
  _margin-left: 150px!important;
}
.homeShik_footerBg {
   _position: relative;
   _left: 150px!important;
   _width: 1013px!important;
}
</style>
<div class="main">
<?php 
$headerComponents = array(
        'css'   =>  array('headerCms','raised_all','mainStyle','footer','cal_style'),
        'js'    =>  array('common','enterprise','home','CalendarPopup','discussion','events','listing','blog'),
        'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
        'tabName'   =>  '',
        'taburl' => site_url('enterprise/Enterprise'),
        'metaKeywords'  =>''
        );
$this->load->view('enterprise/headerCMS', $headerComponents);
$this->load->view('enterprise/cmsTabs',$headerTabs);
?>
<div style="width:500px;">
<div class="orangeColor fontSize_14p bld" style="margin-left:10px;">
<span><b>Edit Redirection URL</b></span><br/><br/>
<span style="color:black;">Page URL: <?php echo 'http://'.$_SERVER['HTTP_HOST'].$page_url;?></span>
<div class="grayLine_1" style="margin-top:5px;">&nbsp;</div>
</div>
<div class="pf10">
<div class="mb10" style="width:500px;"><span>Enter URL of the page if you want to redirect a user to a particular page. Leave blank for default redirection.</span> <br/>
</div> 
<div class="mb10"><br>
<form action="/enterprise/MultipleMarketingPage/saveDestinationURL"  method="post" onsubmit="return validateURL()">
   <input type="hidden" name="page_id" value="<?php echo $page_id;?>"/>
   <div>
   <div style="width: 175px; line-height: 18px;" class="float_L">
        <div style="padding-right: 5px;">Enter new URL:</div>
    </div>
    <div>
        <input onmouseout="document.getElementById('hintbox1').setAttribute('style','display:none');" onmouseover="document.getElementById('hintbox1').setAttribute('style','display:block;padding: 10px; width: 120px;background-color: rgb(242, 228, 171);');" type="text" value="<?php echo $destination_url; ?>" class="txt_1" name="destination_url" style="width:300px;" id="destination_url" size="30" />
    </div>
     <div style="position:relative;left:178px;">
            <div id="destination_url_error" class="errorMsg"></div>
        </div>
    <div class="clear_L withClear">&nbsp;</div>
</div>
 <div class="clear_L withClear">&nbsp;</div>
  <div class="clear_L withClear">&nbsp;</div>
<button type="submit" value=""class="btn-submit5 w12">
<div class="btn-submit5">
<p class="btn-submit6">Save</p>
</div>
</button>
<button type="button"  onclick="location.replace('/enterprise/MultipleMarketingPage/marketingPageDetails')" value=""class="btn-submit5 w12">
<div class="btn-submit5">
<p class="btn-submit6">Cancel</p>
</div>
</button>
</form>
</div>
</div>
</div>
<div class="clear_L withClear">&nbsp;</div>
<div class="clear_L withClear">&nbsp;</div>
</div>
<div id="helpbubble1" style="position: absolute; left: 700px; top: 260px; z-index: 10000;">
<div class="clear_L">&nbsp;</div>
<div style="display: none;" id="hintbox1" class="float_R normaltxt_11p_blk_verdana">
Example of valid URLS <br/> (http://localshiksha.com,<br/>http://shiksha.com) etc..
</div>
<div class="clear_L">&nbsp;</div>
</div>
<?php $this->load->view('common/footer');?>
<script type="text/javascript">
function validateURL() {
	var value = document.getElementById('destination_url').value;
	if(value) {
	var regx = new RegExp();
	regx.compile("^[A-Za-z]+://[A-Za-z0-9-]+\.[A-Za-z0-9]+"); 
	var result = regx.test(value);
	if(result==false) {
		document.getElementById('destination_url_error').innerHTML = "Please enter a valid URL";
		return false;
	}
	}
	return true;
}
</script>
