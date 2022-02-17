	<tr id="row1_H">
		<td colspan="2" class="compare-title"><h2>Rank</h2></td>
	</tr>
	<tr id="row1_C" align="center">
	    <?php
		$j = 0;$k = 0;
		foreach ($courseIdArr as $key => $courseId) {
		    $k++;
		    if($k==2){
				$class = "class = 'last'";
			} else {
				$class = "class = 'border-right'";
			}
			if(is_array($compareData['ranks']['rankData'][$courseId])){
				$j++;
			?>
				<td width="165" valign="top" align="center" <?php echo $class; ?>>
					<div class="compare-items">
						<ul>
						<?php 
				        foreach ($compareData['ranks']['rankData'][$courseId] as $source => $rankDetsils) {
				          ?>
				          <li>
				          <?php if($rankDetsils['rank'] == 'NA'){ ?>
				          	<span>
				              <a class="dull-txt"><?php echo $source; ?></a>
				            </span>
				            <a class="dull-round-col">- -</a>
				          <?php }else{ ?>
				            <span>
				              <a href="<?php echo $compareData['ranks']['rankingPageUrl'][$courseId][$rankDetsils['rankingPageId']]; ?>"><?php echo $source; ?></a>
				            </span>
				            <a class="round-rank" href="<?php echo $compareData['ranks']['rankingPageUrl'][$courseId][$rankDetsils['rankingPageId']]; ?>"><?php echo $rankDetsils['rank']; ?></a>
				            <?php } ?>
				          </li>
				          <?php 
				        }
				        ?>
						</ul>
					</div>
				</td>
			<?php 
			}else{
			?>
				<td class="<?php echo ($x<$compare_count_max)?'border-right':'';?>">--</td>
			<?php
			}
		}
		if($j < $compare_count_max)
		{
		    for ($x = $k+1; $x <=$compare_count_max; $x++)
		    {
			?>
				<td class="<?php echo ($x<$compare_count_max)?'border-right':'';?>">&nbsp;</td>
			<?php
		    }
		}
	?>
	</tr>