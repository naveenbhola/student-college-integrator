<table width="226" border="0" cellspacing="0" cellpadding="0" align="left">
  <tr>
    <td width="218" bgcolor="#ffffff" style="border:1px #dbdbdb solid" valign="top">				     <table width="218" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td height="38" style="border-bottom:1px #dbdbdb solid;font-family:Arial, Helvetica, sans-serif;font-size:15px;color:#474a4b"><img src="<?php echo $image_url; ?>salary_stats.gif" width="39" height="18" vspace="0" hspace="0" align="absmiddle" /><strong>Salary Statistics</strong></td>
  </tr>
  <tr>
    <td height="193" valign="top">
    <table width="211" border="0" cellspacing="0" cellpadding="0" align="left" style="font-family:Arial, Helvetica, sans-serif;font-size:16px;color:#474a4b;">
  <tr>
    <?php
      if($isAbroadRecommendation) {
	$gap = 50;
      }
      else {
	$maxSlabs = count($recommendationWidgets['salaryData']);
	
	$gap = ((3 - $maxSlabs) * 15) + 40;
      }
    ?>
    
    
    <td width="14" height="<?php echo $gap; ?>"></td>
    <td width="192"></td>
  </tr>
  <?php
    if(!$isAbroadRecommendation) {
	if(!empty($recommendationWidgets['salaryData']['max'])) {
  ?>
  <tr>
    <td height="30"></td>
    <td align="center" style="border-bottom:1px #f1f1f1 solid;border-left:3px #6eb414 solid">Max. Salary : <strong><?php echo $recommendationWidgets['salaryData']['max']; ?> Lacs</strong></td>
  </tr>
  <?php } ?>
  <?php if(!empty($recommendationWidgets['salaryData']['avg'])) { ?>
  <tr>
    <td height="30"></td>
    <td align="center" style="border-bottom:1px #f1f1f1 solid;border-left:3px #a9d374 solid">Avg. Salary : <strong><?php echo $recommendationWidgets['salaryData']['avg']; ?> Lacs</strong></td>
  </tr>
  <?php } ?>
  <?php if(!empty($recommendationWidgets['salaryData']['min'])) { ?>
  <tr>
    <td height="30"></td>
    <td align="center" style="border-bottom:1px #f1f1f1 solid;border-left:3px #d9f1ba solid">Min. Salary : <strong><?php echo $recommendationWidgets['salaryData']['min']; ?> Lacs</strong></td>
  </tr>
  <?php
	}
    }
    else if($isAbroadRecommendation) {
  ?>
      <tr>
	<td></td>
	<td>Annually</td>
      </tr>
      <tr>
	<td height="30"></td>
	<td>
	  <?php if(strlen(trim($recommendationWidgets['salaryData']['averageSalary']))) { echo $recommendationWidgets['salaryData']['averageSalary'].' = '; } echo $recommendationWidgets['salaryData']['averageSalaryInRupees']; ?>
	</td>
      </tr>
  <?php
    }
  ?>
  
  <tr>
    <td height="10"></td>
    <td></td>
  </tr>
  <tr>
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