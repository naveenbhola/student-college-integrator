<tr>
		  <td class="compare-title" colspan="5"><h2>Have a question about the college? Ask a current student now!</h2></td>
</tr>

<tr>
		  <td width="165" valign="top">
					<div class="compare-items" style="position:relative"><label>Current <br/>Students<i class="compare-sprite info-icon" style="left:11px;" onmouseover="$j('#campusRepTooltip').show();" onmouseout="$j('#campusRepTooltip').hide();"></i> </label>
					     <div class="compare-tooltip" style="display: none; left:86px;top:48px;" id="campusRepTooltip">
                        		<i class="compare-sprite tooltip-arr"></i>
                                <p>Shiksha has current students of the course who can answer your queries</p>
                            </div>
					</div>
		  </td>
		  
		  <?php 
		$count = count($campusRepList['courses']);	
	for($i = 0; $i <= 3 ;$i++){ ?>
		  <td width="165" align="center"  <?php if($i > $count-1) {?>style="border:0px;" <?php }
							  if($i == $count-1 && $count ==4){ ?> class="last" <?php } ?>>
			<?php if($campusRepList[$i]['caInfo']){
							  $courseId = $campusRepList['courses'][$i];
							  $instituteId = $campusRepList['institute'][$i];
							  $image = $campusRepList[$i]['caInfo'][$courseId][0]['imageURL'];
							  $courseUrl = $campusRepList['courseUrl'][$i];
							  $badge = $campusRepList[$i]['caInfo'][$courseId][0]['badge'];
							    if($badge=='Official'){$badge = 'OFFICIAL';}else if($badge=='CurrentStudent'){$badge = 'CURRENT STUDENT';}else{$badge = 'ALUMNI';}
							  $name = $campusRepList[$i]['caInfo'][$courseId][0]['displayName'];
					?>
							<input type="hidden" id="courseUrl_<?=$courseId;?>" value="<?=$courseUrl?>">
							  <div class="flLt">
								  <div class="rep-image"><img src="<?=$image;?>" width="58" height="52" alt="rep-image" /></div>
								  <div class="rep-details">
									  <p><?=$name;?></p>
									  <a class="current-stu"><?=$badge;?></a>
								  </div>							
							  </div>
							  <div class="flLt"><input type="button" class="button-style" style="margin-top:12px" value="Ask Now" onclick="askQuestionCompare('<?php echo $courseId; ?>','<?php echo $instituteId; ?>','<?php echo $qtrackingPageKeyId;?>'); trackEventByGA('LinkClick','COMPARE_PAGE_ASK_NOW_BUTTON_CLICK');" href="javascript:void(0);"/></div>
					<?php }else{?>
					  <div <?php if($i <= $count-1) {?>
							         <strong style="font-size:22px; color:#828282">-</strong> <?php
									 }?>
							  </div><?php } ?>
          </td>
		  <?php } ?>
</tr>

<script>
function setNoScroll() {
    window.scrollTo(0,h);
    window.onscroll = floatCompareWidgetScroll;
}
</script>

