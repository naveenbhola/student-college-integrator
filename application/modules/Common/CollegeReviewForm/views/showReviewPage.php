<?php

$tempJsArray = array('myShiksha','user');
$headerComponents = array(
                'css'   =>      array('colge_revw_desk'),
                'js' => array('common','facebook','ajax-api','ana_common','processForm','CollegeReview'),
                'jsFooter'=>    $tempJsArray,
                'title' =>      $meta_title,
                'metaDescription' => $meta_description,
                'canonicalURL' =>$canonicalURL,
                'product'       =>'collegeReviewHomepage',
                'showBottomMargin' => false,
                'displayname'=>(isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:"")
);

$this->load->view('common/header', $headerComponents);

?>


<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script>
$j = jQuery.noConflict();
</script>



<div id="content-wrapper-div">

    <div class="clearFix"></div>

    <div class="colgRvwHP2 grybgclr" style="padding-bottom:20px;">

        <div class="rvd_wrapper">

            <div class="rvd_wrapL">

                <?php $this->load->view('showReviewPageLeftContent', $headerComponents); ?>

                <?php $this->load->view('reviewCollegeWidget'); ?>
                
            </div>

            <div class="rvd_wrapR">
                
                <?php if($subcatId == '23' || $subcatId == '56') $this->load->view('showReviewSearchWidget'); ?>

                <?php $this->load->view('tilesWidget'); ?>

                <?php $this->load->view('registrationWidget'); ?>

            </div>          
        
        </div>
        <p style="clear:both;display:block;width:100%"></p>
    </div>

    <div class="clearFix"></div>
</div>


<?php 
    $this->load->view('common/footer');
?>
