<?php if ($users==NULL) { ?>
	<div class="mar_top_6p">No results found.</div>
        <?php } else { ?>

        <div class="OrgangeFont fontSize_14p bld"><strong>View Quotation(s) Summary</strong></div>
        <div class="grayLine"></div>
        <div>
            <table width="100%" border="0" cellpadding="0" cellspacing="3" bordercolor="#EEEEEE">
                <tr>
                    <td colspan="7" bgcolor="#FFFFFF">&nbsp;</td>
                </tr>
                <tr>
                    <td width="5%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>CLIENT ID</strong> </td>
                    <td width="12%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>COLLEGE / INSTITUTE / UNIVERSITY NAME</strong></td>
                    <td width="16%" valign="top"  bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>LOGIN EMAIL ID</strong></td>
                    <td width="12%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>QUOTATION ID</strong></td>
                    <td width="12%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>QUOTATION DATE</strong></td>
                    <td width="12%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>PRODUCTS SELECTED</strong></td>
                    <td width="9%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>AMOUNT</strong></td>
                    <td width="12%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>CREATED BY</strong></td>
			<?php if($prodId==20){ ?>
                        <td width="10%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>TRANSACTION</strong></td>
                        <?php } ?>
                    </tr>
                </table>
                
                <div style="overflow:auto; height:315px">
                <table width="98%" border="0" cellspacing="3" cellpadding="0">                        
                    <tr>
                        <td width="5%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                        <td width="12%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                        <td width="16%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                        <td width="12%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                        <td width="12%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                        <td width="12%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                        <td width="9%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                        <td width="12%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
			<?php if($prodId==20){ ?>
			<td width="10%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                        <?php } ?>
                    </tr>
        <?php $i=1; ?>
        
        <?php foreach ($users as $key=>$val) { ?>
                    <tr>
                        <td valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['ClientId']; ?></td>
                        <td valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['businessCollege']; ?></td>
                        <td valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['email']; ?></td>
                        <?php if($val['QuoteType']=="CUSTOMIZED"){ ?>
                        <td valign="top" style="padding:5px 10px 0px 10px">
			<?php if(array_key_exists(5,$sumsUserInfo['sumsuseracl']))
			{?>
                        <a href="/sums/Quotation/editCustomizedQuote/<?php echo $val['UIQuotationId'].'/-1/ACTIVE/'.$prodId; ?>"><?php echo $val['UIQuotationId']; ?></a>
			<?php } 
			else
			{
				echo $val['UIQuotationId']; 
			} ?>
			</td>
                        <?php }else{ ?>
                        <td valign="top" style="padding:5px 10px 0px 10px">
			<?php if(array_key_exists(3,$sumsUserInfo['sumsuseracl']))
                	{?>
			<a href="/sums/Quotation/editQuotation/<?php echo $val['UIQuotationId'].'/-1/ACTIVE/'.$prodId; ?>"><?php echo $val['UIQuotationId']; ?></a>
			<?php } 
			else
			{
				echo $val['UIQuotationId']; 
			} ?>
			</td>
                        <?php } ?>
                        <td valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['CreatedTime']; ?></td>
                        <td valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['ProductSelected']; ?></td>
                        <td valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['NetAmount']; ?></td>
                        <td valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['CreatedBy']; ?></td>
			<?php if($prodId==20){ ?>
			<?php if(array_key_exists(6,$sumsUserInfo['sumsuseracl'])){ ?>
			<?php if($val['QuoteType']=="CUSTOMIZED"){ ?>
                        <td valign="top" style="padding:5px 10px 0px 10px"><a href="/sums/Quotation/editCustomizedQuote/<?php echo $val['UIQuotationId']?>/1/ACTIVE/<?php echo $prodId; ?>">Convert</a></td>
                        <?php }else{ ?>
                        <td valign="top" style="padding:5px 10px 0px 10px"><a href="/sums/Quotation/editQuotation/<?php echo $val['UIQuotationId']?>/1/ACTIVE/<?php echo $prodId; ?>">Convert</a></td>
                        <?php } ?>
			<?php } ?>
			<?php } ?>
                    </tr>
	<?php $i++; 
        } ?>

                </table>
            </div>
            <table width="100%" border="0" cellpadding="0" cellspacing="3" bordercolor="#EEEEEE">
                <tr>
                    <td width="100%" height="20" colspan="7" bgcolor="#EEEEEE">&nbsp;</td>
                </tr>
            </table>
        <input type="hidden" id="totalUserCount" value="<?php echo $i-1; ?>" />
        <div class="clear_L"></div>
        <?php } ?>
