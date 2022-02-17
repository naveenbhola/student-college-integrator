<?php
    $headerComponents = array(
        'css'      =>        array('headerCms','raised_all','mainStyle','footer','cal_style'),
        'js'         =>            array('user','tooltip','common','newcommon','listing','prototype','CalendarPopup'),
        'jsFooter'         =>      array('prototype','scriptaculous','utils'),
        'title'      =>        'SUMS - Create Customized Quotation',
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
<style>
   .sums_rowbg_b {float:left;width:170px;line-height:15px;background-color:#ccc;padding:5px 5px;font-weight:bold;margin:0 0 0 10px;}
   .sums_row {float:left;width:170px;line-height:15px;padding:5px 5px;margin:0 0 0 10px;}
   .sums_srowbg_b {float:left;width:120px;line-height:15px;background-color:#ccc;padding:5px 5px;font-weight:bold;margin:0 0 0 10px;}
   .sums_srow {float:left;width:120px;line-height:15px;padding:5px 5px;margin:0 0 0 10px;}
   .sums_small_row_b {float:left;width:70px;line-height:15px;background-color:#ccc;padding:5px 5px;margin:0 0 0 10px;font-weight:bold}
   .sums_small_row {float:left;width:70px;line-height:15px;padding:5px 5px;margin:0 0 0 10px;}
</style>
<script>
    var cal = new CalendarPopup("calendardiv");
    cal.offsetX = 20;
    cal.offsetY = 0;
</script>

<?php if($Transaction != 2){
        $formAction = "/sums/Quotation/customizedQuoteSubmit/".$prodId;
    }else{
        $formAction = "/sums/Quotation/viewTransaction/-1/".$prodId;
    } 
?>

<form id="sumsProdForm" action="<?php echo $formAction; ?>" method="POST">
    <?php if (isset($Quotation)) {?>
       <input type="hidden" value="<?php echo $Quotation['QuotationDetails']['UIQuotationId'];?>" name="UIQuotationId">
       <?php } ?>
<?php if(isset($Transaction)) { ?>
<input type="hidden" value="<?php echo $Transaction; ?>" name="Transaction" >
<?php }?>

<div id="createQuotePart" class="mar_full_10p" style="display:inline;">
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
                    <input type="hidden" value="<?php echo $selectedUserDetails['userId'];?>" name="selectedUserId">
        <hr/>


        <div class="OrgangeFont fontSize_18p bld"><strong><?php if (isset ($Quotation)){ echo 'Edit';}else{echo 'Create';} ?> Customized Quotation</strong></div>
		 
        <div class="lineSpace_5">&nbsp;</div>

        <table width="100%" border="0" cellpadding="0" cellspacing="3" bordercolor="#EEEEEE">
            <tr>
                <td height="15" colspan="7" bgcolor="#99CCFF">&nbsp;&nbsp;&nbsp;<strong></strong></td>
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
                <td colspan="2" bgcolor="#FFFFFF">&nbsp;</td>
            </tr>
            <tr>
                <td height="20" colspan="7" bgcolor="#99CCFF">&nbsp;&nbsp;&nbsp;<strong>SELECT CURRENCY</strong></td>
            </tr>
                <tr>
                    <td width="74%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>SELECT CURRENCY FOR CREATING QUOTATION</strong> </td>
                    <td width="21%" valign="top" style="padding:5px 10px 0px 10px">
                        <select id="currSelected" name="currencySelected" onChange="javascript:formreset();">
                            <option value="">Select Currency</option>
                            <?php
                                    foreach($currencies as $CUR){
                                     	if ($Quotation['QuotationDetails']['CurrencyId']==$CUR['CurrencyId']) {
                                    		$selected = "selected";
                                    	} else {
                                    		$selected = "";
                                    	}
                                    	?>
                                <option value="<?php echo $CUR['CurrencyId']; ?>" <?php echo $selected; ?>><?php echo $CUR['CurrencyCode']." : ".$CUR['CurrencyName'];?></option>
                                <?php  }  ?>
                    </select></td>
                </tr>
            </table>



            <table width="100%" border="0" cellpadding="0" cellspacing="3" bordercolor="#EEEEEE">
                <tr>
                    <td height="30" colspan="7" bgcolor="#99CCFF">&nbsp;&nbsp;&nbsp;<strong>SELECT ONE OR MORE BASE PRODUCTS</strong></td>
                </tr>
                <tr>
                    <td width="30%" height="30" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>BASE PRODUCT</strong> </td>
                    <td width="8%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>QUANTITY</strong></td>
                    <td width="8%" valign="top"  bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>DURATION</strong></td>
                    <td width="5%" valign="top"  bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>FREE</strong></td>
                    <td width="15%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>SUGGESTED PRICE</strong></td>
                    <td width="15%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>SALE PRICE</strong></td>
                </tr>
                

                <?php $j=0; 
                    $optedBase = array();
                    $optedBase = $baseProdsOfDerived[$derivedProdId];
                ?>
                    <?php for($i=0;$i<10;$i++) { 
                            $selectedBase = "";
                            $baseQuantity = "";
                            $baseDuration = "";
                            $baseSuggestedPrice = "";
                            $baseManagementPrice = "";
                            $checked = false;
                            $FLAG = false;
                        ?>

                        <?php if(isset($baseProdsOfDerived)) {?>
                                <tr <?php if ($i>= count($optedBase)) echo "style='display:none'"; ?> id="baseProdDiv<?php echo $i;?>">
                        <?php }else{ ?>
                                <tr <?php if ($i>0) echo "style='display:none'"; ?> id="baseProdDiv<?php echo $i;?>">
                        <?php } ?>
                <td>
                    <select style="width:270px;" name="BaseProductId[]" id="BaseProduct_Id<?php echo $i;?>" onchange="javascript:showDurationType(<?php echo $i;?>);getSuggestedPrice(<?php echo $i;?>);">
                        <option value="">Select Base Product</option>

                        <?php foreach ($BaseProducts as $bp) { 
                            
                                if($bp['BaseProductId']==$optedBase[$j]['BaseProductId']){
                                    $selectedBase = "selected";
                                    $baseQuantity = $optedBase[$j]['BaseProdQuantity'];
                                    $baseDuration = $optedBase[$j]['BaseProdDurationInDays'];
                                    $baseSuggestedPrice = $optedBase[$j]['SuggestedPrice'];
                                    $baseManagementPrice = $optedBase[$j]['ManagementPrice'];
                                    if($baseManagementPrice == 0){
                                        $checked = true;
                                    }else{
                                        $checked = false;
                                    }
                                    $FLAG =true;

                                }else{
                                    $selectedBase = "";
                                }
                            
                            ?>

                            <option value="<?php echo $bp['BaseProductId']; ?>" <?php echo $selectedBase; ?> ><?php echo $bp['BaseProdCategory']." - ".$bp['BaseProdSubCategory'];?></option>
                        <?php } ?>
                    </select>
                </td>
                <td>
                    <input type="text" style="width:110px;" name="BaseProductQuantity[]" id="BaseProductQuantity<?php echo $i;?>" onblur="javascript:getSuggestedPrice(<?php echo $i;?>);" value="<?php if($baseQuantity !=''){ echo $baseQuantity; }else{ echo "1"; } ?>" />
                </td>
                <td>
                    <input type="text" style="width:60px;" name="BaseProductDuration[]" id="BaseProductDuration<?php echo $i;?>" onblur="javascript:getSuggestedPrice(<?php echo $i;?>);"  value="<?php echo $baseDuration; ?>"/>&nbsp;&nbsp;
                    <span id="durationType<?php echo $i;?>"></span>
                </td>
                <td>
                    <input type="checkbox" id="isFree<?php echo $i;?>" onclick="javascript:disablePrices(<?php echo $i;?>);" name="isFree<?php echo $i;?>" <?php if($checked) echo "checked"; ?>>
                </td>
                <td>
                    <input type="text" size="20" name="BaseProdSuggestedPrice[]" id="BaseProdSuggestedPrice<?php echo $i;?>" value="<?php echo $baseSuggestedPrice; ?>" readonly/>
                </td>
                <td>
                    <input type="text" size="20" name="BaseProdSalePrice[]" id="BaseProdSalePrice<?php echo $i;?>" onblur="javascript:validateAndCalculatePrice();" value="<?php echo $baseManagementPrice; ?>" <?php if($checked) echo "readOnly"; ?> />
                </td>
            </tr>
            <?php 
                if($FLAG){
                    $j++;
                }
            } ?>
            
        </table>
        
        <input type="button" value="Add Another Product" onclick="javascript:showProduct();" id="addProductBut">&nbsp;
        
        <br/>

        <div class="lineSpace_5">&nbsp;</div>
        <div id="currencySelectCheck" style="display:none;color:red;"></div><br/>
<!--
        <table width="100%" border="0" cellpadding="0" cellspacing="3" bordercolor="#EEEEEE">
            <tr>
                <td width="78%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>TOTAL DISCOUNT OFFERED</strong> </td>
                <td width="17%" valign="top" style="padding:5px 10px 0px 10px"><input type="text" size="20" id="discount_offered" value="<?php if(isset($Quotation['QuotationDetails']['TotalDiscount'])){ echo $Quotation['QuotationDetails']['TotalDiscount'];}else{echo '0';} ?>" onblur="validateAndCalculatePrice();" disabled/></td>
            </tr>
        </table>

        -->
        <div class="lineSpace_5">&nbsp;</div>
        <div id="saleSanityCheck" style="display:none;color:red;"></div><br/>
        <div id="quanSanityCheck" style="display:none;color:red;"></div><br/>
        
        <div class="lineSpace_5">&nbsp;</div>
        <div id="baseSanityCheck" style="display:none;color:red;"></div><br/>
        
        <div class="lineSpace_10">&nbsp;</div>
        <div>
            <div class="OrgangeFont fontSize_14p bld">Add Subscription Validity</div>
        </div>
        <div class="grayLine"></div>
        <div class="lineSpace_10">&nbsp;</div>
        <div>
            <div class="sums_rowbg_b">Property Name</div>
            <div class="sums_rowbg_b">Value</div>
            <div class="clear_L"></div>
        </div>
        <div class="lineSpace_10">&nbsp;</div>

                <?php $j=0; 
                    $optedProp = array();
                    $optedProp = $PropertiesOfThisDerived;
                ?>
                <?php for($i=0;$i<count($DerivedProductProperties);$i++) { 
                        $selectedProp = "";
                        $propertyValue = "";
                        $FLAG = false;
                    ?>
                    <?php if(isset($PropertiesOfThisDerived)){ ?>
                        <div <?php if ($i>=count($PropertiesOfThisDerived)) echo "style='display:none'"; ?> id="propDiv<?php echo $i;?>">
                        <?php }else{ 
                                  if($i==0){ ?>
                                 <div <?php echo "style=''"; ?> id="propDiv<?php echo $i;?>">
                            <?php }else{ ?>
                                 <div <?php echo "style='display:none'"; ?> id="propDiv<?php echo $i;?>">
                             <?php } 
                         } 
                     ?>

            <div class="sums_row">
                <select name="DerivedProductProp[]" id="DerivedProdProp<?php echo $i;?>" >
                    <option value="">Select Property</option>
                    <?php foreach ($DerivedProductProperties as $bp) {

                            if($bp['DerivedPropertyId']==$optedProp[$j]['DerivedPropertyId']){
                                $selectedProp = "selected";
                                $propertyValue = $optedProp[$j]['DerivedPropertyValue'];
                                $FLAG =true;
                                }else{
                                    $selectedBase = "";
                                }
                            ?>

                            <option value="<?php echo $bp['DerivedPropertyId'];?>" <?php //echo $selectedProp; ?> selected ><?php echo $bp['DerivedPropertyName'];?></option>
                    <?php } ?>
                </select><font style="color:red;"> * </font>
            </div>
            <div class="sums_row"><input id="propVal<?php echo $i;?>" type="text" name="DerivedProductPropValue[]" value="<?php echo $propertyValue; ?>" onblur="validateProperties();" /></div>
            <div class="clear_L"></div>
        </div>
        <?php 
                if($FLAG){
                    $j++;
                }
        } ?>
        <div class="sums_row" style="display:none;">
            <input type="button" value="Add New Property" onclick="javascript:showProperty();" id="addPropertyBut">
        </div>
        <div class="clear_L"></div>
        <div class="lineSpace_10">&nbsp;</div>
        <div id="propSanityCheck" style="display:none;color:red;"></div><br/>


            <table width="100%" border="0" cellpadding="0" cellspacing="3" bordercolor="#EEEEEE">
            <tr>
                <td colspan="7" bgcolor="#FFFFFF">&nbsp;</td>
            </tr>
            <tr>
                <td height="15" colspan="7" bgcolor="#99CCFF">&nbsp;&nbsp;&nbsp;<strong></strong></td>
            </tr>
            
	    
	    <tr>
                <td width="75%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>TOTAL SUGGESTED PRICE</strong> </td>
                <td width="21%" valign="top" style="padding:5px 10px 0px 10px"><input type="text" name="totalPrice" id="total_Price" size="20" maxlength="100" minlength="1" readonly value="<?php echo $Quotation['QuotationDetails']['TotalPrice'];?>" /></td>
            </tr>
            <tr style="display:none;">
                <td width="75%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>TOTAL DISCOUNT GIVEN</strong> </td>
                <td width="21%" valign="top" style="padding:5px 10px 0px 10px"><input type="text" name="totalDiscount" id="total_Discount" size="20" maxlength="100" minlength="1" readonly value="<?php echo $Quotation['QuotationDetails']['TotalDiscount'];?>" /></td>
            </tr>
            <tr>
                <td width="75%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>TOTAL SALE PRICE</strong> </td>
                <td width="21%" valign="top" style="padding:5px 10px 0px 10px"><input type="text" name="totalBasePrice" id="total_BasePrice" size="20" maxlength="100" minlength="1" readonly value="<?php echo $Quotation['QuotationDetails']['TotalBasePrice'];?>" /></td>
            </tr>
            <input type="hidden" value="<?php echo $countrycounter; ?>" id="countrycounter" name="countrycounter" > 
	    
	    
            <tr>
                <td width="75%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>NET AMOUNT</strong> </td>
                <td width="21%" valign="top" style="padding:5px 10px 0px 10px"><input type="text" name="netAmount" id="net_Amount" size="20" maxlength="100" minlength="1" readonly value="<?php echo $Quotation['QuotationDetails']['NetAmount'];?>" /></td>
            </tr>
            <tr>
                <td width="75%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>ROUND OFF AMOUNT</strong> </td>
                <td width="21%" valign="top" style="padding:5px 10px 0px 10px"><input type="text" name="roundOffAmount" id="roundOff_Amount" size="20" maxlength="100" minlength="1" readonly value="<?php echo $Quotation['QuotationDetails']['RoundOffAmount'];?>" /></td>
            </tr>
            <tr>
                <td width="75%" valign="top" bgcolor="#EEEEEE" style="padding:5px 10px 0px 10px"><strong>FINAL SALES AMOUNT</strong> </td>
                <td width="21%" valign="top" style="padding:5px 10px 0px 10px"><input type="text" name="finalSalesAmount" id="finalSales_Amount" size="20" maxlength="100" minlength="1" readonly value="<?php echo $Quotation['QuotationDetails']['FinalSalesAmount'];?>" /></td>
            </tr>
        </table>
     <!--   <div id="discountSanityCheck" style="display:none;color:red;"></div><br/>
     <div class="lineSpace_5">&nbsp;</div> -->
        <div id="FinalQuoteCheck" style="display:none;color:red;"></div><br/>

        <div class="lineSpace_10">&nbsp;</div>

        <div id="hiddenVars"></div>

        <table width="98%" border="0" cellpadding="0" cellspacing="3" bordercolor="#EEEEEE">
            <tr>
		 <?php if (isset ($Quotation)) { ?>
		 <td>
		    <button class="btn-submit7 w7" name="editCourse" id="edit_Course" value="" type="button" onClick="showQuotationHistory();">
		       <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">View Quotation History</p></div>
		    </button>
		 </td>
		 <?php } ?>
                <td height="20" align="center">
                    <button class="btn-submit7 w7" name="editQuote" id="edit_Quote" value="" type="button" onClick="validateQuoteAndSubmit();">
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
var setvariable = 'false';

var propDivCount = 0;
var propDivMaxCount = <?php echo count($DerivedProductProperties); ?>;
function showProperty()
{
	$('propDiv'+propDivCount).style.display = "";
	propDivCount++;
	if (propDivCount == propDivMaxCount)
	{
		$('addPropertyBut').disabled = "true";
	}
}


var productCount;
<?php if(isset($baseProdsOfDerived[$derivedProdId])){ ?>
productCount = <?php echo count($baseProdsOfDerived[$derivedProdId]); ?>;
<?php }else{ ?>
        productCount = 1;
<?php } ?>

var productMaxCount = 10;
function showProduct()
{
	$('baseProdDiv'+productCount).style.display = "";
	productCount++;
	if (productCount == productMaxCount)
	{
		$('addProductBut').disabled = "true";
	}
}


function showDurationType(id)
{     
        setvariable = 'true';
	var FLAG = false; 
        if(id != 0){

                for(i=0;i<10;i++)
                {
                        if(i != id)
                        {
                                if(document.getElementById('BaseProduct_Id'+i).value != '')
                                {
                                        if(document.getElementById('BaseProduct_Id'+i).value == document.getElementById('BaseProduct_Id'+id).value)
                                        {
                                                document.getElementById('baseSanityCheck').innerHTML = "You have already selected this Base-Product, Please choose another!!";
                                                document.getElementById('baseSanityCheck').style.display = 'inline';
                                                document.getElementById('BaseProduct_Id'+id).value = '';
                                                return false;
                                        }
                                }
                        }
                }
                        
                valId = id;
                for(i=0;i<valId;i++){
                        if(document.getElementById('BaseProduct_Id'+i).value == ''){
                                document.getElementById('baseSanityCheck').innerHTML = "Please select a Base-product which is left blank above instead of this!!";
                                document.getElementById('baseSanityCheck').style.display = 'inline';
                                document.getElementById('BaseProduct_Id'+valId).value = '';
                                return false;
                            }else{
                                FLAG = true;
                        }
                }
                if(FLAG==true){
                        document.getElementById('baseSanityCheck').innerHTML = "";
                        document.getElementById('baseSanityCheck').style.display = 'none';
                }
        }

        var url = "/sums/Product/getDurationType/"+$('BaseProduct_Id'+id).value;
        new Ajax.Updater('durationType'+id,url);
    }


function getSuggestedPrice(id)
{
    setvariable = 'true';
    if ($('currSelected').value!=""){
            document.getElementById('currencySelectCheck').innerHTML = "";
            document.getElementById('currencySelectCheck').style.display = 'none';
		if ($('isFree'+id).checked!=true)
		{
			var url = "/sums/Product/getSuggestedPrice";
                        var data = "BaseProductId="+$('BaseProduct_Id'+id).value+"&CurrencyId="+$('currSelected').value+"&Duration="+$('BaseProductDuration'+id).value+"&Quantity="+$('BaseProductQuantity'+id).value;
                        new Ajax.Request(url,{method:'post',parameters:(data),onSuccess:function(response){
                                    var retArr = eval(response.responseText);
                                    if ($('isFree'+id).checked!=true)
                                    {
                                            $('BaseProdSuggestedPrice'+id).value=retArr[0];
                                    }
                                    validateAndCalculatePrice();
                            }
                            });
                }
        }else{
            document.getElementById('currencySelectCheck').innerHTML = "Please Select a Currency!!";
            document.getElementById('currencySelectCheck').style.display = 'inline';
    }
}


function disablePrices(id)
{
		if ($('isFree'+id).checked==true)
		{
                        $('BaseProdSuggestedPrice'+id).value = "0";
                        $('BaseProdSuggestedPrice'+id).setAttribute('readOnly','true');
                        $('BaseProdSalePrice'+id).value = "0";
                        $('BaseProdSalePrice'+id).setAttribute('readOnly','true');
		}
		else
		{       
                        $('BaseProduct_Id'+id).value = "";
                        $('BaseProductQuantity'+id).value = "";
                        $('BaseProductDuration'+id).value = "";
                        $('BaseProdSuggestedPrice'+id).value = "";
                        $('BaseProdSuggestedPrice'+id).setAttribute('readOnly','true');
                        $('BaseProdSalePrice'+id).value = "";
                        $('BaseProdSalePrice'+id).removeAttribute('readOnly');
		}
                validateAndCalculatePrice();
}


function validateQuoteAndSubmit(){
        var salePriceCheck = validateSalePrice();
        var propertyCheck = validateProperties();
        var quoteUserChk = quoteByChk();
        var quanSaneCheck = validateBaseQuant();
	setvariable = 'true';

        if(salePriceCheck && propertyCheck && quoteUserChk && quanSaneCheck){
                document.getElementById('FinalQuoteCheck').innerHTML = "";
                document.getElementById('FinalQuoteCheck').style.display = 'none';
                createHiddenVals();
                $('sumsProdForm').submit();
            }else{
                document.getElementById('FinalQuoteCheck').innerHTML = "Please correct above fields in RED !!";
                document.getElementById('FinalQuoteCheck').style.display = 'inline';
        }
}


function validateSalePrice(){
        var FLAG=false;
        for(var i=0;i<=9;i++){
                if(document.getElementById('BaseProduct_Id'+i).value != ''){
                        if(document.getElementById('BaseProdSalePrice'+i).value != ''){
                                var filter = /^\d+(?:\.\d{0,2})?$/;
                                if(!filter.test(document.getElementById('BaseProdSalePrice'+i).value)){
                                        document.getElementById('saleSanityCheck').innerHTML = "Please input Sale-Amount as valid amount eg. 123.45!!";
                                        document.getElementById('saleSanityCheck').style.display = 'inline';
                                        return false;
                                    }else{
                                        FLAG = true;
                                }
                            }else{
                                document.getElementById('saleSanityCheck').innerHTML = "Please Fill the Sale-Price Value for selected Product!! ";
                                document.getElementById('saleSanityCheck').style.display = 'inline';
                                return false;
                        }
                }
        }
        if(FLAG == true){
                document.getElementById('saleSanityCheck').innerHTML = "";
                document.getElementById('saleSanityCheck').style.display = 'none';
                return true;
            }else{
                document.getElementById('saleSanityCheck').innerHTML = "Please input Sale-Amount as valid amount eg. 123.45!!";
                document.getElementById('saleSanityCheck').style.display = 'inline';
                return false;
        }
}

var editcustomizevar = "<?php echo $editcustomizevar; ?>";

function formreset()
{
    if(setvariable == 'true'  )
    {
    alert("This page will be reloaded as selected currency being changed");
    window.location.reload();
    //validateAndCalculatePrice();
    }
    
    else if(editcustomizevar == 'true')
    {	
	alert("The currency has been changed. Please verify this amount");
	validateAndCalculatePrice();
	//calculateQuotePrices();
    }
    
    
    
}

function validateBaseQuant(){
        setvariable = 'true';
	var FLAG=false;
        for(var i=0;i<=9;i++){
                if(document.getElementById('BaseProduct_Id'+i).value != ''){
                        if(document.getElementById('BaseProductQuantity'+i).value != ''){
                                var filter = /^(\d)+$/;
                                if(!filter.test(document.getElementById('BaseProductQuantity'+i).value)){
                                        document.getElementById('quanSanityCheck').innerHTML = "Please input Quantity as valid positive Number(Integer)!!";
                                        document.getElementById('quanSanityCheck').style.display = 'inline';
                                        return false;
                                    }else{
                                        FLAG = true;
                                }
                            }else{
                                document.getElementById('quanSanityCheck').innerHTML = "Please Fill the Quantity for All the chosen Base-Products!! ";
                                document.getElementById('quanSanityCheck').style.display = 'inline';
                                return false;
                        }
                }
        }

        if(FLAG == true){
                document.getElementById('quanSanityCheck').innerHTML = "";
                document.getElementById('quanSanityCheck').style.display = 'none';
                return true;
            }else{
                document.getElementById('quanSanityCheck').innerHTML = "Please input Quantity as valid positive Number(Integer)!!";
                document.getElementById('quanSanityCheck').style.display = 'inline';
                return false;
        }
}


function validateAndCalculatePrice(){
       // setvariable = 'true';
	var saleFLAG =  validateSalePrice();
        if(saleFLAG){
                resetQuotePrices();
                calculateQuotePrices();
        }
}


var totalPrice=0;
var totalSalePrice=0;
var totalDiscount=0;
var totalBasePrice=0;
var serviceTaxVal=0;
var serviceTax=0;
var netAmount=0;
var roundOffAmount=0;
var finalSalesAmount=0;


function calculateQuotePrices(){
        for(var i=0;i<productCount;i++)
        {
                if( (document.getElementById('BaseProduct_Id'+i).value != '') && (document.getElementById('BaseProdSalePrice'+i).value != '') ){
                        totalPrice += parseFloat(document.getElementById('BaseProdSuggestedPrice'+i+'').value);
                        totalSalePrice += parseFloat(document.getElementById('BaseProdSalePrice'+i+'').value);
                }
        }
	
        //totalDiscount = parseFloat(document.getElementById('discount_offered').value);
        totalDiscount = totalPrice - totalSalePrice;
        totalBasePrice = totalSalePrice;
	       
	var selectedCurrencyhere = document.getElementById('currSelected').value;
	
		    var phpvar = "<?php echo $countrycounter; ?>";
		
		    if(selectedCurrencyhere == 2 && phpvar == 'true') 
		    {
			    serviceTaxVal = 0;
		    }
		    else
		    {
			   serviceTaxVal = "<?php echo $parameters['Service_Tax']['parameterValue']; ?>";
		    }

	
	serviceTax = Math.round(( ((totalBasePrice) * serviceTaxVal) /100)*100)/100;
        netAmount = Math.round((totalBasePrice + serviceTax)*100)/100;
        finalSalesAmount = Math.floor(netAmount);
        roundOffAmount = Math.round((netAmount - finalSalesAmount)*100)/100 ;

        document.getElementById('total_Price').value = Math.round(totalPrice*100)/100;
        document.getElementById('total_Discount').value = Math.round(totalDiscount*100)/100;
        document.getElementById('total_BasePrice').value = Math.round(totalBasePrice*100)/100;
       // document.getElementById('service_Tax').value = serviceTax;
        document.getElementById('net_Amount').value = netAmount;
        document.getElementById('roundOff_Amount').value = roundOffAmount;
        document.getElementById('finalSales_Amount').value = finalSalesAmount;
}

function resetQuotePrices(){
        totalPrice = 0;
        totalSalePrice=0;
        totalDiscount=0;
        totalBasePrice=0;
        serviceTax=0;
        netAmount=0;
        roundOffAmount=0;
        finalSalesAmount=0;
}

function createHiddenVals()
{
    createHiddenElement('totalCurrency',1);
    setvariable = 'true';
    var totalProdCount = 0;
    for(var i=0;i<10;i++){
            if(document.getElementById('BaseProduct_Id'+i).value != ''){
                    totalProdCount +=1; 
            }
    }
    createHiddenElement('totalProduct',totalProdCount);
    
    var totalPropCount = 0;
    for(var i=0;i<<?php echo count($DerivedProductProperties); ?>;i++){
                    if(document.getElementById('DerivedProdProp'+i).value != ''){
                            totalPropCount +=1; 
                    }
    }
    createHiddenElement('totalProperties',totalPropCount);
}


function createHiddenElement(elename,elevalue)
{
	setvariable = 'true';
	var obj = document.createElement('input');
	obj.type = "hidden";
	obj.name = elename;
	obj.value = elevalue;
	$('hiddenVars').appendChild(obj);
}

function validateProperties()
{
    setvariable = 'true';
    var propSelect = document.getElementById('DerivedProdProp0').value;
    if(propSelect == ''){
            document.getElementById('propSanityCheck').innerHTML = "Please choose a Property!!";
            document.getElementById('propSanityCheck').style.display = 'inline';
            return false;
    }
    var Val = document.getElementById('propVal0').value;
    var filter = /^(\d)+$/;
    if(!filter.test(Val)){
            document.getElementById('propSanityCheck').innerHTML = "Please input a Number (Positive Integer) for Property!!";
            document.getElementById('propSanityCheck').style.display = 'inline';
            return false;
    }
    document.getElementById('propSanityCheck').innerHTML = "";
    document.getElementById('propSanityCheck').style.display = 'none';
    return true;
}

function quoteByChk(){
       
       setvariable = 'true';
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

</script>
