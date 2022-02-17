<?php
$headerComponents = array(
    'css'          => array('headerCms', 'mainStyle'),
    'js'           => array('common', 'category-sponsor'),
    'title'        => 'CMS - Client selection for setting / unsetting a listing as Main',
    'metaKeywords' => '',
    'product'      => '',
    'search'       => false,
    'displayname'  => $displayName,
);
$this->load->view('enterprise/headerCMS', $headerComponents);
$this->load->view('enterprise/cmsTabs',$cmsUserInfo);
?>
<div id="dataLoaderPanel" style="position:absolute;display:none">
    <img src="/public/images/loader.gif"/>
</div>
<div class="mar_full_10p">
    <div style="margin-left:1px">		
        <div class="bld fontSize_14p OrgangeFont" style="padding-left:10px;">Search Client Profile</div>
        <div class="grayLine"></div>

        <div class="lineSpace_10">&nbsp;</div>

        <form id="searchClientByDetail" action="" method="POST" onsubmit="MainListing.getClientDetails(this, 'userresults');">
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
                <input type="submit" value="Search" class="orange-button"/>
            </div>

            <div class="clear_L"></div>
            <div class="lineSpace_10">&nbsp;</div>
            <div class="clearFix"></div>
        </form>
        <div id="userresults"></div>
        <div class="clearFix"></div>
    </div>
    <div class="clearFix"></div>
    <div class="lineSpace_35">&nbsp;</div>
</div>
<div class="clearFix"></div>
<?php $this->load->view('enterprise/footer');?>
