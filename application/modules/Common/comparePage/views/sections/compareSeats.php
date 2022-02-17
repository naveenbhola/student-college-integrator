<?php 
if(isset($compareData['courseSeats'])){
?>
  <!--Total Seats of Course-->
  <tr id="compareRow3">
    <td><div class="cmpre-head"><label>Total Seats</label></div></td>
    <?php
        foreach($courseIdArr as $courseId){
          $courseSeats = $compareData['courseSeats'][$courseId];
          if(!empty($courseSeats)){
            ?>
            <td>
              <div class="cmpre-head">
                <p class="affliatn-txt"><?php echo $courseSeats?></p>
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