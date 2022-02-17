<?php
$headerComponents = array(
   'css'   => array('headerCms','mainStyle','raised_all','footer','commonAutoSuggestor'),
   'js'    => array('CalendarPopup','common','blog','enterprise','imageUpload','json2','commonAutoSuggestor'),
   'taburl' => site_url('enterprise/Enterprise'),
   'category_id'   => (isset($CategoryId)?$CategoryId:1),
   'country_id'    => (isset($country_id)?$country_id:2),
   'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
   'addArticle' => true
                         );
$this->load->view('enterprise/headerCMS', $headerComponents);
?>
<style>body {overflow-x: hidden;}</style>
</head>
<body>
<script>
var SITE_URL = '<?php echo base_url() ."/";?>';
</script>
<div class="lineSpace_5">&nbsp;</div>
<?php $this->load->view('enterprise/cmsTabs'); ?>
	<div class="mar_full_10p" style="float:left; width:96%;margin:0px 10px">
        <div class="raised_lgraynoBG">
            <b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
            	<div class="boxcontent_lgraynoBG">
            		<div style="padding:10px">
						<?php $this->load->view('blogs/createBlog_Form'); ?>
	                    <div class="lineSpace_10">&nbsp;</div>
	                </div>
				</div>
<!--End_Mid_Panel-->
			<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
		</div>
	</div>

<script>
/*    var objForm = document.getElementById('scholarship_main');
    insertCMSelement(objForm,<?php echo $prodId;  ?>); */
</script>
<?php
     echo "<script language=\"javascript\"> ";
     echo "var captchacheckUrl = '".site_url('blogs/shikshaBlog/validateTitleAndCaptcha')."'";
     echo "</script>";
?>
<?php $this->load->view('enterprise/footer'); ?>
<?php $this->load->view('enterprise/autoSuggestorV2ForCMS'); ?>

