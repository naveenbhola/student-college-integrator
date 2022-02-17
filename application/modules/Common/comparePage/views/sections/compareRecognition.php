<?php 
if(is_array($compareData['recognition'])){
?>
  <!-- Recognition -->
  <tr id="compareRow2">
    <td><div class="cmpre-head"><label>Recognition</label></div></td>
    <?php
    $j = 0;
    foreach($compareData['recognition'] as $courseId => $recoData){
      $recogFlag = false;
      $j++;
      $k = 0;
      ?>
      <td id="compareRow2Col<?php echo $j?>">
      <?php
      ?>
      <div class="cmpre-head" style="height:100%">
        <?php 
        if(!empty($recoData)){
          $recogFlag = true;
          foreach ($recoData as $value) {
            echo '<p class="affliatn-txt">'.$value.'</p>';
          }
        }
        if(!$recogFlag)
        {
          $k = $j;
          echo '<div style="text-align:center;vertical-align:middle;">--</div>';
        }
        ?>
      </div>
      </td>
    <?php 
      if($k != 0)
      {
        echo '<script>$(compareRow2Col'.$k.').style.verticalAlign = "middle";</script>';
      }
    }
    ?>
  </tr>
<?php 
}
?>