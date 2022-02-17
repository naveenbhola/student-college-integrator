<?php 
$headerComponents = array(
                'css'   =>      array('colge_revw_desk'),
                'js'=>array('common', 'multipleapply','ajax-api', 'processForm', 'customCityList'),
                'jsFooter' => array('lazyload'),
                'title' =>      $title,
                'metaDescription' => $metaDescription,
                'canonicalURL' =>$canonicalURL,
                'metaKeywords' => $metaKeywords,
                'product'       =>'',
                'showBottomMargin' => false,
);

$this->load->view('common/header', $headerComponents);
?>
<div class='content-wrap clearFix'>
<div>
<p class="resource-head">MBA Resources</p>
<ul class="resources-list">
	<li><a href="<?= SHIKSHA_HOME ?>/mba/resources/ask-current-mba-students">Talk to current students <span>&#62;</span></a></li>
	<li><a href="<?= SHIKSHA_HOME ?>/mba/resources/college-reviews">College reviews <span>&#62;</span> </a></li>
	<li><a href="<?= SHIKSHA_HOME ?>/mba/resources/mba-alumni-data">Alumni Data <span>&#62;</span> </a></li>
	<li><a href="<?= SHIKSHA_HOME ?>/mba/resources/exam-calendar">Exam Calendar <span>&#62;</span> </a></li>
	<li><a href="<?= SHIKSHA_HOME ?>/mba/resources/application-forms">Application Forms <span>&#62;</span> </a></li>
	<li><a href="<?= SHIKSHA_HOME ?>/tags/mba-tdp-422">Ask a Question <span>&#62;</span> </a></li>
	<li><a href="<?= SHIKSHA_HOME ?>/tags/mba-tdp-422?type=discussion">Discussions <span>&#62;</span> </a></li>
	<li><a href="<?= SHIKSHA_HOME ?>/mba/resources/mba-news-articles">News and Articles <span>&#62;</span> </a></li>
	<li><a href="<?= SHIKSHA_HOME ?>/mba/resources/distance-mba-faq">Frequently Asked Questions (FAQ) <span>&#62;</span> </a></li>
</ul>
</div>
</div>
 <?php 
$this->load->view('common/footerNew');
  ?>