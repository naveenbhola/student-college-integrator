<title>Create New Page </title>
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
<div style="font-size:12px;">
<div class="orangeColor fontSize_14p bld"  style="margin-left:10px;font-size:13px;width:500px;"><b>Give Page Details</b>
<div class="grayLine_1" style="margin-top:5px;">&nbsp;</div>
</div>
<div class="pf10">
<div class="mb10"  style="">
<form action="/enterprise/MultipleMarketingPage/savemarketingPageName"  method="post" onsubmit="return validateName();">
   <input type="hidden" name="page_id" value="<?php echo $page_id;?>"/>
   <div>
    <div style="width: 175px; line-height: 18px;" class="float_L">
        <div style="padding-right: 5px;">Enter new Page name<b class="redcolor">*</b> : </div>
    </div>
    <div style="margin-left: 177px;">
        <div>
        <input type="text" value="" class="txt_1" name="page_name" style="width: 320px;" id="page_name" size="30"  maxlength="100"/>
        </div>
        <div>
            <div id="page_name_error" class="errorMsg"></div>
        </div>
    </div>
    <div class="clear_L withClear">&nbsp;</div>
</div>
  <div>
    <div style="width: 175px; line-height: 18px;" class="float_L">
        <div style="padding-right: 5px;">Display on Page<b class="redcolor">*</b> : </div>
    </div>
    <div style="margin-left: 177px;">
        <div>
			<select name="display_on_page" id="display_on_page">
			  <option value="">Select</option>
<!-- 			  <option value="normalmmp">Normal MMP</option>
			  <option value="article">Article</option>
			  <option value="listing">Listing</option> -->
			  <option value="newmmp">Overlay</option>
			  <option value="newmmparticle">New MMP Article</option>
			  <option value="newmmpcourse">New MMP Course</option>
			  <option value="newmmpinstitute">New MMP Institute</option>
			  <option value="newmmpcategory">New MMP Category</option>
			  <option value="newmmpexam">New MMP Exam</option>
			  <option value="newmmpranking">New MMP Ranking</option>
			</select>			
        </div>
		<div> (Please select only one sub-category in case of other than Normal/New MMP Page)</div> 	
        <div>
            <div id="display_on_page_error" class="errorMsg"></div>
        </div>
    </div>
    <div class="clear_L withClear">&nbsp;</div>
  </div>
<div class="clear_L withClear" style="padding-bottom:10px;">&nbsp;</div>
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
</div>
<?php $this->load->view('common/footer');?>
<script type="text/javascript">
function validateName() {
	document.getElementById('page_name_error').innerHTML = '';
	document.getElementById('display_on_page_error').innerHTML = '';
    var flag1 = true;
	var value = document.getElementById('page_name').value;
	if(!trim(value) || trim(value) == '0') {
		document.getElementById('page_name_error').innerHTML = "Please enter the name for this marketing page";
        flag1 = false;
	} else if(value && value.length>100) {
		document.getElementById('page_name_error').innerHTML = "Please enter less than 100 characters";
        flag1 = false;
	}
	var display_on_page = document.getElementById('display_on_page').value;
	if(display_on_page == '') {
		document.getElementById('display_on_page_error').innerHTML = "Please select the display location for this marketing page";
        flag1 = false;
	}
	if (flag1 == false) {
	  return false;
	}
    var flag2 = validateMarketingPageName();
    return (flag2 && flag1);
}

function validateMarketingPageName()
{
    var name = document.getElementById('page_name').value;
    var url = '/enterprise/MultipleMarketingPage/checkPageNameAjax/'+ name;
    var flag = true;
    $j.ajax({
                async: false,
                url: url,
                type: 'POST',
                success: function(res) {
                        if(trim(res)=='true'){
                            document.getElementById('page_name_error').innerHTML = "This name already exists in database";                       
                            flag = false; 
                        }
                }
    });
    return flag;


}

</script>
