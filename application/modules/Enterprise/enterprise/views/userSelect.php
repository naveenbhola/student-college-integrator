<?php
    $headerComponents = array(
        'css'      =>        array('headerCms','raised_all','mainStyle','footer'),
        'js'         =>            array('user','tooltip','common','newcommon','prototype'),
        'jsFooter'         =>      array('scriptaculous','utils'),
        'title'      =>        'CMS - Client selection for Posting on-behalf-of a client',
        'tabName'          =>        'Register',
        'taburl' =>  SITE_URL_HTTPS.'enterprise/Enterprise/register',
        'metaKeywords'  =>'Some Meta Keywords',
        'product' => '',
        'search'=>false,
        'displayname'=> (isset($cmsUserInfo['validity'][0]['displayname'])?$cmsUserInfo['validity'][0]['displayname']:""),
        'callShiksha'=>1
    );
    $this->load->view('enterprise/headerCMS', $headerComponents);
    if(!isMMM) {
        $this->load->view('enterprise/cmsTabs',$cmsUserInfo);
    }
?>
<div id="dataLoaderPanel" style="position:absolute;display:none">
    <img src="/public/images/loader.gif"/>
</div>
<div class="mar_full_10p">
    <div style="margin-left:1px">		
        <?php if($isMMM) { ?>
            <div class="OrgangeFont fontSize_18p bld" style="margin-top:15px;"><strong>Search Client Profile</strong></div>
        <?php } else { ?>    
            <div class="bld fontSize_14p OrgangeFont" style="padding-left:10px;">Search Client Profile</div>
            <div class="grayLine"></div>
        <?php } ?>    
        
        <div class="lineSpace_10">&nbsp;</div>

        <form id="formForQuoteUsers" action="" method="POST">
            <?php if ($flag_listing_upgrade == '1' || $flag_listing_upgrade == '2') { ?>
            <input type="hidden" name="flag_listing_upgrade" value="<?php echo $flag_listing_upgrade; ?>" />
            <?php } ?>
            <input type="hidden" name="forListingPost" value="<?php echo $forListingPost; ?>" />
            <input type="hidden" name="validity_check" value="<?php echo $validity_check;?>">
            <div class="row">
                <div style="display:inline;float:left;width:100%">
                    <div class="r1_1 bld">LOGIN EMAIL ID:&nbsp;&nbsp;</div>
                    <div class="r2_2">
                        <input type="text" name="email" id="email" size="30" maxlength="125" minlength="5" caption="Email Id"/>
                    </div>
                    <div class="clear_L"></div>
                    <div class="row errorPlace" style="margin-top:2px;">
                        <div class="r1_1">&nbsp;</div>
                        <div class="r2_2 errorMsg" id= "email_error"></div>
                        <div class="clear_L"></div>
                    </div>
                </div>
            </div>
            <div class="lineSpace_10">&nbsp;</div>

            <div class="row">
                <div style="display: inline; float:left; width:100%">
                    <div class="r1_1 bld">DISPLAY NAME:&nbsp;&nbsp;</div>
                    <div class="r2_2">
                        <input name="displayname" id="displayname" type="text" size="30" maxlength="25" minlength="3" caption="Display Name" />
                    </div>
                    <div class="clear_L"></div>
                    <div class="row errorPlace" style="margin-top:2px;">
                        <div class="r1_1">&nbsp;</div>
                        <div class="r2_2 errorMsg" id= "displayname_error"></div>
                    </div>
                </div>
            </div>
            <div class="lineSpace_10">&nbsp;</div>


            <!-- <div class="row">
                <div style="display: inline; float:left; width:100%">
                    <div class="r1_1 bld">COLLEGE/INSTITUTE/UNIVERSITY NAME:&nbsp;&nbsp;</div>
                    <div class="r2_2">
                        <input name="collegeName" id="collegename" type="text" size="30" maxlength="100" minlength="3" caption="College Name" />
                    </div>
                    <div class="clear_L"></div>
                    <div class="row errorPlace" style="margin-top:2px;">
                        <div class="r1_1">&nbsp;</div>
                        <div class="r2_2 errorMsg" id= "collegename_error"></div>
                    </div>
                </div>
            </div>
            <div class="lineSpace_10">&nbsp;</div>

            <div class="row">
                <div style="display: inline; float:left; width:100%">
                    <div class="r1_1 bld">CONTACT NAME:&nbsp;&nbsp;</div>
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
                    <div class="r1_1 bld">CONTACT PHONE NO.:&nbsp;&nbsp;</div>
                    <div class="r2_2">
                        <input name="contactNumber" id="contactNumber" type="text" size="30" maxlength="25" minlength="3" caption="Contact Number" />
                    </div>
                    <div class="clear_L"></div>
                    <div class="row errorPlace" style="margin-top:2px;">
                        <div class="r1_1">&nbsp;</div>
                        <div class="r2_2 errorMsg" id= "contactNumber_error"></div>
                    </div>
                </div>
            </div>
            <div class="lineSpace_10">&nbsp;</div>
 -->
            <div class="row">
                <div style="display: inline; float:left; width:100%">
                    <div class="r1_1 bld">CLIENT ID:&nbsp;&nbsp;</div>
                    <div class="r2_2">
                        <input name="clientId" id="clientId" type="text" size="30" maxlength="25" minlength="3" caption="Client Id" />
                    </div>
                    <div class="clear_L"></div>
                    <div class="row errorPlace" style="margin-top:2px;">
                        <div class="r1_1">&nbsp;</div>
                        <div class="r2_2 errorMsg" id= "clientId_error"></div>
                    </div>
                </div>
            </div>
            <div class="lineSpace_10">&nbsp;</div>


            <div class="r1_1 bld">&nbsp;</div>
            <div class="r2_2">
                <button class="btn-submit19" onclick="validateQuoteUsers();" type="button" value="" style="width:100px">
                    <div class="btn-submit19"><p style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:12px" class="btn-submit20">Search</p></div>
                </button>
            </div>
            <div class="clear_L"></div>
            <div class="lineSpace_10">&nbsp;</div>
            <div class="clearFix"></div>
        </form>

        <form method="POST" id="frmSelectUser" action="">
            <input type="hidden" name="onBehalfOf" value="true" />
            <input type="hidden" name="extraInfoArray" value="<?php echo $extraInfoArray; ?>" />
            <?php if (($flag_listing_upgrade == '1')||($flag_listing_upgrade== '2')) { ?>
            <input type="hidden" name="instituteId" value="<?php echo $instituteId; ?>" />
            <input type="hidden" name="flag_listing_upgrade" value="<?php echo $flag_listing_upgrade; ?>" />
            <?php } ?>
            <div id="userresults"></div>
        </form>
        <div class="clearFix"></div>
    </div>
    <div class="clearFix"></div>
    <div class="lineSpace_35">&nbsp;</div>
</div>
<div class="clearFix"></div>
<?php $this->load->view('enterprise/footer');?>
    <br />
    <br />
</body>
</html>

<script>
    var owner_info = '<?php echo $owner_info;?>'
    //alert(owner_info);
    if(owner_info !=0) {
    	owner_info = eval('eval('+owner_info+')')
    }    
    function validateQuoteUsers(){
            $('userresults').innerHTML = '';
            new Ajax.Updater('userresults','/enterprise/Enterprise/getUsersForQuotation',{onBeforeAjax:showDataLoader($('userresults')),parameters:Form.serialize($('formForQuoteUsers')),onComplete:function(){
            hideDataLoader($('userresults'));
            }}); return false;
            $('formForQuoteUsers').submit();
            }


    function validateFormSums(){
            radioSelChk = validateradio();
            if(radioSelChk){
            		if(owner_info != 0) {
                		var firstname = owner_info.firstname;
                		var lastname = owner_info.lastname;
                		if(firstname == null) {
                			firstname = "";
                        }
                		if(lastname == null) {
                			lastname = "";
                        }
                		var owner_name = owner_info.displayname;
                		if(!owner_name) {
                			owner_name = firstname +" " + lastname;		
                        }
                                var confirm_msg = confirm("Existing owner of this listing is " + owner_name + ", Client Id - " + owner_info.userid + " and Existing subscription expiry date is - "+owner_info.expiry_date);
                                if(!confirm_msg) {
                                    return false;
                                }
            		}	
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
<script>
function addInstituteByType(type){
	var type;
	if($('academicInstitute').checked==true){
		type=1;
	}else if($('testprepInstitute').checked==true){
		type=2;
	}else{
		return false;
	}
	
	$('frmSelectUser').action='/index.php/enterprise/ShowForms/showInstituteForm/-1/'+type;
	if(validateFormSums());
//	document.getElementById("showInstituteSelectionForm").style.display = "block";
//window.location='<?php echo site_url().'/enterprise/ShowForms/ShowInstituteForm/-1/'; ?>'+type;
}
</script>
