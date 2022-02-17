<?php 
if(is_array($compareData['courseStatus']) && count($compareData['courseStatus']) > 0){
?>
  <!--Affliations-->
  <tr id="compareRow3">
    <td><div class="cmpre-head"><label>Course Status</label></div></td>
    <?php
        foreach($courseIdArr as $courseId){
          $courseSts = $compareData['courseStatus'][$courseId];
          if(count($courseSts) > 0){
            ?>
            <td>
              <div class="cmpre-head">
                <?php 
                foreach ($courseSts as $courseStsVal) {
                ?>
                  <p class="affliatn-txt"><?php echo $courseStsVal?></p>
                <?php 
                }
                ?>
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