<!-- Load JS files -->
<?php if(!(isset($doNotLoadMobileFiles) && $doNotLoadMobileFiles=='true')){ ?>
	
		<script type="application/ld+json">
		{"@context" : "http://schema.org", "@type" : "Organization", "url" : "https://www.shiksha.com", "logo" : "https://www.shiksha.com/public/images/nshik_ShikshaLogo1.gif", "name" : "Shiksha.com", "sameAs" : ["https://www.facebook.com/shikshacafe", "https://twitter.com/ShikshaDotCom", "https://en.wikipedia.org/wiki/Shiksha.com", "https://plus.google.com/+shiksha"]}
		</script>
	
<?php } ?>

<?php if(isset($js) && is_array($js)) {
	foreach($js as $jsFile) { ?>
		<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion($jsFile); ?>"></script>
    <?php }
} ?>
            
<?php if(isset($jsMobile) && is_array($jsMobile)) {
    foreach($jsMobile as $jsFile) { ?>
	  	<script src="//<?php echo JSURL; ?>/public/mobile5/js/<?php echo getJSWithVersion($jsFile,'nationalMobile'); ?>"></script>
  	<?php }
} ?>
