<?php 
	$viewParameters = array();
	switch($queueType)
	{
		case 'MANAGER': 	
			$viewParameters['pageHeading']	= 'Search High-Discount Sales Deal';	
			break;
		case 'FINANCE':
			$viewParameters['pageHeading']	= 'Search transactions to validate';
			break;
		case 'OPS':	
			$viewParameters['pageHeading']	= 'Search transactions to create subscription';
			break;
		case 'View':
			$viewParameters['pageHeading']	= 'View/Cancel transactions';
			break;
		case 'subsView':
			$viewParameters['pageHeading']	= 'Edit Subscriptions Validity';
			break;
	}
	if($viewTrans == 'editPayment'){
		$viewParameters['pageHeading']	= 'View/Edit Payment';
	}
	
?>
<?php	
    $headerComponents = array(
        'css'      =>        array('headerCms','raised_all','mainStyle','footer'),
        'js'         =>            array('user','tooltip','common','newcommon','prototype','CalendarPopup'),
        'jsFooter'         =>      array('scriptaculous','utils'),
        'title'      =>        'SUMS - '.$viewParameters['pageHeading'].'' ,
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
	$this->load->view('common/calendardiv');		
?>
<script>
   var cal = new CalendarPopup("calendardiv");
   cal.offsetX = -150;
   cal.offsetY = 20;
</script>
<div id="dataLoaderPanel" style="position:absolute;display:none">
    <img src="/public/images/loader.gif"/>
</div>

<div class="mar_full_10p">
    <div style="width:223px; float:left">
        <?php 
		$leftPanelViewValue = 'leftPanelFor'.$prodId;
		$this->load->view('sums/'.$leftPanelViewValue); 
	?>
    </div>
    <div style="margin-left:233px">
        <div class="lineSpace_10p">&nbsp;</div>
        <div class="bld fontSize_14p OrgangeFont" style="padding-left:10px;"><?php echo  $viewParameters['pageHeading']; ?></div>
        <div class="grayLine"></div>
        <div class="lineSpace_10">&nbsp;</div>

        <div style="display:inline;float:left;width:100%">
        <form id="formForSearchTransacts" action="" method="POST">
            <input type="hidden" name="queueType" value="<?php echo $queueType; ?>" />
	    <input type="hidden" name="flowType" value="<?php echo $flowType; ?>" />	
            <div class="row">
                <div style="display:inline;float:left;width:100%">
                    <div class="r1_1 bld">TRANSACTION ID:&nbsp;&nbsp;</div>
                    <div class="r2_2">
                        <input type="text" name="transactionId" id="transactionId" size="30" maxlength="125" minlength="5" caption="Transaction Id"/>
                    </div>
                    <div class="clear_L"></div>
                    <div class="row errorPlace" style="margin-top:2px;">
                        <div class="r1_1">&nbsp;</div>
                        <div class="r2_2 errorMsg" id= "transactionId_error"></div>
                        <div class="clear_L"></div>
                    </div>
                </div>
            </div>
            <div class="lineSpace_10">&nbsp;</div>

            <div class="row">
                <div style="display:inline;float:left;width:100%">
                    <div class="r1_1 bld">QUOTATION ID:&nbsp;&nbsp;</div>
                    <div class="r2_2">
                        <input type="text" name="uiQuotationId" id="uiQuotationId" size="30" maxlength="125" minlength="5" caption="Quotation Id"/>
                    </div>
                    <div class="clear_L"></div>
                    <div class="row errorPlace" style="margin-top:2px;">
                        <div class="r1_1">&nbsp;</div>
                        <div class="r2_2 errorMsg" id= "uiQuotationId_error"></div>
                        <div class="clear_L"></div>
                    </div>
                </div>
            </div>
            <div class="lineSpace_10">&nbsp;</div>
		
	<?php if($viewTrans == 'editPayment'){ ?>	
	    <div class="row">
                <div style="display:inline;float:left;width:100%">
                    <div class="r1_1 bld">PAYMENT ID:&nbsp;&nbsp;</div>
                    <div class="r2_2">
                        <input type="text" name="paymentId" id="paymentId" size="30" maxlength="125" minlength="5" caption="paymentId Id"/>
                    </div>
                    <div class="clear_L"></div>
                    <div class="row errorPlace" style="margin-top:2px;">
                        <div class="r1_1">&nbsp;</div>
                        <div class="r2_2 errorMsg" id= "paymentId_error"></div>
                        <div class="clear_L"></div>
                    </div>
                </div>
            </div>
            <div class="lineSpace_10">&nbsp;</div>
	<?php } ?>

            <div class="row">
                <div style="display: inline; float:left; width:100%">
                    <div class="r1_1 bld">TRANSACTION DATE BETWEEN:&nbsp;&nbsp;</div>
                    <div class="float_L">
                        <input type="text" readonly id="subs_start_date" name="trans_start_date" onclick="cal.select($('subs_start_date'),'sd','yyyy-MM-dd');"><img src="/public/images/eventIcon.gif" style="cursor:pointer" align="absmiddle" id="sd" onClick="cal.select($('subs_start_date'),'sd','yyyy-MM-dd');" />
                        <b> AND </b>
                        <input type="text" readonly id="subs_end_date" name="trans_end_date" onclick="cal.select($('subs_end_date'),'ed','yyyy-MM-dd');"><img src="/public/images/eventIcon.gif" style="cursor:pointer" align="absmiddle" id="ed" onClick="cal.select($('subs_end_date'),'ed','yyyy-MM-dd');" />
                    </div>
                    <div class="clear_L"></div>
                </div>
            </div>
            <div class="lineSpace_10">&nbsp;</div>

            <div class="row">
                <div style="display: inline; float:left; width:100%">
                    <div class="r1_1 bld">CLIENT-ID:&nbsp;&nbsp;</div>
                    <div class="r2_2">
                        <input name="clientId" id="clientId" type="text" size="30" maxlength="100" minlength="3" caption="Client ID" />
                    </div>
                    <div class="clear_L"></div>
                    <div class="row errorPlace" style="margin-top:2px;">
                        <div class="r1_1">&nbsp;</div>
                        <div class="r2_2 errorMsg" id= "clientId_error"></div>
                    </div>
                </div>
            </div>
            <div class="lineSpace_10">&nbsp;</div>

            <div class="row">
                <div style="display: inline; float:left; width:100%">
                    <div class="r1_1 bld">ENTERPRISE CONTACT NAME:&nbsp;&nbsp;</div>
                    <div class="r2_2">
                        <input name="contactName" id="contactName" type="text" size="30" maxlength="25" minlength="3" caption="Contact Name" />
                    </div>
                    <div class="clear_L"></div>
                    <div class="row errorPlace" style="margin-top:2px;">
                        <div class="r1_1">&nbsp;</div>
                        <div class="r2_2 errorMsg" id= "contactName_error"></div>
                    </div>
                </div>
            </div>
            <div class="lineSpace_10">&nbsp;</div>

            <div class="row">
                <div style="display: inline; float:left; width:100%">
                    <div class="r1_1 bld">COLLEGE/ INSTITUTE/ UNIVERSITY NAME:&nbsp;&nbsp;</div>
                    <div class="r2_2">
                        <input name="collegeName" id="collegeName" type="text" size="30" maxlength="25" minlength="3" caption="College Name" />
                    </div>
                    <div class="clear_L"></div>
                    <div class="row errorPlace" style="margin-top:2px;">
                        <div class="r1_1">&nbsp;</div>
                        <div class="r2_2 errorMsg" id= "collegeName_error"></div>
                    </div>
                </div>
            </div>
            <div class="lineSpace_10">&nbsp;</div>
            
            <div class="row">
                <div style="display: inline; float:left; width:100%">
                    <div class="r1_1 bld">SALE BY:&nbsp;&nbsp;</div>
                    <div class="r2_2">
                        <input name="saleBy" id="saleBy" type="text" size="30" maxlength="25" minlength="3" caption="Sale By" />
                    </div>
                    <div class="clear_L"></div>
                    <div class="row errorPlace" style="margin-top:2px;">
                        <div class="r1_1">&nbsp;</div>
                        <div class="r2_2 errorMsg" id= "saleBy_error"></div>
                    </div>
                </div>
            </div>
            <div class="lineSpace_10">&nbsp;</div>

            <?php if(($viewTrans == 'viewTrans') || ($queueType == 'FINANCE') || ($viewTrans == 'editPayment')){ ?>		
            <div class="row">
                <div style="display: inline; float:left; width:100%">
                    <div class="r1_1 bld">CHEQUE NO.:&nbsp;&nbsp;</div>
                    <div class="r2_2">
                        <input name="chequeNo" id="chequeNo" type="text" size="30" maxlength="25" minlength="3" caption="Sale By" />
                    </div>
                    <div class="clear_L"></div>
                    <div class="row errorPlace" style="margin-top:2px;">
                        <div class="r1_1">&nbsp;</div>
                        <div class="r2_2 errorMsg" id= "chequeNo_error"></div>
                    </div>
                </div>
            </div>
            <div class="lineSpace_10">&nbsp;</div>
	    <?php } ?>

            <?php if(($viewTrans == 'viewTrans') || ($queueType == 'FINANCE') || ($viewTrans == 'editPayment')){ ?>		
            <div class="row">
                <div style="display: inline; float:left; width:100%">
                    <div class="r1_1 bld">SALE TYPE:&nbsp;&nbsp;</div>
                    <div class="r2_2 bld ">
                        Credit<input value="Credit" id="saleType_C" name="saleType[]" type="checkbox" checked />
                        Trial<input value="Trial" id="saleType_T" name="saleType[]" type="checkbox" checked />
                        Full<input value="Full Payment" id="saleType_F" name="saleType[]" type="checkbox" checked />
                        Part<input value="Part Payment" id="saleType_P" name="saleType[]" type="checkbox" checked />
                    </div>
                    <div class="clear_L"></div>
                    <div class="row errorPlace" style="margin-top:2px;">
                        <div class="r1_1">&nbsp;</div>
                        <div class="r2_2 errorMsg" id= "saleType_error"></div>
                    </div>
                </div>
            </div>
            <div class="lineSpace_10">&nbsp;</div>
	    <?php } ?>



            <div class="row">
                <div style="display: inline; float:left; width:100%">
                    <div class="r1_1 bld">SALE AMOUNT BETWEEN:&nbsp;&nbsp;</div>
                    <div class="float_L">
                        <input name="saleFloorAmount" id="saleFloorAmount" type="text" maxlength="25" minlength="3" caption="Sale Floor Amount" /> <b> AND </b>
                        <input name="saleCeilAmount" id="saleCeilAmount" type="text" maxlength="25" minlength="3" caption="Sale Ceiling Amount" />
                    </div>
                    <div class="clear_L"></div>
                </div>
            </div>
            <div class="lineSpace_10">&nbsp;</div>

            <div class="row">
                <div style="display: inline; float:left; width:100%">
                    <div class="r1_1 bld">DISCOUNT AMOUNT BETWEEN(in %):&nbsp;&nbsp;</div>
                    <div class="float_L">
                        <input name="discFloorAmount" id="discFloorAmount" type="text" maxlength="25" minlength="3" caption="Discount Floor Amount" /> <b> AND </b>
                        <input name="discCeilAmount" id="discCeilAmount" type="text" maxlength="25" minlength="3" caption="Discount Ceil Amount" />
                    </div>
                    <div class="clear_L"></div>
                </div>
            </div>
            <div class="lineSpace_10">&nbsp;</div>

            <?php if($viewTrans == 'viewTrans'){ ?>		
	    <div class="row">
                <div style="display: inline; float:left; width:100%">
                    <div class="r1_1 bld">PAYMENT DUE DATE BETWEEN:&nbsp;&nbsp;</div>
                    <div class="float_L">
                        <input type="text" readonly id="payment_start_date" name="payment_start_date" onclick="cal.select($('payment_start_date'),'paymentsd','yyyy-MM-dd');"><img src="/public/images/eventIcon.gif" style="cursor:pointer" align="absmiddle" id="paymentsd" onClick="cal.select($('payment_start_date'),'paymentsd','yyyy-MM-dd');" />
                        <b> AND </b>
                        <input type="text" readonly id="payment_end_date" name="payment_end_date" onclick="cal.select($('payment_end_date'),'paymented','yyyy-MM-dd');"><img src="/public/images/eventIcon.gif" style="cursor:pointer" align="absmiddle" id="paymented" onClick="cal.select($('payment_end_date'),'paymented','yyyy-MM-dd');" />
                    </div>
                    <div class="clear_L"></div>
                </div>
            </div>
            <div class="lineSpace_10">&nbsp;</div>
	    <?php } ?>
		
	    <?php if($viewTrans == 'editPayment'){ ?>		
            <div class="row">
                <div style="display: inline; float:left; width:100%">
                    <div class="r1_1 bld">Status:&nbsp;&nbsp;</div>
                    <div class="r2_2 bld ">
                        <select name="Payment_Status[]" id="Payment_Status" MULTIPLE size="3">
			<option value="Un-paid">Un-paid</option>
			<option value="In-process">In-process</option>
			<option value="Paid">Paid</option>
	    		</select>
                    </div>
                    <div class="clear_L"></div>
                    <div class="row errorPlace" style="margin-top:2px;">
                        <div class="r1_1">&nbsp;</div>
                        <div class="r2_2 errorMsg" id= "saleType_error"></div>
                    </div>
                </div>
            </div>
            <div class="lineSpace_10">&nbsp;</div>
	    <?php } ?>	
	
            <div class="r1_1 bld">&nbsp;</div>
            <div class="r2_2">
                <button class="btn-submit19" id="searchButton" onclick="validateQuoteUsers();" type="button" value="" style="width:100px">
                    <div class="btn-submit19"><p style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:12px" class="btn-submit20">Search</p></div>
                </button>
            </div>
            <div class="clear_L"></div>
            <div class="lineSpace_10">&nbsp;</div>
            <div id="dateSanityCheck" style="display:none;color:red;"></div><br/>
            <div id="saleSanityCheck" style="display:none;color:red;"></div><br/>
            <div id="discSanityCheck" style="display:none;color:red;"></div><br/>
            <div id="paymentSanityCheck" style="display:none;color:red;"></div><br/>
            <hr/>
            <div id="FinalSanityCheck" style="display:none;color:red;"></div><br/>
        </form>
        </div>
        <div class="clear_L"></div>
        
        <form method="POST" id="frmSelectTransact" action="">
            <div id="populateTransacts">
           <img src="/public/images/space.gif" width="115" height="100" />
           </div>
        </form>
    
    </div>
    <div class="lineSpace_35">&nbsp;</div>

</div>
    <br />
    <br />
</body>
</html>
<?php 
	if($viewTrans == -1){
		$searchTransactionUrl = "/sums/Manage/getTransactionsToValidate/".$prodId;
	}else{
		$searchTransactionUrl = "/sums/Manage/getTransactions/".$prodId;
	}
?>
<script>
    var viewTrans = '<?php echo $viewTrans; ?>';		
    function validatePayementFormSums(isDetailed){
		$('checkSelect_error').innerText = '';
		var noOfRes = $('noOfRes').value;
		var flag = 0;
		var temp = -1;
		if(isDetailed == 'undefined')
		{
			isDetailed = false;
		}		
		for(var i=1;i<noOfRes;i++)
		{
			if(isDetailed){
				temp = -1;
			}					
			
			if($('payment_'+i).checked==true){
				flag = 1;
				if((temp != -1) && (parseInt($('clientId_'+i).value) != parseInt($('clientId_'+temp).value))){
					$('checkSelect_error').innerText = 'Can not make a payment for multiple client.';
					return false;
				}
				temp = i;  	
			}
		}
		if(flag == 0)
		{
			$('checkSelect_error').innerText = 'Please select at least one checkbox to make payment.';
			return false;
		}
		$('frmSelectTransact').submit();
		return true;
	}
    function validateQuoteUsers(){
            <?php if($viewTrans=="viewTrans"){ ?>
            var blankChk = validateBlank();
            if(!blankChk){
                    return false;
            }
            validatePayDates();
            <?php } ?>
            var subDateChk = validateSubDates();
            var saleAmtChk = validateSaleAmount();
            var saleDiscChk = validateDiscount();
            if(subDateChk && saleAmtChk && saleDiscChk){
                    document.getElementById('FinalSanityCheck').innerText = "";
                    document.getElementById('FinalSanityCheck').style.display = 'none';
		    if((typeof(viewTrans) != 'undefined') && (viewTrans != 'editPayment')){	
                    	new Ajax.Updater('populateTransacts','<?php echo $searchTransactionUrl; ?>',{onBeforeAjax:beforeAjax(),parameters:Form.serialize($('formForSearchTransacts')),onComplete:function(){
                                hideDataLoader($('populateTransacts'));
                    }}); return false;
		   }else{
			new Ajax.Updater('populateTransacts','/sums/Manage/getPayments/<?php echo $prodId; ?>',{onBeforeAjax:beforeAjax(),parameters:Form.serialize($('formForSearchTransacts')),onComplete:function(){
                                hideDataLoader($('populateTransacts'));
                    }}); return false;	
		   }	
                    $('formForSearchTransacts').submit();
                }else{
                    document.getElementById('FinalSanityCheck').innerText = "Please correct above fields in RED !!";
                    document.getElementById('FinalSanityCheck').style.display = 'inline';
            }
    }

    function beforeAjax(){
        $('populateTransacts').innerText = " "; 
        showDataLoader($('populateTransacts'));
    }

    function validateFormSums(){
            radioSelChk = validateradio();
            if(radioSelChk){
                    $('frmSelectTransact').submit();
            }
    }

    function validateFormSumsSubs(){
            var checkBoxSelChk = validateCheckBox();
            if(checkBoxSelChk){
                    $('frmSelectTransact').submit();
            }
    }

    function validateradio()
    {
            for(var i=1;i<=document.getElementById('totalUserCount').value;i++)
            {
                if(document.getElementById('userNo_'+i+'').checked == true){
                    document.getElementById('radio_unselect_error').innerText = "";
                    document.getElementById('radio_unselect_error').style.display = 'none';
                    if(document.getElementById('subs_start_date'+i) != undefined){
                        if(document.getElementById('subs_start_date'+i).value == '')
                        {
                            document.getElementById('radio_unselect_error').innerText = "Please select a start date to create subscription.";
                            document.getElementById('radio_unselect_error').style.display = 'inline';
                            return false;
                        }			
                    }
                    return true;
                }else{
                    continue;
                }
            }
            document.getElementById('radio_unselect_error').innerText = "Please select a Transaction to continue !!";
            document.getElementById('radio_unselect_error').style.display = 'inline';
            return false;
    }

    function validateCheckBox()
    {
            var checkedFlag = false;
            for(var i=1;i<=document.getElementById('totalUserCount').value;i++)
            {
                    if(document.getElementById('userNo_'+i+'').checked == true){
                            document.getElementById('checkbox_unselect_error').innerText = "";
                            document.getElementById('checkbox_unselect_error').style.display = 'none';
                            if(document.getElementById('subs_start_date'+i).value == '')
                            {
                                    document.getElementById('checkbox_unselect_error').innerText = 'Please select a start date for Transaction ID '+document.getElementById('transId_'+i).value+' and Derived Product "'+document.getElementById('derProdName_'+i).value+'"';
                                    document.getElementById('checkbox_unselect_error').style.display = 'inline';
                                    return false;
                            }
                            checkedFlag = true;
                        }else{
                            continue;
                    }
            }
            if(!checkedFlag){
                    document.getElementById('checkbox_unselect_error').innerText = "Please select a Transaction to continue !!";
                    document.getElementById('checkbox_unselect_error').style.display = 'inline';
                    return false;
                }else{
                    return true;
            }
    }

           function validateSubDates(){
                   var subStartDate = document.getElementById('subs_start_date').value;
                   var startStr = subStartDate.split('-');
                   var startYear = startStr[0];
                   var startMonth = startStr[1]%13;
                   var startDate = startStr[2]%32;

                   var startDateUTC = Date.UTC(startYear,startMonth,startDate);

                   var subEndDate = document.getElementById('subs_end_date').value;
                   var endStr = subEndDate.split('-');
                   var endYear = endStr[0];
                   var endMonth = endStr[1]%13;
                   var endDate = endStr[2]%32;
                   
                   if((subEndDate=='' && subStartDate != '') || (subStartDate=='' && subEndDate != '')){ 
                           document.getElementById('dateSanityCheck').innerText = "Please select both Start as well as End dates !!";
                           document.getElementById('dateSanityCheck').style.display = 'inline';
                           return false;
                       }else{
                           document.getElementById('dateSanityCheck').innerText = "";
                           document.getElementById('dateSanityCheck').style.display = 'none';
                   }

                   var endDateUTC = Date.UTC(endYear,endMonth,endDate);
                   if(subStartDate != '' && subEndDate != ''){
                           if(startDateUTC > endDateUTC){
                                   document.getElementById('dateSanityCheck').innerText = "Please select Start Date to be less than End Date !!";
                                   document.getElementById('dateSanityCheck').style.display = 'inline';
                                   return false;
                               }else{
                                   document.getElementById('dateSanityCheck').innerText = "";
                                   document.getElementById('dateSanityCheck').style.display = 'none';
                                   return true;
                           }
                       }else{
                           document.getElementById('dateSanityCheck').innerText = "";
                           document.getElementById('dateSanityCheck').style.display = 'none';
                           return true;
                   }
           }

           function validateSaleAmount() {
                   var startSaleAmount = parseFloat($('saleFloorAmount').value);
                   var endSaleAmount = parseFloat($('saleCeilAmount').value);
                   if((startSaleAmount=='' && endSaleAmount!= '') || (endSaleAmount=='' && startSaleAmount!= '')){ 
                           document.getElementById('saleSanityCheck').innerText = "Please select both initial as well as Final Sales Amounts !!";
                           document.getElementById('saleSanityCheck').style.display = 'inline';
                           return false;
                       }else{
                           document.getElementById('saleSanityCheck').innerText = "";
                           document.getElementById('saleSanityCheck').style.display = 'none';
                   }
                   if(startSaleAmount!= '' && endSaleAmount!= ''){
                           if(startSaleAmount> endSaleAmount){
                                   document.getElementById('saleSanityCheck').innerText = "Please select initial sale amount to be less than Final Amount !!";
                                   document.getElementById('saleSanityCheck').style.display = 'inline';
                                   return false;
                               }else{
                                   document.getElementById('saleSanityCheck').innerText = "";
                                   document.getElementById('saleSanityCheck').style.display = 'none';
                                   return true;
                           }
                       }else{
                           document.getElementById('saleSanityCheck').innerText = "";
                           document.getElementById('saleSanityCheck').style.display = 'none';
                           return true;
                   }
           }

           function validateDiscount() {
                   var startDiscAmount = parseFloat($('discFloorAmount').value);
                   var endDiscAmount = parseFloat($('discCeilAmount').value);
                   if((startDiscAmount=='' && endDiscAmount!= '') || (endDiscAmount=='' && startDiscAmount!= '')){ 
                           document.getElementById('discSanityCheck').innerText = "Please select both lower as well as Upper Discount Amounts !!";
                           document.getElementById('discSanityCheck').style.display = 'inline';
                           return false;
                       }else{
                           document.getElementById('discSanityCheck').innerText = "";
                           document.getElementById('discSanityCheck').style.display = 'none';
                   }
                   if(startDiscAmount!= '' && endDiscAmount!= ''){
                           if(startDiscAmount> endDiscAmount){
                                   document.getElementById('discSanityCheck').innerText = "Please select lower amount to be less than Upper Amount !!";
                                   document.getElementById('discSanityCheck').style.display = 'inline';
                                   return false;
                               }else{
                                   document.getElementById('discSanityCheck').innerText = "";
                                   document.getElementById('discSanityCheck').style.display = 'none';
                                   return true;
                           }
                       }else{
                           document.getElementById('discSanityCheck').innerText = "";
                           document.getElementById('discSanityCheck').style.display = 'none';
                           return true;
                   }
           }
           
           function validatePayDates(){
                   var subStartDate = document.getElementById('payment_start_date').value;
                   var startStr = subStartDate.split('-');
                   var startYear = startStr[0];
                   var startMonth = startStr[1]%13;
                   var startDate = startStr[2]%32;

                   var startDateUTC = Date.UTC(startYear,startMonth,startDate);

                   var subEndDate = document.getElementById('payment_end_date').value;
                   var endStr = subEndDate.split('-');
                   var endYear = endStr[0];
                   var endMonth = endStr[1]%13;
                   var endDate = endStr[2]%32;
                   
                   if((subEndDate=='' && subStartDate != '') || (subStartDate=='' && subEndDate != '')){ 
                           document.getElementById('paymentSanityCheck').innerText = "Please select both Payment Start as well as End dates !!";
                           document.getElementById('paymentSanityCheck').style.display = 'inline';
                           return false;
                       }else{
                           document.getElementById('paymentSanityCheck').innerText = "";
                           document.getElementById('paymentSanityCheck').style.display = 'none';
                   }

                   var endDateUTC = Date.UTC(endYear,endMonth,endDate);
                   if(subStartDate != '' && subEndDate != ''){
                           if(startDateUTC > endDateUTC){
                                   document.getElementById('paymentSanityCheck').innerText = "Please select Payment Start Date to be less than End Date !!";
                                   document.getElementById('paymentSanityCheck').style.display = 'inline';
                                   return false;
                               }else{
                                   document.getElementById('paymentSanityCheck').innerText = "";
                                   document.getElementById('paymentSanityCheck').style.display = 'none';
                                   return true;
                           }
                       }else{
                           document.getElementById('paymentSanityCheck').innerText = "";
                           document.getElementById('paymentSanityCheck').style.display = 'none';
                           return true;
                   }
           }

           function showHideCancelComment(type){
                   if(type=='show'){
                           $('cancelDiv').style.display = 'inline';
                       }else if(type='hide'){
                           $('CancelComments').value = '';
                           $('cancelDiv').style.display = 'none';
			<?php if($queueType == 'OPS'){?>
                           $('checkbox_unselect_error').innerText='';	
                           <?php }else{ ?>
                           $('radio_unselect_error').innerText='';	
                           <?php } ?>
			
                   }
         }

         function validateBlank(){
                 if(
                     ($('transactionId').value == '') && 
                     ($('uiQuotationId').value == '') && 
                     ($('subs_start_date').value == '') && 
                     ($('subs_end_date').value == '') && 
                     ($('clientId').value == '') && 
                     ($('contactName').value == '') && 
                     ($('collegeName').value == '') && 
                     ($('saleBy').value == '') && 
                     ($('chequeNo').value == '') && 
                     ($('saleFloorAmount').value == '') && 
                     ($('saleCeilAmount').value == '') && 
                     ($('discFloorAmount').value == '') && 
                     ($('discCeilAmount').value == '') && 
                     ($('payment_start_date').value == '') && 
                     ($('payment_end_date').value == '') && 
                     ($('saleType_C').checked == false) &&
                     ($('saleType_T').checked == false) &&
                     ($('saleType_F').checked == false) &&
                     ($('saleType_P').checked == false) 
                 ){
                         document.getElementById('dateSanityCheck').innerText = "Please search within some criteria amongst above field(s) !!";
                         document.getElementById('dateSanityCheck').style.display = 'inline';
                         return false;
                     }else{
                         document.getElementById('dateSanityCheck').innerText = "";
                         document.getElementById('dateSanityCheck').style.display = 'none';
                         return true;
                 }
         }
     
	function selectAll(obj){
		var noOfRes = document.getElementById('noOfRes').value;
		var valToBeSaid = false;
		if(obj.checked==true){
			valToBeSaid = true;
		}
		for(var i=0;i<noOfRes;i++){
			if(document.getElementById('payment_'+i) && (document.getElementById('payment_'+i).disabled == false))
			{
				document.getElementById('payment_'+i).checked = valToBeSaid;
			}
		}
	}
     </script>
<?php
	if($viewTrans==-1)
{
?>
<script>
	document.getElementById('searchButton').click();
</script>
<?php 
}
?>
