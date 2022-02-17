<?php 
$headerComponents = array(
	'js'=>array('common', 'multipleapply','ajax-api', 'processForm', 'customCityList'),
	'css' => array('ranking-revamp'),
	'jsFooter' => array('lazyload'),
	'product'=> "",
	'title'	=>	 $title,
	'canonicalURL' => $canonicalURL,
	'metaDescription' => $description,
	'metaKeywords'	=> $metaKeywords,
	'rankingBannerProperties' => array()
);
$this->load->view('common/header', $headerComponents);

 ?>
 <div class='ranking-wrapper clearFix'>
 <p class="resource-head">MBA Rankings</p>
 <ul class="resources-list">
	<li><a href="<?= SHIKSHA_HOME ?>/mba/ranking/top-mba-colleges-india/2-2-0-0-0">Top MBA colleges in India <span>&#62;</span></a></li>
    <li><a href="<?= SHIKSHA_HOME ?>/mba/ranking/top-mba-colleges-india-accepting-cat/2-2-0-0-305">Top MBA colleges in India accepting CAT <span>&#62;</span> </a></li>
    <li><a href="<?= SHIKSHA_HOME ?>/mba/ranking/top-mba-colleges-india-accepting-cmat/2-2-0-0-5822">Top MBA colleges in India accepting CMAT <span>&#62;</span> </a></li>
    <li><a href="<?= SHIKSHA_HOME ?>/mba/ranking/top-mba-colleges-india-accepting-mat/2-2-0-0-306">Top MBA colleges in India accepting MAT <span>&#62;</span> </a></li>
    <li><a href="<?= SHIKSHA_HOME ?>/mba/ranking/top-mba-colleges-india-accepting-xat/2-2-0-0-309">Top MBA colleges in India accepting XAT <span>&#62;</span> </a></li>
    <li><a href="<?= SHIKSHA_HOME ?>/mba/ranking/top-mba-colleges-delhi-ncr/2-0-0-10223-0">Top MBA colleges in Delhi/NCR <span>&#62;</span> </a></li>
    <li><a href="<?= SHIKSHA_HOME ?>/mba/ranking/top-mba-colleges-bangalore/2-0-0-278-0">Top MBA colleges in Bangalore <span>&#62;</span> </a></li>
    <li><a href="<?= SHIKSHA_HOME ?>/mba/ranking/top-mba-colleges-mumbai-all/2-0-0-10224-0">Top MBA colleges in Mumbai <span>&#62;</span></a></li>
    <li><a href="<?= SHIKSHA_HOME ?>/mba/ranking/top-mba-colleges-pune/2-0-0-174-0">Top MBA colleges in Pune <span>&#62;</span></a></li>
 </ul>
</div>

 <?php 
$this->load->view('common/footerNew');
  ?>

