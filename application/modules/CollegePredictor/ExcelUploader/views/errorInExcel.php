<a href="/ExcelUploader/ExcelUploader/loadExcelUploader" style="text-decoration: none;">< Back</a>
<?php
$html ='<p style="color:red">Error : '.$finalData['errorType'].' occurred in the file.</p>';
unset($finalData['errorType']);
foreach ($finalData as $k => $v) {
	$with = (count($v)*20);
	$with = ($with>90) ? 90 : $with;
	$html.='<table width="'.$with.'%" style=" border-collapse: collapse;">';	
	$html.='<tr>';
	foreach ($v as $key => $value) {
		$html.='<th style="border: 1px solid #dddddd;font-weight:normal;font-size:14px;">'.$key.'</th>';
	}
	$html.='</tr><tr>';
	foreach ($v as $key => $value) {
		if($key !='excel Row' && !empty($value)){ 
			$style ='style="color:red;font-size:12px;padding: 8px;border: 1px solid #dddddd;"';
		}else if($key !='excel Row' && empty($value)){
			$style ='style="font-size:12px;padding: 8px;font-weight:600;border: 1px solid #dddddd;background:yellow;"';
		}else{
			$style ='style="font-size:12px;padding: 8px;font-weight:600;border: 1px solid #dddddd;"';
		}
		$str = $value;
		if(strlen($value)>20){
			$str = substr($value, 0, 17).'...';	
		}
		$html.='<td  align="center" '.$style.' title="'.$value.'">'.$str.'</td>';
	}
	$html.='</tr>';
	$html .= '<tr><td colspan="50" align="center">&nbsp;</td></tr>';
	$html.='</table>';
}
echo $html;
?>