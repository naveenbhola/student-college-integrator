<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php if(isset($pageTitle) && (strlen($pageTitle) > 0)) {?>
<title><?php echo $pageTitle; ?></title>
<?php }else{ ?>
<title>Listing Detail Page</title>
<?php } ?>
<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("mainStyle"); ?>" type="text/css" rel="stylesheet" />
<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("raised_all"); ?>" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("listing_detail"); ?>"></script>
<script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("common"); ?>"></script>
<script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("header"); ?>"></script>
<script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("user"); ?>"></script>
</head>
<body>
<!--Start_MidContainer-->
<div class="mar_full_10p">

<?php
switch($type){
    case 'institute':
        $this->load->view('listing_forms/institute_Listing_Page');
        break;
    case 'course':
        $this->load->view('listing_forms/course_Listing_Page');
        break;
}
?>
</div>
</body>
<script>
function updateOnClicksForAs(){
    var aElems = document.getElementsByTagName('a');
    var curPageUrl =  window.location.href;
    for(var aElemsCount = 0, aElem; aElem = aElems[aElemsCount++];) {
        if(aElem.href != null && aElem.href != "" && ((aElem.href.indexOf('javascript:') < 0) && (aElem.href.indexOf(curPageUrl) < 0))) {
            aElem.onclick = function () { 
                window.top.location.href= this.href;
                return false;
            };
        }
    }
}
updateOnClicksForAs();
</script>
</html>
