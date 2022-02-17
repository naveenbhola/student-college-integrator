<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<?php if($usergroup == "cms"){ ?>
<title>CMS Control Page</title>
<?php } ?>
<?php if($usergroup == "enterprise"){ ?>
<title>Enterprise Control Page</title>
<?php }
$paramArray = array();
$js = array('common','instituteForm');
$jsFooter = array();
$js = array_merge(array('footer','lazyload'),$js);
$headerComponents = array(
                        'css'	=>	array('headerCms','raised_all','mainStyle'),
                        'js'	=> $js,
                        'jsFooter' => $jsFooter,
                        'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
                        'tabName'	=>	'',
                        'taburl' => site_url('enterprise/Enterprise'),
                        'metaKeywords'	=>''
                );

$this->load->view('enterprise/headerCMS', $headerComponents);
?>
<style type="text/css">
.search-inst-table{font:normal 12px Tahoma, Geneva, sans-serif; border-collapse:collapse}
.search-inst-table tr th{font-weight:bold; background-color:#d1d1d3; text-align:left}
.search-inst-table tr td{border-bottom:1px solid #999999}
.left-vspace{margin-left:20px}
.loc-vspace{margin-left:40px}
</style>
</head>
<body>
   <div id="dataLoaderPanel" style="position:absolute;display:none">
      <img src="/public/images/loader.gif"/>
   </div>
<div class="mar_full_10p">
        <?php $this->load->view('enterprise/cmsTabs'); ?>
        <div style="float:left; width:100%">
            <div class="raised_lgraynoBG">
                <b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
                <div id="replacePage" name="replacePage" class="boxcontent_lgraynoBG">
                    <div class="row">
                            <div class="normaltxt_11p_blk_arial bld">

<div style="height:45px;background-image:url(/public/images/enterpriceBg.gif); background-repeat:repeat-x;padding:0 10px;">
&nbsp;&nbsp; Institute Name &nbsp;&nbsp;<input type="textbox" name="instituteNameTxtBox" id="instituteNameTxtBox" value="" onkeypress="return checkEnterKey(event)" />&nbsp;&nbsp; <input type="button" class="orange-button" id="bttnSearch" name="bttnSearch" value="Search" onClick="javascript: searchInstitutesForKeyword();" />
</div><div class="lineSpace_10">&nbsp;</div>
<div style="display:block;" id="location_contact_container"><table cellpadding="6" cellspacing="0" border="0" width="100%" class="search-inst-table">
	<tr><td width="100%" height="80" valign="middle" align="center">Please search the Institute Name to start with!</td></tr></table>
</div>
                            <div class="bgsplit">
                            </div><div class="lineSpace_10">&nbsp;</div>
                            <div name="prod_detail" id="prod_detail" class="normaltxt_11p_blk">
                            <div style='width:100%;margin-left:20px;' id="updateBttnContainer"> &nbsp;</div>
                            <div style='width:100%;margin-left:20px;' id="previewPaneContainer"> &nbsp;</div>
                            </div>
                        </div>
                    </div>
                     <div class="lineSpace_10">&nbsp;</div>
                </div>
                <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
        </div>
    </div>
<div class="spacer10 clearFix"></div>
</div>
<?php $this->load->view('enterprise/footer'); ?>
<script>
    function showContactDetailsLayer() {            
            if(!validateCheckBoxes()) {
                alert("Please select any Location - Contact Details to update first!");
                return false;
            }
            
            if (!$('contact_details_div')) {
                    loadOverlay("-enterprise-listings_contact_details_overlay", "contact_details_div","showContactDetailsLayer");
                    $('genOverlayAnA').style.top = "300px";
                    setScroll(400,parseInt($('genOverlayAnA').style.top));
                    return;
            }

            var imgObject = $('overlayCloseCrossAnA');
            if (imgObject.addEventListener) {
                imgObject.addEventListener("click", function () {hideOverlayAnA();$('contact_details_div').style.display='none';} , false);
            }

            var overlayWidth = 720;
            var overlayHeight = 500;
            var overlayTitle = '<span style="padding-left:5px" class="Fnt12" style="font-size:12px;"><b>Update Contact Person Details</b></span>';
            var overlayDivId = 'contact_details_div';
            var overlayContent = $(overlayDivId).innerHTML;
            showOverlayAnA(overlayWidth, overlayHeight, overlayTitle, overlayContent,false);            
            overlayTop = parseInt($('genOverlayAnA').style.top) - 80;
            $('genOverlayAnA').style.top = overlayTop+"px";
            setScroll(400,parseInt($('genOverlayAnA').style.top));
    }

    function updateListingsContactInfo() {

        if(!validateContactDetails()){
            return false;
        }

        $("bttnContainerDiv").innerHTML = '<div style="width:100%;text-align: center; margin: 20px;"><b>Please wait, updating info...</b> <img src="/public/images/loader.gif"></div>';
        $('location_contact_container').innerHTML = '<div class="Fnt14" style="height:200px; margin: 20px;"> &nbsp;Please wait, getting updated Contact Details... <img src="/public/images/loader.gif"></div>';
        $('previewPaneContainer').innerHTML = "&nbsp;";
        
        // Need to fire Ajax call to run the replication for all of the selected Locations' Contact details..
        $('updateBttnContainer').innerHTML = '&nbsp;';
		
        var mysack = new sack();
        //mysack.requestFile = "/enterprise/Enterprise/updateListingsContactDetails/";
		mysack.requestFile = "/listing/posting/ListingUpdate/updateContactDetails/";
        mysack.method = 'POST';

        mysack.setVar("contact_name_location", $('contact_name_location').value);
        mysack.setVar("contact_phone_location", $('contact_phone_location').value);
        mysack.setVar("contact_mobile_location", $('contact_mobile_location').value);
        mysack.setVar("contact_email_location", $('contact_email_location').value);        
        mysack.setVar("locationIds_for_institute", locationIds_for_institute);
        mysack.setVar("locationIds_for_courses", locationIds_for_courses);
        mysack.setVar("institute_id", institute_id);
        
        mysack.onError = function() {
            $('location_contact_container').innerHTML = 'Request Failed';
        };
        mysack.onCompletion = function() {
            showLocationContactDetails(institute_id);
            //alert("Thanks, Contact Details have been updated successfully for the selected listing(s). Please Publish the listing(s) to make them live.");
			alert("Thanks, Contact Details have been updated successfully for the selected listing(s).");
            hideOverlayAnA();
            $('contact_details_div').style.display='none';
            //$('previewPaneContainer').innerHTML = mysack.response;
        };
        mysack.runAJAX();
    }

    function publishListing(listings) {
        $('updateBttnContainer').innerHTML = '&nbsp;';
        $('previewPaneContainer').innerHTML = '<div class="Fnt14" style="height:50px; margin: 20px;"> &nbsp;Please wait, publishing Listing(s) ... <img src="/public/images/loader.gif"></div>';
        $('bttnSearch').disabled = true;
        var mysack = new sack();
        mysack.requestFile = "/enterprise/ShowForms/publishListings/";
        mysack.method = 'POST';

        mysack.setVar("listings", listings);

        mysack.onError = function() {
            $('previewPaneContainer').innerHTML = 'Request Failed';
        };
        mysack.onCompletion = function() {
            $('updateBttnContainer').innerHTML = '<input type="button" class="orange-button" id="bttnUpdate" name="bttnUpdate" value="Update Contact Details" onClick="showContactDetailsLayer()" />';
            refreshListingsPreviewPane();
            alert("Thanks, Listing(s) have been published successfully.");
            $('bttnSearch').disabled = false;
        };
        mysack.runAJAX();
    }

    function refreshListingsPreviewPane() {
        var mysack = new sack();
        mysack.requestFile = "/enterprise/Enterprise/refreshListingsPreviewPane/"+institute_id;
        mysack.method = 'POST';
        mysack.onCompletion = function() {
            $('previewPaneContainer').innerHTML = mysack.response;
        };
        mysack.runAJAX();
    }

    function searchInstitutesForKeyword()
    {
        if(!checkEmptyValue()) {
            return false;
        }
        $('location_contact_container').innerHTML = '<div class="Fnt14" style="height:200px; margin: 20px;"> &nbsp;Please wait, searching Institutes ... <img src="/public/images/loader.gif"></div>';
        $('previewPaneContainer').innerHTML = '&nbsp;';
        $('updateBttnContainer').innerHTML = '&nbsp;';
        var mysack = new sack();
        mysack.requestFile = "/enterprise/Enterprise/searchInstitutesForKeyword/";
        mysack.method = 'POST';
        mysack.setVar("searchedKeyword", $("instituteNameTxtBox").value);
        mysack.onError = function() {
            $('location_contact_container').innerHTML = 'Request Failed';
        };
        mysack.onCompletion = function() {
            $('location_contact_container').innerHTML = mysack.response;
        };
        mysack.runAJAX();
    }
    
    var institute_id;
    function showLocationContactDetails(insId)
    {
        $('location_contact_container').innerHTML = '<div class="Fnt14" style="height:200px; margin: 20px;"> &nbsp;Please wait, fetching Contact Details ... <img src="/public/images/loader.gif"></div>';
        institute_id = insId;
        var mysack = new sack();
        mysack.requestFile = "/enterprise/Enterprise/getInstituteLocationContactDetails/";
        mysack.method = 'POST';
        mysack.setVar("instituteId", insId);
        mysack.onError = function() {
            $('location_contact_container').innerHTML = 'Request Failed';
        };
        mysack.onCompletion = function() {
           $('location_contact_container').innerHTML = mysack.response;
           $('updateBttnContainer').innerHTML = '<input type="button" class="orange-button" id="bttnUpdate" name="bttnUpdate" value="Update Contact Details" onClick="showContactDetailsLayer()" />';
        };
        mysack.runAJAX();
    }

    function checkEmptyValue()
    {
        if(trim($("instituteNameTxtBox").value) == "") {
            alert("Please enter the Institute name to search first!");
            $("instituteNameTxtBox").focus();
            return false;
        }
        return true;
    }

    var locationIds_for_institute;
    var locationIds_for_courses;
    function validateCheckBoxes() {
        var isChecked = 0; locationIds_for_institute = ""; locationIds_for_courses = "";
        var c = document.forms['update_contact_form'].getElementsByTagName('input');
        fieldLen = c.length;
        for( var i=0; i < fieldLen; i++) {
            if (c[i].type == 'checkbox' && c[i].checked == true) {
                // Now lets collect the location ids that user wants to update..
                chkBoxId = c[i].id;
                if(chkBoxId.indexOf("_institute_") != -1) { // This is the location of the Institute
                        locationIds_for_institute += (locationIds_for_institute == "" ? c[i].value : ", " + c[i].value);
                } else { // This is the location of the Course
                        courseIndex = chkBoxId.indexOf("_course_");
                        courseIdIndex = courseIndex + 8;
                        courseId = chkBoxId.substring(courseIdIndex);
                        valueToStore = (courseId + "||" + c[i].value);
                        locationIds_for_courses += (locationIds_for_courses == "" ? valueToStore : ", " + valueToStore);
                }

                isChecked = 1;
                                
            }   // End of if (c[i].type == 'checkbox' && c[i].checked == true)
            
        }   // End of for( var i=0; i < fieldLen; i++).
        
        if(isChecked == 0) {
            return false;
        } else {
            return true;
        }
    }

    function validateContactDetails() {
	
	    if (!validateContactDetailsFormFields()) {
		return false;
	    }
      
            var contact_name_location = trim($('contact_name_location').value);
            var contact_phone_location = trim($('contact_phone_location').value);
            var contact_mobile_location = trim($('contact_mobile_location').value);
            var contact_email_location =  trim($('contact_email_location').value);
            var isAllDetailsEmptyFlag = 1;

            if(contact_name_location !='') {
                    isAllDetailsEmptyFlag = 0;
            }
            if(contact_phone_location !='') {
                    isAllDetailsEmptyFlag = 0;
            }
            if(contact_mobile_location !='') {
                    isAllDetailsEmptyFlag = 0;
            }
            if(contact_email_location !='') {
                    isAllDetailsEmptyFlag = 0;
            }
	    
            if(isAllDetailsEmptyFlag == 1 && !confirm("You are about to remove the Contact Details of selected listing(s), are you sure you want to continue?")) {
                return false;
            }

            return true;
    }

    function checkEnterKey(e) {
        if (e.keyCode == 13) {
            searchInstitutesForKeyword();
            return false;
        }
    }
</script>