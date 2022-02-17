<table width="226" border="0" cellspacing="0" cellpadding="0" align="left">
  <tr>
    <td width="218" bgcolor="#ffffff" style="border:1px #dbdbdb solid" valign="top">				     <table width="218" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td height="38" style="border-bottom:1px #dbdbdb solid;font-family:Arial, Helvetica, sans-serif;font-size:15px;color:#474a4b"><img src="<?php echo $image_url; ?>exams.gif" width="39" height="18" vspace="0" hspace="0" align="absmiddle" /><strong>Exams & Cut-offs</strong></td>
  </tr>
  <tr>
    <td height="193" valign="top">
    <table width="200" border="0" cellspacing="0" cellpadding="0" align="left" style="font-family:Arial, Helvetica, sans-serif;font-size:16px;color:#474a4b;">
  
    <?php
	$maxExams = count($recommendationWidgets['examsRequired']) >= 3 ? 3 : count($recommendationWidgets['examsRequired']);
	$minGap = 17;
	
	if($isAbroadRecommendation) {
	  $minGap = 20;
	}
	
	$examCount = 0;
	foreach($recommendationWidgets['examsRequired'] as $examName => $examInfo) {
	    $gap = $examCount == 0 ? (((3 - $maxExams) * 27) + $minGap) : 8;
	    $examCount++;
    ?>
  <tr>
    <td width="12" height="<?php echo $gap; ?>"></td>
    <td width="9"></td>
    <td width="108"></td>
  </tr>
  <tr>
    <td width="13"></td>
    <td><img src="<?php echo $image_url; ?>bullets.gif" width="5" height="5" vspace="0" hspace="0" align="left" /></td>
    <td><?php echo $examName; if(!empty($examInfo)) { echo ' : '.$examInfo['marks']; if($examInfo['marksType'] == 'percentile') { echo ' %tile'; } else if($examInfo['marksType'] == 'percentage') { echo ' %age'; } else if($examInfo['marksType'] == 'rank') { echo ' Rank'; } else if($examInfo['marksType'] == 'total_marks') { echo ' Marks'; } if($examInfo['maxScore'] > 0) { echo '/'.$examInfo['maxScore']; } } ?></td>
  </tr>
    <?php
	    if($examCount == 3) {
		break;
	    }
	}
    ?>
  
  <tr>
    <td height="15"></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td><a href="<?php echo $course_url."~recommendation"; ?><!-- #widgettracker --><!-- widgettracker# -->" title="More info" target="_blank" style="font-family:Arial, Helvetica, sans-serif;font-size:13px;color:#2170e8;text-decoration:none;">More Info<img src="<?php echo $image_url; ?>more_info.gif" width="9" height="8" vspace="0" hspace="0" align="absmiddle" border="0" /></a></td>
  </tr>
</table>

    </td>
  </tr>
</table>
	</td>
    <td width="10"></td>
  </tr>
  <tr>
  <td height="10"></td>
  <td></td>
  </tr>
</table>