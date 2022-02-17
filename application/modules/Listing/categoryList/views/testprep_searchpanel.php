<!--Start_SearchPanel-->
    <div style="width:100%">
    	<div class="shik_bgSearchPanel" style="height:108px">
        	<div style="width:100%">
            	<div style="width:238px;height:108px;position:relative" class="float_L">
                	<!--Start-LeftSide_Logo-->
                    <div id = "imagespace">

                    <!--End_LeftSide_Logo-->
                    </div>
                </div>
                <div style="margin-left:238px">
                	<div style="width:100%" class="float_L">
                            <div class="shik_bgSearchPanel_2">
                        <!-- Search Panel-->
                        <?php $this->load->view('home/commonSearchPanel');?>
                        <!-- Search Panel -->
                            <!--Start_CategoryPage_Title-->
                            <div style="width:100%">
                                <div>
                                    <div class="float_L">
                                    <h1><span class="Fnt18" style="color:#000"><?php echo $exam_categories['parent_blog_title'];?>&nbsp;<!--<span class="orangeColor Fnt12">&#9660;</span>--></span>
                                    <span class="Fnt14" style = "color:#000"><b> - <?php echo $exam_categories['exam_title']; ?></b></span> &nbsp;</h1>
                                    <br/>
                                        <span style="font-size:12px;font-weight:400">
                                    	<b style="color:#bdbdbd">[</b>
                                        <a href="#" style=""  onClick = "drpdwnOpen(this, 'overlayCategoryHolder');return false;">Change Category <span class="orangeColor">&#9660;</span></a>
                                        <b style="color:#bdbdbd">]</b>
                                    </span>
                                    </div>
                                    <div class="float_L" style="padding-top:2px">
                                        <h1><span class="Fnt14" style="color:#000"><?php echo $city_name?> &nbsp;</span></h1><br/>
                                    <span style="font-size:12px;font-weight:400">
                                    	<b style="color:#bdbdbd">[</b>
                                    	<a href="#" onclick = "showTestprepLocationlayer(<?php echo $openOverlay;?>);return false;" style="">Change Location <span class="orangeColor">&#9660;</span></a>
                                    	<b style="color:#bdbdbd">]</b>
                                    </span>
                                    </div>
                                    <div class="float_R"  style="padding-top:3px;width:220px;">

                                <?php
                                $btnimage = '';
                                $blogacronym = $params['blog_acronym'];
                                switch (true) {
    case $blogacronym == "Engineering" || $blogacronym == "WBJEE" || $blogacronym == "AIEEE" || $blogacronym == "IIT" || $blogacronym == "BITSAT":
        $btnimage = 'topIITcoaching.gif';
        $acr = "Engineering";
        break;
    case $blogacronym == "Medical" || $blogacronym == "AIPMT" || $blogacronym == "AIIMS":
        $btnimage = 'topMMBScoaching.gif';
        $acr = "Medical";
        break;
    case $blogacronym == "Foreign" || $blogacronym == "GMAT" || $blogacronym == "SAT":
        $btnimage = 'topGMATcoaching.gif';
        $acr = "Foreign";
        break;
    case $blogacronym == "MBA" || $blogacronym == "CAT" || $blogacronym == "MAT" || $blogacronym == "SNAP" || $blogacronym == "XAT":
        $btnimage = 'TopCATcoaching.gif';
        $acr = "MBA";
        break;
}
$user = $validateuser;
                                $logged_in = true;
                                if ($user == "false")
                                {$logged_in = false;}
                                if($btnimage != '') {
                                if ($logged_in) { ?>
                                        <a href='/shiksha/take_test/<?php echo $acr?>'><img src="/public/images/<?php echo $btnimage ?>" alt="" align="right" border="0" hspace="10" /></a>
                                <?php }
                                else { ?>
                                <a href="#" onclick="showuserLoginOverLay_ForAnA($('loginoverlaydiv'), 'TESTPREP_CATEGORY_PAGE_TOP_ONLINETEST', 'jsfunction', 'dummy');return false;"><img src="/public/images/<?php echo $btnimage ?>" alt="" align="right" border="0" hspace="10" /></a>
                                <?php } } ?>
                                                            <script type="text/javascript">
                                                            function dummy(){
                                                                location = '/shiksha/take_test/<?php echo $acr?>'
                                                            }
                                                            </script>

                                    </div>
                                    <div class="clear_B">&nbsp;</div>
                                </div>
                            </div>
                                                            <div id="loginoverlaydiv"></div>

                            <!--End_CategoryPage_Title-->
                    	</div>
                    </div>
                </div>
                <div class="clear_L">&nbsp;</div>
            </div>
        </div>
    </div>
    <!--End_SearchPanel-->

    <style>
    .ad_float_on{width:760px; height:100px;}
    .ad_float_off{width:220px; height:100px;}
    </style>
