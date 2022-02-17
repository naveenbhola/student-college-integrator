<?php
    $headerComponents = array(
        'css'      =>        array('headerCms','raised_all','mainStyle','footer'),
        'js'         =>            array('user','tooltip','common','newcommon','prototype'),
        'jsFooter'         =>      array('scriptaculous','utils'),
        'title'      =>        'SUMS - Client selection for creating quotation page',
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
        <div class="bld fontSize_14p OrgangeFont" style="padding-left:10px;">Search Quotation</div>
        <div class="grayLine"></div>
        <div class="lineSpace_10">&nbsp;</div>

        <div style="display:inline;float:left;width:100%">
        <form id="formForQuoteUsers" action="" method="POST">
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


            <div class="row">
                <div style="display: inline; float:left; width:100%">
                    <div class="r1_1 bld">COLLEGE/INSTITUTE/UNIVERSITY NAME:&nbsp;&nbsp;</div>
                    <div class="r2_2">
                        <input name="collegename" id="collegename" type="text" size="30" maxlength="100" minlength="3" caption="College Name" />
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

            <div class="row">
                <div style="display: inline; float:left; width:100%">
                    <div class="r1_1 bld">Quotation ID:&nbsp;&nbsp;</div>
                    <div class="r2_2">
                        <input name="quotationId" id="quotationId" type="text" size="30" maxlength="25" minlength="3" caption="Quotation Id" />
                    </div>
                    <div class="clear_L"></div>
                    <div class="row errorPlace" style="margin-top:2px;">
                        <div class="r1_1">&nbsp;</div>
                        <div class="r2_2 errorMsg" id= "quotationId_error"></div>
                    </div>
                </div>
            </div>
            <div class="lineSpace_10">&nbsp;</div>

           <div class="row">
                <div style="display: inline; float:left; width:100%">
                    <div class="r1_1 bld">Quotation Ammount:&nbsp;&nbsp;</div>
                    <div class="r2_2">
                        <input name="quotationValue" id="quotationValue" type="text" size="30" maxlength="25" minlength="3" caption="Quotation Ammount" />
                    </div>
                    <div class="clear_L"></div>
                    <div class="row errorPlace" style="margin-top:2px;">
                        <div class="r1_1">&nbsp;</div>
                        <div class="r2_2 errorMsg" id= "quotationValue_error"></div>
                    </div>
                </div>
            </div>
            <div class="lineSpace_10">&nbsp;</div>

           <div class="row">
                <div style="display: inline; float:left; width:100%">
                    <div class="r1_1 bld">Quotation Created By:&nbsp;&nbsp;</div>
                    <div class="r2_2">
                        <input name="quotationCreater" id="quotationCreater" type="text" size="30" maxlength="25" minlength="3" caption="Quotation Created By" />
                    </div>
                    <div class="clear_L"></div>
                    <div class="row errorPlace" style="margin-top:2px;">
                        <div class="r1_1">&nbsp;</div>
                        <div class="r2_2 errorMsg" id= "quotationCreater_error"></div>
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
        </form>
        </div>
    <div class="clear_L"></div>

        <form method="POST" id="frmSelectUser" action="">
            <div id="userresults">
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

<script>

    function validateQuoteUsers(){
            new Ajax.Updater('userresults','/sums/Quotation/getUsersForQuotation/<?php echo $prodId; ?>',{onBeforeAjax:showDataLoader($('userresults')),parameters:Form.serialize($('formForQuoteUsers')),onComplete:function(){
                                 hideDataLoader($('userresults'));       
            }}); return false;
            $('formForQuoteUsers').submit();
            }


    function validateFormSums(){
            radioSelChk = validateradio();
            if(radioSelChk){
                    $('frmSelectUser').submit();
                }else{
                    document.getElementById('radio_unselect_error').innerHTML = "Please select a Quotation to continue !!";
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
