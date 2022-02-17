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
        'css'	=>	array('headerCms','mainStyle','footer'),
        // 'js'	=>	array('common','enterprise','home','CalendarPopup','prototype','discussion','events','listing'),
        'js'	=>	array(),
        'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
        'tabName'	=>	'',
        'taburl' => site_url('enterprise/Enterprise'),
        'metaKeywords'	=>''
    );
    $this->load->view('enterprise/headerCMS', $headerComponents);

    $actionURL = site_url().'enterprise/Enterprise/unsetMainInstitute';
    
?>
<style type="text/css">
.mainInstTable{font:normal 12px Arial, Helvetica, sans-serif; color:#000}
.mainInstTable tr th{background:#e8e8e8; font-size:14px; font-weight:normal; padding:8px 6px}
.gray-rule{background:#dfdfdf; height:1px; overflow:hidden; width:100%; margin:8px 0}
.alt-rowBg{background:#f5f5f5}
</style>
</head>

<?php $this->load->view('enterprise/cmsTabs'); ?>

<body>
<form name="frm1" action="<?=$actionURL?>" onsubmit="return validateData();" method="post">
<div style="margin: 10px 15px;">
	<h2 style="font:normal 20px 'Trebuchet MS', Arial, Helvetica, sans-serif; margin-bottom:15px; color:#fd7f04; display:block">Unset Main Institute</h2>
        <?php // echo "Cookie = ".$_COOKIE['thanksMsgCookie'];
         if( isset( $_COOKIE['thanksMsgCookie'] ) && $_COOKIE['thanksMsgCookie'] != "" ) {
        ?>
        <p style="font:normal 18px 'Trebuchet MS', Arial, Helvetica, sans-serif; margin-bottom:15px; color:red; text-align:center;"><?=$_COOKIE['thanksMsgCookie']?></p>
        <?php
         } // End of if( isset( $_COOKIE['thanksMsgCookie'] ) && $_COOKIE['thanksMsgCookie'] != "" ).
        ?>
    <div style="border:1px solid #cdcdcd; padding:12px">
    <?php if(isset($userDetails)){ ?>
    
    <table width="100%" cellspacing="2">
        <tr>
            <td><span class="OrgangeFont bld" style="font-size:15px">Email:</span> <b style="font-size:15px; font-weight:normal"><?php echo $userDetails['email']; ?></b> </td>
            <td><span class="OrgangeFont bld" style="font-size:15px">Client Id:</span><b style="font-size:15px; font-weight:normal"> <?php echo $userDetails['clientUserId']; ?></b> </td>
            <td><span class="OrgangeFont bld" style="font-size:15px">Display Name:</span><b style="font-size:15px; font-weight:normal"> <?php echo $userDetails['displayname']; ?></b> </td>
	    <td><span class="OrgangeFont bld" style="font-size:15px">Country:</span><select name="countryRequested" onchange="changeResultForCountries(this.value,true,<?=$userDetails['clientUserId'];?>);"><option value="national" <?php if($countryRequested == 'national' || $countryRequested == ''){echo "selected";}?>>India</option><option value="abroad" <?php if($countryRequested == 'abroad'){echo "selected";}?>>Abroad</option></select></td>
        </tr>
        <tr>
        	<td colspan="3"><div class="gray-rule"></div></td>
        </tr>
<input type="hidden" name="clientUserId" id="clientUserId" value="<?php echo $userDetails['clientUserId']; ?>" />
<input type="hidden" name="cmsUserId" id="cmsUserId" value="<?php echo $userid; ?>" />
    </table>
    <?php }else{ ?>
<input type="hidden" name="clientUserId"  id="clientUserId" value="<?php echo $userid; ?>" />
<input type="hidden" name="cmsUserId"  id="cmsUserId" value="" />
   <?php }
    ?>
<div class="lineSpace_10" style="width:100%">&nbsp;</div>
<?php

if($clientListings == 'NO_INSTITUTE_FOUND' || $clientListings == "") { ?>
    <div class="lineSpace_10" style="width:100%">&nbsp;</div>
    <table width="100%" border="0" cellspacing="0" cellpadding="6" class="mainInstTable">
        <tr><?php if($countryRequested == 'abroad'){
		$noInstMsg = "Sorry! No University is set as Main University for this client.  Please select National from drop-down.";
	    }else{
		$noInstMsg = "Sorry! No Institute is set as Main Institute for this client. Please select Abroad from drop-down.";
	    }?>
        <td align="center" valign="middle"><?=$noInstMsg;?></td>
    </tr>
    </table>
<?php
} else {
?>    
    <table width="100%" border="0" cellspacing="0" cellpadding="6" class="mainInstTable">
        <tr>
        <th width="3%" align="center" valign="middle"><input type="checkbox" name="selectAll" onChange="toggleAll(this);"/></th>
        <th width="16%" align="left" valign="middle">Listing Name</th>
        <th width="15%" align="left" valign="middle">Category</th>
        <th width="16%" align="left" valign="middle">Subcategory</th>
        <th width="10%" align="left" valign="middle">Country</th>
        <th width="10%" align="left" valign="middle">State</th>
        <th width="10%" align="left" valign="middle">City</th>
        <th width="10%" align="left" valign="middle">Start Date</th>
        <th align="left" valign="middle">Expiry Date</th>
    </tr>
    
<?php
            $rowCount =0;
            foreach($clientListings[0] as $instituteId => $instituteData){
                // echo "<br> instituteId : ".$instituteId.". institute key = ";_p($instituteData['key']);
                
                $totalPageKeyCount = count($instituteData['key']);
                for($i = 0; $i < $totalPageKeyCount; $i++) {
                    $pageKeyId = $instituteData['key'][$i]['KeyId'];
                    if($i % 2 == 1) {
                        $class = ' class="alt-rowBg"';
                    } else {
                        $class = '';
                    }
?>
        <tr<?=$class?>>
            <td width="3%" valign="top"><input type="checkbox" name="selectedInstitutesChkbox[]" value="<?php echo $instituteData['key'][$i]['id']; ?>" id="selectedListingId_<?php echo $rowCount; ?>"  /></td>
            <td width="16%" valign="top"><?php echo $instituteData['title']; ?></td>
            <td width="15%" valign="top"><?php echo ($clientListings[1][$pageKeyId]['catName'] == "" ? "--" : $clientListings[1][$pageKeyId]['catName']); ?></td>
            <td width="16%" valign="top"><?php echo ($clientListings[1][$pageKeyId]['subCatName'] == "" ? "--" : $clientListings[1][$pageKeyId]['subCatName']); ?></td>
            <td width="10%" valign="top"><?php echo ($clientListings[1][$pageKeyId]['countryName'] == "" ? "--" : $clientListings[1][$pageKeyId]['countryName']); ?></td>
            <td width="10%" valign="top"><?php echo ($clientListings[1][$pageKeyId]['stateName'] == "" ? "--" : $clientListings[1][$pageKeyId]['stateName']); ?></td>
            <td width="10%" valign="top"><?php echo ($clientListings[1][$pageKeyId]['cityName'] == "" ? "--" : $clientListings[1][$pageKeyId]['cityName']); ?></td>
            <td width="10%" valign="top"><?php echo $instituteData['key'][$i]['StartDate']; ?></td>
            <td width="10%" valign="top"><?php echo $instituteData['key'][$i]['EndDate']; ?></td>
        </tr>
<?php
                $rowCount++;
            
            }   // End of for($i = 0; $i < $totalPageKeyCount; $i++).

        }   // End of foreach($clientListings[0] as $instituteId => $instituteData).  ?>
         <tr>
            <td colspan="9">
            <div class="gray-rule"></div>
			<div class="lineSpace_10" style="width:100%">&nbsp;</div>            
            <input type="submit" value="Unset Main Institute" name="bttnSubmit" id="bttnSubmit" class="orange-button" /></td>
        </tr>
    </table>
<?php
} // End of if($clientListings == 'NO_INSTITUTE_FOUND').
?>
</div>
</div>
<input type="hidden" name="totalListings" id="totalListings" value="<?php echo $rowCount; ?>" />
<div class="lineSpace_10" style="width:100%">&nbsp;</div>
</form>
<?php $this->load->view('enterprise/footer'); ?>
<script>
    var totalListings = $("totalListings").value;
    function toggleAll(objtest) {
        var elementId;
	for(i = 0; i < totalListings; i++) {
            elementId =  "selectedListingId_"+i;
            // alert("elementId = "+elementId+" , chkd = "+$(elementId).checked);
            if($(objtest).checked) {
                $(elementId).checked = true;
            } else {
                $(elementId).checked = false;
            }
        }
    }

    function validateData() {
        
        // alert("totalListings = "+totalListings);
        var elementId;
        var checked = 0;
        for(i = 0; i < totalListings; i++) {
            elementId =  "selectedListingId_"+i;
            // alert("elementId = "+elementId+" , chkd = "+$(elementId).checked);
            if($(elementId).checked) {
                checked = 1;
                break;
            }
        }
        
        if(checked == 0) {
            alert("Please select at least 1 institute to unset!");
            return false;
        } else {
            if(confirm("Are you sure, you want to unset the Main Institute on the selected combination(s)?")) {
                return true;
            } else {
                return false;
            }
        }
    }
    
    function changeResultForCountries(countryRequested,onBeHalf,clientID) {
	var form = document.createElement("form");
	form.setAttribute("method" , "post");
	form.setAttribute("action" , "/index.php/enterprise/Enterprise/showMainInstituteForClient");
	
	var cntrReq = document.createElement("input");
	var onBhf = document.createElement("input");
	var clnId = document.createElement("input");
	//var cmsUsr = document.createElement("input");
	
	cntrReq.setAttribute("name" , "countryRequested");
	onBhf.setAttribute("name" , "onBehalfOf");
	clnId.setAttribute("name" , "selectedUserId");
	//cmsUsr.setAttribute("name" , "cmsUserId");
	
	cntrReq.setAttribute("value" , countryRequested);
	onBhf.setAttribute("value" , onBeHalf);
	clnId.setAttribute("value" , clientID);
	//cmsUsr.setAttribute("value" , <?php echo $userid; ?>);
	
	cntrReq.setAttribute("type" , "hidden");
	onBhf.setAttribute("type" , "hidden");
	clnId.setAttribute("type" , "hidden");
	//cmsUsr.setAttribute("type" , "hidden");
	
	form.appendChild(cntrReq);
	form.appendChild(onBhf);
	form.appendChild(clnId);
	//form.appendChild(cmsUsr);
	
	document.body.appendChild(form);
	form.submit();
    }
</script>