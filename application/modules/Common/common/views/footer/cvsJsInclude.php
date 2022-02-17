<?php
	$cvsJsIncludedOnPage = '';
	if(is_array($js)){
		$cvsJsIncludedOnPage = implode(",",$js);
	}
	if(is_array($jsFooter)){
		if(strlen($cvsJsIncludedOnPage) > 0)
			$cvsJsIncludedOnPage .= ','.implode(",",$jsFooter);
		else
			$cvsJsIncludedOnPage .= implode(",",$jsFooter);
	}
?>
<input type="hidden" name="cvsJsIncludedOnPage" id="cvsJsIncludedOnPage" value="<?php echo $cvsJsIncludedOnPage; ?>" />
