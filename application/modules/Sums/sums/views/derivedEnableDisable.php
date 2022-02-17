<?php
    $headerComponents = array(
        'css'      =>        array('headerCms','raised_all','mainStyle','footer','cal_style'),
        'js'         =>            array('user','tooltip','common','newcommon','listing','prototype','CalendarPopup'),
        'jsFooter'         =>      array('prototype','scriptaculous','utils'),
        'title'      =>        'SUMS - Enable / Disable / Delete Derived Products',
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

<form id="dervProdForm" action="/sums/Product/updateDerivedProdStatus" method="POST">
<div id="selectProdPart" class="mar_full_10p" style="">
        <div style="width:223px; float:left">
            <?php 
		$leftPanelViewValue = 'leftPanelFor'.$prodId;
		$this->load->view('sums/'.$leftPanelViewValue); 
	    ?>
        </div>

        <div  style="margin-left:233px">
            <hr/>

            <table width="100%" border="0" cellpadding="0" cellspacing="3" bordercolor="#EEEEEE">
                <tr>
                    <td width="47%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>Select Currency for seeing price</strong> </td>
                    <td width="35%" valign="top" style="padding:5px 10px 0px 10px">
                        <select id="currencySelected" onChange="changeRateForCurrencies();">
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
            <div class="lineSpace_5">&nbsp;</div>

            <div class="OrgangeFont fontSize_18p bld"><strong>Enable / Disable Derived Products</strong></div>
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
                    <td width="14%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>PRODUCT TYPE</strong></td>
                    <td width="14%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>STATUS</strong></td>
                </tr>
            </table>

            <table width="98%" border="0" cellspacing="3" cellpadding="0">
                <tr>
                    <td width="4%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                    <td width="17%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                    <td width="31%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                    <td width="9%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
                    <td width="14%" valign="top" style="padding:0"><img src="/public/images/space.gif" width="15" height="1" /></td>
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
                        <?php }?>
                        </div>
                    </td>
                    <td valign="top" style="padding:5px 10px 0px 10px"><?php echo $derProds['DerivedProductType']; ?></td>
                    <td valign="top" <?php if($derProds['Status']=='ACTIVE'){echo "bgcolor='#5FFB17'";}else{echo "bgcolor='#ff3300'";} ?> style="padding:5px 10px 0px 10px"><?php echo $derProds['Status']; ?></td>
                </tr>
                <?php
                    $i++;
                }
            ?>
        </table>
        <?php } ?>

        <input type="hidden" id="totalProdCount" value="<?php echo $i-1; ?>" />
        <input type="hidden" id="updateType" name="updateTypeDerived" value="" />
        
        <div id="checkBoxSanityCheck" style="display:none;color:red;">
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
                <td height="20"  >
                    <button class="btn-submit7 w7" name="editCourse" id="editCourse" value="editCourse" type="button" onClick="validateUpdateDerv('enable');">
                        <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">ENABLE All Selected</p></div>
                    </button>
                </td>
                <td height="20"  >
                    <button class="btn-submit7 w7" name="editCourse" id="editCourse" value="editCourse" type="button" onClick="validateUpdateDerv('disable');">
                        <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">DISABLE all Selected</p></div>
                    </button>
                </td>
                <td height="20" colspan="7">
                    <button class="btn-submit7 w7" name="editCourse" id="editCourse" value="editCourse" type="button" onClick="validateUpdateDerv('delete');">
                        <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">DELETE all Selected</p></div>
                    </button>
                </td>
            </tr>
        </table>
    </div>
    <div class="clear_L"></div>
<div class="lineSpace_35">&nbsp;</div>
</div>
</form>

</body>
</html>

<script>


    function validateCheckbox()
    {
            for(var i=1;i<=document.getElementById('totalProdCount').value;i++)
            {
                    if(document.getElementById('productId_'+i+'').checked == true){
                            document.getElementById('checkBoxSanityCheck').innerHTML = "";
                            document.getElementById('checkBoxSanityCheck').style.display = 'none';
                            return true;
                        }else{
                            continue;
                    }
            }
            document.getElementById('checkBoxSanityCheck').innerHTML = "Please select a Derived Product !!";
            document.getElementById('checkBoxSanityCheck').style.display = 'inline';

            return false;
    }



    function validateUpdateDerv(changeStatus){
            var checkBoxSelChk = validateCheckbox();

            if(checkBoxSelChk){
                    var FLAG = true;
                    if(changeStatus == 'delete'){
                            var con = confirm('If you '+changeStatus+' the selected Derived Products, you will NOT be able to Active/Deactive them in Future!! Are you sure to continue?');
                            FLAG = con;
                    }
                    if(FLAG){
                            var con = confirm('This action will '+changeStatus+' all the selected Derived Products .Are you sure to continue?');
                            if(con){
                                    document.getElementById('updateType').value = changeStatus; 
                                    document.getElementById('FinalSanityCheck').innerHTML = "";
                                    document.getElementById('FinalSanityCheck').style.display = 'none';
                                    $('dervProdForm').submit();
                            }
                    }
                }else{
                    document.getElementById('FinalSanityCheck').innerHTML = "Please correct above fields in RED !!";
                    document.getElementById('FinalSanityCheck').style.display = 'inline';
            }
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

document.getElementById('currencySelected').value = 1;
changeRateForCurrencies();


</script>
