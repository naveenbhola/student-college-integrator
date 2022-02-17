<?php ob_start('compress'); ?>
<?php
$headerComponents = array(
      'mobilecss' => array('style'),
      'jsMobile' => array('ana'),
      'css' => array('sitemap'),
      'm_meta_title' => $seoTitle,
      'm_meta_description' => $seoDesc
        );
$this->load->view('/mcommon5/header',$headerComponents);
?>
<div id="wrapper" data-role="page" style="min-height: 413px;paddding-top:40px;">    
<?php
    echo Modules::run('mcommon5/MobileSiteHamburgerV2/getWrapperHtmlForHamburger','mypanel');
    echo Modules::run('mcommon5/MobileSiteHamburger/getRightPanel','myrightpanel');
?>
<header id="page-header"  data-role="header" class="header" data-position="fixed">
      <?php echo Modules::run('mcommon5/MobileSiteHamburger/getMainHeader',true);?>
</header>

<div data-role="content" data-enhance="false">
<?php
    if($sitemapPageType == 'home')
        $this->load->view("common/sitemap/HTMLSitemapHomeContent");
    else if($sitemapPageType == 'location')
        $this->load->view("common/sitemap/HTMLsitemapLocationDetails");
?>
<?php
    $this->load->view('/mcommon5/footerLinks');
?>
</div>
</div>
<div id="popupBasicBack" data-enhance='false'></div>
<?php $this->load->view('/mcommon5/footer');?>
<script>
  jQuery(document).ready(function(){
    jQuery("#filterCities").on("input", function(){
      filterList(this); 
    });
  });
</script>
<?php ob_end_flush();?>