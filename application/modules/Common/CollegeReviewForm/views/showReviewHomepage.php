<?php

$headerComponents = array(
                'css'   =>      array('colge_revw_desk'),
                'js' => array('common'),
                'title' =>      $m_meta_title,
                'metaDescription' => $m_meta_description,
                'canonicalURL' =>$canonicalURL,
                'product'       =>'collegeReviewHomepage',
                'showBottomMargin' => false,
                'previousURL' => $previousURL,
                'nextURL' => $nextURL,
                'displayname'=>(isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),

);

        $this->load->view('common/header', $headerComponents);

?>

<?php $this->load->view( 'messageBoard/headerPanelForAnA',array('collegePredictor' => true) );?>



   <div id="content-wrapper-div">

     <div class="clearFix"></div>

     <div class="colgRvwHP2 grybgclr">
      
       <?php $this->load->view('searchWidget'); ?>
	       
       <div class="rvd_wrapper">
        
         <!-- Left Side Widget STart -->
         <div class="rvd_wrapL">
           <h2 class="secHead1"> 

            <?=$pageFor?> College Reviews</h2>
           <?php $this->load->view('reviewsWidget'); ?>
           <?php $this->load->view('disclaimer'); ?>
           <?php $this->load->view('reviewCollegeWidget'); ?>
           
         </div>
         
         <!-- Left Side Widget Ends -->
         
         
         <!-- Right Side Widget STart -->
         <div class="rvd_wrapR" style="width:293px !important;">
           <h2 style="color: #575757;margin-bottom: 10px;font-size: 20px;text-align: left;display: inline-block;font-weight: normal;line-height: 31px;">Review Collections</h2>
           <?php $this->load->view('tilesWidget'); ?>
           <?php $this->load->view('registrationWidget'); ?>
           <?php 
            global $managementStreamMR; 
            if($stream == $managementStreamMR) {
             $this->load->view('naukriToolWidget');
            }
           ?>

         </div>
         <!-- Right Side Widget Ends -->
         
         
         <p class="clr"></p>
       </div>
       
     </div>
     <div class="clearFix"></div>
     
   </div>



<?php 
        $this->load->view('common/footer');
?>

<?php
$this->load->view('autoSuggestorCollegeReviews');
?>
<script>
var basePageUrl = '<?=$basePageUrl?>';

var $stream = ""+"<?=$stream;?>";
var $baseCourse = ""+"<?=$baseCourse;?>";
var $substream = ""+"<?=$substream;?>";
var $educationType = ""+"<?=$educationType;?>";
var $pageRead = 'collegeReviewHomepage';

</script>
<div id="toTop">&#9650; Back to Top</div>
