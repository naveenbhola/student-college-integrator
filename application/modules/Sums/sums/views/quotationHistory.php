<?php
$headerComponents = array(
		'css'      =>        array('headerCms','raised_all','mainStyle','footer'),
		'js'         =>            array('user','tooltip','common','newcommon','prototype'),
		'jsFooter'         =>      array('scriptaculous','utils'),
		'title'      =>        'SUMS - Client selection for creating quotation page',
		'tabName'          =>        'Register',
		'taburl' =>  SITE_URL_HTTPS.'enterprise/Enterprise/register',
		'metaKeywords'  =>'Some Meta Keywords',
		'product' => '',
		'search'=>false,
		'displayname'=> (isset($sumsUserInfo['validity'][0]['displayname'])?$sumsUserInfo['validity'][0]['displayname']:""),
		'callShiksha'=>1
		);
$this->load->view('enterprise/headerCMS', $headerComponents);
$this->load->view('enterprise/cmsTabs',$sumsUserInfo);
?>
<?php if ($users==NULL) { ?>
	<div class="mar_top_6p">No results found.</div>
		<?php } else { ?>

			<div class="OrgangeFont fontSize_14p bld"><strong>View Quotation(s) History</strong></div>
				<div class="grayLine"></div>
				<div class="lineSpace_10">
				<table width="100%" border="0" cellpadding="0" cellspacing="3" bordercolor="#EEEEEE">
				<tr>
				<td colspan="7" bgcolor="#FFFFFF">&nbsp;</td>
				</tr>
				<tr>
				<td width="12%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>UPDATE DATE</strong> </td>
                                <td width="12%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>EDITED BY: UserId</strong></td>
				<td width="16%" valign="top"  bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>PRODUCT LIST</strong></td>
				<td width="12%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>PRICE</strong></td>
				<td width="12%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>DISCOUNTS</strong></td>
				<td width="12%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>FINAL SALES AMOUNT</strong></td>
				</tr>
				</table>

				<div style="overflow:auto; height:115px">
				<table width="98%" border="0" cellspacing="3" cellpadding="0">                        
				<tr>
				<td width="12%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
				<td width="12%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
				<td width="16%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
				<td width="12%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
				<td width="12%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
				<td width="12%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
				</tr>
				<?php $i=1; ?> 

				<?php foreach ($users as $key=>$val) { ?>
					<tr>
						<td valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['CreatedTime']; ?></td>
                                                <td valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['editingUserName'].': '.$val['sumsLoggedInUser']; ?></td>
						<td valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['ProductSelected']; ?></td>
						<td valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['NetAmount']; ?></td>
						<td valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['TotalDiscount']; ?></td>
						<td valign="top" style="padding:5px 10px 0px 10px"><?php echo $val['FinalSalesAmount']; ?></td>
						</tr>
						<?php $i++; 
				} ?>

			</table>
				</div>
				<input type="hidden" id="totalUserCount" value="<?php echo $i-1; ?>" />
				<div class="lineSpace_5">&nbsp;</div>
				<table width="100%" border="0" cellpadding="0" cellspacing="3" bordercolor="#EEEEEE">
				<tr>
				<td width="100%" height="20" colspan="7" bgcolor="#EEEEEE">&nbsp;</td>
				</tr>
				<tr>
				<td height="20" colspan="7" align="right">
				</td>
				</tr>
				</table>
				</div>
				<div class="clear_L"></div>
				<?php } ?>
