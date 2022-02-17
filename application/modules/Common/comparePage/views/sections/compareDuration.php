<?php 
if(isset($compareData['courseDuration'])){
?>
  <!--Total duration of Course-->
  <tr id="compareRow3">
    <td><div class="cmpre-head"><label>Course Duration</label></div></td>
    <?php
        foreach($courseIdArr as $courseId){
          $courseDuration = $compareData['courseDuration'][$courseId];
          if(!empty($courseDuration)){
            ?>
            <td>
              <div class="cmpre-head">
                <p class="affliatn-txt"><?php echo $courseDuration['value'].' '.ucfirst($courseDuration['unit'])?></p>
              </div>
            </td>
            <?php 
          }else{
            ?>
            <td align="center" style="vertical-align:middle;">--</td>
            <?php 
          }
        }
        ?>
  </tr>
<?php 
}
?>