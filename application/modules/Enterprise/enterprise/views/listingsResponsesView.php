<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<script>
function hideDivElement(obj, divElement)
{
    if(divElement)
    {
        if(divElement.style.display != 'none')
        {
            divElement.style.display = 'none';
            obj.className = "cmsSearch_plusImg";
        }
        else
        {
            divElement.style.display = 'block';
            obj.className = "cmsSearch_minImg";
        }
    }
}
 function Numbers(e)
{
var tr_match = /(^\d+\.\d{1,2}$)|(^\d+$)/;
var chk = tr_match.test(e.value);
if(chk){
	return true;
	} else {
		alert("Please Enter only number");
		return false;
	}
} 
</script>
<title>Enterprise View Responses</title>
<?php 
$headerComponents = array(
        'css'   =>  array('headerCms','raised_all','mainStyle','footer','cal_style','modal-message'),
        'js'    =>  array('common','enterprise','home','CalendarPopup','discussion','events','listing','blog','ajax-api','ana_common'),//pragya
        'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
        'tabName'   =>  '',
        'taburl' => site_url('enterprise/Enterprise'),
        'metaKeywords'  =>''
        );
$this->load->view('enterprise/headerCMS', $headerComponents);
$this->load->view('enterprise/cmsTabs');
?>
<div style="width:100%">
	<div>
		<div style="margin:0 0px">
    	<div style="width:100%">
            <div id="studentresolutionSet_800">
            <script>
             if(document.body.offsetWidth<900){
                 document.getElementById('studentresolutionSet_800').style.width='994px';
                  }
                  </script>
<?php
$this->load->view('enterprise/listingResponsesListView');
$this->load->view('enterprise/searchResultViewContactDetails'); 
?>
</div>
</div>
</div>
<div style="margin-top:40px">
<?php
$this->load->view('enterprise/footer');
?>
