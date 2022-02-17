<?php
    if($isAjaxCall){
        echo $this->load->view('widgets/categoryPageListing',true);
        return;
    }
    $headerComponents = array(
                                'css'             => array('postFFCategoryPageSA'),
                                'js'              => array('categoryPageSA'),
                                'canonicalURL'    => $canonicalUrl,
                                'title'           => $title,
                                'metaDescription' => $metaDescription,
                                'deferCSS'              => true,
                                'firstFoldCssPath'      => 'categoryPage/css/categoryPageFirstFoldCss'
                                );
    $this->load->view('commonModule/headerV2',$headerComponents);
    echo jsb9recordServerTime('SA_MOB_CATEGORYPAGE',1);
?>
<?php    
    $this->load->view('widgets/categoryPageHeaderTitle');
    
    $this->load->view('widgets/categoryPageListing');
    $this->load->view('widgets/popularSpecialisations');
    $this->load->view('categoryPageFooter');
?>