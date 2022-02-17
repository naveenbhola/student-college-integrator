<?php

//Get the Express Interest String. If the cookie is not set, this will be empty
$expressInterestString = "";
$expressInterestObjectParams=(json_decode($_COOKIE['expressInterestDetail']));
$expressInterestDetailsArray=array();

if (is_object($expressInterestObjectParams)) {
    $expressInterestDetailsArray = get_object_vars($expressInterestObjectParams);
    if($expressInterestDetailsArray['display1'])
	$eiVals = $expressInterestDetailsArray['display1'];
    if($expressInterestDetailsArray['display2'])
        $eiVals .= ', '.$expressInterestDetailsArray['display2'];
    $expressInterestString = "<a href='".CAREER_SUGGESTION_PAGE."'>$eiVals</a> &#155; ";
}

//Get the Stream String. If the cookie is not set, this will be empty
$streamString = "";
if(isset($_COOKIE['streamSelected'])){
	$stream = $_COOKIE['streamSelected'];
	$streamString = "<a href='".CAREER_EXPRESSINTEREST_PAGE."'>".preg_replace('/Humanities/','Humanities/Arts',$stream)."</a> &#155; ";
}

//Get the Career Homepage string
$careerHomeString = "<a href='".CAREER_HOME_PAGE."'>Careers</a> &#155; ";
$homePage = "<a href='".SHIKSHA_HOME."'>Home</a> &#155; "

?>

<div style="padding-bottom:10px;font:12px Tahoma,Geneva,sans-serif !important">
	<?php echo $homePage.$careerHomeString.$careerName; ?>
</div>
