<?php
    $seoData = $universityObj->getMetaData();
    $seoData['seoUrl'] = $universityObj->getURL();
    $headerComponents = array(
        'cssBundle'=>'sa-univ-page',
        'canonicalURL'      => $seoData['seoUrl'],	
        'title'             => $seoData['seoTitle'],
        'metaDescription'   => $seoDescription,
        'metaKeywords'      => $seoData['seoKeywords'],
        'pgType'	        => 'universityPage',
        'pageIdentifier'	=> $beaconTrackData['pageIdentifier'],
        'trackingPageKeyId' => 467,
	'deferCSS'	=> true,
	'pageWiseFFCss' => 'listing/abroad/css/universityFirstFoldCss'
    );
	$this->load->view('studyAbroadCommon/saHeader', $headerComponents);
	// echo jsb9recordServerTime('SHIKSHA_ABROAD_COURSE_DETAIL_PAGE',1);
	/*Below script section is for structured data markup using JSON-LD defined pattern for SEO*/
	$contactDetails 		= $universityObj->getContactDetails();
	$universityEmail 		= $contactDetails->getContactEmail();
	$universityPhoneNumber 	= $contactDetails->getContactMainPhone();
?>
<!-- UNIV ID: <?php echo $universityObj->getId(); ?> -->
<script type="application/ld+json">
{
    "@context" : "http://schema.org",
    "@type"    : "CollegeOrUniversity",
    "url"      : "<?php echo $universityObj->getURL(); ?>",    
    "name"     : "<?php echo htmlentities($universityObj->getName());?>",
    "email"    : "<?php echo $universityEmail; ?>",
    "telephone" : "<?php echo $universityPhoneNumber;?>"
}
</script>
