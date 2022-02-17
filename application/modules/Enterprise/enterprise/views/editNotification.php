<?php
$headerComponents = array(
                          'css'   => array('headerCms','mainStyle','raised_all','footer','cal_style'),
                          'js'    => array('common','newcommon','listing','prototype','utils','tooltip','CalendarPopup'),
                          'title' => 'Edit Admission Notification',
                          'tabName' => 'Listing',
                          'taburl' => $thisUrl,
                          'product' => 'home',
                          'displayname'=> (isset($validity[0]['displayname'])?$validity[0]['displayname']:""),
                          'callShiksha'=>1,
                          'metaKeywords'  =>'course, institute, scholarship, examination listing',
                          'search'=> false
                         );
$this->load->view('enterprise/headerCMS', $headerComponents);
$this->load->view('common/overlay');
$this->load->view('common/calendardiv');
?>
<script language="javascript" src="/public/js/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script>
   var SITE_URL = '<?php echo base_url() ."/";?>';
   var completeCategoryTree = eval(<?php echo $completeCategoryTree; ?>);
</script>
<div class="lineSpace_13">&nbsp;</div>
<?php echo $this->load->view('enterprise/cmsTabs'); ?>
<div class="raised_lgraynobg">
   <b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
   <div class="mar_full_10p">
      <div id="mid_Panel_noLRpanel">
	 <div>
	    <div class="normaltxt_11p_blk fontSize_16p OrgangeFont"><strong>Edit Admission Notification </strong></div>
	    <div class="lineSpace_5">&nbsp;</div>
	 </div>
	 <div class="lineSpace_5">&nbsp;</div>
      </div>
      <div style="float:left; width:100%;">
	 <div class="raised_lgraynobg">
	    <b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
	    <div class="boxcontent_lgraynoBG">
	       <div style="float:left; display:inline; width:100%; margin-top:5px"></div>
	       <div class="clear_L"></div>
	       <?php $this->load->view('enterprise/editNotificationForm'); ?>
	    </div>
	    <b class="b4b"/><b class="b3b"/><b class="b2b"/><b class="b1b"/>
          </div>
       </div>
    </div>
    <div class="lineSpace_5">&nbsp;</div>
</div>
