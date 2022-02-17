<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Add New Institute and Course</title>

        <?php
            $headerComponents = array(
                'css'   => array('headerCms','mainStyle','raised_all','footer','cal_style'),
                'js'    => array('common','newcommon','listing','prototype','utils','tooltip','CalendarPopup','enterprise'),
                'taburl' => site_url('enterprise/Enterprise'),
                'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:"")
            );
            $this->load->view('enterprise/headerCMS', $headerComponents);
            $this->load->view('common/calendardiv');
        ?>
    </head>

    <body>

        <script>
            var SITE_URL = '<?php echo base_url() ."/";?>';
        </script>

        <div class="lineSpace_5">&nbsp;</div>
        <div>
            <?php $this->load->view('enterprise/cmsTabs'); ?>
        </div>

        <div class="mar_full_10p">
            <div class="raised_lgraynoBG">
                <b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
                <div class="boxcontent_lgraynoBg">
                    <!--Start_Center-->
                    <!--End_Left_Panel-->
                    <?php if($onBehalfOf=="true") { ?>
                    <div class="lineSpace_5">&nbsp;</div>
                    <div class="mar_full_10p mar_top_6p">Selected Client - <b><?php echo $userDetails['email'];?></b> &amp; Client-Id :<b><?php echo $userid;?></b></div>
                    <div class="lineSpace_5">&nbsp;</div>
                    <?php } ?>
                    <div class="normaltxt_11p_blk fontSize_16p OrgangeFont mar_full_10p"><strong>Add Institute & Course</strong></div>
                    <div class="lineSpace_15">&nbsp;</div>
                    <div class="mar_full_10p" style="float_L">
                        <div class="raised_lgraynoBG">
                            <b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
                            <div class="boxcontent_lgraynoBG">
			       <?php $this->load->view('listing/course_sub_listing'); ?>
                            </div>
                            <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
                        </div>
                    </div>
                    <div class="clear_L"></div>
                </div>
                <!--End_Mid_Panel-->
                <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
            </div>
        </div>
        <script>
            var objForm = document.getElementById('courseListing');
            <?php if($usergroup=="cms" && $onBehalfOf!="true") { ?>
            insertCMSelement(objForm,<?php echo $prodId;  ?>);
            <?php } ?>
        </script>
        <?php $this->load->view('enterprise/footer'); ?>
