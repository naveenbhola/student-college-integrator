<?php
$criteriaKeyword = $city == '' ? 'BMS_ALL_CITIES' : ''; $criteriaArray = array(
        'category' => $categoryId,
        'country' => 2,
        'city' => $city,
        'keyword'=>$criteriaKeyword
        );

$headerComponents = array(
        'js'=>array('common','lazyload','multipleapply','category','user','onlinetooltip','customCityList','listingPage'),
        'product'=>'categoryHeader',
        'taburl' =>  site_url(),
        'title'    =>    "Recommendations"
        );

$this->load->view('common/header', $headerComponents);

global $categoryTree;
foreach($categoryTree as $category) {
    if($category['categoryID'] == $categoryId) {
        $categoryUrl = constant('SHIKSHA_'. 
                strtoupper($category['urlName']) .'_HOME');
    }
}
?>


<?php if(isset($institutes)): ?>
<div class='recommendation_page_outer'>
<div class='recommendation_page_left'>
<h2><?php echo $headingVerbiage; ?></h2>
<div class="instituteLists">
<?php $this->load->view('categoryList/categoryPageSnippets'); ?>
</div>
</div>

<div class='recommendation_page_right'>
<?php
$bannerProperties = array('pageId'=>'CATEGORY', 'pageZone'=>'RIGHT','shikshaCriteria' => $criteriaArray); $this->load->view('common/banner',$bannerProperties);
?>
</div>
<div style='clear:both;'></div>
</div>
<?php endif; ?>
<br /><br />
<?php $this->load->view('common/footerNew'); ?>

