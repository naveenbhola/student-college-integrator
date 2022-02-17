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
			<?php if(!empty($noJqueryMobile)){?> // tmp variable 
				var noJqueryMobile = <?php echo $noJqueryMobile;?>;
			<?php } ?>
			var bannerPool = new Array();
			function pushBannerToPool(bannerId, bannerUrl){
			  //console.log('aaaaaaaaaaaa');
			    if(bannerId != '') bannerPool[bannerId] = bannerUrl;
			}
			function publishBanners(){
			  try{
			      for(var bannerId in bannerPool) if($('#'+bannerId) != null && $('#'+bannerId).attr('src').trim() != bannerPool[bannerId].trim()) $('#'+bannerId).attr('src', bannerPool[bannerId]); 
			  }catch(e){ }
			}
		</script>
		
		<?php
			$this->load->view('mcommon5/headerMetaTags');
			$this->load->view('mcommon5/headerLinkTags');
		?>		
		<style type="text/css">
			<?php $this->load->view('mcommon5/css/headerCSS'); ?>
		</style>

		<?php
			if(isset($preloadCss) && is_array($preloadCss)) {
			    foreach($preloadCss as $preloadCssFile) { ?>
			    	<link rel="preload" href="//<?php echo CSSURL; ?>/public/mobile5/css/<?php echo getCSSWithVersion($preloadCssFile,'nationalMobile');?>" as="style">
			<?php }
			}
		?>

		<?php
			if(isset($mobilecss) && is_array($mobilecss)) {
			    foreach($mobilecss as $cssFile) { ?>
				<link rel="stylesheet" href="//<?php echo CSSURL; ?>/public/mobile5/css/<?php echo getCSSWithVersion($cssFile,'nationalMobile'); ?>" >
			<?php }
			}
			$this->load->view('common/TrackingCodes/JSErrorTrackingCode');
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

			$this->load->view('mcommon5/dfpBannerCode');
		?>

	</head>
	<!-- END HEADER TAG -->
<body id="<?php echo  $noHeader ? 'noHeader' : '';?>" class="ui-mobile-viewport ui-overlay-a">
<div style="display:none;">
<?php
if($_REQUEST['mmpbeacon'] != 1) {
    loadBeaconTracker($beaconTrackData);
}
?>
</div>

<?php if($product== 'mListingDetailPage') echo includeCSSFiles($product,'nationalMobile'); ?>
<?php $this->load->view('common/googleCommon',array('gtmParams'=> $gtmParams, 'pageSource' => 'mobile')); ?>
<?php $this->load->view('mcommon5/headerInlineJsCode'); ?>
