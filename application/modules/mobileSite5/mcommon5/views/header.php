<!DOCTYPE html>
<html>
	<!-- START HEADER TAG -->
	<head>

		<?php if(isset($isAbTestPage) && $isAbTestPage == 'yes') { 
		 	$this->load->view('common/contentExperimentCode'); 
		} ?>

		<script type="text/javascript">

			var t_pagestart=new Date().getTime();
			var mobilePageName = "<?php echo $mobilePageName;?>", DO_SEARCHPAGE_TRACKING = '<?php echo DO_SEARCHPAGE_TRACKING; ?>', DO_TUPLE_TRACKING = '<?php echo DO_TUPLE_TRACKING; ?>';
			var COOKIEDOMAIN = '<?=COOKIEDOMAIN?>';
		</script>
		<?php
			$this->load->view('mcommon5/headerMetaTags');
			$this->load->view('mcommon5/headerLinkTags');
			$this->load->view('mcommon5/headerCssFiles');
			$this->load->view('common/TrackingCodes/JSErrorTrackingCode');
			if($pageName == 'home'){
				$this->load->view('mcommon5/homePageHeaderJsFiles');
			}else{
				$this->load->view('mcommon5/headerJsFiles');
			}
			

			//JSB9 Tracking
			echo getHeadTrackJs();
		?>
		<script type="text/javascript">
			var t_headend = new Date().getTime();
		</script>

		<?php if(!empty($scriptData)){ ?>
			<script type="application/ld+json">
			{"@context": "http://schema.org", "@type": "CollegeOrUniversity", "name":"<?=$scriptData['nameForHeader']?>", "url":"<?=$scriptData['websiteForHeader']?>", "email":"<?=$scriptData['emailForHeader']?>", "telephone":"<?=$scriptData['phoneForHeader']?>"}
			</script>
		<?php } ?>
		
		<?php
			if($pageName == 'mobileExamPage'){
		?>
		<script type="application/ld+json">
		{
			"@context" : "http://schema.org",
			"@type"    : "Organization",
			"url"      : "https://www.shiksha.com",
			"logo"     : "https://www.shiksha.com/public/images/nshik_ShikshaLogo1.gif",
			"name"     : "Shiksha.com",
			"sameAs"   : ["https://www.facebook.com/shikshacafe", "https://twitter.com/ShikshaDotCom", "https://en.wikipedia.org/wiki/Shiksha.com", "https://plus.google.com/+shiksha"]
		}
		</script>
		<script type="application/ld+json"> 
		{
			"@context"        : "http://schema.org",
			"@type"           : "Article",
			"mainEntityOfPage": {"@type": "WebPage", "@id":"<?php echo $m_canonical_url; ?>"},
			"headline"        : "<?php echo $m_meta_title; ?>",
			"dateModified"    : "<?php echo $homepageData['updationDate']; ?>",
			"datePublished"   : "<?php echo $homepageData['creationDate']; ?>",
			"author"          : {"@type":"Person","name":"Shiksha"},
			"publisher"       : {"@type":"Organization","name":"Shiksha","logo":{"@type":"ImageObject","name":"shiksha","url":"https://www.shiksha.com/public/images/shiksha-amp-logo.jpg","height":60,"width":167}}
		}
		</script>
		<?php
			}
		?>
		
		<?php
			//Adding New google ad sense on Homepage
			if($pageName == 'home'){
			        $this->load->view('common/header/googleAdsenseNew');
			}

                        if($pageName == 'home'){ ?>
                        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                        <script>
                          (adsbygoogle = window.adsbygoogle || []).push({
                            google_ad_client: "ca-pub-6373956451888355",
                            enable_page_level_ads: true
                          });
                        </script>
                        <?php }

		?>
		<?php 
			$this->load->view('mcommon5/dfpBannerCode');
		?>
	</head>
	<!-- END HEADER TAG -->

	<body id="<?php echo  $noHeader ? 'noHeader' : '';?>">
<?php if(isset($pageName) && $pageName=='home'){?>
<div style=" left: 0px; top: 0px; bottom: 0px; right: 0px; position: fixed; z-index: 99; background: rgba(255, 255, 255, 1) none repeat scroll 0px 0px !important;background:1px solid red" id="backgroundLayer" data-enhance="false">
        <div class="loader" id="homepageloader" style="text-align: center; vertical-align: middle; position: fixed;z-index: 9999;top:45%;left:42%" data-enhance="false">
         </div>
</div>
<script>
var ratio = window.devicePixelRatio || 1;
var w = screen.width * ratio;
var h = screen.height * ratio;
document.getElementById('backgroundLayer').style.height = h+'px';
document.getElementById('backgroundLayer').style.width = w+'px';
</script>
<?php } ?>
<div style="display:none;">
<?php
if($_REQUEST['mmpbeacon'] != 1) {
    loadBeaconTracker($beaconTrackData);
}
?>
</div>
		<?php $this->load->view('common/googleCommon',array('gtmParams'=> $gtmParams, 'pageSource' => 'mobile')); ?>
		<?php $this->load->view('mcommon5/headerInlineJsCode'); ?>
