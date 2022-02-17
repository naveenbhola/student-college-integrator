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
        'css'	=>	array('headerCms','raised_all','mainStyle','footer','cal_style'),
        'js'	=>	array('common','enterprise','home','CalendarPopup','prototype','discussion','events','listing'),
        'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
        'tabName'	=>	'',
        'taburl' => site_url('enterprise/Enterprise'),
        'metaKeywords'	=>''
    );
    $this->load->view('enterprise/headerCMS', $headerComponents);
?>
</head>

<?php $this->load->view('enterprise/cmsTabs'); ?>

<form id="frmSelectUser" action='' method="post">
<body style="margin:0 10px">
    <?php if(isset($userDetails)){ ?>
    <table width="100%" cellspacing="2">
        <tr>
            <td><span class="OrgangeFont bld">Email:</span> <b><?php echo $userDetails['email']; ?></b> </td>
            <td><span class="OrgangeFont bld">Client Id:</span><b> <?php echo $userDetails['clientUserId']; ?></b> </td>
            <td><span class="OrgangeFont bld">Display Name:</span><b> <?php echo $userDetails['displayname']; ?></b> </td>
        </tr>
<input type="hidden" id="clientUserId" name="clientUserId" value="<?php echo $userDetails['clientUserId']; ?>" />
<input type="hidden" id="cmsUserId" value="<?php echo $userid; ?>" />
    </table>
    <?php }else{ ?>
<input type="hidden" id="clientUserId" name="clientUserId" value="<?php echo $userid; ?>" />
<input type="hidden" id="cmsUserId" value="" />
   <?php }
    ?>

<div class="lineSpace_10" style="width:100%">&nbsp;</div>

<strong> Please choose an Institute for which you intend to post a Course..... </strong>

<div class="lineSpace_10" style="width:100%">&nbsp;</div>

<table width="100%" border="1" cellspacing="0" cellpadding="0" style="border-collapse:collapse;" bordercolor="#999999">
    <tr>
        <td width="3%" height="25" align="center" valign="middle" bgcolor="#99CCFF">&nbsp;</td>
        <td width="23%" align="left" valign="middle" bgcolor="#99CCFF" style="padding-left:10px"><strong>Listing Type ID</strong></td>
        <td width="25%" align="left" valign="middle" bgcolor="#99CCFF" style="padding-left:10px"><strong>Listing Title</strong> </td>
        <td width="25%" align="left" valign="middle" bgcolor="#99CCFF" style="padding-left:10px"><strong>Listing Type</strong> </td>
        <td width="24%" align="left" valign="middle" bgcolor="#99CCFF" style="padding-left:10px"><strong>Expiry Date</strong> </td>
    </tr>
</table>

<div style="overflow:auto; height:115px">
    <table width="100%" border="1" cellspacing="0" cellpadding="0" style="border-collapse:collapse;" bordercolor="#999999">
        <?php
            $i =0;
            foreach($clientListings as $key => $val){ ?>
        <tr>
            <td width="3%" valign="top"><input type="radio" name="selectedListingId" value="<?php echo $val['listing_id']; ?>" id="selectedListingId_<?php echo $i; ?>" onClick="setListingInfoDiv(<?php echo $i;?>);"  /></td>
            <td width="23%" valign="top" style="padding:0 10px"><?php echo $val['listing_type_id']; ?></td>
            <td width="25%" valign="top" style="padding:0 10px"><?php echo $val['listing_title']; ?></td>
            <td width="25%" valign="top" style="padding:0 10px"><?php echo $val['listing_type']; ?></td>
            <td width="25%" valign="top" style="padding:0 10px"><?php echo $val['expiry_date']; ?></td>
            <td> 
                <input type="hidden" id="selectedListingTypeId_<?php echo $i; ?>" value="<?php echo $val['listing_type_id']; ?>" /> 
                <input type="hidden" id="selectedListingType_<?php echo $i; ?>" value="<?php echo $val['listing_type']; ?>" /> 
            </td>
        </tr>
        <?php $i++; 
        } ?>

    </table>
</div>
<input type="hidden" id="totalListings" value="<?php echo $i; ?>" />

<div id="listingInfo">
<input type="hidden" id="selectedListingTypeId" name="selectedListingTypeId" value="" />
<input type="hidden" id="selectedListingType" name="selectedListingType" value="" />
</div>

<div id="correct_above_error" style="display:none;color:red;"></div><br/>
<div class="lineSpace_20" style="width:100%">&nbsp;</div>

<button id="postInstiButton" class="btn-submit19" onclick="$('frmSelectUser').action='/enterprise/ShowForms/showCourseForm/'; formProceed();" type="button" value="" style="width:180px;">
    <div class="btn-submit19"><p style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:12px" class="btn-submit20">Add Course</p></div>
</button>
            </form>
</body>
</html>
<script>
    function setListingInfoDiv(i){
            $('selectedListingTypeId').value = $('selectedListingTypeId_'+i).value;
            $('selectedListingType').value = $('selectedListingType_'+i).value;
    }

    function chooseInstituteCheck(listingType){
            var FLAG=false;
            for(var i=0;i<document.getElementById('totalListings').value;i++)
            {
                    if(document.getElementById('selectedListingId_'+i+'').checked == true){
                            return true;
                        }else{
                            continue;
                    }
            }

            if(FLAG == false){
                    alert("Please Select an "+listingType+" Listing!!");
                    return false;
            }
    }

   function formProceed(){
           if(chooseInstituteCheck('institute')){
                    $('correct_above_error').innerHTML  = "";
                    $('correct_above_error').style.display = 'none';
                    $('frmSelectUser').submit();
                }else{
                    $('correct_above_error').innerHTML  = "Please scroll up and choose an Institute!";
                    $('correct_above_error').style.display = 'inline';
                    return false;
            }
    }

</script>
