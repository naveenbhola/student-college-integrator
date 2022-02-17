<?php
  ob_start('compress');

  $headerComponents = array(
                'css' =>   array('mobile_listing5/institute/AMP/CSS/institutePageCSS'), //view file path of amp css 
                'js' => $ampJsArray,//array of js files to be included
                'm_meta_title'     => $m_meta_title,
                'm_meta_description' => $m_meta_description,
                'm_canonical_url' => $m_canonical_url,
                'pageType'  => $listing_type,
  );
  $this->load->view('mcommon5/AMP/header',$headerComponents);
?>
<body>
  <?php
    $this->load->view('mcommon5/AMP/googleAnalytics');
  echo Modules::run('mcommon5/MobileSiteHamburgerV2/getAMPHamburger',array('fromwhere'=>$listing_type,'listingId'=>$listing_id));
    $this->load->view('institute/AMP/instituteDetailPageContent');
    $this->load->view('mcommon5/AMP/footer');
    ?>
 </body>
 <?php
  if($validateuser != "false" && $validResponseUser){
    if($userId > 0){
      $courseid = $course->getId();
      $tracking_keyid = $ampKeys['ampCourseViewedTrackingPageKeyId'];
      if(empty($courseid) || empty($tracking_keyid) || !is_numeric($courseid) || !is_numeric($tracking_keyid)){
        return;
      }
      $_POST['listing_id']       = $courseid;     
      $_POST['tracking_keyid']   = $tracking_keyid;
      $_POST['action_type']      = 'mobile_viewedListing';
      $_POST['listing_type']     = 'course';
      $_POST['isViewedResponse'] = 'yes';
      modules::run('response/Response/createResponse');
      } 
  }
  ?>
<?php
  ob_end_flush();
?>

