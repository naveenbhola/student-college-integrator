<?php
$headerComponents = array (
        'js' => array (
                'multipleapply',
                'user' 
        ),
        'css' => array('sitemap'),
        'jsFooter' => array (
                'common',
                'processForm' 
        ),
        'title'           =>  $seoTitle,
        'metaDescription' => $seoDesc
);

$this->load->view ('common/header', $headerComponents );
?>
<div class="breadcrumb2">
<span itemscope="" itemtype="https://data-vocabulary.org/Breadcrumb">                     
<a href="<?php echo SHIKSHA_HOME;?>" itemprop="url"><span itemprop="title">Home</span></a>
</span>
<span itemscope="" itemtype="https://data-vocabulary.org/Breadcrumb">                     
<span class="breadcrumb-arrow">&rsaquo;</span>
<a href="<?php echo SHIKSHA_HOME."/sitemap";?>" itemprop="url"><span itemprop="title">Sitemap</span></a>
</span>
<span class="breadcrumb-arrow">&rsaquo;</span>
<span itemscope="" itemtype="https://data-vocabulary.org/Breadcrumb"><?php echo "Browse ".$heading; ?></span>
</div>
<?php
    $this->load->view("common/sitemap/HTMLsitemapLocationDetails");
    $this->load->view ( 'common/footerNew', array (
            'loadJQUERY' => 'YES',
            'loadUpgradedJQUERY' => 'YES'
         
    ) );
?>
<script>
    // jQuery(document).ready(function(){
    //     jQuery("#filterCities").on("input", function(){
    //     filterList(this); 
    //     });
    // });
</script>