<?php
echo "
<div id=\"custom-form-content\">
	<ul>
";
//Now, for each field, create the form

foreach ($pageData['pageTemplate'] as $template){
	$field = $template['details'][0];
	$fieldName = $field['name'];
	
	if($field['Heading']){
	echo "
		<li>
            <h3 class=\"form-title\">".$field['Heading']."</h3>
		</li>	
	";
	}
	if($field['Linked'] == "Education"){
		
		$fieldName = $fieldName . "_mul_";
		addEducationMultiHeader1($fieldName);
		$fieldName = "\$fieldName";
		$field['label'] = str_replace("<Masters>","<?=\${'graduationExaminationName_mul_'.\$j}?>",$field['label']);
		
	}
	
	if($field['Linked'] == "Job"){
		
		$fieldName = $fieldName . "_mul_";
		addJobMultiHeader1($fieldName);
		$fieldName = "\$fieldName";
		$field['label'] = str_replace("<Company>","<?=(\${'weCompanyName_mul_'.\$i}?\${'weCompanyName_mul_'.\$i}:\${'weCompanyName'})?>",$field['label']);
		
	}
	
	echo "
		<li>
			<div class=\"colums-width\">
				<label>".$field['label'].": </label>
				<div class=\"form-details\"><?php echo \$".$fieldName."; ?></div>
			</div>
		</li>
	";
	
	if($field['Linked']){
		echo "<?php
				}
			}
			?>";
	}
}
echo "
	</ul>
<div>
";


function addEducationMultiHeader1($fieldName){
	echo "
	<?php
					for(\$j=1;\$j<=4;\$j++){
                    if(!empty(\${'graduationExaminationName_mul_'.\$j})){
						\$fieldName = "."'".$fieldName."'."."\$j;
	?>
	";
	
}
  
  
function addJobMultiHeader1($fieldName){
	echo "
		<?php
			for(\$i=0;\$i<=3;\$i++){
				if(\$i == 0){
					\$lable = 'weCompanyName';
				}else{
					\$lable = 'weCompanyName_mul_'.\$i;
				}
				if(!empty(\${\$lable})){
					//if(\$i == 0){
						\$fieldName = "."'".$fieldName."'."."\$i;
					//}
					
		?>	
	";
	
  }
?>