<?php
$headerComponents = array(
    'css'          => array('headerCms','mainStyle'),
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
            <div
                style="height:45px;background-image:url(/public/images/enterpriceBg.gif); background-repeat:repeat-x;padding:0 10px;">
                Enter Client Id:
                <input type="text" id="categorysponsorclientid" value="<?php echo $clientId; ?>" onkeyup="if(event.keyCode == 13){ CategorySponsor.validateClient('categorysponsorclientid','shoshkele'); }"/>
            <span id="categorysponsorclientid_error" class="errorMsg"></span>

                <button class="btn-submit7 w6" name="filterMedia" id="filterMedia" value="Filter_MediaData_CMS"
                        type="button" onClick="CategorySponsor.validateClient('categorysponsorclientid','shoshkele');"
                        style="margin-left:10px;width:150px">
                    <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Search Shoshkeles</p></div>
                </button>
                <?php if ($clientId!= '') { ?>
                    <button class="btn-submit7 txt_align_r" style="margin-left: 365px;" onClick="Shoshkele.showShoshkeleLayer('Add Shoshkele',''); event.preventDefault();">
                        <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog" >Add Shoshkele</p></div>
                    </button>
                <?php } ?>
            </div>
        </div>


        <?php
        if (is_array($shoshkeleList) && count($shoshkeleList) > 0) { ?>
            <div class="lineSpace_10">&nbsp;</div>
            <div class="row normaltxt_11p_blk">
                <input type="hidden" id="methodName" value="getPopularNetworkCMSajax"/>

                <div id="paginataionPlace1" style="display:none;"></div>
                <div class="boxcontent_lgraynoBG bld">
                    <div class="float_L"
                         style="background-color:#EFEFEF; width:19%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4"><span style="padding-left:10px">Shoshkele Name</span>
                    </div>
                    <div class="float_L"
                         style="background-color:#EFEFEF; width:8%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4; word-wrap: break-word;">
                        <span style="padding-left:10px">Status</span></div>
                    <div class="float_L"
                         style="background-color:#EFEFEF; width:12%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4; word-wrap: break-word;">
                        <span style="padding-left:10px">Criterion</span></div>
                    <div class="float_L"
                         style="background-color:#EFEFEF; width:15%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4; word-wrap: break-word;">
                        <span style="padding-left:10px">Location</span></div>
                    <div class="float_L"
                         style="background-color:#EFEFEF; width:12%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4; word-wrap: break-word;">
                        <span style="padding-left:10px">Subscription Id</span></div>
                    <div class="float_L"
                         style="background-color:#EFEFEF; width:15%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4; word-wrap: break-word;">
                        <span style="padding-left:10px">Change Shoshkele</span></div>
                    <div class="float_L"
                         style="background-color:#EFEFEF; width:15%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4; word-wrap: break-word;">
                        <span style="padding-left:10px">Use Shoshkele</span></div>
                    <div class="clear_L"></div>
                </div>
            </div>
        <?php } else {
            if ($clientId > 0 && is_array($shoshkeleList)) {
                ?>
                <div class="lineSpace_20">&nbsp;</div>
                <div class="boxcontent_lgraynoBG bld">
                    <div
                        style="background-color:#D1D1D3;padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4"
                        align="center">No results found for the mentioned client id.
                    </div>
                </div>

            <?php }
        }
        ?>
        <?php if ($clientId > 0) { ?>

        <div id="cmsNetworkTable" name="cmsNetworkTable" class="row normaltxt_11p_blk boxcontent_lgraynoBG"
             style="height:290px; overflow:auto;">
            <div class="float_L" style="padding-left:80%;width:18%;">
                <div class="lineSpace_10">&nbsp;</div>
            </div>
            <div class="clear_L"></div>
            <?php }
            $index = 0;
            foreach($shoshkeleList as $oneBanner) {
                if ($oneBanner[0]['criterion_name'] == '' && $oneBanner[0]['subscription_id'] == '') {
            ?>
                <span>
            <div style="border-bottom:1px solid #999999;cursor:pointer" onclick="">
                <div class="float_L" style="width:19%;">
                    <div class="mar_full_10p">
                        <div class="lineSpace_10">&nbsp;</div>
                        <div>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" style="color:#0065DE"
                                                        onclick="openwindow('<?php echo $oneBanner[0]["bannerurl"]; ?>');"><?php echo $oneBanner[0]["bannername"]; ?></a>
                        </div>
                        <div class="clear_L"></div>
                    </div>
                </div>
                <div class="float_L" style="width:8%; word-wrap: break-word;">
                    <div class="mar_full_10p">
                        <div class="lineSpace_10">&nbsp;</div>
                        <div>Unused</div>
                        <div class="clear_L"></div>
                    </div>
                </div>
                <div class="float_L" style="width:12%; word-wrap: break-word;">
                    <div class="mar_full_10p">
                        <div class="lineSpace_10">&nbsp;</div>
                        <div><?php echo $oneBanner[0]['criterion_name']; ?></div>
                        <div class="clear_L"></div>
                    </div>
                </div>
                <div class="float_L" style="width:15%; word-wrap: break-word;">
                    <div class="mar_full_10p">
                        <div class="lineSpace_10">&nbsp;</div>
                        <div><?php echo $oneBanner[0]['location']; ?></div>
                        <div class="clear_L"></div>
                    </div>
                </div>
                <div class="float_L" style="width:12%; word-wrap: break-word;">
                    <div class="mar_full_10p">
                        <div class="lineSpace_10">&nbsp;</div>
                        <div><?php echo $oneBanner[0]['subscription_id']; ?></div>
                        <div class="clear_L"></div>
                    </div>
                </div>
                <div class="float_L" style="width:15%; word-wrap: break-word;">
                    <div class="mar_full_10p">
                        <div class="lineSpace_10">&nbsp;</div>
                        <a href="#"
                           onclick="Shoshkele.showShoshkeleLayer('Change Shoshkele','<?php echo $oneBanner[0]["bannerid"]; ?>','<?php echo $oneBanner[0]['bannername']; ?>');">Change
                            Shoshkele</a>

                        <div class="clear_L"></div>
                    </div>
                </div>
                <div class="float_L" style="width:15%; word-wrap: break-word;">
                    <div class="mar_full_10p">
                        <div class="lineSpace_10">&nbsp;</div>
                        <a href="javascript: void(0)"
                           onclick="Shoshkele.checkExistence('<?php echo $oneBanner[0]["bannerid"]; ?>','<?php echo $oneBanner[0]["bannername"]; ?>');">Select
                            &amp; Use</a>
                    </div>
                </div>
                <div class="float_L" style="width:8%; word-wrap: break-word;">
                    <div class="mar_full_10p">
                        <div class="lineSpace_10">&nbsp;</div>
                        <a href="javascript:void(0)"
                           onclick="Shoshkele.removeShoshkele('<?php echo $oneBanner[0]['bannerid']; ?>', 'unused');">Removal</a>
                    </div>
                </div>
                <div class="clear_L"></div>
                <div class="lineSpace_5">&nbsp;</div>
            </div>
        </span>
                <?php } else {?>
                    <span>
                    <div style="border-bottom:1px solid #999999;cursor:pointer" onclick="">
                        <div class="float_L" style="width:19%;">
                            <div class="mar_full_10p">
                                <div class="lineSpace_10">&nbsp;</div>
                                <div><img src="/public/images/plusSign.gif"
                                          onclick="Shoshkele.showMultiUsedShoshkeles(<?php echo $index; ?>,'show');"
                                          id="imgsign<?php echo $index; ?>">&nbsp;<a
                                        style="color:#0065DE"
                                        href="javascript:void(0);"
                                        onclick="openwindow('<?php echo $oneBanner[0]['bannerurl']; ?>');"><?php echo $oneBanner[0]['bannername']; ?></a>
                                </div>
                                <div class="clear_L"></div>
                            </div>
                        </div>
                        <div class="float_L" style="width:8%; word-wrap: break-word;">
                            <div class="mar_full_10p">
                                <div class="lineSpace_10">&nbsp;</div>
                                <div></div>
                                <div class="clear_L"></div>
                            </div>
                        </div>
                        <div class="float_L" style="width:12%; word-wrap: break-word;">
                            <div class="mar_full_10p">
                                <div class="lineSpace_10">&nbsp;</div>
                                <div><br></div>
                                <div class="clear_L"></div>
                            </div>
                        </div>
                        <div class="float_L" style="width:15%; word-wrap: break-word;">
                            <div class="mar_full_10p">
                                <div class="lineSpace_10">&nbsp;</div>
                                <div></div>
                                <div class="clear_L"></div>
                            </div>
                        </div>
                        <div class="float_L" style="width:12%; word-wrap: break-word;">
                            <div class="mar_full_10p">
                                <div class="lineSpace_10">&nbsp;</div>
                                <div></div>
                                <div class="clear_L"></div>
                            </div>
                        </div>
                        <div class="float_L" style="width:15%; word-wrap: break-word;">
                            <div class="mar_full_10p">
                                <div class="lineSpace_10">&nbsp;</div>
                                <a href="javascript:void(0);"
                                   onclick="Shoshkele.showShoshkeleLayer('Change Shoshkele','<?php echo $oneBanner[0]['bannerid']; ?>','<?php echo $oneBanner[0]['bannername']; ?>');">Change
                                    Shoshkele</a>

                                <div class="clear_L"></div>
                            </div>
                        </div>
                        <div class="float_L" style="width:15%; word-wrap: break-word;">
                            <div class="mar_full_10p">
                                <div class="lineSpace_10">&nbsp;</div>
                                <a href="javascript:void(0);"
                                   onclick="Shoshkele.checkExistence('<?php echo $oneBanner[0]['bannerid']; ?>','<?php echo $oneBanner[0]['bannername']; ?>');">Select
                                    &amp; Use</a>
                            </div>
                        </div>
                        <div class="float_L" style="width:8%; word-wrap: break-word;">
                            <div class="mar_full_10p">
                                <div class="lineSpace_10">&nbsp;</div>
                                <a href="javascript:void(0)"
                                   onclick="Shoshkele.removeShoshkele('<?php echo $oneBanner[0]['bannerid']; ?>', 'unused');">Remove</a>
                            </div>
                        </div>
                        <div class="clear_L"></div>
                        <div class="lineSpace_5">&nbsp;</div>
                    </div>
                </span>

                    <div id="shoshid<?php echo $index; ?>" style="display: none;">
                        <?php foreach ($oneBanner as $banner) { ?>
                            <div style="border-bottom:1px solid #999999;cursor:pointer" onclick="">
                                <div class="float_L" style="width:19%;">
                                    <div class="mar_full_10p">
                                        <div class="lineSpace_10">&nbsp;</div>
                                        <div>&nbsp;&nbsp;&nbsp;</div>
                                        <div class="clear_L"></div>
                                    </div>
                                </div>
                                <div class="float_L" style="width:8%; word-wrap: break-word;">
                                    <div class="mar_full_10p">
                                        <div class="lineSpace_10">&nbsp;</div>
                                        <div>Used:</div>
                                        <div class="clear_L"></div>
                                    </div>
                                </div>
                                <div class="float_L" style="width:12%; word-wrap: break-word;">
                                    <div class="mar_full_10p">
                                        <div class="lineSpace_10">&nbsp;</div>
                                        <div><?php echo $banner['criterion_name']; ?></div>
                                        <div class="clear_L"></div>
                                    </div>
                                </div>
                                <div class="float_L" style="width:15%; word-wrap: break-word;">
                                    <div class="mar_full_10p">
                                        <div class="lineSpace_10">&nbsp;</div>
                                        <div><?php echo $banner['location']; ?></div>
                                        <div class="clear_L"></div>
                                    </div>
                                </div>
                                <div class="float_L" style="width:12%; word-wrap: break-word;">
                                    <div class="mar_full_10p">
                                        <div class="lineSpace_10">&nbsp;</div>
                                        <div><?php echo $banner['subscription_id']; ?></div>
                                        <div class="clear_L"></div>
                                    </div>
                                </div>
                                <div class="float_L" style="width:15%; word-wrap: break-word;">
                                    <div class="mar_full_10p">
                                        <div class="lineSpace_10">&nbsp;</div>
                                        &nbsp;
                                        <div class="clear_L"></div>
                                    </div>
                                </div>
                                <div class="float_L" style="width:8%; word-wrap: break-word;">
                                    <div class="mar_full_10p">
                                        <div class="lineSpace_10">&nbsp;</div>
                                        <a href="javascript:void(0)"
                                           onclick="Shoshkele.removeShoshkele('<?php echo $banner['banner_link_id']; ?>', 'used');">Remove</a>
                                    </div>
                                </div>
                                <div class="clear_L"></div>
                                <div class="lineSpace_5">&nbsp;</div>
                            </div>
                    <?php } ?>
                    </div>

                <?php }
                $index ++;
            }?>
        </div>
    </div>
<?php
$this->load->view('enterprise/footer');
$this->load->view('nationalCategoryList/enterprise/uploadShoshkele');
$this->load->view('nationalCategoryList/enterprise/selectShoshkele');
?>