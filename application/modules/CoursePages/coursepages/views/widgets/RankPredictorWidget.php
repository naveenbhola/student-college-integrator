<?php
  $examsForRankPrediction = $widgets_data['rankPredictorWidget']['examsForRankPrediction'];
?>
<div class="predictor-rank-widegt clear-width">
    <div class="flLt"><i class="common-sprite predictor-rank-icon"></i></div>
    <div class="predictor-widget-info">
      <?php  foreach ($examsForRankPrediction as $dataForAnExam) {
        echo "<h2>".$dataForAnExam['heading']."</h2>";
        echo '<a href="'.$dataForAnExam['url'].'">'.$dataForAnExam['urlCaption'].'</a> ';
      } ?>
    </div>
    <div class="clearFix"></div>
</div>
