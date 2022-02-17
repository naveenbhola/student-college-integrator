<?php
$headerComponents = array(
                          'css'   => array('headerCms','mainStyle','raised_all','footer','cal_style'),
                          'js'    => array('common','newcommon','listing','prototype','utils','tooltip','CalendarPopup','enterprise'),
						  'taburl' => site_url('enterprise/Enterprise'),
						  'title'=> 'Listing Updated',
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
</br>
</br>
</br>
<div class="normaltxt_11p_blk bld txt_align_r float_L">You have recently Updated <a href="<?php echo site_url('enterprise/Enterprise/getDetailsForListingCMS/'.$response["type_id"]."/".$response["listing_type"]."/".$prodId); ?>" ><?php echo $response["title"]; ?></a> listing. &nbsp;</div>
</br>
</br>
</br>
                        <div class="lineSpace_10">&nbsp;</div>
					</div>
                <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
        </div>
</div>
<div style="line-height:400px">&nbsp;</div>
<?php $this->load->view('enterprise/footer'); ?>
