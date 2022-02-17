<?php
    $headerComponents = array(
        'css'      =>        array('headerCms','raised_all','mainStyle','footer','cal_style'),
        'js'         =>            array('user','tooltip','common','newcommon','listing','prototype','CalendarPopup'),
        'jsFooter'         =>      array('prototype','scriptaculous','utils'),
        'title'      =>        'SUMS - Select Derived Product for a Client',
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
    $this->load->view('common/overlay');
    $this->load->view('common/calendardiv');
?>

<?php global $dprods;
global $selectedCurrencyId;
$selectedCurrencyId = $Quotation['QuotationDetails']['CurrencyId'];
$dprods = $products;
function getProductDetails($productId) {
	global $dprods,$selectedCurrencyId;
	$returnArray = array();
	foreach ($dprods as $prod)
	{
		foreach ($prod['DerivedOfThisBase'] as $dpp)
		{
			if ($dpp['DerivedProductId']==$productId)
			{
				$returnArray['name'] = $dpp['DerivedProductName'];
				$returnArray['rate'] = $dpp['Currency'][$selectedCurrencyId]['ManagementPrice'];
				return $returnArray;
			}
		}
	}
}
$arrIdMap = array();
?>
<script>
    var cal = new CalendarPopup("calendardiv");
    cal.offsetX = 20;
    cal.offsetY = 0;
</script>


<?php if($Transaction != 2){
$formAction = "/sums/Quotation/createQuote/".$prodId;
}else{
$formAction = "/sums/Quotation/viewTransaction/-1/".$prodId;
} ?>

<form id="sumsProdForm" action="<?php echo $formAction; ?>" method="POST">
   <?php if (isset($Quotation)) { $editQuote = "display:none";?>
<input type="hidden" value="<?php echo $Quotation['QuotationDetails']['UIQuotationId'];?>" name="UIQuotationId">
<?php } else {
$newQuote = "display:none";
} ?>
<?php  if(isset($Transaction)) {  ?>
<input type="hidden" value="<?php echo $Transaction; ?>" name="Transaction" >
<?php }?>
<div id="selectProdPart" class="mar_full_10p" style="<?php echo $editQuote; ?>">
        <div style="width:223px; float:left">
            <?php 
		$leftPanelViewValue = 'leftPanelFor'.$prodId;
		$this->load->view('sums/'.$leftPanelViewValue); 
	    ?>
        </div>

        <div  style="margin-left:233px">
            <div class="mar_top_6p">
                Selected Client : <b><?php echo $selectedUserDetails['email']; ?></b> &amp; Client-Id : <b><?php echo $selectedUserDetails['userId'];?></b>
            </div>
	<?php if(array_key_exists(4,$sumsUserInfo['sumsuseracl'])){?>
            <table width="100%" border="0" cellpadding="0" cellspacing="3" bordercolor="#EEEEEE">
                <tr>
                    <td height="20" colspan="7" align="right">
                        <button class="btn-submit7 w7" name="editCourse" id="editCourse" value="editCourse" type="button" onClick="window.location='/sums/Quotation/createCustomizedQuote/<?php echo $selectedUserDetails['userId'];?>';">
                            <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Create Customized Quotation</p></div>
                        </button>
                    </td>
                </tr>
            </table>
	<?php } ?>
            <hr/>
            <input type="hidden" value="<?php echo $selectedUserDetails['userId'];?>" name="selectedUserId">

            <div class="OrgangeFont fontSize_18p bld"><strong>Select Currency</strong></div>
            <div class="lineSpace_5">&nbsp;</div>
            <table width="100%" border="0" cellpadding="0" cellspacing="3" bordercolor="#EEEEEE">
                <tr>
                    <td width="47%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>SELECT CURRENCY FOR CREATING QUOTATION</strong> </td>
                    <td width="35%" valign="top" style="padding:5px 10px 0px 10px">
                        <select id="currencySelected" name="currencySelected" onChange="changeRateForCurrencies();">
                            <option value="">Select Currency</option>
                            <?php
                                    foreach($currencies as $CUR){
                                     	if ($Quotation['QuotationDetails']['CurrencyId']==$CUR['CurrencyId']) {
                                    		$selected = "selected";
                                    	} else {
                                    		$selected = "";
                                    	}
                                    	?>
                            <option id="showSelected_<?php echo $CUR['CurrencyId']; ?>" value="<?php echo $CUR['CurrencyId']; ?>" <?php echo $selected; ?>><?php echo $CUR['CurrencyCode']." : ".$CUR['CurrencyName'];?></option>
                                <?php  }  ?>
                    </select></td>
                </tr>
            </table>

            <div id="checkBoxSanityCheck1" style="display:none;color:red;">
                <div class="lineSpace_5">&nbsp;</div>
            </div><br/>
            <div id="quantitySanityCheck1" style="display:none;color:red;">
                <div class="lineSpace_5">&nbsp;</div>
            </div><br/>
            <div id="CurrencySanityCheck1" style="display:none;color:red;">
                <div class="lineSpace_5">&nbsp;</div>
            </div><br/>
            <div id="FinalSanityCheck1" style="display:none;color:red;">
                <div class="lineSpace_5">&nbsp;</div>
            </div><br/>


            <table width="100%" border="0" cellpadding="0" cellspacing="3" bordercolor="#EEEEEE">
                <tr>
                    <td width="100%" height="10" colspan="7" bgcolor="#EEEEEE">&nbsp;</td>
                </tr>
                <tr>
                    <td height="20" colspan="7" align="right">
                        <button class="btn-submit7 w7" name="editCourse1" id="editCourse1" value="editCourse" type="button" onClick="validateSumsProds();">
                            <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Select Product & Category</p></div>
                        </button>
                    </td>
                </tr>
            </table>

            <div class="OrgangeFont fontSize_18p bld"><strong>Select Products</strong></div>
            <?php $i=1; ?>
            <?php foreach($products as $key => $val){ ?>

            <table width="100%" border="0" cellpadding="0" cellspacing="3" bordercolor="#EEEEEE">
                <tr>
                    <td height="25" colspan="7" bgcolor="#99CCFF">&nbsp;&nbsp;&nbsp;<strong><?php echo $val['CategoryName']; ?></strong></td>
                </tr>
                <tr>
                    <td width="4%" height="25" valign="top" bgcolor="#EEEEEE" style="padding:5px 5px 0px 5px"><img src="/public/images/space.gif" width="23" height="30" /></td>
                    <td width="17%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>PRODUCT</strong> </td>
                    <td width="31%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>DESCRIPTION</strong></td>
                    <td width="9%" valign="top"  bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>RATE </strong></td>
                    <td width="18%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>QUANTITY</strong></td>
                    <td width="14%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>PRODUCT TYPE</strong></td>
                </tr>
            </table>

            <table width="98%" border="0" cellspacing="3" cellpadding="0">
                <tr>
                    <td width="4%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                    <td width="17%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                    <td width="31%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                    <td width="9%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                    <td width="18%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                    <td width="14%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                </tr>
                <?php foreach ($val['DerivedOfThisBase'] as $derProds){ ?>
                <?php
                    if (array_key_exists($derProds['DerivedProductId'],$Quotation['QuotationProducts'])) {
                    	$checked = true;
						$quantity = $Quotation['QuotationProducts'][$derProds['DerivedProductId']]['Quantity'];
						$arrIdMap[$derProds['DerivedProductId']] = $i;
                    }
                    else {
                    	$checked = false;
                    	unset($quantity);
                    }
                ?>
                <tr>
                    <td height="25" valign="top"  style="padding:0"><input type="checkbox" id="productId_<?php echo $i; ?>" name="selectedDeriveds[]" value="<?php echo $derProds['DerivedProductId'] ?>" <?php if($checked) echo "checked"; ?> /></td>
                    <td id="td_name_<?php echo $i; ?>" name="td_name_<?php echo $i; ?>" valign="top" style="padding:5px 10px 0px 10px"><?php echo $derProds['DerivedProductName']; ?></td>
                    <td valign="top" style="padding:5px 10px 0px 10px"><?php echo $derProds['DerivedProductDescription']; ?></td>
                    <td><div id="td_price_<?php echo $i; ?>" name="td_price_<?php echo $i; ?>" valign="top" style="padding:5px 10px 0px 10px">
                        <?php foreach ($derProds['Currency'] as $currVal => $currProp){ ?>
			<?php $showCurrency = 'style="display:none"';
			 if($Quotation['QuotationDetails']['CurrencyId'] == $currVal) {
				$showCurrency = 'style="display:inline"';
			}?>
                        <div id="td_price_<?php echo $i;?>_cur_<?php echo $currVal;?>" <?php echo $showCurrency; ?> ><?php echo $currProp['ManagementPrice']; ?></div>
                        <input type="hidden" name="Price_DervId_<?php echo $derProds['DerivedProductId'] ?>_CurrencyId_<?php echo $currVal;?>" value="<?php echo $currProp['ManagementPrice']; ?>">
                        <?php }?>
                        </div>
                    </td>
                    <td valign="top" style="padding:5px 10px 0px 10px"><input type="text" name="quantity_<?php echo $derProds['DerivedProductId']; ?>" id="quantity_id_<?php echo $i;?>" value="<?php echo $quantity; ?>" size="7" maxlength="100" minlength="1" /></td>
                    <td valign="top" style="padding:5px 10px 0px 10px"><?php echo $derProds['DerivedProductType']; ?></td>
                </tr>
                <?php
                    $i++;
                }
            ?>
        </table>
        <?php } ?>

        <input type="hidden" id="totalProdCount" value="<?php echo $i-1; ?>" />
        
        <div id="checkBoxSanityCheck" style="display:none;color:red;">
            <div class="lineSpace_5">&nbsp;</div>
        </div><br/>
        <div id="quantitySanityCheck" style="display:none;color:red;">
            <div class="lineSpace_5">&nbsp;</div>
        </div><br/>
        <div id="CurrencySanityCheck" style="display:none;color:red;">
            <div class="lineSpace_5">&nbsp;</div>
        </div><br/>
        <div id="FinalSanityCheck" style="display:none;color:red;">
            <div class="lineSpace_5">&nbsp;</div>
        </div><br/>
        
        <table width="100%" border="0" cellpadding="0" cellspacing="3" bordercolor="#EEEEEE">
            <tr>
                <td width="100%" height="20" colspan="7" bgcolor="#EEEEEE">&nbsp;</td>
            </tr>
            <tr>
                <td height="20" colspan="7" align="right">
                    <button class="btn-submit7 w7" name="editCourse" id="editCourse" value="editCourse" type="button" onClick="validateSumsProds();">
                        <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Select Product & Category</p></div>
                    </button>
                </td>
            </tr>
        </table>
    </div>
    <div class="clear_L"></div>
<div class="lineSpace_35">&nbsp;</div>
</div>




<div id="createQuotePart" class="mar_full_10p" style="<?php echo $newQuote;?>">
    <div style="width:223px; float:left">
        <?php 
		$leftPanelViewValue = 'leftPanelFor'.$prodId;
		$this->load->view('sums/'.$leftPanelViewValue); 
	?>
    </div>
    <div style="margin-left:233px;">
        <div class="mar_top_6p">
            Selected Client : <b><?php echo $selectedUserDetails['email']; ?></b> &amp; Client-Id : <b><?php echo $selectedUserDetails['userId'];?></b>
        </div>
        <hr/>


        <div class="OrgangeFont fontSize_18p bld"><strong>Price Quotation</strong></div>
        <div class="lineSpace_5">&nbsp;</div>

        <table width="100%" border="0" cellpadding="0" cellspacing="3" bordercolor="#EEEEEE">
            <tr>
                <td colspan="7" bgcolor="#FFFFFF">&nbsp;</td>
            </tr>
            <tr>
                <td height="25" colspan="7" bgcolor="#99CCFF">&nbsp;&nbsp;&nbsp;<strong></strong></td>
            </tr>
            <tr>
                <td width="75%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>SELECTED CURRENCY</strong> </td>
                <td width="21%" valign="top" style="padding:5px 10px 0px 10px" id="showSelectedCurr">
                            <?php
                                    foreach($currencies as $CUR){
                                     	if ($Quotation['QuotationDetails']['CurrencyId']==$CUR['CurrencyId']) {
                                            echo $CUR['CurrencyCode']." : ".$CUR['CurrencyName'];
                                    	} else {
                                            echo "";
                                        }
                                    }
                            ?>
                </td>
            </tr>
            <tr>
                <td width="75%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>QUOTATION CREATED BY</strong> </td>
                <td width="21%" valign="top" style="padding:5px 10px 0px 10px">
                    <div class="" style="">
                        <select name="quotationCreator" id="quoteCreator" size="4">
                                <?php foreach ($quoteUsers as $user) {
                                        if ($Quotation['QuotationDetails']['CreatedBy']==$user['userid']) {
                                            $selected = "selected";
                                        } else {
                                            $selected = "";
                                        } 
                                        ?>
                                        <option value="<?php echo $user['userid'];?>" <?php echo $selected; ?>><?php echo $user['displayname']." : ".$user['BranchName'];?></option> 
                            <?php } ?>
                        </select>
                    </div>
                </td>
            </tr>
        </table>
        <div class="lineSpace_5">&nbsp;</div>
        <div id="quoteUserCheck" style="display:none;color:red;"></div><br/>

        <table width="100%" border="0" cellpadding="0" cellspacing="3" bordercolor="#EEEEEE">
            <tr>
                <td colspan="7" bgcolor="#FFFFFF">&nbsp;</td>
            </tr>
            <tr>
                <td height="25" colspan="7" bgcolor="#99CCFF">&nbsp;&nbsp;&nbsp;<strong></strong></td>
            </tr>
            <tr>
                <td width="34%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>PRODUCT</strong> </td>
                <td width="20%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>QUANTITY</strong></td>
                <td width="20%" valign="top"  bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>PRICE</strong></td>
                <td width="22%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>DISCOUNT</strong></td>
            </tr>
        </table>
    
        <div id="quoteTable">
        <table width="100%" border="0" cellspacing="3" cellpadding="0">
            <tr>
                <td width="34%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                <td width="20%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                <td width="18%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                <td width="22%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
            </tr>
			<?php foreach($Quotation['QuotationProducts'] as $quoteProds) {
			$prodData = getProductDetails($quoteProds['DerivedProductId']);
			?>
			<tr>
				<td valign="top" style="padding: 5px 10px 0px;"><?php echo $prodData['name'];?></td>
				<td valign="top" style="padding: 5px 10px 0px;"><?php echo $quoteProds['Quantity'];?></td>
				<td valign="top" style="padding: 5px 10px 0px;"><div id="netItemPrice_<?php echo $arrIdMap[$quoteProds['DerivedProductId']];?>"><?php echo $quoteProds['Quantity']*$prodData['rate'];?></div></td>
				<td valign="top" style="padding: 5px 10px 0px;"><input type="text" value="<?php echo $quoteProds['Discount'];?>" onblur="validateAndCalculatePrice();" minlength="1" maxlength="100" size="18" id="discount_<?php echo $arrIdMap[$quoteProds['DerivedProductId']];?>" name="discount_<?php echo $quoteProds['DerivedProductId'];?>"/></td>
			</tr>
			<?php } ?>
        </table>
        </div>

        <table width="100%" border="0" cellpadding="0" cellspacing="3" bordercolor="#EEEEEE">
            <tr>
                <td colspan="7" bgcolor="#FFFFFF">&nbsp;</td>
            </tr>
            <tr>
                <td height="25" colspan="7" bgcolor="#99CCFF">&nbsp;&nbsp;&nbsp;<strong></strong></td>
            </tr>
            <tr>
                <td width="75%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>TOTAL PRICE</strong> </td>
                <td width="21%" valign="top" style="padding:5px 10px 0px 10px"><input type="text" name="totalPrice" id="totalPrice" size="20" maxlength="100" minlength="1" readonly value="<?php echo $Quotation['QuotationDetails']['TotalPrice'];?>" /></td>
            </tr>
            <tr>
                <td width="75%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>TOTAL DISCOUNT GIVEN</strong> </td>
                <td width="21%" valign="top" style="padding:5px 10px 0px 10px"><input type="text" name="totalDiscount" id="totalDiscount" size="20" maxlength="100" minlength="1" readonly value="<?php echo $Quotation['QuotationDetails']['TotalDiscount'];?>" /></td>
            </tr>
            <tr>
                <td width="75%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>PRICE AFTER DISCOUNT(S)</strong> </td>
                <td width="21%" valign="top" style="padding:5px 10px 0px 10px"><input type="text" name="totalBasePrice" id="totalBasePrice" size="20" maxlength="100" minlength="1" readonly value="<?php echo $Quotation['QuotationDetails']['TotalBasePrice'];?>" /></td>
            </tr>
	    <input type="hidden" value="<?php echo $countrycounter; ?>" id="countrycounter" name="countrycounter" > 
	    
       <tr>
                <td width="75%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>NET AMOUNT</strong> </td>
                <td width="21%" valign="top" style="padding:5px 10px 0px 10px"><input type="text" name="netAmount" id="netAmount" size="20" maxlength="100" minlength="1" readonly value="<?php echo $Quotation['QuotationDetails']['NetAmount'];?>" /></td>
            </tr>
            <tr>
                <td width="75%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>ROUND OFF AMOUNT</strong> </td>
                <td width="21%" valign="top" style="padding:5px 10px 0px 10px"><input type="text" name="roundOffAmount" id="roundOffAmount" size="20" maxlength="100" minlength="1" readonly value="<?php echo $Quotation['QuotationDetails']['RoundOffAmount'];?>" /></td>
            </tr>
            <tr>
                <td width="75%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>FINAL SALES AMOUNT</strong> </td>
                <td width="21%" valign="top" style="padding:5px 10px 0px 10px"><input type="text" name="finalSalesAmount" id="finalSalesAmount" size="20" maxlength="100" minlength="1" readonly value="<?php echo $Quotation['QuotationDetails']['FinalSalesAmount'];?>" /></td>
            </tr>
        </table>
        <div id="discountSanityCheck" style="display:none;color:red;"></div><br/>
        <div class="lineSpace_5">&nbsp;</div>
        <div id="FinalQuoteCheck" style="display:none;color:red;"></div><br/>
        <div class="lineSpace_5">&nbsp;</div>

        <div class="lineSpace_10">&nbsp;</div>

        <table width="98%" border="0" cellpadding="0" cellspacing="3" bordercolor="#EEEEEE">
            <tr>
                <td height="20" align="center">
		    <?php if(!isset($Transaction) || (isset($Transaction)&&($Transaction != 2))): ?>	
                    <button class="btn-submit7 w7" name="editCourse" id="editCourse" value="editCourse" type="button" onClick="editProductSelection();">
                        <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Edit Product Selection</p></div>
                    </button>
		   <?php endif; ?>	
		 </td>
		 <?php if (isset($Quotation) && (isset($Transaction)&&($Transaction != 2))) { ?>
		 <td>
		    <button class="btn-submit7 w7" name="editCourse" id="editCourse" value="editCourse" type="button" onClick="showQuotationHistory();">
		       <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">View Quotation History</p></div>
		    </button>
		 </td>
		 <?php } ?>
                <td height="20" align="center">
			<button class="btn-submit7 w7" name="editCourse" id="editCourse" value="editCourse" type="button" onClick="validateQuoteAndSubmit();">
			<?php if(isset($Transaction) && ($Transaction != 2) && ($Transaction!=-1)) { ?>
			<div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Save Quotation & Enter Payment Details</p></div>
            		<?php } elseif (isset($Quotation) && isset($Transaction) && ($Transaction != 2)) { ?>
                        <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Edit Quotation</p></div>
			<?php } elseif(!isset($Transaction) || (isset($Transaction)&&($Transaction != 2))) { ?>
                        <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Generate Quotation</p></div>
			<?php } elseif(isset($Transaction) && ($Transaction == 2)) { ?>		
                        <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">View Payment Details</p></div>
			<?php } ?>
			</button>
                </td>
            </tr>
        </table>
    </div>
    <div class="lineSpace_35">&nbsp;</div>
</div>
</form>

</body>
</html>

<script>
   function showQuotationHistory()
   {
	 window.open('/sums/Quotation/getQuotationHistory/<?php echo $Quotation['QuotationDetails']['UIQuotationId'];?>');

   }

    function validateSumsProds(){
            var checkBoxSelChk = validateCheckbox();
            var prodQtyChk = validateProdQuantity();
            var currencyChk = validateCurrSelect();

            if(checkBoxSelChk && prodQtyChk && currencyChk){
                    document.getElementById('FinalSanityCheck').innerText = "";
                    document.getElementById('FinalSanityCheck').style.display = 'none';
                    document.getElementById('FinalSanityCheck1').innerText = "";
                    document.getElementById('FinalSanityCheck1').style.display = 'none';
                    showCreateQuotationPage();
                }else{
                    document.getElementById('FinalSanityCheck').innerText = "Please correct above fields in RED !!";
                    document.getElementById('FinalSanityCheck').style.display = 'inline';
                    document.getElementById('FinalSanityCheck1').innerText = "Please correct above fields in RED !!";
                    document.getElementById('FinalSanityCheck1').style.display = 'inline';
            }
    }

    function validateCheckbox()
    {
            for(var i=1;i<=document.getElementById('totalProdCount').value;i++)
            {
                    if(document.getElementById('productId_'+i+'').checked == true){
                            document.getElementById('checkBoxSanityCheck').innerText = "";
                            document.getElementById('checkBoxSanityCheck').style.display = 'none';
                            document.getElementById('checkBoxSanityCheck1').innerText = "";
                            document.getElementById('checkBoxSanityCheck1').style.display = 'none';
                            return true;
                        }else{
                            continue;
                    }
            }
            document.getElementById('checkBoxSanityCheck').innerText = "Please select a Product !!";
            document.getElementById('checkBoxSanityCheck').style.display = 'inline';
            document.getElementById('checkBoxSanityCheck1').innerText = "Please select a Product !!";
            document.getElementById('checkBoxSanityCheck1').style.display = 'inline';

            return false;
    }

    function validateProdQuantity(){
            var FLAG=false;
            for(var i=1;i<=document.getElementById('totalProdCount').value;i++)
            {
                    if(document.getElementById('productId_'+i+'').checked == true){
                            if(document.getElementById('quantity_id_'+i+'').value != ''){
                                    var filter = /^(\d)+$/;
                                    if(!filter.test(document.getElementById('quantity_id_'+i+'').value)){
                                            document.getElementById('quantitySanityCheck').innerText = "Please input Quanity as a Positive Number!!";
                                            document.getElementById('quantitySanityCheck').style.display = 'inline';
                                            document.getElementById('quantitySanityCheck1').innerText = "Please input Quanity as a Positive Number!!";
                                            document.getElementById('quantitySanityCheck1').style.display = 'inline';
                                            return false;
                                        }else{
                                            FLAG = true;
                                            continue;
                                    }
                            }
                            document.getElementById('quantitySanityCheck').innerText = "Please input Quanity as a Positive Number!!";
                            document.getElementById('quantitySanityCheck').style.display = 'inline';
                            document.getElementById('quantitySanityCheck1').innerText = "Please input Quanity as a Positive Number!!";
                            document.getElementById('quantitySanityCheck1').style.display = 'inline';
                            return false;
                        }else{
                            continue;
                    }
            }
            if(FLAG == true){
                    document.getElementById('quantitySanityCheck').innerText = "";
                    document.getElementById('quantitySanityCheck').style.display = 'none';
                    document.getElementById('quantitySanityCheck1').innerText = "";
                    document.getElementById('quantitySanityCheck1').style.display = 'none';
                    return true;
                }else{
                    return false;
            }
    }

    function validateCurrSelect()
    {
            var catCombo = document.getElementById('currencySelected');
            for(var i=1; i < (catCombo.options.length); i++) {
                    if(catCombo.options[i].selected == true) {
                            document.getElementById('CurrencySanityCheck').innerText = "";
                            document.getElementById('CurrencySanityCheck').style.display = 'none';
                            document.getElementById('CurrencySanityCheck1').innerText = "";
                            document.getElementById('CurrencySanityCheck1').style.display = 'none';
                            return true;
                        }else{
                            continue;
                    }
            }
            document.getElementById('CurrencySanityCheck').innerText = "Please select a Currency !!";
            document.getElementById('CurrencySanityCheck').style.display = 'inline';
            document.getElementById('CurrencySanityCheck1').innerText = "Please select a Currency !!";
            document.getElementById('CurrencySanityCheck1').style.display = 'inline';
            return false;
    }

    function showCreateQuotationPage(){
            var objTable = document.getElementById('quoteTable');
            var tmpInnerHTML = '';
            tmpInnerHTML += '\
            <table width="100%" border="0" cellspacing="3" cellpadding="0">\
            <tr>\
                <td width="34%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>\
                <td width="20%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>\
                <td width="18%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>\
                <td width="22%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>\
            </tr>';

            var selectedCur = document.getElementById('currencySelected').value;

            for(var i=1;i<=document.getElementById('totalProdCount').value;i++)
            {
                    if(document.getElementById('productId_'+i+'').checked == true){
                            tmpInnerHTML += '\
                            <tr>\
                                <td valign="top" style="padding:5px 10px 0px 10px">'+document.getElementById('td_name_'+i+'').innerHTML+'</td>\
                                <td valign="top" style="padding:5px 10px 0px 10px">'+document.getElementById('quantity_id_'+i+'').value+'</td>\
                                <td valign="top" style="padding:5px 10px 0px 10px"><div id="netItemPrice_'+i+'" > '+parseFloat(document.getElementById('quantity_id_'+i+'').value)*parseFloat(document.getElementById('td_price_'+i+'_cur_'+selectedCur+'').innerHTML)+'</div></td>\
                                <td valign="top" style="padding:5px 10px 0px 10px"><input type="text" name="discount_'+document.getElementById('productId_'+i+'').value+'" id="discount_'+i+'" size="18" maxlength="100" minlength="1" onblur="validateAndCalculatePrice();" value="0" /></td>\
                            </tr>';
                    }
            }
            tmpInnerHTML += '</table>';
            objTable.innerHTML = tmpInnerHTML;

            validateAndCalculatePrice();
            document.getElementById('selectProdPart').style.display='none';
            document.getElementById('createQuotePart').style.display='inline';
    }

    function editProductSelection(){
            validateAndCalculatePrice();
            document.getElementById('createQuotePart').style.display='none';
            document.getElementById('selectProdPart').style.display='inline';
    }

    function validateQuoteAndSubmit(){
        var discountCheck = validateDiscount();
        var quoteUserChk = quoteByChk();

            if(discountCheck && quoteUserChk){
                    document.getElementById('FinalQuoteCheck').innerText = "";
                    document.getElementById('FinalQuoteCheck').style.display = 'none';
                    $('sumsProdForm').submit();
                }else{
                    document.getElementById('FinalQuoteCheck').innerText = "Please correct above fields in RED !!";
                    document.getElementById('FinalQuoteCheck').style.display = 'inline';
            }
    }

    function validateDiscount(){
            var FLAG=false;
            for(var i=1;i<=document.getElementById('totalProdCount').value;i++)
            {
                    if(document.getElementById('productId_'+i+'').checked == true){
                            if(document.getElementById('discount_'+i+'').value != ''){
                                    var filter = /^[-+]?\d+(?:\.\d{0,2})?$/;
                                    if(!filter.test(document.getElementById('discount_'+i+'').value)){
                                            document.getElementById('discountSanityCheck').innerHTML = "Please input Discount as valid amount eg. 123.45!!";
                                            document.getElementById('discountSanityCheck').style.display = 'inline';
                                            return false;
                                        }else{
                                            FLAG = true;
                                            continue;
                                    }
                            }
                            document.getElementById('discountSanityCheck').innerHTML = "Please input Discount as valid amount eg. 123.45!!";
                            document.getElementById('discountSanityCheck').style.display = 'inline';
                            return false;
                        }else{
                            continue;
                    }
            }
            if(FLAG == true){
                    document.getElementById('discountSanityCheck').innerHTML = "";
                    document.getElementById('discountSanityCheck').style.display = 'none';
                    return true;
                }else{
                    return false;
            }
    }

    function validateAndCalculatePrice(){
            var DISCFLAG =  validateDiscount();
            if(DISCFLAG){
                    resetQuotePrices();
                    calculateQuotePrices();
            }
    }


    var totalPrice=0;
    var totalDiscount=0;
    var totalBasePrice=0;
    var serviceTaxVal=0;
    var serviceTax=0;
    var netAmount=0;
    var roundOffAmount=0;
    var finalSalesAmount=0;
    function calculateQuotePrices(){

	    for(var i=1;i<=document.getElementById('totalProdCount').value;i++)
	    {
		    if(document.getElementById('productId_'+i+'').checked == true){
			    totalDiscount += parseFloat(document.getElementById('discount_'+i+'').value);
			    totalPrice += parseFloat(document.getElementById('netItemPrice_'+i+'').innerHTML);
		    }
	    }
	    totalBasePrice = totalPrice - totalDiscount;

	    var selectedCurrencyhere = document.getElementById('currencySelected').value;
	    
	    var phpvar = "<?php echo $countrycounter; ?>";


		    if(selectedCurrencyhere == 2 && phpvar == 'true')
		    {
			    serviceTax = 0;

		    }
		    else
		    {
			   serviceTax = "<?php echo $parameters['Service_Tax']['parameterValue']; ?>";
		    }



	    serviceTax = Math.round(( ((totalBasePrice) * serviceTax) /100)*100)/100;

	    netAmount = Math.round((totalBasePrice + serviceTax)*100)/100;
	    finalSalesAmount = Math.floor(netAmount);
	    roundOffAmount = Math.round((netAmount - finalSalesAmount)*100)/100 ;

	    var selectedCurrency = document.getElementById('currencySelected').value;


	    document.getElementById('totalPrice').value = totalPrice;
	    document.getElementById('totalDiscount').value = totalDiscount;
	    document.getElementById('totalBasePrice').value = totalBasePrice;
	    //     document.getElementById('serviceTax').value = serviceTax;
	    document.getElementById('netAmount').value = netAmount;
	    document.getElementById('roundOffAmount').value = roundOffAmount;
	    document.getElementById('finalSalesAmount').value = finalSalesAmount;
    }

    function resetDiscounts(){
            for(var i=1;i<=document.getElementById('totalProdCount').value;i++)
            {
                    if(document.getElementById('productId_'+i+'').checked == true){
                            document.getElementById('discount_'+i+'').value = '';
                    }
            }
    }

    function resetQuotePrices(){
            totalPrice = 0;
            totalDiscount=0;
            totalBasePrice=0;
            serviceTax=0;
            netAmount=0;
            roundOffAmount=0;
            finalSalesAmount=0;
    }


    function changeRateForCurrencies(){
            var selectedCur = document.getElementById('currencySelected').value;

        if(selectedCur != ''){
                for(var i=1;i<=document.getElementById('totalProdCount').value;i++){
                        for(var j=1; j < document.getElementById('currencySelected').options.length; j++) {
                                var currId = document.getElementById('currencySelected').options[j].value;

                                if(currId == selectedCur){
                                        if(document.getElementById('td_price_'+i+'_cur_'+currId+'') != null){
                                                document.getElementById('td_price_'+i+'_cur_'+currId+'').style.display = 'inline';
                                        }
                                    }else{
                                        if(document.getElementById('td_price_'+i+'_cur_'+currId+'') != null){
                                                document.getElementById('td_price_'+i+'_cur_'+currId+'').style.display = 'none';
                                        }
                                }
                        }
                }
                document.getElementById('showSelectedCurr').innerHTML = document.getElementById('showSelected_'+selectedCur).innerHTML; 
            }else{
                for(var i=1;i<=document.getElementById('totalProdCount').value;i++){
                        for(var j=1; j < document.getElementById('currencySelected').options.length; j++) {
                                var currId = document.getElementById('currencySelected').options[j].value;
                                if(document.getElementById('td_price_'+i+'_cur_'+currId+'') != null){
                                        document.getElementById('td_price_'+i+'_cur_'+currId+'').style.display = 'none';
                                }
                        }
                }
        }
}

function quoteByChk(){
        if($('quoteCreator').value == '')
	{	
                $('quoteUserCheck').innerHTML = 'Please select the user who made the Quotation.';
                $('quoteUserCheck').style.display = 'inline';
		return false;
            }else{
                $('quoteUserCheck').innerHTML = '';
                $('quoteUserCheck').style.display = 'none';
                return true;
        }
}

<?php if(isset($Quotation['QuotationDetails']['CurrencyId'])){ ?>
document.getElementById('currencySelected').value = <?php echo $Quotation['QuotationDetails']['CurrencyId']; ?>;
<?php }else{ ?>
document.getElementById('currencySelected').value = 1;
<?php
    }
?>
changeRateForCurrencies();


</script>
