<?php
if(!empty($compareData['ranks']) && !empty($compareData['ranks']['rankData'])){
?>
<tr id="compareRow1">
  <td>
    <div class="cmpre-head"><label>Rank</label></div>
  </td>
  <?php 
  foreach ($courseIdArr as $key => $courseId) {
    if(is_array($compareData['ranks']['rankData'][$courseId])){
    ?>
      <td>
      <div class="cmpre-items">
        <ul>
        <?php 
        foreach ($compareData['ranks']['rankData'][$courseId] as $source => $rankDetsils) { ?>
          <li>
          <?php if($rankDetsils['rank'] == 'NA'){ ?>
            <span>
              <a  class="dull-txt"><?php echo $source; ?></a>
            </span>
            <a class="dull-round-col" >- -</a>
          <?php }else{ ?>
            <span>
              <a href="<?php echo $compareData['ranks']['rankingPageUrl'][$courseId][$rankDetsils['rankingPageId']]; ?>" class="busns-txt"><?php echo $source; ?></a>
            </span>
            <a class="round-col" href="<?php echo $compareData['ranks']['rankingPageUrl'][$courseId][$rankDetsils['rankingPageId']]; ?>"><?php echo $rankDetsils['rank']; ?></a>
          <?php } ?>
          </li>
        <?php }
        ?>
        </ul>
      </div>
      </td>
    <?php 
    }else{
      echo '<td align="center" style="vertical-align:middle;">-</td>';
    }
  }
  ?>
</tr>
<?php 
}
?>