<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>LIBA Mailer</title>
</head>
<body>
<table border="0" cellpadding="0" cellspacing="0" style="width:600px; margin:0 auto; font:normal 12px Arial, Helvetica, sans-serif; padding-bottom:20px; border:1px solid #000; padding:10px;">
    <tr>
        <td>
                <table border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td width="40%"><a href="#"><img src="http://shiksha.com/public/images/onlineforms/institutes/liba/logo2New.gif" alt="institue logo" style="border:0 none;"/></a></td>
                <td width="60%" align="right">LIBA (Loyola Institute of Business Administration)</td>
            </tr>
</table>
<table border="0" cellpadding="6" cellspacing="0" width="100%" style="margin-top:20px;">
                <tr>
                        <td width="30%">LIBA ID :</td>
                    	<td width="70%"><?php echo ($form_details[0]['instituteSpecId']>0)?'LIBA'.$form_details[0]['instituteSpecId']:'';?></td>
                </tr>
                <tr>
                    <td>Test Type :</td>
                    <td><?php if($ResultOfDetails['xatIdLIBA'] && $ResultOfDetails['xatIdLIBA']!='NA') echo "XAT ID"; if($ResultOfDetails['xatIdLIBA'] && $ResultOfDetails['catRollNumberAdditional'] && $ResultOfDetails['xatIdLIBA']!='NA' && $ResultOfDetails['catRollNumberAdditional']!='NA') echo " , "; if($ResultOfDetails['catRollNumberAdditional'] && $ResultOfDetails['catRollNumberAdditional']!='NA') echo "CAT ID"; if(!($ResultOfDetails['xatIdLIBA'] && $ResultOfDetails['catRollNumberAdditional'])) echo "XAT ID , CAT ID"; ?></td>
                </tr>
                <tr>
                    <td>Test ID :</td>
                    <td><?php if($ResultOfDetails['xatIdLIBA'] && $ResultOfDetails['xatIdLIBA']!='NA') echo "XAT ID: ".$ResultOfDetails['xatIdLIBA']; if($ResultOfDetails['xatIdLIBA'] && $ResultOfDetails['catRollNumberAdditional'] && $ResultOfDetails['xatIdLIBA']!='NA' && $ResultOfDetails['catRollNumberAdditional']!='NA') echo " , "; if($ResultOfDetails['catRollNumberAdditional'] && $ResultOfDetails['catRollNumberAdditional']!='NA') echo "CAT ID: ".$ResultOfDetails['catRollNumberAdditional']; ?></td>
                </tr>
 		<tr>
                    <td>Name :</td>
                    <td><?php echo $ResultOfDetails['firstName']." ".$ResultOfDetails['middleName']." ".$ResultOfDetails['lastName'];?></td>
                </tr>
                <tr>
                    <td>Mailing Address :</td>
                    <td><?php $ResultOfDetails['streetName'] = ($ResultOfDetails['streetName']!='')?$ResultOfDetails['streetName'].' ':'';
                                    $ResultOfDetails['area'] = ($ResultOfDetails['area']!='')?$ResultOfDetails['area'].' ':'';
                          echo $permAddress = $ResultOfDetails['houseNumber'].' '.$ResultOfDetails['area'].$ResultOfDetails['streetName'].$ResultOfDetails['city'].' '.$ResultOfDetails['state'].' '.$ResultOfDetails['pincode'];?></td>
                </tr>
                <tr>
                    <td>Mobile no :</td>
                    <td><?php echo $ResultOfDetails['mobileISDCode'].'-'.$ResultOfDetails['mobileNumber'];?></td>
                </tr>
 		<tr>
                    <td>Phone  no :</td>
	            <td><?php echo $ResultOfDetails['landlineISDCode'].'-';
                          echo (isset($ResultOfDetails['landlineSTDCode']) && $ResultOfDetails['landlineSTDCode']!='')?$ResultOfDetails['landlineSTDCode'].'-':'';
                          echo $ResultOfDetails['landlineNumber']; ?></td>
                </tr>
                <tr>
                    <td>Email :</td>
                    <td><?php echo $ResultOfDetails['email']?></td>
                </tr>
                <tr>
                <td> Alternate email :</td>
                <td><?php echo $ResultOfDetails['altEmail'];?></td>
                </tr>
		<tr>
                    <td>Payment Mode :</td>
                    <?php if($payment_mode == 'Offline'){ ?>
                        <td>Draft </td>
                    <?php }else{ ?>
                        <td>Online (Paid at Shiksha.com)</td>
                    <?php } ?>
                </tr>
                <tr>
                        <td>Paid Date:</td>
                        <td><?php echo date('d/m/Y',strtotime($transaction_date));?></td>
                </tr>
		<tr>
                        <td>Transaction No :</td>
                        <td></strong><?php echo $transaction_id;?></td>
                </tr>
                    </table>
            <table border="0" cellpadding="6" cellspacing="0" width="100%">
                        <tr>
                        <td>
                    <p style="line-height:22px; margin:0"><strong><u>Important Note:</u></strong>You should send this payment advice form immediately to LIBA along with fee payment instrument
                    In case of Demand Draft mention your name and XAT ID/CAT registration no. and LIBA ID on the reverse of the DD.
                    Send your DD with this payment advice form to</p>
                    </td>
                </tr>
                <tr>
                       <td>
			 <p style="line-height:22px; margin:0">
                    The Admission office<br />
                    LIBA, Loyola College<br />
                    Nungambakkam<br />
                    Chennai-600034
                       </td>
                </tr>
                <tr>
                        <td>
                    <p style="line-height:22px; margin:0"><strong><u>Your application for admission will be accepted only on receipt of application fee by LIBA.</u></strong> A registration confirmation note has already been generated and sent to your email ID. On receipt of your application fee/DD and payment advice form, a message confirming the acceptance of your application will be sent to you by email subsequently, the LIBA bulletin 2014 will be sent to your current address, by post (except to those who  have paid at IMS centres)</p>
                   </td>
                </tr>
		<tr>
                        <td><p style="line-height:22px; margin:0">The last date for receiving the application fee along with payment advice form at LIBA is 1st February 2014</p></td>
                </tr>
		<tr>
                        <td><p style="line-height:22px; margin:0"><strong>For any doubt or query, please Call us at 011-4046-9621 (between 09:00 AM to 06:00 PM, Monday through Friday) or write a mail to us at Help@shiksha.com</strong></p></td>
                </tr>
                <tr>
                <td><a href="<?php echo SHIKSHA_HOME;?>"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/mailer_logo_2015.png" width="239" height="60" alt="shiksha logo" style="border:0 none;"/></a></td>
                </tr>
                        </table>
        </td>
    </tr>
</table>
</body>
</html>


