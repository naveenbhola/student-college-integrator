<?php 
$quickClickActionContact = 'javascript:showContactDetails();';
if($validateuser[0]['requestinfouser'] == 1)
{
    $base64url = base64_encode($thisUrl);
    $quickClickActionContact = "javascript:window.location = '/user/Userregistration/index/".$base64url."/1';";
}

if(!isset($validateuser[0])){
$quickClickActionContact = "showuserLoginOverLay('myShiksha',2);"; 
}
if((strlen($details['contact_name']) > 0) ||  (strlen($details['contact_cell'])> 0 )) { 
?>
<div class="lineSpace_5">&nbsp;</div>
<div class="h22 bld bgcolor_div_sky fonSize_12p">
    <div class="bld mar_left_10p">Contact Details</div>
</div>
<div class="lineSpace_5">&nbsp;</div>
<!--<div class="mar_left_10p  fonSize_12p bld" id="showContactLink"><a href="javascript:void();" onclick="<?php echo $quickClickActionContact; ?> return false;">Show Contact Details</a></div>-->
<div id="listingContactInfo" class="mar_left_10p">
<?php if(strlen($details['contact_name'])) { ?>
    <div>
        <span style="margin-right:57px;" class="bld">Name</span>
        <span>: <?php echo $details['contact_name']; ?></span>
    </div>
    <div class="lineSpace_5">&nbsp;</div>
<?php } ?>

<?php if(strlen($details['contact_cell'])) { ?>
    <div>
        <span style="margin-right:20px;" class="bld">Contact No.</span>
        <span>: <?php echo $details['contact_cell']; ?></span>
    </div>

    <div class="lineSpace_5">&nbsp;</div>
<?php } ?>
<?php if(strlen($details['contact_email'])) { ?>
    <div>
        <span style="margin-right:58px;" class="bld">Email</span>
        <span>: <?php echo $details['contact_email']; ?></span>
    </div>
<?php } ?>
    <div class="lineSpace_5">&nbsp;</div>
</div>
<div class="lineSpace_5">&nbsp;</div>
<script>

function showContactDetails(){
    if(document.getElementById('listingContactInfo')){
        document.getElementById('listingContactInfo').style.display = 'block';
        document.getElementById('showContactLink').innerHTML = '';

        var xmlHttp = getXMLHTTPObject();
        xmlHttp.onreadystatechange=function() {
            if(xmlHttp.readyState==4) {
                var tracked = xmlHttp.responseText;
            }
        }
        var url = '/listing/Listing/trackViewContactDetail';
        xmlHttp.open("POST",url,true);
        setXHRHeaders(xmlHttp);
        xmlHttp.send(null);
    }
}
</script>
<?php } ?>
