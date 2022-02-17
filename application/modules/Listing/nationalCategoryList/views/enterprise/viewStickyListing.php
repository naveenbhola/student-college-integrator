<?php
$headerComponents = array(
    'css'          => array('headerCms', 'mainStyle'),
    'js'           => array('common', 'category-sponsor'),
    'displayname'  => $displayName,
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
    <form id="searchshoskeles"
          onsubmit="CategorySponsor.validateClient('categorysponsorclientid','stickyListing');return false;">
        <div style="float:left; width:100%">
            <div
                style="height:45px;background-image:url(/public/images/enterpriceBg.gif); background-repeat:repeat-x;padding:0 10px;">
                Enter Client Id:
                <input type="text" id="categorysponsorclientid" value="<?php echo $clientId; ?>" onkeyup="if(event.keyCode == 13){ CategorySponsor.validateClient('categorysponsorclientid','stickyListing'); }"/>
	    <span id="categorysponsorclientid_error" style="color:red">
                <?php if (!is_array($sponsoredInstitutes)) {
                    echo $sponsoredInstitutes;
                } ?>
        </span>
                <button class="btn-submit7 w6" name="filterMedia" id="filterMedia" value="Filter_MediaData_CMS"
                        type="button"
                        onClick="return CategorySponsor.validateClient('categorysponsorclientid','stickyListing');"
                        style="margin-left:10px;width:150px">
                    <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Search Sticky Listing</p></div>
                </button>
                <?php if ($clientId != '') { ?>
                    <button class="btn-submit7 txt_align_r" style="margin-left: 300px;" onclick="CategorySponsor.showStickyListingAdditionLayer(event);">
                        <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog"
                                                    >Add New
                                Sticky Listing</p></div>
                    </button>
                <?php } ?>
            </div>
        </div>
    </form>
    <?php if (is_array($sponsoredInstitutes)) {
        ?>

        <div class="lineSpace_10">&nbsp;</div>
        <div class="row normaltxt_11p_blk">
            <input type="hidden" id="methodName" value="getPopularNetworkCMSajax"/>

            <div id="paginataionPlace1" style="display:none;"></div>
            <div class="boxcontent_lgraynoBG bld">
                <div class="float_L"
                     style="background-color:#EFEFEF; width:10%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4">
                    &nbsp; &nbsp;Listing Id
                </div>
                <div class="float_L"
                     style="background-color:#EFEFEF; width:30%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4">
                    &nbsp; &nbsp;Criterion
                </div>
                <div class="float_L"
                     style="background-color:#EFEFEF; width:15%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4">
                    &nbsp; &nbsp;Subscription Id
                </div>
                <div class="float_L"
                     style="background-color:#EFEFEF; width:20%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4">
                    &nbsp; &nbsp;City / State
                </div>
                <div class="float_L"
                     style="background-color:#EFEFEF; width:23%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4">
                    &nbsp; &nbsp;Remove Listing
                </div>
                <div class="clear_L"></div>
            </div>
        </div>
    <?php } ?>
    <div id="cmsNetworkTable" name="cmsNetworkTable" class="row normaltxt_11p_blk boxcontent_lgraynoBG"
         style="height:290px; overflow:auto">
        <?php
        if (is_array($sponsoredInstitutes)) {
            for ($i = 0; $i < count($sponsoredInstitutes); $i++) { ?>
                <div class="normaltxt_11p_blk" style="border-bottom:1px solid #999999;cursor:pointer" onClick="">
                    <div class="float_L" style="width:10%;">
                        <div class="mar_full_10p">
                            <div class="lineSpace_10">&nbsp;</div>
                            <div><?php echo $sponsoredInstitutes[ $i ]['institute_id']; ?></div>
                            <div class="clear_L"></div>
                        </div>
                    </div>
                    <div class="float_L" style="width:30%;">
                        <div class="mar_full_10p">
                            <div class="lineSpace_10">&nbsp;</div>
                            <div><?php echo $sponsoredInstitutes[ $i ]['criterion_name']; ?></div>
                            <div class="clear_L"></div>
                        </div>
                    </div>
                    <div class="float_L" style="width:15%;">
                        <div class="mar_full_10p">
                            <div class="lineSpace_10">&nbsp;</div>
                            <div
                                style="margin-left:10px;"><?php echo $sponsoredInstitutes[ $i ]['subscription_id']; ?></div>
                            <div class="clear_L"></div>
                        </div>
                    </div>

                    <div class="float_L" style="width:20%;">
                        <div class="mar_full_10p">
                            <div class="lineSpace_10">&nbsp;</div>
                            <div style="margin-left:10px;"><?php echo $sponsoredInstitutes[ $i ]['location']; ?></div>
                            <div class="clear_L"></div>
                        </div>
                    </div>
                    <div class="float_L" style="width:23%;">
                        <div class="mar_full_10p">
                            <div class="lineSpace_10">&nbsp;</div>
                            <div style="margin-left:15px;"><a href="javascript:void(0)"
                                                              onClick="CategorySponsor.unsetStickyListing('<?php echo $sponsoredInstitutes[ $i ]['listing_subs_id']; ?>');">Remove
                                    Listing</a></div>
                            <div class="clear_L"></div>
                        </div>
                    </div>
                    <div class="clear_L"></div>
                    <div class="lineSpace_5">&nbsp;</div>
                </div>
            <?php }
        } ?>
    </div>
</div>
<?php
$this->load->view('enterprise/footer');
$this->load->view('nationalCategoryList/enterprise/addStickyListing');
?>
