<?php
  ob_start('compress');
  
  $headerComponents = array(
                'css'              =>   array('mobile_examPages5/newExam/AMP/CSS/ampExamPage'), //view file path of amp css 
                'js' => $ampJsArray,//array of js files to be included
                'm_meta_title'     => $m_meta_title,
                'm_meta_description' => $m_meta_description,
		'm_meta_keywords' => $m_meta_keywords,
                'm_canonical_url' => $m_canonical_url,
                'pageType'  => 'examPage',
                'ampExternalCSS' => $sectionCSSForAMP 
  );
  $this->load->view('mcommon5/AMP/header',$headerComponents);
?>
  <body>
      <?php 
          $this->load->view('mcommon5/AMP/googleAnalytics');
	  //$this->load->view('mcommon5/AMP/customClickAnalytics');
          echo Modules::run('mcommon5/MobileSiteHamburgerV2/getAMPHamburger',array('fromwhere'=>'exampage','listingId'=>$groupId));
          $this->load->view('mobile_examPages5/newExam/AMP/examHomePageContent');
          $this->load->view('mobile_examPages5/newExam/AMP/stickyCTA');
          $this->load->view('mcommon5/AMP/footer');
      ?>
  </body>
<?php 
  ob_end_flush();
?>
