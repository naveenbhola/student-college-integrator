<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>CMS: Edit/Update Institute and Course</title>

<?php
$headerComponents = array(
                          'css'   => array('headerCms','mainStyle','raised_all','footer','cal_style'),
			  'js'    => array('common','newcommon','listing','prototype','utils','tooltip','CalendarPopup','enterprise'),
			  'taburl' => site_url('enterprise/Enterprise'),
                          'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:"")
                         );
$this->load->view('enterprise/headerCMS', $headerComponents);
$this->load->view('common/calendardiv');
?>
</head>

<body>

<script>
		var SITE_URL = '<?php echo base_url() ."/";?>';
</script>

<div class="lineSpace_5">&nbsp;</div>

        <?php $this->load->view('enterprise/cmsTabs'); ?>

        <div style="float:left; width:100%">
        <div class="raised_lgraynoBG">
                <b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>

<!--Start_Center-->
   <style>
   		SELECT {font-size:11px; font-family:Verdana, Arial, Helvetica, sans-serif;}
		INPUT {font-size:11px; font-family:Verdana, Arial, Helvetica, sans-serif;}
		TEXTAREA {font-size:11px; font-family:Verdana, Arial, Helvetica, sans-serif;}
	</style>
	<!--End_Left_Panel-->
	<div id="mid_Panel_noLRpanel">
            <div class="">
                <?php if($listingType =='course'){ ?> 
                <div class="normaltxt_11p_blk fontSize_16p OrgangeFont"><strong>Edit / Update Course</strong></div>
                <?php }else{ ?>
                <div class="normaltxt_11p_blk fontSize_16p OrgangeFont"><strong>Edit / Update Institute</strong></div>
                <?php } ?>
			<span class="normaltxt_11p_blk darkgray disBlock txt_align_r">
		</div>
			<div class="lineSpace_5">&nbsp;</div>
	</div>
		<div style="float:left; width:100%;">
			<div class="raised_lgraynoBG">
				<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
					<div class="boxcontent_lgraynoBG">

                                            <?php if($listingType =='course'){ ?> 
                                            <?php $this->load->view('enterprise/editCourCol'); ?>
                                            <?php }else{ ?>
                                            <?php $this->load->view('enterprise/editInstitute'); ?>
                                            <?php } ?>
                        <div class="lineSpace_10">&nbsp;</div>
					</div>
				<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
			</div>
		</div>
	<!--End_Mid_Panel-->
                <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
        </div>
</div>
<script>
//    var objForm = document.getElementById('courseListing');
//    insertCMSelement(objForm,<?php echo $prodId;  ?>);
</script>
<?php $this->load->view('enterprise/footer'); ?>
