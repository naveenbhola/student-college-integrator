 <?php 
    $footerComponents = array('nonAsyncJSBundle' => 'sa-countryhome-page',
              'asyncJSBundle'    => 'async-sa-countryhome-page'
          );
    // Study Abroad Header file
    $this->load->view('studyAbroadCommon/saFooter', $footerComponents);
?>

<script>
      var ldbCourseId = '<?php echo $activeCourseId; ?>';
      var countryId = '<?php echo $countryObj->getId(); ?>';
      var catPageTransitionActive = false;
      var coursesOnPage = <?php echo json_encode(array_keys($coursesData)) ?>;
      $j(document).ready(function($j) {
            var intervalId;
            initializeActiveCourse();
            initializeNavBar();
            initializePopularUniversitiesWidget();
      });    

      $j(window).load(function() {
            initializeFindCollegesWithExamScoreWidget();
      <?php if($showGutterHelpText){?>
            setTimeout(function(){ $j("#countryHomeGutterHelpText").fadeOut(3000); },10000);
      <?php } ?>
      });
</script>