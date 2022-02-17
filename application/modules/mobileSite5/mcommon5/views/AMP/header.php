<!doctype html>
<html ⚡>
 	<head>   <!-- <%>  ampstyle %> -->   
	   	<?php
		   if(in_array($pageType, array('university', 'institute', 'coursePage'))) {
		   	  header( 'Cache-Control: max-age=86400,stale-while-revalidate=86400, stale-if-error=86400');
		   }
		   else {
		   	  header( 'Cache-Control: max-age=0');
		   }
	      $this->load->view('mcommon5/AMP/headerMetaTags');
	      $this->load->view('mcommon5/AMP/headerLinkTags');
	      $this->load->view('mcommon5/AMP/headerJsFiles');
	      $this->load->view('mcommon5/AMP/headerCssFiles');
	    ?>
	    <?php
			if($pageType == 'examPage'){
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
	</head>