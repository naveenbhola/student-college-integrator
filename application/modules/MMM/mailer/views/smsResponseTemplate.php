<?php

$this->load->view('sms_response');
$this->load->view('savedSMSResponse');

?>

<style type="text/css">
    label{
        width:250px; font-size:13px; padding-top:5px; color:#333;
    }

    .input{
        width:300px; border:1px solid #ccc; font-size:13px; padding:5px 2px;
    }

    #fileErrorMessage{
        color:red;
    }

    .submit_Button{
        background-color: #F78640;
        border: solid 1px #D2D2D2;
        overflow: visible;
        padding: 4px 8px;
        font: bold 14px Tahoma, Geneva, sans-serif !important;
        color: #fff;
        line-height: normal;
        cursor: pointer;
        margin: 0;
        }
</style>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript">
$j = jQuery.noConflict();

// basic Validation for all fields 
function validateField(value,id){
    if(value == ''){
        $j('#'+id).html("Can't be empty");
    }else if(id == 'clientErrorMsg' && (isNaN(value) || value < 1)){
        $j('#'+id).html('Invalid clientId');
    }else if(id=="ErrorMsg" && value.length > 140){
        $j('#'+id).html('Message cannot be greater then 140 characters');
    }else{
        $j('#'+id).html('');
        return true;
    }

    return false;
}


// Validate File Extension
function validateFile(){
    var fileName = $j('#userSetFile').val();
    var validExt = ['csv','ods','xls','xlsx'];
    var fileExt = fileName.substr(fileName.lastIndexOf('.')+1);

    if(validExt.indexOf(fileExt) >= 0){
        $j('#fileErrorMessage').html("");
        return true;
    }
    $j('#fileErrorMessage').html("Invalid File Extension.");
    return false;
}

// Check if @landingURL is there in message
function validateLandingURL(){
    var message = $j('#message').val();

    if(message.lastIndexOf("@landingURL") > -1){
        $j('#ErrorMsg').html("");
        return true;
    }

    $j('#ErrorMsg').html("Please add '@landingURL' label");
    return false;
}

//Make Ajax call to mailer.php to start sms campaign
function startSMSCampaign(){
    var campaignName = {};
    var flag = 1;
    showLoader();

    $j('input[name="clientCampaign"]:checked').each(function(){
        var campaignNameValue = this.value;
        var campaignTime = new Date($j('#time_at_'+campaignNameValue).val());
        var currentTime = new Date();

        if((campaignTime - currentTime)<0){
            flag = 0;
        }
        campaignName[$j(this).attr('campaign')] = $j('#time_at_'+campaignNameValue).val();
    });
    
    if(flag == 0){
        alert("Please select date greater than today and time greater than now.");
        hideLoader();
        return;
    }

    var myJsonString = JSON.stringify(campaignName);

    if($j.isEmptyObject(campaignName)){
        hideLoader();
        return;
    }

    $j.ajax({
        type:'POST',
        url:'/mailer/Mailer/startSMSCampaign',
        data : {'campaignName':myJsonString},
        success:function(reponse){
            if(reponse == 'saved'){
                window.location.reload(true);
            }else{
                hideLoader();
                alert("Some problem has occured please try again");
                window.location.reload(true);
            }   
        }
    });
}


function deleteCampaign(campaignName){
    $j.ajax({
        type:'POST',
        url:'/mailer/Mailer/deleteCampaign',
        data : {'campaignName':campaignName},
        success:function(reponse){
            if(reponse == 1){
                window.location.reload(true);
            }   
        }
    });
}

// Form validation before submitting form
$j('#saveCSV').submit(function(){

    var $form = $j("#saveCSV :input");

    var values = {};
    var fieldsValidated = true;
    var Validated = true;
    var errorFields = {clientId:'clientErrorMsg', campaignName:'campaignErrorMsg', message:'ErrorMsg',userSetFile:'fileErrorMessage'};
    
    // Validate All fields
    $form.each(function(){
       Validated = validateField(this.value, errorFields[this.id]);
       if(!Validated){
        fieldsValidated =  false;
       }
    });

    //return if any field is invalid
    if(!fieldsValidated){
        return fieldsValidated;
    }

    //check for file Extension
    Validated = validateFile();
    if(!Validated){
        fieldsValidated =  false;
        return fieldsValidated;
    }

    // check for @landingURL 
    Validated = validateLandingURL();
    if(!Validated){
        fieldsValidated =  false;
        return fieldsValidated;
    }
    if(fieldsValidated){
        showLoader();
    }
    
     return fieldsValidated;
});

function showLoader(){
    var top = $j(window).scrollTop()+210;
    var height = $j('.wrapperFxd').height()+100;
    $j("body").append('<div id="enterpriseLoader"><div class="modalDialog_transparentDivs" id="DHTMLSuite_modalBox_transparentDiv" style="top: 0px; left: 0px; width: 1288px; height:'+height+'px; display: block;"></div><div style="border-radius: 6px; width: 242px; position: absolute; left: 47%; z-index: 100; height: 125px; top:'+top+'px;" id="otpLoader"> <img align="bsmiddle" src="/public/images/loader.gif"></div></div>');
}

function hideLoader(){
    $j("#enterpriseLoader").remove();
}
</script>