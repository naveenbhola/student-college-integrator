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

<?php $this->load->view('enterprise/cmsTabs',$cmsPageArr); ?>


<body style="margin:0 10px">
<div class="lineSpace_25">&nbsp; </div>

<h2>
<span class="OrgangeFont" style="font-size:14px;">For USD Payment transaction By American Express/Visa Card , click on pay now
</span>
</h2>
<!-- <form method="post" action="https://world.ccavenue.com/servlet/ccw.CCAvenueController" id="ccAvenuePaymentForm">  -->
<form method="post" action="https://secure.ccavenue.com/transaction/transaction.do?command=initiateTransaction" id="ccAvenuePaymentForm">

	<!-- <input type=hidden name="Merchant_Id" value="< ?php echo $Merchant_Id; ?>">
	<input type=hidden name="Access_Code" value="< ?php echo $Access_Code; ?>">
	<input type=hidden name="Amount" value="< ?php echo $Amount; ?>">
	<input type=hidden name="Order_Id" value="< ?php echo $Order_Id; ?>">
	<input type=hidden name="Currency" value="USD">
	<input type='hidden' name="TxnType" value="< ?php echo $TxnType; ?>" />
	<input type='hidden' name="actionID" value="< ?php echo $actionID; ?>" />
	<input type=hidden name="Redirect_Url" value="< ?php echo $Redirect_Url; ?>" >
	<input type=hidden name="Checksum" value="< ?php echo $checksum; ?>"> -->
	<input type=hidden name="encRequest" value=<?php echo $encrypted_data; ?> >
	<input type=hidden name="access_code" value=<?php echo $Access_Code; ?> >
<div class="lineSpace_25">&nbsp; </div>
<!--INPUT TYPE="submit" value="Pay Now"-->
<button class="btn-submit7 w9" id="goBkEnterp" value="Go_Back_Enterprise" type="button" onclick="$('ccAvenuePaymentForm').submit();" style="width: 155px;">
<div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Pay Now</p></div>
</button>    
<button style="width: 155px;" onclick="window.location='/enterprise/Enterprise/payment'" type="button" value="Go_Back_Enterprise" id="goBkEnterp" class="btn-submit7 w9">
<div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Go Back</p></div>
</button>
</form>
<div class="lineSpace_20" style="width:100%">&nbsp;</div>
<div class="lineSpace_20" style="width:100%">&nbsp;</div>
<div class="lineSpace_20" style="width:100%">&nbsp;</div>
<div class="lineSpace_20" style="width:100%">&nbsp;</div>
<div class="lineSpace_20" style="width:100%">&nbsp;</div>
<div class="lineSpace_20" style="width:100%">&nbsp;</div>
<div class="lineSpace_20" style="width:100%">&nbsp;</div>
<?php $this->load->view('enterprise/footer');  ?>

</body>
</html>
