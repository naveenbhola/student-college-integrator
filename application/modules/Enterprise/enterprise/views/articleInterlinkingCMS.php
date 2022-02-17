<?php
$headerComponents = array(
   'css'   => array('headerCms','mainStyle','footer'),
   'js'    => array('common','enterprise'),
   'taburl' => site_url('enterprise/Enterprise/addArticleInterlinkingHTML'),
   'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
   );
$this->load->view('enterprise/headerCMS', $headerComponents);
?>
</head>
<body>
<script>
var SITE_URL = '<?php echo base_url() ."/";?>';
</script>
<div class="lineSpace_5">&nbsp;</div>
<?php $this->load->view('enterprise/cmsTabs'); ?>
	<div class="mar_full_10p" style="float:left; width:96%;margin:0px 10px 10px">
        <div class="raised_lgraynoBG">
            <b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
            	<div class="boxcontent_lgraynoBG">
            		<div style="padding:10px">
			    <?php $this->load->view('articleInterlinkingForm'); ?>
	                    <div class="lineSpace_10">&nbsp;</div>
	                </div>
				</div>
<!--End_Mid_Panel-->
			<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
		</div>
	</div>
  <div class="show-layer">
    <div class="alert-div">
      <p id="err_lyr" style="position:relative;"></p>
      <div class="" style="text-align:right;">
      <a id="okbtn" class="pop">Ok</a>
      </div>
    </div>
  </div>

<?php $this->load->view('enterprise/footer'); ?>



