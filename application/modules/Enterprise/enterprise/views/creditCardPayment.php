<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/11009/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<?php if($usergroup == "cms"){ ?>
    <title>CMS Control Page</title>
        <?php } ?>
        <?php if($usergroup == "enterprise"){ ?>
            <title>Enterprise Control Page</title>
                <?php } ?>

                <?php
                $headerComponents = array(
                        'css'   =>  array('headerCms','raised_all','mainStyle','footer','cal_style'),
                        'js'    =>  array('common','enterprise','home','CalendarPopup','prototype','discussion','events','listing'),
                        'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
                        'tabName'   =>  '',
                        'taburl' => site_url('enterprise/Enterprise'),
                        'metaKeywords'  =>''
                        );
                $this->load->view('enterprise/headerCMS', $headerComponents);
                ?>
                </head>

                <?php $this->load->view('enterprise/cmsTabs'); ?>

                <body style="margin:0 10px">
                <div class="lineSpace_25">&nbsp; </div>
                <h2>
                <span class="OrgangeFont">Pending Credit Card Payments.</span>
                </h2>
                <form method="post" action="/enterprise/Enterprise/payment" id="paymentForm" name="paymentFormName"  >
                <?php 
                $i = 0;
                if(count($paymentData) == 0) {
                    echo '<div class="lineSpace_15">&nbsp; </div>';
                    echo "<div>You don't have any pending credit card Payments.</div>";
                }else{
                    echo '<div class="lineSpace_25">&nbsp; </div><div class="bgRequestInfo"><div class="lineSpace_10">&nbsp; </div>';
                    echo '<b><div style="clear: left;"><div style="width: 30px; float: left;">&nbsp;</div><div style="width: 225px; float: left;">Products</div> <div style="width: 225px; float: left;">Total Transaction Price</div><div style="width: 125px; float: left;">To Be Paid</div><div style="width: 125px; float: left;">Currency Type</div><div style="width: 125px; float: left;">Transaction Id.</div></div></b>';
                    echo '</div><div class="lineSpace_25">&nbsp; </div>';
                }
foreach($paymentData as $payment) {
    echo "<div style='clear:left;'><div class='lineSpace_10'>&nbsp; </div>";
    if($i == 0) {
        echo "<div style='width: 30px; float: left;'><input type='radio' onclick='selectPaymentOPtion(this,".$payment['DueAmount'].");' id='".paymentOption.$i."' paymentType='".$payment['CurrencyType']."' name='paymentOption'  value='".$payment['Payment_Id']."_".$payment['Part_Number']."_".$payment['NetAmount']."' checked/></div><div style='width: 225px; float: left;'>".$payment['ProductList']."</div> <div style='width: 225px; float: left;'>".$payment['NetAmount']."</div><div style='width: 125px; float: left;'>".$payment['DueAmount']."</div><b><div style='width: 125px; float: left;'>(".$payment['CurrencyType'].")</div></b><div style='width: 125px; float: left;'>".ltrim($payment['TransactionId'],0)."</div>";
        $defaultAmount = $payment['DueAmount'];
    }else {
        echo "<div style='width: 30px; float: left;'><input type='radio' onclick='selectPaymentOPtion(this,".$payment['DueAmount'].");' id='".paymentOption.$i."' paymentType='".$payment['CurrencyType']."' name='paymentOption'  value='".$payment['Payment_Id']."_".$payment['Part_Number']."_".$payment['NetAmount']."' /> </div><div style='width: 225px; float: left;'>".$payment['ProductList']."</div><div style='width: 225px; float: left;'>".$payment['NetAmount']."</div><div style='width: 125px; float: left;'>".$payment['DueAmount']."</div><b><div style='width: 125px; float: left;'>(".$payment['CurrencyType'].")</div></b><div style='width: 125px; float: left;'>".ltrim($payment['TransactionId'],0)."</div>";
    }
    echo "</div>";
    $i++;
}
if(count($paymentData) == 0) {
    ?>

        <div class="lineSpace_25">&nbsp; </div>
        <div class="normaltxt_11p_blk bld">
        <button style="width: 125px;" onclick="window.location='/enterprise/Enterprise';" type="button" value="Go_Back_Enterprise" id="goBkEnterp" class="btn-submit7 w9">
        <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Home</p></div>
        </button>
        </div>

        <?php }else {
            ?>
                <div class="lineSpace_25">&nbsp; </div>
                <div class="lineSpace_20" style="width:100%">&nbsp;</div>
<!--                <h1>
                <span class="OrgangeFont" style="padding:5px; "> Please fill the amount you want to pay right now for above chosen part payment:</span>
                </h1>-->

                <!--<div class="normaltxt_11p_blk bld" style="font-size:15px; padding:10px;  ">-->
                <input align ="middle" type="hidden" maxlength="20" size="15" name="amount" id="amount" value="<?php echo $defaultAmount; ?>"  onblur="javascript:allnumeric()" />
                <!--</div>-->
                <div  class="errorMsg" id="innerhtmltags" style="padding:10px;display:none;color:red;"></div><br/>

                <div class="normaltxt_11p_blk bld">
                <div class="lineSpace_20" style="width:100%">&nbsp;</div>
                <h1>
                <span class="OrgangeFont"> Choose your payment mode</span>
                </h1>
                <div class="lineSpace_20" style="width:100%">&nbsp;</div>


                <form>
                <!-- <INPUT TYPE=RADIO NAME="a" onclick='selectAction(this)' id="ICICIParaINR"  VALUE="S1"> INR Payment through Payseal(ICICI Payseal)<br>-->
                <INPUT TYPE=RADIO NAME="a" onclick='selectAction(this)' id="CCAVENUEParaINR" VALUE="M"> INR Payment By Credit Card/Debit Card/Net Banking/Cash Card/Mobile Payments through CCAvenue   <br> 
                <!-- <INPUT TYPE=RADIO NAME="a" onclick='selectAction(this)' id="ICICIParaUSD"  VALUE="S2"> USD Payment through Payseal(ICICI Payseal)<br>-->
                <INPUT TYPE=RADIO NAME="a" onclick='selectAction(this)' id="CCAVENUEParaUSD" VALUE="L"> USD Payment By American Express/Visa Card through CCAvenue  <br>
                <INPUT TYPE=RADIO NAME="a" onclick='selectAction(this)' id="PaypalgatewayParaId" VALUE="L"> USD Payment By Paypal Gateway  <br>

                </form>

                <div class="lineSpace_25">&nbsp; </div>

                <div class="errorMsg" id="error"></div>
                <div class="lineSpace_25">&nbsp; </div>


                <div class="normaltxt_11p_blk bld">
                <button style="width: 125px;" onclick="allnumeric();onsubmitform();" type="button" value="Go_Back_Enterprise" id="goBkEnterpICICI" class="btn-submit7 w9">
                <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog" id="CCAVENUEPara">Make Payment</p></div>
                </button>
                <button style="width: 137px;" onclick="window.location='/enterprise/Enterprise'" type="button" value="Go_Back_Enterprise" id="goBkEnterpCCAVENUE" class="btn-submit7 w9">
                <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog" id="goBkEnterpCCAVENUE">Cancel</p></div>
                </button>
                <div class="lineSpace_20" style="width:100%">&nbsp;</div>



                </div>
                </form>
                <?php }?>
                <div class="lineSpace_20" style="width:100%">&nbsp;</div>
                <div class="lineSpace_20" style="width:100%">&nbsp;</div>
                <div class="lineSpace_20" style="width:100%">&nbsp;</div>
                <div class="lineSpace_20" style="width:100%">&nbsp;</div>
                <div class="lineSpace_20" style="width:100%">&nbsp;</div>
                <div class="lineSpace_20" style="width:100%">&nbsp;</div>
                <script type="text/javascript">

                <?php if($checkAmountVariable == 'true')  {?>
                    alert("Partly paid amount should not be zero or can not be greater than the selective part payment.");
                    <?php } ?>

                    try {
                        if($('paymentOption0').getAttribute('paymentType') == "" || $('paymentOption0').getAttribute('paymentType') == "INR") {
                            $('CCAVENUEParaINR').disabled = false;
                            // $('ICICIParaINR').disabled = false;
                            $('CCAVENUEParaUSD').disabled = true;
                            // $('ICICIParaUSD').disabled = true;
                            $('PaypalgatewayParaId').disabled = true;


                        } else {
                            $('CCAVENUEParaINR').disabled = true;
                            // $('ICICIParaINR').disabled = true;
                            $('CCAVENUEParaUSD').disabled = false;
                            // $('ICICIParaUSD').disabled = false;
                            $('PaypalgatewayParaId').disabled = false;

                        }
                    } catch(e) {
                        // do nothing
                    }
function selectPaymentOPtion(type, paymentAmount) {

    try {
        var paymentType = type.getAttribute('paymentType');
        document.getElementById("amount").value = paymentAmount;
        if(!paymentType) {
            paymentType = "INR";
        }
        if(paymentType == 'INR') {
            $('CCAVENUEParaINR').disabled = false;
            // $('ICICIParaINR').disabled = false;
            $('CCAVENUEParaUSD').disabled = true;
            // $('ICICIParaUSD').disabled = true;
            $('PaypalgatewayParaId').disabled = true;

        } else {
            $('CCAVENUEParaINR').disabled = true;
            // $('ICICIParaINR').disabled = true;
            $('CCAVENUEParaUSD').disabled = false;
            // $('ICICIParaUSD').disabled = false;
            $('PaypalgatewayParaId').disabled = false;
        }
    } catch (e) {
        // do nothing
    }
}

function allnumeric()  
{  
    var currency = document.getElementById("amount").value;
    currency = parseInt(currency).toFixed(3).slice(0, -1);
    document.getElementById("amount").value = currency;
    var pattern = /^\d+(?:\.\d{0,2})$/ ;
    
    if(pattern.test(currency))  
    {
        document.getElementById('innerhtmltags').innerHTML = "";
        document.getElementById('innerhtmltags').style.display = 'none';
        return true;  
    }
    else  
    {  
        document.getElementById('innerhtmltags').innerHTML = "Please input numeric characters only with amount in two decimal values !!";
        document.getElementById('innerhtmltags').style.display = 'inline';
        return false;

    }  
}   

function selectAction(element) {
    //validateForm();
    try {
        var id = element.id;
        var action = "/enterprise/Enterprise/payment";
        if(id == 'ICICIParaINR') {
            action = "/enterprise/Enterprise/paymentGateway";
        } else if(id == 'ICICIParaUSD') {
            action = "/enterprise/Enterprise/paymentGateway";
        }
        else if(id == 'CCAVENUEParaINR') {
            action = "/enterprise/Enterprise/ccavenueindian";
        } else if(id == 'CCAVENUEParaUSD') {
            action = "/enterprise/Enterprise/ccavenue";
        }
        else if(id == 'PaypalgatewayParaId') {
            action = "/enterprise/Enterprise/paypalgateway";
        }
        $('paymentForm').action = action;
    } catch(e) 
    {   
        // do nothing
    }
}


function onsubmitform() {
    var radios = document.getElementsByName('a');
    var flag = false;
    for (var i = 0; i < radios.length; i++) {
        if (radios[i].type === 'radio' && radios[i].checked) {
            flag = true;
        }
    }
    if(!flag){
        alert('Please choose payment gateway first to proceed !!');
        return false;
    }
    

    if(document.getElementById('innerhtmltags').style.display == 'none') {
        document.getElementById('error').style.display = 'none';
        document.paymentFormName.submit();
        return true;
    }

    else if(document.getElementById('innerhtmltags').style.display == 'inline') {
        $('error').innerHTML = 'Error found on form.Please correct it to proceed.';
        document.getElementById('error').style.display = 'inline';
        return false;
    }
}

function clearErrorFields()
{
    var errorPlaces = getElementsByClassName(document.body,'div','errorMsg');
    for(var i=0;i<errorPlaces.length;i++)
    {
        errorPlaces[i].innerHTML = '';
    }
}

</script>
<?php $this->load->view('enterprise/footer');  ?>

</body>
</html>


