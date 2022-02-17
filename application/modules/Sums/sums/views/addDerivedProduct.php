<?php
$headerComponents = array (
	'css' => array (
		'headerCms',
		'raised_all',
		'mainStyle',
		'footer'
	),
	'js' => array (
		'user',
		'tooltip',
		'common',
		'newcommon',
		'listing',
		'prototype',
		'scriptaculous',
		'utils'
	),
	'title' => 'SUMS - Add Base Product',
	'product' => '',
	'displayname' => (isset ($sumsUserInfo['validity'][0]['displayname']) ? $sumsUserInfo['validity'][0]['displayname'] : ""),
	
);
$this->load->view('enterprise/headerCMS', $headerComponents);
$this->load->view('enterprise/cmsTabs', $sumsUserInfo);
?>
<style>
   .sums_rowbg_b {float:left;width:170px;line-height:15px;background-color:#ccc;padding:5px 5px;font-weight:bold;margin:0 0 0 10px;}
   .sums_row {float:left;width:170px;line-height:15px;padding:5px 5px;margin:0 0 0 10px;}
   .sums_srowbg_b {float:left;width:120px;line-height:15px;background-color:#ccc;padding:5px 5px;font-weight:bold;margin:0 0 0 10px;}
   .sums_srow {float:left;width:120px;line-height:15px;padding:5px 5px;margin:0 0 0 10px;}
   .sums_small_row_b {float:left;width:70px;line-height:15px;background-color:#ccc;padding:5px 5px;margin:0 0 0 10px;font-weight:bold}
   .sums_small_row {float:left;width:70px;line-height:15px;padding:5px 5px;margin:0 0 0 10px;}
</style>
<form action="/sums/Product/addDerivedSubmit/<?php echo $prodId; ?>" method="POST">
<div style="line-height:10px">&nbsp;</div>
<div class="mar_full_10p">
			<div style="width:223px; float:left">
					<?php

$leftPanelViewValue = 'leftPanelFor' . $prodId;
$this->load->view('sums/' . $leftPanelViewValue);
?>
			</div>
			<div style="margin-left:233px">
					<div class="OrgangeFont fontSize_14p bld">Add Derived Products </div>
					<div style="float:left; width:100%">
						<div class="grayLine"></div>
						<div class="lineSpace_10">&nbsp;</div>
						<div>
								<div class=" bld txt_align_r fontSize_12p float_L" style="width:191px">Product Name:</div>
								<div style="margin-left:200px"><input type="text" name="DerivedProductName" id="DerivedProductName"/></div>
								<div id="DerivedProductName_error" style="margin-left:200px;display:none;color:red;"> </div>		
						</div>
						<div class="lineSpace_10">&nbsp;</div>
						<div>
								<div class=" bld txt_align_r fontSize_12p float_L" style="width:191px">Description:</div>
								<div style="margin-left:200px"><textarea style="width:200px; height:60px" name="Description" id="Description"></textarea></div>
								<div id="Description_error" style="margin-left:200px;display:none;color:red;"> </div>		
						</div>
						<div class="lineSpace_10">&nbsp;</div>
						<div>
								<div class=" bld txt_align_r fontSize_12p float_L" style="width:191px">Category:</div>
								<div style="margin-left:200px">
									<select name="Category" id="Category">
										<option value="">Select Category</option>
										<?php foreach ($Categories as $cats) { ?>
										<option value="<?php echo $cats['CategoryId'];?>"><?php echo $cats['CategoryName'];?></option>
										<?php } ?>
									</select>
								</div>
								<div id="Category_error" style="margin-left:200px;display:none;color:red;"> </div>		
						</div>
						<div class="lineSpace_10">&nbsp;</div>
						<div class="OrgangeFont fontSize_14p bld">Select Products</div>
						<div class="grayLine"></div>
						<div class="lineSpace_10">&nbsp;</div>
						<div>
							<div class="sums_srowbg_b">Base Product</div>
							<div class="sums_srowbg_b">Quantity</div>
							<div class="sums_srowbg_b">Duration</div>
							<div class="sums_small_row_b">Free</div>
							<?php for($j=0;$j<count($Currencies);$j++) { ?>
							<div class="sums_rowbg_b" id="priceDiv<?php echo $j;?>" >Price
								<select name="currency[]" id="CurrencyId<?php echo $j;?>">
						    		<option value="<?php echo $Currencies[$j]['CurrencyId'];?>"><?php echo $Currencies[$j]['CurrencyCode']; ?></option>
						    	</select><br/>Suggested&nbsp;&nbsp;|&nbsp;Mangement
							</div>
							<?php } ?>
							<div class="clear_L"></div>
						</div>
						<?php for($i=0;$i<10;$i++) { ?>
						<div <?php if ($i>0) echo "style='display:none'"; ?> id="baseProdDiv<?php echo $i;?>">
							<div class="sums_srow">
								<select style="width:120px;" name="BaseProductId[]" id="BaseProductId<?php echo $i;?>" onchange="javascript:showDurationType(<?php echo $i;?>);">
									<option value="">Select Base Product</option>
									<?php foreach ($BaseProducts as $bp) { ?>
										<option value="<?php echo $bp['BaseProductId']; ?>"><?php echo $bp['BaseProdCategory']." - ".$bp['BaseProdSubCategory'];?></option>
									<?php } ?>
								</select>
							</div>
							<div class="sums_srow">
								<input type="text" style="width:110px;" name="BaseProductQuantity[]" id="BaseProductQuantity<?php echo $i;?>" onblur="javascript:getSuggestedPrice(<?php echo $i;?>);" />
							</div>
							<div class="sums_srow">
								<input type="text" style="width:60px;" name="BaseProductDuration[]" id="BaseProductDuration<?php echo $i;?>" onblur="javascript:getSuggestedPrice(<?php echo $i;?>);" />&nbsp;&nbsp;
								<span id="durationType<?php echo $i;?>"></span>
							</div>
							<div class="sums_small_row">
								<input type="checkbox" id="isFree<?php echo $i;?>" onclick="javascript:disablePrices(<?php echo $i;?>);" name="isFree<?php echo $i;?>">
							</div>
							<?php for($j=0;$j<count($Currencies);$j++) { ?>
							<div class="sums_row" id="priceRowDiv<?php echo $i,$j;?>">
								<input type="text" style="width:60px;" name="sugPrice<?php echo $j;?>[]" id="sugPrice<?php echo $i,$j;?>" readonly />&nbsp;
								<input type="text" style="width:60px;" name="manPrice<?php echo $j;?>[]" id="manPrice<?php echo $i,$j;?>" onblur="javascript:calcuateTotalManagementPrice();"/>
							</div>
							<?php } ?>
							<div class="clear_L"></div>
						</div>
						<?php } ?>		
						<div class="grayLine"></div>
						<div>
							<div class="sums_srow">&nbsp;</div>
							<div class="sums_srow">&nbsp;</div>
							<div class="sums_small_row">&nbsp;</div>
							<div class="sums_srow bld">&nbsp;Total Price</div>
							<?php for($j=0;$j<count($Currencies);$j++) { ?>
							<div class="sums_row" id="priceTotalDiv<?php echo $j;?>">
								<input type="text" style="width:60px;" name="sugTotalPrice<?php echo $j;?>" id="sugPrice<?php echo $j;?>" readonly />&nbsp;
								<input type="text" style="width:60px;" name="manTotalPrice<?php echo $j;?>" id="manPrice<?php echo $j;?>" readonly />
							</div>
							<?php } ?>
							<div class="clear_L"></div>
						</div>
						<div class="mar_full_10p">
							<input type="button" value="Add Another Product" onclick="javascript:showProduct();" id="addProductBut">&nbsp;
							<input type="button" value="Add Another Currency" onclick="javascript:showPrice();" id="addPriceBut">
						</div>
						<div id="BaseProd_error" style="display:none;color:red;"> </div>
						<div class="lineSpace_10">&nbsp;</div>
						<div class="lineSpace_10">&nbsp;</div>
						<div>
								<div class="bld txt_align_r fontSize_12p float_L" style="width:191px">Other Settings:</div>
								<div class="" style="margin-left:200px">
									<div><input type="checkbox" name="online" value="YES"/>Available Online</div>
									<div><input type="checkbox" name="offline" value="YES"/>Available Offline</div>
									<div><input type="checkbox" name="customizable" value="YES"/>Is Customizable</div>
								</div>
						</div>
						<div class="lineSpace_10">&nbsp;</div>
						<div>
							<div class="OrgangeFont fontSize_14p bld">Add Product Property</div>
						</div>
						<div class="grayLine"></div>
						<div class="lineSpace_10">&nbsp;</div>
						<div>
							<div class="sums_rowbg_b">Property Name</div>
							<div class="sums_rowbg_b">Value</div>
						<div class="clear_L"></div>
						</div>
						<div class="lineSpace_10">&nbsp;</div>

						<?php for($i=0;$i<count($DerivedProductProperties);$i++) { ?>
						<div <?php if ($i>2) echo "style='display:none'"; ?> id="propDiv<?php echo $i;?>">
						<div class="sums_row">
							<select name="DerivedProductProp[]" id="DerivedProductProp<?php echo $i?>">
								<option value="">Select Property</option>
								<?php foreach ($DerivedProductProperties as $bp) { ?>
								<option value="<?php echo $bp['DerivedPropertyId'];?>"><?php echo $bp['DerivedPropertyName'];?></option>
								<?php } ?>
							</select>
						</div>
						<div class="sums_row"><input type="text" name="DerivedProductPropValue[]" id="propVal<?php echo $i?>"/></div>
						<div class="clear_L"></div>
						</div>
						<?php } ?>
						<div class="sums_row">
						    <input type="button" value="Add New Property" onclick="javascript:showProperty();" id="addPropertyBut">
						</div>
						<div class="clear_L"></div>
						<div id="Property_error" style="margin-left:200px;display:none;color:red;"> </div>
						<div class="lineSpace_10">&nbsp;</div>
						<div class="grayLine"></div>
						<div id="hiddenVars"></div>
						<div class="lineSpace_10">&nbsp;</div>
						<div class="sums_row">
							<input type="submit" value="Submit" onclick="return validateform();">
						</div>
						<div class="clear_L"></div>
						<div class="lineSpace_28">&nbsp;</div>
					</div>
			</div>
</div>
</form>
<div class="lineSpace_35">&nbsp;</div>
<script>
var propDivCount = 1;
var propDivMaxCount = <?php echo count($DerivedProductProperties); ?>;
function showProperty()
{
	try{
		$('propDiv'+propDivCount).style.display = "";
		propDivCount++;
		if (propDivCount == propDivMaxCount)
		{
			$('addPropertyBut').disabled = "true";
		}
	}catch(e){}

}

if(propDivMaxCount == 1)
{
	$('addPropertyBut').disabled = true;
}

var productCount = 1;
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

var priceCount = 1;
var maxPriceCount = <?php echo count($Currencies); ?>;
function showPrice()
{
	$('priceDiv'+priceCount).style.display = "";
	$('priceTotalDiv'+priceCount).style.display = "";
	for(i=0;i<productMaxCount;i++)
	{
		$('priceRowDiv'+i+priceCount).style.display = "";
	}
	priceCount++;
	if (priceCount == maxPriceCount)
	{
		$('addPriceBut').disabled = "true";
	}
}

function showError(errorMsg,type)
{
	var type_error= type + '_error';
	document.getElementById(type_error).innerHTML = errorMsg;
	document.getElementById(type_error).style.display = 'inline';
	document.getElementById(type_error).style.color = "red";
}

function hideError(type)
{
	var type_error= type + '_error';
	document.getElementById(type_error).style.display='none';
}

function showDurationType(id)
{
        if(id != 0){

                for(i=0;i<10;i++)
                {
                        if(i != id)
                        {
                                if(document.getElementById('BaseProductId'+i).value != '')
                                {
                                        if(document.getElementById('BaseProductId'+i).value == document.getElementById('BaseProductId'+id).value)
                                        {
                                                errorMsg = "You have already selected this Base-Product, Please choose another!!";
                                                document.getElementById('BaseProductId'+id).value = '';
                                                showError(errorMsg,"BaseProd");
                                                return false;
                                        }
                                }
                        }
                }
                        
                valId = id;
                for(i=0;i<valId;i++){
                        if(document.getElementById('BaseProductId'+i).value == ''){
                                errorMsg = "Please select a Base-product which is left blank above instead of this!!";
                                document.getElementById('BaseProductId'+valId).value = '';
                                showError(errorMsg,"BaseProd");
                                return false;
                            }else{
                                hideError("BaseProd");
                        }
                }
                hideError("BaseProd");
        }
	var url = "/sums/Product/getDurationType/"+$('BaseProductId'+id).value;
	new Ajax.Updater('durationType'+id,url);
}

function getSuggestedPrice(id)
{
	for(i=0;i<=priceCount;i++)
	{
		if ($('isFree'+id).checked!=true)
		{
			if($('BaseProductId'+id).value!='')
			{ 
			var url = "/sums/Product/getSuggestedPrice";
			var data = "BaseProductId="+$('BaseProductId'+id).value+"&CurrencyId="+$('CurrencyId'+i).value+"&Duration="+$('BaseProductDuration'+id).value+"&Quantity="+$('BaseProductQuantity'+id).value+"&id=sugPrice"+id+i;
			new Ajax.Request(url,{method:'post',parameters:(data),onSuccess:setPrice });
			}
			else
			{
				errorMsg="Please select Base Product";
				showError(errorMsg,"BaseProd");
			}
		}
	}

}

function setPrice(response)
{
	var retArr = eval(response.responseText);
	var id = retArr[1];
	id = id.substring((id.length-2),(id.length-1));
	if ($('isFree'+id).checked!=true)
	{
		$(retArr[1]).value=retArr[0];
	}
	calcuateTotalSuggestedPrice();
}

function calcuateTotalSuggestedPrice()
{
	for(i=0;i<maxPriceCount;i++)
	{
		var totalPrice = 0;
		for(j=0;j<productCount;j++)
		{
			if ($('isFree'+j).checked!=true)
			{
				totalPrice += ($('sugPrice'+j+i).value)*1;
			}
		}
		$('sugPrice'+i).value = (totalPrice);
	}
}

function calcuateTotalManagementPrice()
{
	for(i=0;i<maxPriceCount;i++)
	{
		var totalPrice = 0;
		for(j=0;j<productCount;j++)
		{
			if ($('isFree'+j).checked!=true)
			{
				totalPrice += ($('manPrice'+j+i).value)*1;
			}
		}
		$('manPrice'+i).value = (totalPrice);
	}
}

function createHiddenVals()
{
	createHiddenElement('totalCurrency',priceCount);
	createHiddenElement('totalProduct',productCount);
	createHiddenElement('totalProperties',propDivCount);
}

function createHiddenElement(elename,elevalue)
{
	var obj = document.createElement('input');
	obj.type = "hidden";
	obj.name = elename;
	obj.value = elevalue;
	$('hiddenVars').appendChild(obj);

}

function disablePrices(id)
{
	for(i=0;i<maxPriceCount;i++)
	{
		if ($('isFree'+id).checked==true)
		{
			$('manPrice'+id+i).value = "0";
			$('sugPrice'+id+i).value = "0";
			$('sugPrice'+id+i).disabled = "true";
			$('manPrice'+id+i).disabled = "true";
		}
		else
		{
			$('sugPrice'+id+i).disabled = "";
			$('manPrice'+id+i).disabled = "";
		}
	}
	getSuggestedPrice(id);
	calcuateTotalSuggestedPrice();
	calcuateTotalManagementPrice();
}
function validateBaseQuant(){
      var chosen=0;
        for(var i=0;i<=9;i++){
                if(document.getElementById('BaseProductId'+i).value != ''){
                   chosen++;
                        if(document.getElementById('BaseProductQuantity'+i).value != ''){
                                var filter = /^(\d)+$/;
                                if(!filter.test(document.getElementById('BaseProductQuantity'+i).value)){
                                        errorMsg = "Please input Quantity as valid positive Number(Integer)!!";
                                        showError(errorMsg,"BaseProd");
                                        return false;
                                    }else{
                                        hideError("BaseProd");
                                }
                            }else{
                                errorMsg = "Please Fill the Quantity for All the chosen Base-Products!! ";
                                showError(errorMsg,"BaseProd");
                                return false;
                        }
                }
        }
		if(chosen==0)
		{
			errorMsg="Please choose at least a Base Product";
			showError(errorMsg,"BaseProd");
			return false;	
		}
        hideError("BaseProd");
        return true;
}

function validateProperties()
{
    var propSelect = document.getElementById('DerivedProductProp0').value;
    if(propSelect == ''){
            errorMsg= "Please choose a Property!!";
			showError(errorMsg,"Property");
            return false;
    }
    var Val = document.getElementById('propVal0').value;
    var filter = /^(\d)+$/;
    if(!filter.test(Val)){
            errorMsg = "Please input a Number (Positive Integer) for Property!!";
            showError(errorMsg,"Property");
            return false;
    }
	hideError("Property");
    return true;
}

function validateform()
{
	if(trim(document.getElementById("DerivedProductName").value)=='')
	{
		errorMessage="Please enter the Derived Product Name";
		showError(errorMessage,"DerivedProductName");
		return false;
	}
	else
	{
		hideError('DerivedProductName');
		if(trim(document.getElementById("Description").value)=='')
	{
		errorMessage="Please enter the Description";
		showError(errorMessage,"Description");
		return false;
	}
	else
	{
		hideError('Description');
	}
	if(trim(document.getElementById("Category").value)=='')
	{
		errorMessage="Please enter the Category";
		showError(errorMessage,"Category");
		return false;
	}
	else
	{
		hideError('Category');
		if((!validateBaseQuant()) || (!validateProperties()))
		{
			return false;
		}
	}
	}
	createHiddenVals();
	return true;
}
</script>
</body>
</html>
