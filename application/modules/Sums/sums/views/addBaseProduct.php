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
   .sums_rowbg {float:left;width:170px;line-height:15px;background-color:#ccc;padding:5px 5px;margin:0 10px}
   .sums_row {float:left;width:170px;line-height:15px;padding:5px 5px;margin:0 0 0 10px;}
</style>
<form method="POST" action="/sums/Product/submitAddBase/<?php echo $prodId; ?>">
<div style="line-height:10px">&nbsp;</div>
<div class="mar_full_10p">
   <div style="width:223px; float:left">
	 <?php


$leftPanelViewValue = 'leftPanelFor' . $prodId;
$this->load->view('sums/' . $leftPanelViewValue);
?>
   </div>
   <div style="margin-left:233px">
      <div class="OrgangeFont fontSize_14p bld">Define Base Product</div>
      <div style="float:left; width:100%">
	 <div class="grayLine"></div>
	 <div class="lineSpace_10">&nbsp;</div>
	 <div>
	    <div class=" bld txt_align_r fontSize_12p float_L" style="width:191px">Product Category:<span class="redcolor">*</span></div>
	    <div class="row" style="margin-left:200px">
	    	<select name="BaseProdCategory" id="BaseProdCategory">
	    		<option value="">Select Product Category</option>
	    		<option value="Listing">Listing</option>
                <option value="Keywords-Featured Panel">Keywords-Featured Panel</option>
                <option value="Keywords-Sponsored Listing">Keywords-Sponsored Listing</option>
	    		<option value="Category Pages">Category Pages</option>
	    		<option value="Leads">Leads</option>
                <option value="Google-Ads">Google-Ads</option>
                <option value="Banner Campaign">Banner Campaign</option>
                <option value="Mass-Mailer">Mass-Mailer</option>
                <option value="Mass-SMS">Mass-SMS</option>
                <option value="Education-Fair">Education-Fair</option>
                <option value="SMS- Keyword">SMS- Keyword</option>
                <option value="Featured-Links">Featured-Links</option>
                <option value="Category-Sponsor">Category-Sponsor</option>
				<option value="Lead-Search">Lead-Search</option>
				<option value="Lead-Porting">Lead-Porting</option>
				<option value="SA-ConsultantProfileQuery">SA-ConsultantProfileQuery</option>	
				<option value="Voice SMS">Voice SMS</option>	
	    	</select>
	    </div>
	    
		<div id="BaseProdCategory_error" style="margin-left:200px;display:none;color:red;"> </div>
	 </div>
	 <div class="lineSpace_10">&nbsp;</div>
	 <div>
	    <div class=" bld txt_align_r fontSize_12p float_L" style="width:191px">Name:</div>
	    <div class="row" style="margin-left:200px"><input type="text" name="BaseProdSubCategory" id="BaseProdSubCategory"/></div>
	    <div id="BaseProdSubCategory_error" style="margin-left:200px;display:none;color:red;"> </div>
	    
	 </div>
	 <div class="lineSpace_10">&nbsp;</div>
	 <div>
	    <div class=" bld txt_align_r fontSize_12p float_L" style="width:191px">Description:</div>
	    <div class="row" style="margin-left:200px"><textarea style="width:200px; height:60px" name="Description" id="Description"></textarea></div>
	    <div id="Description_error" style="margin-left:200px;display:none;color:red;"> </div>
	   
	 </div>
	 <div class="lineSpace_10">&nbsp;</div>
	 
	 <div class="lineSpace_10">&nbsp;</div>
	 <div>
	    <div class=" bld txt_align_r fontSize_12p float_L" style="width:191px">Fundamental  Quantity:</div>
	    <div class="row" style="margin-left:200px"><input type="text" name="Base_Quantity" id="Base_Quantity" value="1"/></div>
	    <div id="Base_Quantity_error" style="margin-left:200px;display:none;color:red;"> </div>
	    
	 </div>
	 <div class="lineSpace_10">&nbsp;</div>
	 <div>
	    <div class=" bld txt_align_r fontSize_12p float_L" style="width:191px">Fundamental Duration:</div>
	    <div class="row" style="margin-left:200px">
	    	<input type="text" name="Base_Duration_Days" id="Base_Duration_Days" value="1"/>
	    	<select name="Base_Duration_Type">
	    		<option value="Day">Day</option>
	    		<option value="Month">Month</option>
	    		<option value="Year">Year</option>
	    	</select>
	    </div>
	    <div id="Base_Duration_Days_error" style="margin-left:200px;display:none;color:red;"> </div>
	    
	 </div>
	 <div class="lineSpace_10">&nbsp;</div>
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

	 <?php for($i=0;$i<count($BaseProductProperties);$i++) { ?>
	 <div <?php if ($i>2) echo "style='display:none'"; ?> id="propDiv<?php echo $i;?>">
	    <div class="sums_row">
	    	<select name="BaseProductProp[]" id="BaseProductProp<?php echo $i;?>" onchange="javascript:validatePropertyDuplicacy(<?php echo $i;?>);">
	    		<option value="">Select Property</option>
	    		<?php foreach ($BaseProductProperties as $bp) { ?>
				<option value="<?php echo $bp['BasePropertyId'];?>"><?php echo $bp['BasePropertyName'];?></option>
	    		<?php } ?>
	    	</select>
	    </div>
	    <div class="sums_row"><input type="text" name="BaseProductPropValue[]" id="BaseProductPropValue<?php echo $i;?>" /></div>
	    <div class="clear_L"></div>
	 </div>
	 <?php } ?>

	 <div class="sums_row">
	    <input type="button" value="Add New Property" onclick="javascript:showProperty();" id="addPropertyBut">
	 </div>
	 <div class="clear_L"></div>
	 <div id="BaseProductProp_error" style="margin-left:20px;display:none;color:red;"> </div>
	 <div class="lineSpace_10">&nbsp;</div>
	 <div class="lineSpace_10">&nbsp;</div>

	 <div>
	    <div class="OrgangeFont fontSize_14p bld">Create Price Index</div>
	 </div>
	 <div class="grayLine"></div>
	 <?php for($j=0;$j<count($Currencies);$j++) { ?>
	 <div id="basePrice<?php echo $j; ?>">
		 <div class="lineSpace_10">&nbsp;</div>
		 <div>
		    <div class=" bld txt_align_r fontSize_12p float_L" style="width:191px">Rate:</div>
		    <div style="margin-left:200px">
		    	<input type="text" name="Rate[]" id="Rate_<?php echo $j?>"/>
		    	<select name="currency[]">
		    		<option value="<?php echo $Currencies[$j]['CurrencyId'];?>"><?php echo $Currencies[$j]['CurrencyCode']; ?></option>
		    	</select>
		    </div>
		    <div id="Rate_<?php echo $j; ?>_error" style="margin-left:200px;display:none;color:red;"> </div>
		    
		 </div>
		 <div class="lineSpace_10">&nbsp;</div>
		 <div>
		    <div class="sums_rowbg_b" style="width:22%">Quantity (End)</div>
		    <div class="sums_rowbg_b" style="width:22%">Duration (End)</div>
		    <div class="sums_rowbg_b" style="width:22%">Discount Coefficient</div>
		    <div class="sums_rowbg_b" style="width:22%">Discount exponential factor</div>
		    <div class="clear_L"></div>
		 </div>
		 <?php for ($i=0;$i<30;$i++) { ?>
		 <div <?php if ($i>2) echo "style='display:none'"; ?> id="basePriceRow<?php echo $j,$i;?>">
		    <div class="sums_row" style="width:22%"><input name="quantity<?php echo $j;?>[]" id="quantity<?php echo $j."_".$i;?>" type="text" size="15"/></div>
		    <div class="sums_row" style="width:22%"><input name="duration<?php echo $j;?>[]" id="duration<?php echo $j."_".$i;?>" type="text" size="15" /></div>
		    <div class="sums_row" style="width:22%"><input name="discountC<?php echo $j;?>[]" id="discountC<?php echo $j."_".$i;?>" type="text" size="15" /></div>
		    <div class="sums_row" style="width:22%"><input name="discountE<?php echo $j;?>[]" id="discountE<?php echo $j."_".$i;?>" type="text" size="15" /></div>
		    <div class="clear_L"></div>
		 </div>
		 <?php } ?>
		 <div id="BPI_<?php echo $j; ?>_error" style="margin-left:20px;display:none;color:red;"> </div>
		
		 <div>
		    <div class="sums_row"><input type="button" value="Add New Range" onclick="javascript:showBasePriceRow(<?php echo $j; ?>)" id="addNewRange<?php echo $j;?>"/></div>
		    <div class="clear_L"></div>
		 </div>
		 <div class="lineSpace_10">&nbsp;</div>
	 </div>
	 <?php } ?>
	 
	 <div>
	    <div class="sums_row"><input type="button" value="Add Price Index" onclick="javascript:showBasePriceIndex();" id="addNewIndex"/></div>
	    <div class="clear_L"></div>
	 </div>
	 
	 <div class="grayLine"></div>
      </div>
   </div>
   <div class="clear_L"></div>
</div>
<div class="lineSpace_10">&nbsp;</div>
<div class="txt_align_c">
   <input type="submit" value="Submit" onclick="javascript:return validateFields()" />
</div>
<div class="lineSpace_28">&nbsp;</div>
<div id="hiddenVars"></div>
</form>
<script>
var currencyCount = <?php echo count($Currencies); ?>;
var propDivCount = 3;
var propDivMaxCount = <?php echo count($BaseProductProperties); ?>;
function showProperty()
{
	$('propDiv'+propDivCount).style.display = "";
	propDivCount++;
	if (propDivCount == propDivMaxCount)
	{
		$('addPropertyBut').disabled = "true";
	}
}

var basePriceArr = new Array ();
var maxBasePriceIndex = <?php echo count($Currencies); ?>;
var basePriceIndex = 1;
var maxBasePriceRows = 30;
for (i=0;i<maxBasePriceIndex;i++)
{
	basePriceArr[i] = new Array();
	basePriceArr[i]['rowDiv'] = 3;
}

function showBasePriceRow(rowNo)
{
	$('basePriceRow'+rowNo+basePriceArr[rowNo]['rowDiv']).style.display = "";
	basePriceArr[rowNo]['rowDiv']++;
	if (basePriceArr[rowNo]['rowDiv'] == maxBasePriceRows)
	{
		$('addNewRange'+rowNo).disabled = "true";
	}
}

function showBasePriceIndex()
{
	$('basePrice'+basePriceIndex).style.display = "";
	basePriceIndex++;
	if (basePriceIndex == maxBasePriceIndex)
	{
		$('addNewIndex').disabled = "true";
	}
}

function createHiddenVals()
{
	createHiddenElement('totalProperties',propDivCount);
	createHiddenElement('totalBasePriceIndex',basePriceIndex);
	for(i=0;i<basePriceIndex;i++)
	{
		createHiddenElement('basePriceRows'+i,basePriceArr[i]['rowDiv']);
	}
}

function createHiddenElement(elename,elevalue)
{
	var obj = document.createElement('input');
	obj.type = "hidden";
	obj.name = elename;
	obj.value = elevalue;
	$('hiddenVars').appendChild(obj);

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

function validateFields()
{
	var errorMessage='';
	var filterInteger = /^(\d)+$/;	
	if(trim(document.getElementById("BaseProdCategory").value)=='')
	{
		errorMessage="Please enter the Base Product Category";
		showError(errorMessage,"BaseProdCategory");
		return false;
	}
	else
	{
		hideError('BaseProdCategory');
		if(trim(document.getElementById("BaseProdSubCategory").value)=='')
		{
			errorMessage="Please enter the Base Product Name";
			showError(errorMessage,"BaseProdSubCategory");
			return false;
		}
		else
		{
			hideError('BaseProdSubCategory');
			if(trim(document.getElementById("Description").value)=='')
			{
				errorMessage="Please enter the Base Product Description";
				showError(errorMessage,"Description");
				return false;
			}
			else
			{
				hideError('Description');
				if(trim(document.getElementById("Base_Quantity").value)=='')
				{
					errorMessage="Please enter the Base Quantity";
					showError(errorMessage,"Base_Quantity");
					return false;
				}
				else
				{
					hideError('Base_Quantity');
					if(trim(document.getElementById("Base_Duration_Days").value)=='')
					{
						errorMessage="Please enter the Base Duration in Days";
						showError(errorMessage,"Base_Duration_Days");
						return false;
					}
					else
					{
						hideError('Base_Duration_Days');
						for(i=0;i<propDivMaxCount;i++)
                {
                        
                                if(document.getElementById('BaseProductProp'+i).value != '')
                                {
                                        if(document.getElementById('BaseProductPropValue'+i).value == "")
                                        {
                                                errorMessage = "Please Provide Value for "+document.getElementById('BaseProductProp'+i).options[document.getElementById('BaseProductProp'+i).selectedIndex].text;
                                                showError(errorMessage,"BaseProductProp");
                                                return false;
                                        }
                                        else
                                        {
                                        	hideError("BaseProductProp");
                                        }
                                }
                        
                }
						for(var i=0;i<currencyCount;i++)
						{
							if(trim(document.getElementById("Rate_"+i).value)=='')
							{
								errorMessage="Please enter the Rate";
								showError(errorMessage,"Rate_"+i);
								return false;
							}
							else
							{
								hideError("Rate_"+i);
							}
							for(var j=0;j<30;j++)
							{
								if(j<1)
								{ 
								if(((trim(document.getElementById("quantity"+i+"_"+j).value)=='') && (trim(document.getElementById("duration"+i+"_"+j).value)=='')) || (trim(document.getElementById("discountC"+i+"_"+j).value)=='') || (trim(document.getElementById("discountE"+i+"_"+j).value)==''))
								{ 
									errorMessage="Please Enter the BPI";
									showError(errorMessage,"BPI_"+i);
									return false;
								}
								else
								{
									hideError("BPI_"+i);
								}
								}							
							}
						}
						
					}
				}
			}
		}
		createHiddenVals();
		return true;	
	}
}

function validatePropertyDuplicacy(id)
{     
        var FLAG = false; 
        if(id != 0){

                for(i=0;i<propDivMaxCount;i++)
                {
                        if(i != id)
                        {
                                if(document.getElementById('BaseProductProp'+i).value != '')
                                {
                                        if(document.getElementById('BaseProductProp'+i).value == document.getElementById('BaseProductProp'+id).value)
                                        {
                                                errorMessage = "You have already selected this Property, Please choose another!!";
                                                showError(errorMessage,"BaseProductProp");
                                                document.getElementById('BaseProductProp'+id).value = '';
                                                return false;
                                        }
                                }
                        }
                }
                        
                valId = id;
                for(i=0;i<valId;i++){
                        if(document.getElementById('BaseProductProp'+i).value == ''){
                                errorMessage= "Please select a Property which is left blank above instead of this!!";
                                showError(errorMessage,"BaseProductProp");
                                document.getElementById('BaseProductProp'+valId).value = '';
                                return false;
                            }else{
                                hideError("BaseProductProp");
                        }
                }
        }
    }


</script>

</body>
</html>
