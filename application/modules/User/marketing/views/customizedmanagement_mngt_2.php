<div style="width:410px;height:250px;margin-bottom:30px;">
<?php
if ( $pagename == 'campaign1') {
$bannerProperties = array(
			'pageId'=>'CUSTOMIZED1_MARKETING',
			'pageZone'=>'LEFT'		
		    );
} else if($pagename == 'campaign2') {
$bannerProperties = array(
			'pageId'=>'CUSTOMIZED2_MARKETING',
			'pageZone'=>'LEFT'		
		    );
} else if($pagename == 'campaign3') {
$bannerProperties = array(
                        'pageId'=>'CUSTOMIZED3_MARKETING',
                        'pageZone'=>'LEFT'
                    );
}else if($pagename == 'campaign4') {
$bannerProperties = array(
                        'pageId'=>'CUSTOMIZED4_MARKETING',
                        'pageZone'=>'LEFT'
                    );
}else if($pagename == 'campaign5') {
$bannerProperties = array(
                        'pageId'=>'CUSTOMIZED5_MARKETING',
                        'pageZone'=>'LEFT'
                    );
}else if($pagename == 'campaign6') {
$bannerProperties = array(
                        'pageId'=>'CUSTOMIZED6_MARKETING',
                        'pageZone'=>'LEFT'
                    );
}else if($pagename == 'campaign7') {
$bannerProperties = array(
                        'pageId'=>'CUSTOMIZED7_MARKETING',
                        'pageZone'=>'LEFT'
                    );
}else if($pagename == 'campaign8') {
$bannerProperties = array(
                        'pageId'=>'CUSTOMIZED8_MARKETING',
                        'pageZone'=>'LEFT'
                    );
}else if($pagename == 'campaign9') {
$bannerProperties = array(
                        'pageId'=>'CUSTOMIZED9_MARKETING',
                        'pageZone'=>'LEFT'
                    );
}

$this->load->view('common/banner.php', $bannerProperties);
?>
</div>
<div><?php echo $config_data_array['TEXT_BELOW_LEFT_PANEL']; ?></div>
