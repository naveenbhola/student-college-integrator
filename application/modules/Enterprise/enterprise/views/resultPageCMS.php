<?php
$headerComponents = array(
                          'css'   => array('headerCms','mainStyle','raised_all','footer','cal_style'),
                          'js'    => array('common','newcommon','listing','prototype','utils','tooltip','CalendarPopup','enterprise'),
						  'taburl' => site_url('enterprise/Enterprise'),
						  'title'=>'Listing Addition Page',
                          'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:"")
                         );
$this->load->view('enterprise/headerCMS', $headerComponents);
$this->load->view('common/calendardiv');
?>
</head>

<body>
<div class="lineSpace_5">&nbsp;</div>
        <?php $this->load->view('enterprise/cmsTabs'); ?>
        <div style="float:left; width:100%">
        <div class="raised_lgraynoBG">
                <b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>

					<div class="boxcontent_lgraynoBG">
                        <div class="lineSpace_10">&nbsp;</div>
                        <div class="lineSpace_10">&nbsp;</div>
                        <div class="normaltxt_11p_blk bld float_L mar_full_10p lineSpace_16">
                        <?php if(isset($duplicate['institute']) && is_array($duplicate['institute'])) : ?>
                            <div>The institute "<?php echo $instituteName; ?>" already exists. To view institute details <a href="/enterprise/Enterprise/getDetailsForListingCMS/<?php echo $duplicate['institute']['type_id'];?>/institute">click here</a>.</div>
                        <?php endif; ?>
                        <?php if(isset($duplicate['course']) && is_array($duplicate['course'])):?>
                            <div>Course "<?php echo $response["title"]; ?>" already exists in the institute "<?php echo $instituteName; ?>"</b>.
                            To edit course <a href="/enterprise/Enterprise/cmsEditCourse/<?php echo $response['type_id']; ?>">click here</a>.</div>
                        <?php else: ?>
                           <div> You have recently added <a href="<?php echo site_url('enterprise/Enterprise/getDetailsForListingCMS/'.$response["type_id"]."/".$response["listing_type"]."/".$prodId); ?>" ><?php echo $response["title"]; ?></a> listing.</div>
                        <?php endif; ?>
                        </div>
                        <div class="lineSpace_10">&nbsp;</div>
                        <div class="lineSpace_10">&nbsp;</div>
                        <div class="lineSpace_10">&nbsp;</div>
					</div>
                <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
        </div>
</div>
<div style="line-height:350px">&nbsp;</div>
<?php $this->load->view('enterprise/footer'); ?>