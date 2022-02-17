<?php
$headerComponents = array (
  'css' =>  array('headerCms','raised_all','mainStyle','footer'),
  'js'  =>  array('user','common','prototype','CalendarPopup','sums_mis_common'),
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
		$this->load->view('sums/'. $leftPanelViewValue, array('prodId'=>$prodId,'mis_reprot_type'=>$mis_reprot_type,'sumsUserInfo'=>$sumsUserInfo));
	?>
    </div>
    <div style="margin-left:233px">
        <div class="lineSpace_10p">&nbsp;</div>
        <div class="bld fontSize_14p OrgangeFont" style="padding-left:10px;">MIS Home</div>
        <div class="grayLine"></div>
        <div class="lineSpace_10">&nbsp;</div>

        <div style="display:inline;float:left;width:100%">
        </div>
        <form id="MisForm" method="post" name='MisForm' action="/sums/MIS/handleParams" onSubmit="return Misformvalidation(this);" >
        <input type="hidden" name="FormType" id="FormType" value="<?php echo $mis_reprot_type; ?>"/>
      <?php 
	if ($mis_reprot_type != 'inventory')  {
		$this->load->view('sums/MISCollection.php', array('prodId'=>$prodId,'mis_reprot_type'=>$mis_reprot_type,'branchlist'=>$branchList,'sumsUserInfo'=>$sumsUserInfo,'clientList'=>$clientList));
	}
	if ($mis_reprot_type == 'payment') {
		$this->load->view('sums/mis_payment_mode.php', array('prodId'=>$prodId,'mis_reprot_type'=>$mis_reprot_type,'branchlist'=>$branchList,'sumsUserInfo'=>$sumsUserInfo));
		$this->load->view('sums/mis_search_form_payment_currency.php', array('prodId'=>$prodId,'mis_reprot_type'=>$mis_reprot_type,'branchlist'=>$branchList,'sumsUserInfo'=>$sumsUserInfo));
		$this->load->view('sums/mis_payment_status.php', array('prodId'=>$prodId,'mis_reprot_type'=>$mis_reprot_type,'branchlist'=>$branchList,'sumsUserInfo'=>$sumsUserInfo));
	} elseif ($mis_reprot_type == 'transaction')  {
		$this->load->view('sums/mis_sell_type.php', array('prodId'=>$prodId,'mis_reprot_type'=>$mis_reprot_type,'branchlist'=>$branchList,'sumsUserInfo'=>$sumsUserInfo));
		//$this->load->view('sums/mis_show_part_payment.php', array('prodId'=>$prodId,'mis_reprot_type'=>$mis_reprot_type,'branchlist'=>$branchList));
	} elseif ($mis_reprot_type == 'inventory')  {
		$this->load->view('sums/mis_sums_inventory.php', array('prodId'=>$prodId,'mis_reprot_type'=>$mis_reprot_type,'branchlist'=>$branchList,'sumsUserInfo'=>$sumsUserInfo,'clientList'=>$clientList));
	}
		$this->load->view('sums/mis_search_form_bottom.php', array('prodId'=>$prodId,'mis_reprot_type'=>$mis_reprot_type,'branchlist'=>$branchList,'sumsUserInfo'=>$sumsUserInfo));
	?>
    </div>
    <div class="lineSpace_35">&nbsp;</div>
</div>
</body>
</html>
