<html xmlns="http://www.w3.org/1999/xhtml">
<?php if(!isset($cmsAjaxFetch)){ ?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Listing Detail Page</title>
<?php
$headerComponents = array(
                          'css'   => array(
						                 	'headerCms',
                                          'mainStyle',
                                          'raised_all',
                                          'footer'
                                          ),
                          'js'    => array(
                                          'common',
                                          'newcommon',
                                          'alerts',
					  'listing',
					  'network',
					  'prototype',
					  'user',
					  'discussion',
                      'enterprise'
                                           ),
                          'tabName' => 'Listing',
                          'taburl' => $thisUrl,
                          'product' => 'home',
                          'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:"")
                         );
$this->load->view('enterprise/headerCMS', $headerComponents);
?>
</head>

    <script>
			var SITE_URL = '<?php echo base_url() ."/";?>';
var BASE_URL = SITE_URL;
	</script>

<body>
<div class="lineSpace_5">&nbsp;</div>

<?php $this->load->view('enterprise/cmsTabs'); ?>

<?php } ?>
        <div style="float:left; width:100%">
            <div class="raised_lgraynoBG">
                <b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
	        	<div class="boxcontent_lgraynoBG">
                                 <div class="lineSpace_10">&nbsp;</div>
<?php if(isset($cmsAjaxFetch)){

        $this->load->view('enterprise/fetchDetailButtons');
}
?>
                                <div class="lineSpace_10">&nbsp;</div>

                                <?php  $this->load->view("listing/".$listing_type."Details"); ?>

<?php if(isset($cmsAjaxFetch)){

        $this->load->view('enterprise/fetchDetailButtons');
}
?>

                                <div class="lineSpace_10">&nbsp;</div>
			</div>
                <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
            </div>
        </div>
 <img id = 'beacon_img' src="/public/images/blankImg.gif" width=1 height=1 >
   <script>
       var img = document.getElementById('beacon_img');
       var randNumForBeacon = Math.floor(Math.random()*Math.pow(10,16));
       img.src = '<?php echo BEACON_URL; ?>/'+randNumForBeacon+'/0010004/<?php echo $type_id; ?>+<?php echo $listing_type; ?>';
       fillProfaneWordsBag() ;
</script>

<?php if(!isset($cmsAjaxFetch))
    {
        $this->load->view('enterprise/footer');
    }
?>
