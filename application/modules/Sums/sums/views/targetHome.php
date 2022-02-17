<?php
$headerComponents = array (
  'css' =>  array('headerCms','raised_all','mainStyle','footer'),
  'js'  =>  array('user','common','prototype','CalendarPopup','sums_mis_common','sums_target'),
  'title' =>  'SUMS - Client selection for creating quotation page',
  'tabName' =>        'Register',
  'taburl'   =>  SITE_URL_HTTPS.'enterprise/Enterprise/register',
  'metaKeywords'  =>'Some Meta Keywords',
  'product' => '',
  'search'  =>  false,
  'displayname' =>  (isset($sumsUserInfo['validity'][0]['displayname'])?$sumsUserInfo['validity'][0]['displayname']:""),
  'callShiksha' =>  1
);

$this->load->view('enterprise/headerCMS', $headerComponents);
$this->load->view('enterprise/cmsTabs',$sumsUserInfo);
$this->load->view('common/calendardiv');
?>
<script>
   var cal = new CalendarPopup("calendardiv");
   cal.offsetX = -150;
   cal.offsetY = 20;
</script>
<div id="dataLoaderPanel" style="position:absolute;display:none">
    <img src="/public/images/loader.gif"/>
</div>
<div class="mar_full_10p">
    <div style="width:223px; float:left">
        <?php 
		$leftPanelViewValue = 'leftPanelFor' . $prodId;
        // Load another view to display MIS collection form side bar
		$this->load->view('sums/'. $leftPanelViewValue, array('prodId'=>$prodId,'type'=>$type,'sumsUserInfo'=>$sumsUserInfo));
	?>
    </div>
    <div style="margin-left:233px">
        <div class="lineSpace_10p">&nbsp;</div>
        <div class="bld fontSize_14p OrgangeFont" style="padding-left:10px;"><?php if ($type === 'Target_Input') { echo "Assign Target to Branch Executives"; } elseif ($type === 'Month_till_date_sales_Report') { echo "Month sales Report  (Till date)";}elseif ($type === 'Quarter_till_date_sales_Report') { echo "Quarter sales Report  (Till date)"; }elseif ($type === 'Product_MIX_Report') { echo "Product MIX Report";} ?></div>
        <div class="grayLine"></div>
        <div class="lineSpace_10">&nbsp;</div>
        <div style="display:inline;float:left;width:100%">
        </div>
        <form id="TargetSubmitForm" method="post" name='TargetSubmitForm' action="/sums/targetInput/handleParams" onSubmit="return formvalidation(this);" >
      <?php 
		if ($type === 'Target_Input') {
			if(array_key_exists(47,$sumsUserInfo['sumsuseracl'])) {
				$this->load->view('sums/Target_Input_home.php', array('prodId'=>$prodId,'branchlist'=>$branchList,'sumsUserInfo'=>$sumsUserInfo,'AllQuarters'=>$AllQuarters));
			}
		}
		if ($type === 'Month_till_date_sales_Report') {
			if(array_key_exists(48,$sumsUserInfo['sumsuseracl'])) {
				$this->load->view('sums/Month_till_date_sales_Report.php', array('prodId'=>$prodId,'branchlist'=>$branchList,'sumsUserInfo'=>$sumsUserInfo,'AllQuarters'=>$AllQuarters));
			}
		}
		if ($type === 'Quarter_till_date_sales_Report') {
			if(array_key_exists(49,$sumsUserInfo['sumsuseracl'])) {
				$this->load->view('sums/Quarter_till_date_sales_Report.php', array('prodId'=>$prodId,'branchlist'=>$branchList,'sumsUserInfo'=>$sumsUserInfo,'AllQuarters'=>$AllQuarters));
			}
		}
		if ($type === 'Product_MIX_Report') {
			if(array_key_exists(50,$sumsUserInfo['sumsuseracl'])) {
				$this->load->view('sums/Product_MIX_Report.php', array('prodId'=>$prodId,'branchlist'=>$branchList,'sumsUserInfo'=>$sumsUserInfo,'AllQuarters'=>$AllQuarters));
			}
		}
	?>
		</form>
    </div>
    <div id="display_crud_result">
    <?php if ((is_array($crud_result)) && (isset($crud_result))) { echo "<b> Records Updated Successfully.</b>";} ?>
    </div>
    <div class="lineSpace_35">&nbsp;</div>
</div>
</body>
</html>
