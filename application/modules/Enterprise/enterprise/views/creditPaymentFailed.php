<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/11009/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<?php if($usergroup == "cms"){ ?>
<title>CMS Control Page</title>
<?php } ?>
<?php if($usergroup == "enterprise"){ ?>
<title>Enterprise Control Page</title>
<?php } ?>

<?php
    $headerComponents = array(
        'css'	=>	array('headerCms','raised_all','mainStyle','footer','cal_style'),
        'js'	=>	array('common','enterprise','home','CalendarPopup','prototype','discussion','events','listing'),
        'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
        'tabName'	=>	'',
        'taburl' => site_url('enterprise/Enterprise'),
        'metaKeywords'	=>''
    );
    $this->load->view('enterprise/headerCMS', $headerComponents);
?>
</head>

<?php $this->load->view('enterprise/cmsTabs'); ?>



<body style="margin:0 10px">
<div class="lineSpace_25">&nbsp; </div>
<h2>
<span class="OrgangeFont">Your Transaction was declined by the Payment Gateway.</span>
</h2>
<div class="lineSpace_25">&nbsp; </div>
<div class="normaltxt_11p_blk bld">
If you want to try again 
        <button style="width: 115px;" onclick="window.location='/enterprise/Enterprise/payment'" type="button" value="Go_Back_Enterprise" id="goBkEnterp" class="btn-submit7 w9">
            <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Click Here</p></div>
        </button>
<button onclick="window.location='/smart/SmartMis/viewDashboard/'" type="button" value="" class="btn-submit5" style="width: 150;"><div class="btn-submit5"><p class="btn-submit6">Go to MyShiksha</p></div></button>
    </div>

<div class="lineSpace_20" style="width:100%">&nbsp;</div>
<?php $this->load->view('enterprise/footer');  ?>

</body>
</html>
