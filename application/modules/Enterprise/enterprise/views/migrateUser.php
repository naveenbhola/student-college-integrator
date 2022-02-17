<?php
    $this->load->helper('form');
    $headerComponents = array(
        'css'      =>        array('headerCms','raised_all','mainStyle','footer'),
        'js'         =>            array('user','tooltip','common','newcommon','listing'),
        'jsFooter'         =>      array('prototype','scriptaculous','utils'),
        'title'      =>        'Upgrade to Enterprise',
        'tabName'          =>        'Register',
        'taburl' =>  SITE_URL_HTTPS.'enterprise/Enterprise/register',
        'metaKeywords'  =>'Some Meta Keywords',
        'product' => '',
        'search'=>false,
        'displayname'=> (isset($user['displayname'])?$user['displayname']:""),
        'callShiksha'=>1
    );
    $this->load->view('enterprise/headerCMS', $headerComponents);
?>
</head>
<div class="spacer8 clearFix"></div>
<!--Start_Center-->
<div class="mar_full_10p">
    <!--Start_Left_Panel-->
    <div id="left_Panel" style="margin:0">
        <!--Course_TYPE-->
        <div class="raised_blue_L">
            <b class="b2"></b>
            <div class="boxcontent_blue fontSize_12p">
                <div class="lineSpace_5">&nbsp;</div>
                <div class="bld"><span class="mar_left_10p">Why Join</span></div>
                <div class="lineSpace_5">&nbsp;</div>
                <div class="fontSize_16p bld"><span class="mar_left_10p">Shiksha - </span><span class="mar_left_10p">Enterprise?</span></div>
                <div class="lineSpace_11">&nbsp;</div>
                <div class="row">
                    <div class="w9 mar_left_5p">
                        <div class="row">
                            <div><img src="/public/images/eventArrow.gif" align="left" style="position:relative; top:5px"  /></div>
                            <div style="margin-left:12px">Be able to Post, Edit and Delete Your Listings</div>
                        </div>
                        <div class="spacer5 clearFix"></div>
                        <div class="row">
                            <div><img src="/public/images/eventArrow.gif" align="left" style="position:relative; top:5px"  /></div>
                            <div style="margin-left:12px">See the vital statistics of your Listings by Response Viewer, eg. How many hits it received in the last 1 week/month.</div>
                        </div>
                        <div class="spacer5 clearFix"></div>
                        <div class="row">
                            <div><img src="/public/images/eventArrow.gif" align="left" style="position:relative; top:5px"  /></div>
                            <div style="margin-left:12px">Get personal control and enhanced functionalities for better management of your content! </div>
                        </div>
                        <div class="spacer5 clearFix"></div>
                    </div>
                </div>

                <div class="spacer10 clearFix"></div>
            </div>
            <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
        </div>
        <div class="spacer10 clearFix"></div>
        <!--End_Course_TYPE-->
    </div>
    <!--End_Left_Panel-->
    <!--Start_Mid_Panel-->
    <div style="float:left; width:790px; margin-left:15px">
        <div class="normaltxt_11p_blk fontSize_14p bld OrgangeFont" style="margin-bottom:10px"><span class="">Registration Form</span></div>
	<div class="raised_lgraynoBG">
	   <b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
	   <div class="boxcontent_lgraynoBG" style="*height:0.01%">
	      <?php $this->load->view('enterprise/reducedRegistrationForm'); ?>
	   </div>
	   <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
	</div>
    </div>
    <!--End_Mid_Panel-->
    <div class="clearFix"></div>
 </div>
 <div class="spacer10 clearFix"></div>
<!--End_Center-->
<?php $this->load->view('enterprise/footer'); ?>
