<?php if($examFilter['examCalendarTitle']=="Engineering"){
    global $collegePredictorExams;
    if(count($collegePredictorExams)>4){
          $collegePredictorExamWidget=array_rand($collegePredictorExams,4);
    }
    else
    {
      $collegePredictorExamWidget = array();
      foreach($collegePredictorExams as $collegePredictorExamWidgets=>$val)
      {
          $collegePredictorExamWidget[]=$collegePredictorExamWidgets;
      } 
    }
?>
        <div class="xamPgWidgt xamPgWidgt2">
            <h3>Enter your Rank to find Colleges & Branches using :</h3>
            <div class="xamPgCnt">
            <ul>
              <?php foreach($collegePredictorExamWidget as $CollegePredictorWidget){?>
                <li onclick="trackEventByGA('CALENDAR_COLLEGE_PREDICTOR_WIDGET_CLICK','EXAM_CALENDAR_COLLEGE_PREDICTOR');"><h2>
                <a href="<?php echo $collegePredictorExams[$CollegePredictorWidget]['url'];?>" target="_blank"><?php echo strtoupper($CollegePredictorWidget); ?><span>College Predictor</span></a></h2></li>
              <?php } ?>
            </ul>
                <p class="clr"></p>
            </div>
        </div>
    <?php } ?>
    </div>