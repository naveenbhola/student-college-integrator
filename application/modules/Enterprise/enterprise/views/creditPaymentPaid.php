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
<span class="OrgangeFont">Your Transaction For the Payment Gateway is successful with the payment Id as &lt;<?php echo $paymentId;?>&gt;</span>
</h2>
<?php
if(!empty($Transaction_Id) && isset($Transaction_Id)){
?>
<div> <br /> </div>
<h3>
<span class="OrgangeFont">Your SUMS Transaction Id is &lt;<?php echo $Transaction_Id;?>&gt;</span>
</h3>
<?php
}
?>
<div class="lineSpace_25">&nbsp; </div>
<div class="normaltxt_11p_blk bld">
        <button style="width: 155px;" onclick="window.location='/enterprise/Enterprise/payment'" type="button" value="Go_Back_Enterprise" id="goBkEnterp" class="btn-submit7 w9">
            <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Click To Continue</p></div>
        </button>
    </div>
<div class="lineSpace_20" style="width:100%">&nbsp;</div>
<?php $this->load->view('enterprise/footer');  ?>

</body>
</html>
