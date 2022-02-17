<?php
    $headerComponents = array(
        'css'      =>        array('headerCms','raised_all','mainStyle','footer'),
        'js'         =>            array('user','tooltip','common','newcommon','prototype'),
        'jsFooter'         =>      array('scriptaculous','utils'),
        'title'      =>        'Response Viewer By Client / By Listings',
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
?>
<script>
function showSearchForm() 
	{
		if(document.getElementById('byClient').checked) {
			document.getElementById('showSearchByClient').style.display ='block';
			document.getElementById('showSearchByListing').style.display ='none';
		}
		if(document.getElementById('byListing').checked) {
			document.getElementById('showSearchByListing').style.display ='block';
			document.getElementById('showSearchByClient').style.display ='none';
		}
	}
function showDiv(divName){
	document.getElementById(divName).style.display='block';
}
function hideDiv(divName){
	document.getElementById(divName).style.display='none';
}
</script>
<div class="lineSpace_10">&nbsp;</div>
<div class="lineSpace_10">&nbsp;</div>
<div class="mar_full_10p">
	<div class="OrgangeFont fontSize_16p bld">Response Viewer</div>
	<div style="float:right;"><a href="/lms/lms/getLeadsByListingBE" target="_blank">Get All Responses</a></div>
	<div class="lineSpace_20">&nbsp;</div>	
	<div class="fontSize_13p bld">Get Response</div>
	<div class="lineSpace_10">&nbsp;</div>	
	<div class="fontSize_13p"><span style="font-weight:normal;padding-left:15px"><input type="radio" name="r1" id="byClient" onclick="showSearchForm()" /> By Client</span> <span style="font-weight:normal; padding-left:30px"><input type="radio" name="r1" id="byListing" onclick="showSearchForm()" /> By Listing</span></div>
	<div class="lineSpace_10">&nbsp;</div>
	<div id="showSearchByClient" style="display:none">
		<?php $this->load->view('lms/searchByClientForm'); ?>
	</div>
	<div id="showSearchByListing" style="display:none">
		<?php $this->load->view('lms/searchByListingForm'); ?>
	</div>
</div>
<script>
	function getXMLHTTPObject() {
                var xmlHttp;
                try {
                    xmlHttp=new XMLHttpRequest();
                } catch (e) {
                    try {
                        xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
                    } catch (e) {
                        try {
                            xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
                        } catch (e) {
                            alert("Your browser does not support AJAX!");
                            return false;
                        }
                    }
                }
                return xmlHttp;
    }
	function getClientListing(id){
	 var xmlHttp = getXMLHTTPObject();
                xmlHttp.onreadystatechange=function() {
                    if(xmlHttp.readyState==4) {
                        var retData = xmlHttp.responseText;						
						document.getElementById('showClientListingDetail').innerHTML = retData;	
						getSelectBoxListing(id);
                    }
                }
				var url = "getLeadsForClient/"+id;
                xmlHttp.open("GET",url ,true);
                xmlHttp.send(null);
				
	}
	function getSelectBoxListing(id){
	 var xmlHttp = getXMLHTTPObject();
                xmlHttp.onreadystatechange=function() {
                    if(xmlHttp.readyState==4) {
                        var retData = xmlHttp.responseText;
						document.getElementById('showSearchListingLeads').innerHTML = retData;						
                    }
                }
                var url = "getListings/"+id;
                xmlHttp.open("GET",url ,true);
                xmlHttp.send(null);
	}

	function getListingLeads(){
		var listingId= document.getElementById('listing_type_id').value;
		var listingType= document.getElementById('listing_type').value;
		var xmlHttp = getXMLHTTPObject();
                xmlHttp.onreadystatechange=function() {
                    if(xmlHttp.readyState==4) {
                        var retData = xmlHttp.responseText;
						document.getElementById('showListingLeads').innerHTML = retData;						
                    }
                }
				if(listingId!='') {
					var url = "getLeadsByListing/"+listingType+"/"+listingId;
				}
				else {
					var url = "getLeadsByListing/"+listingType;
				}
                xmlHttp.open("GET",url ,true);
                xmlHttp.send(null);
	}

	function getByClientListingLeads(){
		var listingValue = document.getElementById('searchLeadByClient').value;
		if(!isNaN(listingValue)) {
			getClientListing(listingValue);
		}
		else
		{ 
			var listingArray = listingValue.split("-");
			var xmlHttp = getXMLHTTPObject();
                xmlHttp.onreadystatechange=function() {
                    if(xmlHttp.readyState==4) {
                        var retData = xmlHttp.responseText;
						document.getElementById('showLeadsBySearch').innerHTML = '';
						document.getElementById('showLeadsBySearch').innerHTML = retData;						
                    }
                }
				if(listingArray[1]!='') {
					var url = "getLeadsByListing/"+listingArray[0]+"/"+listingArray[1];
				}
				else {
					var url = "getLeadsByListing/"+listingArray[0];
				}
                xmlHttp.open("GET",url ,true);
                xmlHttp.send(null);
		}
	}

    function validateQuoteUsers(){
            new Ajax.Updater('userresults','getUsersForQuotation',{parameters:Form.serialize($('formForQuoteUsers'))}); return false;
            $('formForQuoteUsers').submit();
    }
    function validateFormSums(){
            radioSelChk = validateradio();
            if(radioSelChk){
                    $('frmSelectUser').submit();
                }else{
                    document.getElementById('radio_unselect_error').innerHTML = "Please select a User to continue !!";
                    document.getElementById('radio_unselect_error').style.display = 'inline';
            }
    }
    function validateradio()
    {
            for(var i=1;i<=document.getElementById('totalUserCount').value;i++)
            {
                    if(document.getElementById('userNo_'+i+'').checked == true){
                            return true;
                        }else{
                            continue;
                    }
            }
            return false;
    }
</script>
</body>
</html>
