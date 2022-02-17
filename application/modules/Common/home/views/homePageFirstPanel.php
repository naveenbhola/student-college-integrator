<div class="marfull_LeftRight10">
    <div style="width:100%">
        <div id="homeShik_leftSide">
            <div id="homeShik_rightSide">
                <div id="homeShik_midSide">
                    <div style="width:100%">
                        <div class="float_L" style="width:356px;height:258px">
                            <div class=" loaderBg" style="width:356px;height:129px">
                            <!-- Code added for FConnect js on Banner -->
                            <!--<div style="position:relative">
                            <iframe scrolling="no" id="iframe_div_FB" name="iframe_div_FB" src="about:blank" frameborder="0" style="width:356px; height:129px;position:absolute; display:block; top:0px;left:0px;z-index:998;filter:alpha(opacity:20);opacity: .2;" container="hackFConnect"></iframe>
                            <div id='hackFConnect' style="width:356px;height:129px;opacity:1;z-index:999;position:absolute;top:0px;left:0px;cursor:pointer;" class="float_L" onClick="setCookieForFB('fHeader');showOverlayForFConnect();"></div> 
                            </div>-->
                            <!-- Code end for Fconnect JS -->
                                <?php
                                //$this->load->view('home/homePageLeftAdsPanel');
                                $bannerProperties = array('pageId'=>'HOME', 'pageZone'=>'LEFTSIDE1');
                                $this->load->view('common/banner',$bannerProperties);
                                ?>
                            </div>
                            <div class="lineSpace_10">&nbsp;</div>
                            <div style="border:2px solid #b8deff;background:#fff;padding:10px">

                                <div class="Fnt14" style="margin-bottom:4px"><strong>Featured Institutes</strong></div>

                                <div style="width:100%">
                                    <div>
                                        <?php
                                        //$this->load->view('home/homePageLeftAdsPanel');
                                        $bannerProperties = array('pageId'=>'HOME', 'pageZone'=>'LEFTSIDE2');
                                        $this->load->view('common/banner',$bannerProperties);
                                        ?>
                                    </div>
                                    <div class="lineSpace_8">&nbsp;</div>
                                    <?php
                                    //$this->load->view('home/homePageLeftAdsPanel');
                                    $bannerProperties = array('pageId'=>'HOME', 'pageZone'=>'LEFTSIDE3');
                                    $this->load->view('common/banner',$bannerProperties);
                                    ?>

                                </div>

                            </div>
                        </div>

                        <div style="margin-left:368px">
                            <div class="float_L" style="width:100%">
                                <div style="width:100%">
                                    <?php
                                    //$this->load->view('home/homePageRightSearchPanel',array('showProduct' => false,'productSelect' => 'institute', 'showAutoSuggestion' => true));
                                    $this->load->view('home/homePageRightSearchPanel',array('showProduct' => false,'productSelect' => 'institute'));
                                    $this->load->view('home/homePageRightCategoryPanel');
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="clear_L">&nbsp;</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function showSideBannerForHomePage() {
        var leftSideBanner = $('LEFTSIDE1');
        leftSideBanner.src = bannerPool['LEFTSIDE1'];
        leftSideBanner.style.display = 'none';
        window.setTimeout(function(){$('LEFTSIDE1').style.display = ''; },2000);

    }
    showSideBannerForHomePage();
</script>

