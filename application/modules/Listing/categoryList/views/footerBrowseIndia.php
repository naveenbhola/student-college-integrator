<?php
$count = 0;
$product = 'categoryHeader';
$headerComponents = array(
						'js' => array(),
						'jsFooter' =>array('common','lazyload'),
						'product'=> $product,
						'taburl' =>  site_url(),
						'bannerProperties' => array(
													'pageId'=>'CATEGORY',
													'pageZone'=>'TOP',
													'shikshaCriteria' => $criteriaArray
													),
						'title'	=>	$titleText,
						'searchEnable' => true,
						'canonicalURL' => $canonicalurl,
						'metaDescription' => $mediaData['description'],
						'metaKeywords'	=> $mediaData['keywords']

);
$this->load->view('common/header', $headerComponents);
?>
<div class="footer-browse">
<h5>Browse Section</h5>
<h4 class="footer-browse-title"><?php echo $courseName; ?> Courses in India</h4>
<ul>
<li>
<strong>Courses by State</strong>
<p>
<?php
$stateLinks = array();
foreach($states as $state) {
	$categoryPageRequest->setData(array('stateId' => $state->getId()));
	$stateLinks[] = getBrowseLink($courseName,$state->getName(),$categoryPageRequest->getURL());
}
echo implode(', ',$stateLinks);
?>
</p>
</li>

<li>
<strong>Courses by City</strong>
<p>
<?php
$cityLinks = array();
foreach($cities as $city) {
	$categoryPageRequest->setData(array('cityId' => $city->getId()));
	$cityLinks[] = getBrowseLink($courseName,$city->getName(),$categoryPageRequest->getURL());
}
echo implode(', ',$cityLinks);
?>
</p>
</li>

<li>
<strong>Courses by Locality</strong>
<p>
<?php
$localityLinks = array();
foreach($localities as $locality) {
	$categoryPageRequest->setData(array('localityId' => $locality->getId()));
	$localityLinks[] = getBrowseLink($courseName,$locality->getName(),$categoryPageRequest->getURL());
}
echo implode(', ',$localityLinks);
?>
</p>
</li>
</ul>
</div>

<?php
$this->load->view('common/footerNew');
?>