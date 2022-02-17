<?php

//Get the Career Homepage string

if(!empty($careerPage)){
	$careerHomeString = "<a href='".CAREER_HOME_PAGE."'>Careers</a> &#155; ";
}else{
	$careerHomeString = "Careers";
}
	$homePage = "<a href='".SHIKSHA_HOME."'>Home</a> &#155; ";


?>

<div style="padding-bottom:10px;font:12px Tahoma,Geneva,sans-serif !important">
	<?php echo $homePage.$careerHomeString.$careerPage; ?>
</div>
