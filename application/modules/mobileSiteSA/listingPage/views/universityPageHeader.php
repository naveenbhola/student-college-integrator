<?php
    $seoData = $universityObj->getMetaData();
    $seoData['seoUrl'] = $universityObj->getURL();
    $headerComponents = array(  'cssBundleMobile'    => 'sa-university-page-mobile',
                                'title'             => $seoData['seoTitle'],
                                'canonicalURL'      => $seoData['seoUrl'],
                                'metaDescription'   => $seoDescription,
                                'metaKeywords'      => $seoData['seoKeywords'],
                                'firstFoldCssPath'    => 'listingPage/css/universityPageFirstFoldCss',
                                'deferCSS' => true
                             );
    $this->load->view('commonModule/headerV2',$headerComponents);
?>
<div class="header-unfixed">
    <div class="_topHeadingCont">
        <img id='beacon_img' src="<?php echo IMGURL_SECURE; ?>/public/images/blankImg.gif" width=1 height=1 >
        <!--<a href="javascript:void(0)" class="back-box"><i class="sprite back-icn"></i></a>-->
        <!-- <p style="text-align:center"><?//=$universityObj->getName()?></p> -->
    </div>
    <?php //$this->load->view('widgets/universityNavigatorBar');?>
</div>
<?php
    /*Below script section is for structured data markup using JSON-LD defined pattern for SEO*/
    $contactDetails         = $universityObj->getContactDetails();
    $universityEmail        = $contactDetails->getContactEmail();
    $universityPhoneNumber  = $contactDetails->getContactMainPhone();
?>
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
