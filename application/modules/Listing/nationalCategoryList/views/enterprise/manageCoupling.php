<?php
$headerComponents = array(
    'css'          => array('headerCms', 'mainStyle'),
    'js'           => array('common', 'category-sponsor'),
    'displayname'  => (isset($cmsUserInfo[0]['displayname']) ? $cmsUserInfo[0]['displayname'] : ""),
    'tabName'      => '',
    'taburl'       => site_url('enterprise/Enterprise'),
    'metaKeywords' => ''
);
$this->load->view('enterprise/headerCMS', $headerComponents);
?>
<div id="dataLoaderPanel" style="position:absolute;display:none">
    <img src="/public/images/loader.gif"/>
</div>
<!--Start_Center-->
<div class="mar_full_10p">
    <?php
    $this->load->view('enterprise/cmsTabs');
    $this->load->view('nationalCategoryList/enterprise/categorySponsorTabs');
    ?>
    <div class="lineSpace_20">&nbsp;</div>
    <div style="float:left; width:100%">
        <form id="searchShoshkeles" method="post" action="/nationalCategoryList/CategoryProductEnterprise/manageCoupling/<?php echo $clientId; ?>" onsubmit="return false;">
            <div>
                Enter Client Id:&nbsp;
                <input type="text" id="categorysponsorclientid" value="<?php echo $clientId; ?>" onkeyup="if(event.keyCode == 13){  Shoshkele.getShoshkeleDetails(); }"/>
                <button class="orange-button" name="filterMedia" id="filterMedia" value="Filter_MediaData_CMS"
                        type="button"
                        onclick="Shoshkele.getShoshkeleDetails();"
                        style="margin-left:10px;width:150px">
                    <p class="btn-submit8 btnTxtBlog">Search Details</p>
                </button>
            </div>
            <div class="clear_L"></div>
            <div style="margin-top:2px;">
                <div class="errorMsg" style="padding-left:85px" id="categorysponsorclientid_error"></div>
            </div>
            <div class="lineSpace_10">&nbsp;</div>

            <?php if (intval($clientId) > 0) { ?>

                <div>
                    <select id="cities" name="cityId" caption="city" style="width: 27%;">
                        <option value=''>Select a City</option>
                        <?php
                        if (count($cities) > 0) {
                            foreach ($cities as $oneCity) { ?>
                                <option
                                    value='<?php echo $oneCity['city_id']; ?>' <?php echo ($cityId == $oneCity['city_id']) ? 'selected' : ''; ?>><?php echo $oneCity['city_name']; ?></option>
                            <?php }
                        } ?>
                    </select>
                </div>
                <div style="width: 250px;text-align: center;"><b>OR</b></div>
                <div>
                    <div>
                        <select id="states" name="stateId"
                                caption="state" style="width: 27%;">
                            <option value=''>Select a State</option>
                            <?php
                            if (count($states) > 0) {
                                foreach ($states as $oneState) { ?>
                                    <option
                                        value='<?php echo $oneState['state_id']; ?>' <?php echo ($stateId == $oneState['state_id']) ? 'selected' : ''; ?>><?php echo $oneState['state_name']; ?></option>
                                <?php }
                            } ?>
                        </select>
                    </div>
                </div>

                <div class="lineSpace_10">&nbsp;</div>

                <div>
                    <select id="criterion" name="criterionId" caption="category" style="width: 27%;">
                        <option value="">Select a Criterion</option>
                        <?php
                        if (count($criteria) > 0) {
                            foreach ($criteria as $oneCriteria) { ?>
                                <option
                                    value='<?php echo $oneCriteria['criterion_id']; ?>' <?php echo ($criterionId == $oneCriteria['criterion_id']) ? 'selected' : ''; ?>><?php echo $oneCriteria['criterion_name']; ?></option>
                            <?php }
                        } ?>
                    </select>
                </div>

                <div class="lineSpace_10">&nbsp;</div>

                <div class="orange-button" onClick="Shoshkele.searchStickyListing($('searchShoshkeles'));" style="height: 15px; width: 25%; text-align: center;">
                        <p class="btn-submit8 btnTxtBlog">Search Sticky Listing</p>
                </div>
            <div id="response_error" class="errorMsg"></div>
            <?php } ?>

        </form>
    </div>


    <?php if(intval($clientId) > 0){ ?>

    <div class="lineSpace_20">&nbsp;</div>
    <div class="boxcontent_lgraynoBG bld">
        Coupled:
    </div>
    <div class="lineSpace_10">&nbsp;</div>
    <div class="row normaltxt_11p_blk">
        <input type="hidden" id="methodName" value="getPopularNetworkCMSajax"/>

        <div id="paginataionPlace1" style="display:none;"></div>
        <div class="boxcontent_lgraynoBG bld">
            <div class="float_L"
                 style="background-color:#D1D1D3; width:33%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4">
                &nbsp; &nbsp;Shoshkele
            </div>
            <div class="float_L"
                 style="background-color:#EFEFEF; width:33%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4">
                &nbsp; &nbsp;Sticky Listing Id
            </div>
            <div class="float_L"
                 style="background-color:#EFEFEF; width:33%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4">
                &nbsp; &nbsp;</div>
            <div class="clear_L"></div>
        </div>

        <div class="lineSpace_10">&nbsp;</div>

        <?php foreach ($coupledData as $oneInstitute) { ?>
            <div class="normaltxt_11p_blk" style="border-bottom:1px solid #999999;cursor:pointer">
                <div class="float_L" style="width:33%;">
                    <div class="mar_full_10p">
                        <div class="lineSpace_10">&nbsp;</div>
                        <div><?php echo $oneInstitute['bannername']; ?></div>
                        <div class="clear_L"></div>
                    </div>
                </div>
                <div class="float_L" style="width:33%;">
                    <div class="mar_full_10p">
                        <div class="lineSpace_10">&nbsp;</div>
                        <div><?php echo $oneInstitute['institute_id']; ?></div>
                        <div class="clear_L"></div>
                    </div>
                </div>
                <div class="float_L" style="width:34%;">
                    <div class="mar_full_10p">
                        <div class="lineSpace_10">&nbsp;</div>
                        <div><a href="javascript:void(0)"
                                onClick="CategorySponsor.decouple('<?php echo $oneInstitute['couplingid']; ?>');">Decouple</a>
                        </div>
                        <div class="clear_L"></div>
                    </div>
                </div>

            </div>
            <div class="lineSpace_20">&nbsp;</div>
            <?php
        }
        ?>
        <div class="boxcontent_lgraynoBG bld">
            Decoupled:
        </div>
        <div class="lineSpace_10">&nbsp;</div>
        <div class="boxcontent_lgraynoBG bld">
            <div class="float_L"
                 style="background-color:#D1D1D3; width:33%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4">
                &nbsp; &nbsp;Shoshkele
            </div>
            <div class="float_L"
                 style="background-color:#EFEFEF; width:33%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4">
                &nbsp; &nbsp;Sticky Listing Id
            </div>
            <div class="float_L"
                 style="background-color:#EFEFEF; width:33%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4">
                &nbsp; &nbsp;</div>
            <div class="clear_L"></div>
        </div>
    </div>

    <div class="normaltxt_11p_blk" style="border-bottom:1px solid #999999;cursor:pointer" onClick="">
        <div class="float_L" style="width:33%;">
            <div class="mar_full_10p">
                <div class="lineSpace_10">&nbsp;</div>
                <div><select id="bannerlinkids">
                        <option value=''>Shoshkele Name</option>
                        <?php
                        foreach($shoshkeleList as $oneShoshkele) { ?>
                            <option value="<?php echo $oneShoshkele['shoshkele_id']; ?>"><?php echo $oneShoshkele['shoshkele_name']; ?></option>
                        <?php } ?>
                    </select></div>

                <div class="clear_L"></div>
            </div>
        </div>
        <div class="float_L" style="width:33%;">
            <div class="mar_full_10p">
                <div class="lineSpace_10">&nbsp;</div>
                <div><select id="listinglinkids">
                        <option value=''>
                            Listing Id
                        </option>
                        <?php foreach($clientListings as $oneListing){ ?>
                            <option value="<?php echo $oneListing['listing_subs_id']; ?>" ><?php echo $oneListing['institute_name']; ?> (<?php echo $oneListing['institute_id'] ;?> / <?php echo $oneListing['listing_subs_id'] ;?>)</option>

                        <?php }?>
                    </select></div>
                <div class="clear_L"></div>
            </div>
        </div>
        <div class="float_L" style="width:34%;">
            <div class="mar_full_10p">
                <div class="lineSpace_10">&nbsp;</div>
                <div><a href="javascript:void(0)" onClick="CategorySponsor.couple($j('#listinglinkids').val(), $j('#bannerlinkids').val());">Couple</a></div>
                <div class="clear_L"></div>
            </div>
        </div>
    </div>
    <div class="lineSpace_20">&nbsp;</div>
    <div class="errorMsg" style="padding: 5px 0 5px 30px;" id="coupledecouple_error"></div>

    <?php } ?>

</div>
<?php $this->load->view('enterprise/footer'); ?>
