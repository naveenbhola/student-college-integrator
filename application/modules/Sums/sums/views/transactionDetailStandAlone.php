<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Transaction Details for TransactionId: <?php echo $transactionId; ?></title>
</head>

<body style="margin:0px">
<table width="1000" border="0" align="center" cellpadding="0" cellspacing="0" style="font-family:Verdana;font-size:13px">
  <tr>
    <td background="images/border.gif"><img src="images/border.gif" width="11" height="6" /></td>
  </tr>
  <tr>
    <td><table width="1000" border="0" cellspacing="0" cellpadding="0">

      <tr>
        <td height="30" colspan="2" align="center" valign="middle" style="font-family:Verdana;font-size:16px;color:#FD8103"><b>Transaction Details</b></td>
        </tr>
      <tr>
          <td height="30" colspan="2" align="center" valign="middle" bgcolor="#f9f9f9"><strong>Transaction ID :<?php echo $transactionId; ?></strong></td>
        </tr>
      <tr>
        <td colspan="2" valign="top"><table width="100%" border="1" cellspacing="3" cellpadding="0" style="border-collapse:collapse" bordercolor="#CCCCCC">
          <tr>
            <td width="167" height="30" align="right" valign="top"><strong>Client Name:</strong></td>
            <td width="258" valign="top" style="padding-left:10px"><?php echo $clientDetails['displayName']; ?></td>
            <td width="154" align="right" valign="top" style="padding-left:10px"><strong>Address:</strong></td>
            <td width="361" valign="top" style="padding-left:10px"><?php echo isset($clientDetails['Address'])?$clientDetails['Address']:''; ?></td>
          </tr>
          <tr>
            <td height="30" align="right" valign="top"><strong>Sale Type:</strong></td>
            <td valign="top" style="padding-left:10px"><?php echo isset($paymentDetails[0]['Sale_Type'])?$paymentDetails[0]['Sale_Type']:''; ?></td>
            <td align="right" valign="top" style="padding-left:10px"><strong>City:</strong></td>
            <td valign="top" style="padding-left:10px"><?php echo isset($clientDetails['City'])?$clientDetails['City']:''; ?></td>
          </tr>
          <tr>
            <td height="30" align="right" valign="top"><strong>Transaction Status:</strong></td>
            <td valign="top" style="padding-left:10px"><?php echo isset($transactionDetails[0]['TransactionStatus'])?$transactionDetails[0]['TransactionStatus']:''; ?></td>
            <td align="right" valign="top" style="padding-left:10px"><strong>Country:</strong></td>
            <td valign="top" style="padding-left:10px"><?php echo isset($clientDetails['Country'])?$clientDetails['Country']:''; ?></td>
          </tr>
          <tr>
            <td height="30" align="right" valign="top"><strong>Comments:</strong></td>
            <td valign="top" style="padding-left:10px"><?php echo isset($transactionDetails[0]['CancelCommets'])?$transactionDetails[0]['CancelCommets']:''; ?></td>
            <td align="right" valign="top" style="padding-left:10px"><strong>Phone:</strong></td>
            <td valign="top" style="padding-left:10px"><?php echo isset($clientDetails['Phone_No'])?$clientDetails['Phone_No']:''; ?></td>
          </tr>
          <tr>
            <td height="30" align="right" valign="top">&nbsp;</td>
            <td valign="top" style="padding-left:10px">&nbsp;</td>
            <td align="right" valign="top" style="padding-left:10px"><strong>Email Id:</strong></td>
            <td valign="top" style="padding-left:10px"><?php echo isset($clientDetails['Email_Address'])?$clientDetails['Email_Address']:''; ?></td>
          </tr>
        </table></td>
        </tr>
      <tr>
        <td width="500" valign="top">&nbsp;</td>
        <td width="500" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" valign="top"><table width="1000" border="1" cellspacing="2" cellpadding="0" style="border-collapse:collapse" bordercolor="#CCCCCC">
          <tr>
            <td width="64" height="30" align="center" valign="middle" bgcolor="#f9f9f9"><strong>Sr. No.</strong> </td>
            <td width="388" align="center" valign="middle" bgcolor="#f9f9f9"><strong>Products</strong></td>
            <td width="130" align="center" valign="middle" bgcolor="#f9f9f9"><strong>Quantity</strong></td>
            <td width="112" align="center" valign="middle" bgcolor="#f9f9f9"><strong>Price</strong></td>
            <td width="123" align="center" valign="middle" bgcolor="#f9f9f9"><strong>Discount</strong></td>
            <td width="169" align="center" valign="middle" bgcolor="#f9f9f9"><strong>Sale price</strong></td>
          </tr>
        <?php
            $i=1; $sumDiscount=0;$sumSP=0;
            foreach($transactionDetails as $val):
            //echo "<pre>";print_r($val);echo "</pre>";
            $pricePerProduct = $val['Quantity']*$val['ManagementPrice'];
            $spPerProduct = $pricePerProduct - $val['Discount'];
            $sumDiscount += $val['Discount'];
            $sumSP += $spPerProduct;
        ?>
          <tr>
              <td height="30" align="center" valign="middle"><?php echo $i; ?></td>
              <td height="30" align="center" valign="middle"><?php echo $val['DerivedProductName']; ?></td>
              <td height="30" align="center" valign="middle"><?php echo $val['Quantity']; ?></td>
              <td height="30" align="center" valign="middle"><?php echo $pricePerProduct; ?></td>
              <td height="30" align="center" valign="middle"><?php echo $val['Discount']; ?></td>
              <td height="30" align="center" valign="middle"><?php echo $spPerProduct; ?></td>
        </tr>
        <?php 
            $i++; endforeach; 
            $FinalSalesAmount = $val['FinalSalesAmount'];
        ?>

          <tr>
            <td height="30" colspan="4" align="right" valign="middle"><strong>Total&nbsp;&nbsp;</strong></td>
            <td height="30" align="center" valign="middle"><?php echo $sumDiscount; ?></td>
            <td height="30" align="center" valign="middle"><?php echo $sumSP; ?></td>
          </tr>
        </table></td>
        </tr>
      <tr>
        <td valign="top">&nbsp;</td>
        <td valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td valign="top">&nbsp;</td>
        <td height="30" valign="top"><strong>Service Tax :</strong> <?php echo $val['ServiceTax']; ?></td>
      </tr>
      <tr>
        <td valign="top">&nbsp;</td>
        <td height="30" valign="top"> <strong>Total After Service Tax And Discount :</strong><?php echo $FinalSalesAmount; ?></td>
      </tr>
      <tr>
        <td height="30" valign="middle"><strong>Payment Id: </strong><?php echo $paymentDetails[0]['Payment_Id'] ?></td>
      </tr>
      <tr>
        <td valign="top">&nbsp;</td>
        <td valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" valign="top"><table width="1000" border="1" cellspacing="2" cellpadding="0" style="border-collapse:collapse" bordercolor="#CCCCCC">
            <td width="69" height="30" align="center" valign="middle" bgcolor="#f9f9f9"><strong>Part Number</strong> </td>
            <td width="91" align="center" valign="middle" bgcolor="#f9f9f9"><strong>Payment Amount </strong></td>
            <td width="80" align="center" valign="middle" bgcolor="#f9f9f9"><strong>TDS Amount </strong></td>
            <td width="87" align="center" valign="middle" bgcolor="#f9f9f9"><strong>Payment Mode </strong></td>
            <td width="140" align="center" valign="middle" bgcolor="#f9f9f9"><strong>Receipt Date</strong></td>
            <td width="120" align="center" valign="middle" bgcolor="#f9f9f9"><strong>Cheque/DD Date</strong> </td>
            <td width="137" align="center" valign="middle" bgcolor="#f9f9f9"><strong>Check Bank-Number</strong></td>
            <td width="102" align="center" valign="middle" bgcolor="#f9f9f9"><strong>Status </strong> </td>
            <td width="154" align="center" valign="middle" bgcolor="#f9f9f9"><strong>Cheque/DD Comments </strong></td>
        </tr>

        <?php
            $i=1; $totalPaid=0;
            foreach($paymentDetails as $val):
            $Cheque_Date = (($val['Cheque_Date'] != '0000-00-00 00:00:00')&&($val['Cheque_Date'] != ''))?date('d - m - Y',strtotime($val['Cheque_Date'])):'Not Entered';
            $Cheque_Receiving_Date = (($val['Cheque_Receiving_Date'] != '0000-00-00 00:00:00')&&($val['Cheque_Receiving_Date'] != ''))?date('d - m - Y',strtotime($val['Cheque_Receiving_Date'])):'Not Entered';
            $checkDetails = 'Not Available';
            if(($val['Cheque_Bank']) && ($val['Cheque_No']))
            $checkDetails = $val['Cheque_Bank'].'-'.$val['Cheque_No'];
            if($val['isPaid'] == 'Paid')
            $totalPaid += ($val['Amount_Received']+$val['TDS_Amount']);
        ?>

          <tr>
              <td height="30" align="center" valign="middle"><?php echo $val['Part_Number']; ?></td>
              <td align="center" valign="middle"><?php echo $val['Amount_Received']; ?></td>
              <td align="center" valign="middle"><?php echo $val['TDS_Amount']; ?></td>
              <td align="center" valign="middle"><?php echo $val['Payment_Mode']; ?></td>
              <td align="center" valign="middle"><?php echo $Cheque_Date ; ?></td>
              <td align="center" valign="middle"><?php echo $Cheque_Receiving_Date; ?></td>
              <td align="center" valign="middle"><?php echo $checkDetails; ?></td>
              <td align="center" valign="middle"><?php echo $val['isPaid']; ?></td>
              <td align="center" valign="middle"><?php echo $val['Cheque_DD_Comments']; ?></td>
          </tr>
          <?php $i++; endforeach; ?>
        </table></td>
        </tr>
      <tr>
        <td valign="top">&nbsp;</td>
        <td valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td valign="top">&nbsp;</td>
        <td height="30" valign="top"><strong>Total Collected :</strong><?php echo $totalPaid; ?> </td>
      </tr>
      <tr>
        <td valign="top">&nbsp;</td>
        <td height="30" valign="top"><strong>OutStanding Amount :</strong><?php echo ($FinalSalesAmount - $totalPaid); ?> </td>
      </tr>
      <tr>
        <td valign="top">&nbsp;</td>
        <td valign="top">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td background="images/border.gif"><img src="images/border.gif" width="11" height="6" /></td>
  </tr>
</table>
</body>
</html>
