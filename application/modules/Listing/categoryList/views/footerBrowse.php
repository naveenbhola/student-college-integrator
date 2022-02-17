<?php
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
<?php
/*
 * On First 2 pages, show courses in India
 */ 
if($page <= 1) {
?>
	<h4 class="footer-browse-title">Courses in India</h4>
<?php
	/*
	 * Distribute the categories into 2 pages
	 * No. of categories to be shown on a page depends on
	 * the number of links in those categories
	 * so that we can have roughly same number of links on each page
	 *
	 * Following is the distribution based on number of links in each category
	 * 
	 * On 1st page, show first 5 categories
	 * On 2nd page, show remaining categories
	 */ 
	if($page == 0) {
		$categoryData['national'] = array_slice($categoryData['national'],0,5);
	}
	else if($page == 1) {
		$categoryData['national'] = array_slice($categoryData['national'],5);
	}
	echo showBrowseLinks($categoryData['national'],'India',$categoryPageRequest);
}
else {
	
	$startIndex = ($page - 2) * 5;
	$studyAbroadLocations = array_slice($studyAbroadLocations,$startIndex,5);
	foreach($studyAbroadLocations as $studyAbroadLocation) {
		if($studyAbroadLocation instanceof Region) {
			$categoryPageRequest->setData(array('regionId' => $studyAbroadLocation->getId(),'countryId' => NULL));
		}
		else {
			$categoryPageRequest->setData(array('countryId' => $studyAbroadLocation->getId(),'regionId' => NULL));
		}
?>
		<h4 class="footer-browse-title">Courses in <?php echo $studyAbroadLocation->getName(); ?></h4>
<?php
		echo showBrowseLinks($categoryData['studyabroad'],$studyAbroadLocation->getName(),$categoryPageRequest,TRUE);
	}
}
?>
</div>

<?php
$this->load->library('pagination');
$config['base_url'] = 'http://localshiksha.com/categoryList/Browse/index/';
$config['total_rows'] = 8;
$config['per_page'] = 1;
$config['num_links'] = 8;
$config['use_page_numbers'] = TRUE;
$config['cur_page'] = $page;
$config['next_link'] = 'Next';
$config['prev_link'] = 'Previous';
$config['cur_tag_open'] = '<span>';
$config['cur_tag_close'] = '</span>';

$this->pagination->initialize($config); 
echo "<div class='browse-pagination'>";
echo $this->pagination->create_links();
echo "<div style='clear:both;'></div></div>";
echo "<br /><br />";

$this->load->view('common/footerNew');
?>