<?php
$bannerProperties = array();
if($pageId == "DISCUSSION_DETAIL")
	$bannerProperties = array('pageId'=>'DISCUSSION_DETAIL', 'pageZone'=>'FOOTER');
else if($pageId == "ASK_DISCUSS")
	$bannerProperties = array('pageId'=>'ASK_DISCUSS', 'pageZone'=>'FOOTER');

if(!empty($bannerProperties)){
?>
<div class="sponsored-links" style="padding: 0px;">
<?php
	$this->load->view('common/banner',$bannerProperties);
?>
</div>
<?php
}
?>
