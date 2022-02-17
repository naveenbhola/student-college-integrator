<?php 
if(isset($compareData['accreditation'])){
?>
  <!--Course and Institute Accreditation-->
  <tr id="compareRow3">
    <td><div class="cmpre-head"><label>Accreditation</label></div></td>
    <?php
        foreach($courseIdArr as $courseId){
          $courseAccr = $compareData['accreditation'][$courseId];
          if(!empty($courseAccr)){
            ?>
            <td>
              <div class="cmpre-head">
                <?php 
                foreach ($courseAccr as $courseAccrVal) {
                ?>
                  <p class="affliatn-txt"><?php echo $courseAccrVal?></p>
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