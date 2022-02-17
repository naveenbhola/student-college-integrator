<?php
  ob_start('compress');
  
  $headerComponents = array(
                'css'              =>   array('mobile_listing5/course/AMP/css/coursePageCSS'), //view file path of amp css 
                'js' => $ampJsArray,//array of js files to be included
                'm_meta_title'     => $seoTitle,
                'm_meta_description' => $metaDescription,
                'm_canonical_url' => $canonicalURL,
                'pageType'  => 'coursePage',
                'm_meta_keywords' => $m_meta_keywords
  );
  if(!empty($ifNaukriDataExists) && $ifNaukriDataExists != 0){ 
    array_push($headerComponents['css'],'mobile_listing5/course/AMP/css/businessGraphCSS');
  }
  $this->load->view('mcommon5/AMP/header',$headerComponents);
?>
<body>
  <?php
    $this->load->view('mcommon5/AMP/googleAnalytics');
    echo Modules::run('mcommon5/MobileSiteHamburgerV2/getAMPHamburger',array('fromwhere'=>'coursepage','listingId'=>$courseId));
    $this->load->view('course/AMP/courseDetailPageContent');
    $this->load->view('course/AMP/Widgets/courseStickyWidget');
	  $this->load->view('mcommon5/AMP/footer');
    ?>
 </body>
<?php 
  ob_end_flush();
?>